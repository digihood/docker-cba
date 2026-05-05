/* Net Worth Calculator — net-worth.js */
(function ($) {
    'use strict';

    // Guard: require localized data
    if (typeof NetWorthData === 'undefined') {
        return;
    }

    var cfg          = NetWorthData.config || {};
    var items        = cfg.items        || {};
    var categories   = cfg.categories   || {};
    var benchmarks   = cfg.benchmarks   || {};
    var resultMsgs   = cfg.result_messages || {};
    var recContent   = cfg.recommended_content || [];
    var isLoggedIn   = !!NetWorthData.isLoggedIn;
    var userEmail    = NetWorthData.userEmail || '';
    var calcId       = NetWorthData.calculatorId || 0;
    var snapshots    = NetWorthData.snapshots    || [];

    // Convert object-keyed maps to arrays for iteration
    function toArray(obj) {
        if (Array.isArray(obj)) return obj;
        return Object.values(obj);
    }

    var itemsArr      = toArray(items);
    var categoriesArr = toArray(categories);

    // Chart instances
    var donutChart    = null;
    var snapshotChart = null;

    // Save debounce timer
    var saveTimer = null;

    /* =============================================
       Format numbers Czech locale
       ============================================= */
    function formatCzk(num) {
        var n = Math.round(Number(num) || 0);
        return n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ' ') + ' Kč';
    }

    function formatPct(num, decimals) {
        decimals = decimals !== undefined ? decimals : 1;
        return Number(num).toFixed(decimals).replace('.', ',') + ' %';
    }

    function formatMonths(num) {
        return Number(num).toFixed(1).replace('.', ',') + ' měs.';
    }

    /* =============================================
       Read all input values
       ============================================= */
    function getAllValues() {
        var values = {};
        // Item inputs
        $('.net-worth__input').each(function () {
            var name = $(this).attr('name');
            if (name) {
                values[name] = Math.max(0, parseFloat($(this).val()) || 0);
            }
        });
        // Monthly expenses
        var me = parseFloat($('#nw-monthly-expenses').val()) || 0;
        values['monthly_expenses'] = Math.max(0, me);
        return values;
    }

    /* =============================================
       Calculate results (mirrors PHP logic)
       ============================================= */
    function calculateAll(values) {
        var totalAssets      = 0;
        var totalLiabilities = 0;
        var liquidAssets     = 0;
        var categoryTotals   = {};

        itemsArr.forEach(function (item) {
            if (item.active === false) return;
            var slug  = item.slug || item.id || '';
            var val   = values[slug] ? parseFloat(values[slug]) : 0;
            var type  = item.type || '';

            if (val <= 0) return;

            if (type === 'asset') {
                totalAssets += val;
                if (item.is_liquid) {
                    liquidAssets += val;
                }
                var cat = item.category || 'other';
                categoryTotals[cat] = (categoryTotals[cat] || 0) + val;
            } else if (type === 'liability') {
                totalLiabilities += val;
            }
        });

        var netWorth          = totalAssets - totalLiabilities;
        var equityRatio       = totalAssets > 0 ? (netWorth / totalAssets) * 100 : 0;
        var debtToAssetRatio  = totalAssets > 0 ? (totalLiabilities / totalAssets) * 100 : 0;
        var liquidityIndex    = totalAssets > 0 ? (liquidAssets / totalAssets) * 100 : 0;

        var monthlyExpensesDefault = (benchmarks.monthly_expenses && benchmarks.monthly_expenses.default)
            ? parseFloat(benchmarks.monthly_expenses.default) : 30000;
        var monthlyExpenses = values['monthly_expenses'] !== undefined
            ? parseFloat(values['monthly_expenses']) : monthlyExpensesDefault;
        var crisisResilienceMonths = monthlyExpenses > 0 ? liquidAssets / monthlyExpenses : null;

        // Largest asset category
        var largestCat   = '';
        var largestVal   = 0;
        Object.keys(categoryTotals).forEach(function (catSlug) {
            if (categoryTotals[catSlug] > largestVal) {
                largestVal = categoryTotals[catSlug];
                largestCat = catSlug;
            }
        });

        var largestShare   = totalAssets > 0 ? (largestVal / totalAssets) * 100 : 0;
        var maxShare = (benchmarks.diversification && benchmarks.diversification.max_single_asset_category_share)
            ? parseFloat(benchmarks.diversification.max_single_asset_category_share) : 80;
        var divWarn = totalAssets > 0 && largestShare > maxShare;

        return {
            total_assets:                 Math.round(totalAssets),
            total_liabilities:            Math.round(totalLiabilities),
            net_worth:                    Math.round(netWorth),
            equity_ratio:                 Math.round(equityRatio * 10) / 10,
            debt_to_asset_ratio:          Math.round(debtToAssetRatio * 10) / 10,
            liquid_assets:                Math.round(liquidAssets),
            liquidity_index:              Math.round(liquidityIndex * 10) / 10,
            crisis_resilience_months:     crisisResilienceMonths !== null ? Math.round(crisisResilienceMonths * 10) / 10 : null,
            monthly_expenses:             Math.round(monthlyExpenses),
            category_totals:              categoryTotals,
            largest_asset_category:       largestCat,
            largest_asset_category_share: Math.round(largestShare * 10) / 10,
            diversification_warning:      divWarn
        };
    }

    /* =============================================
       Update UI
       ============================================= */
    function updateUI(results, values) {
        // Net worth value + color
        var nwEl = $('#nw-net-worth');
        nwEl.text(formatCzk(results.net_worth));
        nwEl.removeClass('is-positive is-negative');
        if (results.net_worth > 0) nwEl.addClass('is-positive');
        else if (results.net_worth < 0) nwEl.addClass('is-negative');

        $('#nw-total-assets').text(formatCzk(results.total_assets));
        $('#nw-total-liabilities').text(formatCzk(results.total_liabilities));
        $('#nw-equity-ratio').text(formatPct(results.equity_ratio));
        $('#nw-liquidity-index').text(formatPct(results.liquidity_index));

        updateScale(results.total_assets, results.total_liabilities);
        updateBarometer(results.debt_to_asset_ratio, benchmarks);
        updateResilienceBlock(results.crisis_resilience_months, benchmarks);
        updateCategoryTotals(results.category_totals);
        updateDonutChart(results.category_totals, categoriesArr);
        updateDiversificationWarning(results.diversification_warning, results.largest_asset_category, results.largest_asset_category_share, categoriesArr);
        updateResultMessage(results, resultMsgs, benchmarks);
        updateRecommendations(results, cfg);
    }

    /* ---- Scale bar ---- */
    function updateScale(totalAssets, totalLiabilities) {
        var total = totalAssets + totalLiabilities;
        var assetPct  = total > 0 ? (totalAssets / total) * 100 : 50;
        var liabPct   = total > 0 ? (totalLiabilities / total) * 100 : 50;

        $('#nw-scale-assets').text(formatCzk(totalAssets));
        $('#nw-scale-liabilities').text(formatCzk(totalLiabilities));
        $('#nw-scale-bar-assets').css('width', assetPct + '%');
        $('#nw-scale-bar-liabilities').css('width', liabPct + '%');
    }

    /* ---- Barometer ---- */
    function updateBarometer(debtRatio, bench) {
        var fill   = $('#nw-barometer-fill');
        var status = $('#nw-debt-status');
        var pct    = Math.min(100, Math.max(0, debtRatio));

        $('#nw-debt-ratio-pct').text(formatPct(debtRatio));
        fill.css('width', pct + '%');
        fill.removeClass('is-orange is-red');

        var lowMax  = bench.debt_to_asset ? bench.debt_to_asset.low_max  : 30;
        var highFrom = bench.debt_to_asset ? bench.debt_to_asset.high_from : 70;

        if (debtRatio > highFrom) {
            fill.addClass('is-red');
            status.text('Vysoké zadlužení – doporučujeme aktivně splácet dluhy.');
        } else if (debtRatio > lowMax) {
            fill.addClass('is-orange');
            status.text('Střední zadlužení – sledujte vývoj závazků.');
        } else {
            status.text('Nízké zadlužení – zdravý poměr.');
        }
    }

    /* ---- Donut chart ---- */
    function updateDonutChart(categoryTotals, catsArr) {
        var labels = [];
        var data   = [];
        var colors = ['#3498db','#2ecc71','#e67e22','#9b59b6','#1abc9c','#e74c3c','#f39c12','#34495e'];
        var colorIdx = 0;
        var chartColors = [];

        // Only asset categories with value > 0
        catsArr.forEach(function (cat) {
            if ((cat.type || '') !== 'asset') return;
            if (!(cat.active !== false)) return;
            var slug = cat.slug || cat.id || '';
            var val  = categoryTotals[slug] || 0;
            if (val <= 0) return;
            labels.push(cat.name || slug);
            data.push(Math.round(val));
            chartColors.push(colors[colorIdx % colors.length]);
            colorIdx++;
        });

        var canvas = document.getElementById('nw-donut-chart');
        if (!canvas) return;

        if (data.length === 0) {
            if (donutChart) {
                donutChart.destroy();
                donutChart = null;
            }
            $('#nw-chart-section').hide();
            return;
        }

        $('#nw-chart-section').show();

        if (donutChart) {
            donutChart.data.labels        = labels;
            donutChart.data.datasets[0].data   = data;
            donutChart.data.datasets[0].backgroundColor = chartColors;
            donutChart.update();
        } else {
            donutChart = new Chart(canvas, {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        data:            data,
                        backgroundColor: chartColors,
                        borderWidth:     2,
                        borderColor:     '#fff'
                    }]
                },
                options: {
                    responsive:          true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels:   { font: { size: 11 }, padding: 10 }
                        },
                        tooltip: {
                            callbacks: {
                                label: function (ctx) {
                                    var val = ctx.parsed || 0;
                                    return ' ' + val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ' ') + ' Kč';
                                }
                            }
                        }
                    }
                }
            });
        }
    }

    /* ---- Resilience block ---- */
    function updateResilienceBlock(months, bench) {
        var valEl  = $('#nw-resilience-value');
        var hintEl = $('#nw-resilience-hint');

        valEl.removeClass('is-green is-orange is-red');

        if (months === null || months === undefined) {
            valEl.text('– měsíců');
            hintEl.text('Zadejte měsíční výdaje pro výpočet.');
            return;
        }

        valEl.text(formatMonths(months));

        var cr   = bench.crisis_resilience || {};
        var redMax   = cr.red_max_months    || 3;
        var orangeMax = cr.orange_max_months || 6;

        if (months < redMax) {
            valEl.addClass('is-red');
            hintEl.text('Kritická odolnost – zásoby by vydržely méně než ' + redMax + ' měsíce.');
        } else if (months < orangeMax) {
            valEl.addClass('is-orange');
            hintEl.text('Nízká odolnost – doporučujeme navýšit likvidní rezervy.');
        } else {
            valEl.addClass('is-green');
            hintEl.text('Dobrá odolnost – likvidní prostředky pokryjí ' + formatMonths(months) + ' výdajů.');
        }
    }

    /* ---- Diversification warning ---- */
    function updateDiversificationWarning(warning, largestCat, share, catsArr) {
        var el = $('#nw-diversification-warning');
        if (!warning) {
            el.hide();
            return;
        }
        // Find category name
        var catName = largestCat;
        catsArr.forEach(function (c) {
            if ((c.slug || c.id || '') === largestCat) catName = c.name || largestCat;
        });
        $('#nw-diversification-text').text(
            'Kategorie „' + catName + '" tvoří ' + formatPct(share) + ' vašich aktiv. Zvažte rozložení do více kategorií.'
        );
        el.show();
    }

    /* ---- Category totals in accordion headers ---- */
    function updateCategoryTotals(categoryTotals) {
        Object.keys(categoryTotals).forEach(function (slug) {
            $('#nw-cat-' + slug).text(formatCzk(categoryTotals[slug]));
        });
        // Zero out categories not in totals
        categoriesArr.forEach(function (cat) {
            var slug = cat.slug || cat.id || '';
            if (!categoryTotals[slug]) {
                $('#nw-cat-' + slug).text('0 Kč');
            }
        });
    }

    /* ---- Result message ---- */
    function updateResultMessage(results, msgs, bench) {
        var msgEl    = $('#nw-result-message');
        var titleEl  = $('#nw-message-title');
        var textEl   = $('#nw-message-text');

        if (results.total_assets === 0 && results.total_liabilities === 0) {
            msgEl.hide();
            return;
        }

        var key;
        var nw = results.net_worth;
        if (nw < 0) {
            key = 'negative_net_worth';
        } else if (nw < 500000) {
            key = 'low_net_worth';
        } else if (nw < 5000000) {
            key = 'medium_net_worth';
        } else {
            key = 'high_net_worth';
        }

        // Support both array and object messages
        var msg = null;
        if (Array.isArray(msgs)) {
            msgs.forEach(function (m) { if (m.key === key) msg = m; });
        } else {
            msg = msgs[key] || null;
        }

        if (!msg) {
            msgEl.hide();
            return;
        }

        msgEl.removeClass('is-red is-orange is-green');
        if (msg.status) msgEl.addClass('is-' + msg.status);
        titleEl.text(msg.title || '');
        textEl.text(msg.text || '');
        msgEl.show();
    }

    /* ---- Recommendations ---- */
    function updateRecommendations(results, config) {
        var recArr  = toArray(config.recommended_content || []);
        var listEl  = $('#nw-recommendation-list');
        var wrapEl  = $('#nw-recommendations');

        listEl.empty();

        var shown = 0;
        recArr.forEach(function (rec) {
            var conditions = rec.conditions || [];
            var match = false;

            conditions.forEach(function (cond) {
                if (cond === 'diversification_warning' && results.diversification_warning) match = true;
                if (cond === 'low_crisis_resilience' && results.crisis_resilience_months !== null && results.crisis_resilience_months < 3) match = true;
                if (cond === 'high_debt' && results.debt_to_asset_ratio > 70) match = true;
                if (cond === 'negative_net_worth' && results.net_worth < 0) match = true;
                if (cond === 'low_net_worth' && results.net_worth >= 0 && results.net_worth < 500000) match = true;
            });

            if (match) {
                var a = $('<a>')
                    .addClass('nw-recommendation-card')
                    .attr('href', rec.url || '#')
                    .attr('target', '_blank')
                    .attr('rel', 'noopener noreferrer')
                    .text(rec.title || '');
                listEl.append(a);
                shown++;
            }
        });

        if (shown > 0) {
            wrapEl.show();
        } else {
            wrapEl.hide();
        }
    }

    /* =============================================
       Session storage (guests)
       ============================================= */
    var SS_KEY = 'net_worth_values_ciste_jmeni';

    function saveToSessionStorage(values) {
        try {
            sessionStorage.setItem(SS_KEY, JSON.stringify(values));
        } catch (e) { /* silent */ }
    }

    function loadFromSessionStorage() {
        try {
            var raw = sessionStorage.getItem(SS_KEY);
            return raw ? JSON.parse(raw) : null;
        } catch (e) {
            return null;
        }
    }

    /* =============================================
       Server persistence (logged-in)
       ============================================= */
    function saveToServer(values) {
        if (!isLoggedIn) return;

        clearTimeout(saveTimer);
        saveTimer = setTimeout(function () {
            var statusEl = $('#nw-save-status');
            statusEl.removeClass('is-saved is-error').addClass('is-saving').text('Ukládám...').show();

            $.post(NetWorthData.ajaxurl, {
                action:          'net_worth_save_user_data',
                nonce:           NetWorthData.nonce,
                calculator_id:   calcId,
                calculator_slug: 'ciste-jmeni',
                values:          values
            })
            .done(function (res) {
                if (res && res.success) {
                    statusEl.removeClass('is-saving is-error').addClass('is-saved').text('Uloženo.');
                } else {
                    statusEl.removeClass('is-saving is-saved').addClass('is-error').text('Chyba při ukládání.');
                }
            })
            .fail(function () {
                statusEl.removeClass('is-saving is-saved').addClass('is-error').text('Chyba při ukládání.');
            });
        }, 1200);
    }

    /* =============================================
       Create snapshot
       ============================================= */
    function createSnapshot(values, results) {
        if (!isLoggedIn) return;

        $.post(NetWorthData.ajaxurl, {
            action:          'net_worth_create_snapshot',
            nonce:           NetWorthData.nonce,
            calculator_id:   calcId,
            calculator_slug: 'ciste-jmeni',
            values:          values
        })
        .done(function (res) {
            if (res && res.success && res.data && res.data.snapshot) {
                snapshots.push(res.data.snapshot);
                renderSnapshotChart();
                updateSnapshotPrompt();
                showSnapshotSection();
            }
        });
    }

    /* =============================================
       Send email
       ============================================= */
    function sendEmailReport(email, values, results) {
        var statusEl = $('#nw-modal-status');
        statusEl.removeClass('is-success is-error').text('Odesílám...');

        $.post(NetWorthData.ajaxurl, {
            action:        'net_worth_send_email_report',
            nonce:         NetWorthData.nonce,
            email:         email,
            calculator_id: calcId,
            values:        values
        })
        .done(function (res) {
            if (res && res.success) {
                statusEl.addClass('is-success').text('E-mail byl úspěšně odeslán.');
            } else {
                var msg = (res && res.data && res.data.message) ? res.data.message : 'Chyba při odesílání.';
                statusEl.addClass('is-error').text(msg);
            }
        })
        .fail(function () {
            statusEl.addClass('is-error').text('Chyba při odesílání e-mailu.');
        });
    }

    /* =============================================
       Snapshot chart (line)
       ============================================= */
    function renderSnapshotChart() {
        var canvas = document.getElementById('nw-snapshot-chart');
        if (!canvas || snapshots.length === 0) return;

        var labels = [];
        var data   = [];

        snapshots.forEach(function (s) {
            var d = s.created_at ? s.created_at.substring(0, 10) : '?';
            labels.push(d);
            var nw = s.results && s.results.net_worth !== undefined ? s.results.net_worth : 0;
            data.push(Math.round(nw));
        });

        if (snapshotChart) {
            snapshotChart.data.labels         = labels;
            snapshotChart.data.datasets[0].data = data;
            snapshotChart.update();
        } else {
            snapshotChart = new Chart(canvas, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label:            'Čisté jmění (Kč)',
                        data:             data,
                        borderColor:      '#1e3a5f',
                        backgroundColor:  'rgba(30,58,95,0.08)',
                        borderWidth:      2,
                        pointRadius:      4,
                        fill:             true,
                        tension:          0.3
                    }]
                },
                options: {
                    responsive:          true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function (ctx) {
                                    var v = ctx.parsed.y || 0;
                                    return ' ' + v.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ' ') + ' Kč';
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            ticks: {
                                callback: function (v) {
                                    return v.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ' ');
                                }
                            }
                        }
                    }
                }
            });
        }
    }

    function showSnapshotSection() {
        if (snapshots.length > 0) {
            $('#nw-snapshot-section').show();
        }
    }

    /* =============================================
       Snapshot prompt logic
       ============================================= */
    function updateSnapshotPrompt() {
        var promptEl = $('#nw-snapshot-prompt');
        if (!isLoggedIn) { promptEl.hide(); return; }

        if (snapshots.length === 0) {
            promptEl.html('<p>Uložte svůj první snapshot a sledujte vývoj čistého jmění v čase.</p>').show();
            return;
        }

        // Check last snapshot date
        var lastSnap = snapshots[snapshots.length - 1];
        var lastDate = lastSnap.created_at ? new Date(lastSnap.created_at) : null;
        if (lastDate) {
            var now        = new Date();
            var diffMonths = (now.getFullYear() - lastDate.getFullYear()) * 12 + (now.getMonth() - lastDate.getMonth());
            if (diffMonths >= 3) {
                promptEl.html('<p>Poslední snapshot je starší než 3 měsíce. Uložte aktuální stav.</p>').show();
                return;
            }
        }
        promptEl.hide();
    }

    /* =============================================
       Load saved values into inputs
       ============================================= */
    function applyValues(values) {
        if (!values || typeof values !== 'object') return;
        Object.keys(values).forEach(function (key) {
            var el = $('[name="' + key + '"]');
            if (el.length) {
                el.val(values[key]);
            }
        });
    }

    /* =============================================
       Category accordion
       ============================================= */
    function initAccordions() {
        $(document).on('click', '.net-worth__category-toggle', function () {
            var cat = $(this).closest('.net-worth__category');
            cat.toggleClass('is-open');
        });
    }

    /* =============================================
       Tooltip
       ============================================= */
    var tooltipEl = $('#nw-tooltip-popup');

    function initTooltips() {
        $(document).on('click', '.net-worth__tooltip-btn', function (e) {
            e.stopPropagation();
            var text = $(this).data('tooltip');
            if (!text) { tooltipEl.hide(); return; }

            if (tooltipEl.text() === text && tooltipEl.is(':visible')) {
                tooltipEl.hide();
                return;
            }

            tooltipEl.text(text).show();

            var btn    = $(this);
            var offset = btn.offset();
            var top    = offset.top - tooltipEl.outerHeight() - 8;
            var left   = offset.left;

            // Keep within viewport
            var maxLeft = $(window).width() - tooltipEl.outerWidth() - 10;
            if (left > maxLeft) left = maxLeft;
            if (top < 5) top = offset.top + btn.outerHeight() + 8;

            tooltipEl.css({ top: top, left: left });
        });

        $(document).on('click', function () {
            tooltipEl.hide();
        });
    }

    /* =============================================
       Email modal
       ============================================= */
    function initModal() {
        $('#nw-email-btn').on('click', function () {
            // Pre-fill email if available
            if (userEmail) {
                $('#nw-modal-email').val(userEmail);
            }
            $('#nw-modal-status').removeClass('is-success is-error').text('');
            $('#nw-email-modal, #nw-modal-overlay').show();
            $('#nw-modal-email').focus();
        });

        function closeModal() {
            $('#nw-email-modal, #nw-modal-overlay').hide();
        }

        $('#nw-modal-close, #nw-modal-overlay').on('click', closeModal);

        $('#nw-modal-send').on('click', function () {
            var email  = $('#nw-modal-email').val().trim();
            if (!email || !email.match(/^[^\s@]+@[^\s@]+\.[^\s@]+$/)) {
                $('#nw-modal-status').removeClass('is-success').addClass('is-error').text('Zadejte platnou e-mailovou adresu.');
                return;
            }
            var vals    = getAllValues();
            var results = calculateAll(vals);
            sendEmailReport(email, vals, results);
        });

        // Close on Escape
        $(document).on('keydown', function (e) {
            if (e.key === 'Escape') closeModal();
        });
    }

    /* =============================================
       Snapshot button
       ============================================= */
    function initSnapshotBtn() {
        $('#nw-snapshot-btn').on('click', function () {
            var vals    = getAllValues();
            var results = calculateAll(vals);
            createSnapshot(vals, results);
        });
    }

    /* =============================================
       Main input listener
       ============================================= */
    function initInputListeners() {
        var onChange = function () {
            var vals    = getAllValues();
            var results = calculateAll(vals);
            updateUI(results, vals);
            if (isLoggedIn) {
                saveToServer(vals);
            } else {
                saveToSessionStorage(vals);
            }
        };

        $(document).on('input change', '.net-worth__input, #nw-monthly-expenses', onChange);
    }

    /* =============================================
       Init
       ============================================= */
    $(document).ready(function () {
        // Accordion
        initAccordions();
        // Tooltips
        initTooltips();
        // Modal
        initModal();
        // Snapshot button (logged-in)
        if (isLoggedIn) {
            initSnapshotBtn();
        }
        // Input listeners
        initInputListeners();

        // Load saved values
        var loadedValues = null;
        if (isLoggedIn && NetWorthData.savedValues) {
            loadedValues = NetWorthData.savedValues;
        } else if (!isLoggedIn) {
            loadedValues = loadFromSessionStorage();
        }

        if (loadedValues) {
            applyValues(loadedValues);
        }

        // Initial calculation
        var initVals    = getAllValues();
        var initResults = calculateAll(initVals);
        updateUI(initResults, initVals);

        // Snapshot section (logged-in)
        if (isLoggedIn) {
            showSnapshotSection();
            renderSnapshotChart();
            updateSnapshotPrompt();
        }
    });

})(jQuery);
