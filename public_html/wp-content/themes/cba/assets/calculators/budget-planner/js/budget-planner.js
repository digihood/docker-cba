/* global BudgetPlannerData, Chart */
(function ($) {
    'use strict';

    if (typeof BudgetPlannerData === 'undefined') return;

    var data      = BudgetPlannerData;
    var chartInst = null;
    var saveTimer = null;
    var SESSION_KEY = 'budget_planner_values_' + data.calculatorSlug.replace(/-/g, '_');

    // ── Helpers ──────────────────────────────────────────────────────────
    function parseMoney(val) {
        var n = parseFloat(String(val).replace(/,/g, '.').replace(/\s/g, ''));
        return isFinite(n) && n >= 0 ? n : 0;
    }

    function formatMoney(n) {
        return Math.round(Math.abs(n)).toLocaleString('cs-CZ') + ' Kč';
    }

    function getHealthMessage(saving) {
        var msgs = data.resultMessages || [];
        for (var i = 0; i < msgs.length; i++) {
            var m = msgs[i];
            var aboveMin = (m.min === null || m.min === undefined) ? true : saving >= m.min;
            var belowMax = (m.max === null || m.max === undefined) ? true : saving <= m.max;
            if (aboveMin && belowMax) return m;
        }
        return { status: 'orange', title: '–', text: '' };
    }

    // ── Read current values from form ──────────────────────────────────
    function getFormValues() {
        var vals = {};
        $('#budget-planner-form .budget-planner__input').each(function () {
            vals[$(this).data('item-slug')] = parseMoney($(this).val());
        });
        return vals;
    }

    // ── Calculate totals ──────────────────────────────────────────────
    function calcTotals(vals) {
        var totalIncome = 0, totalFixed = 0, totalVariable = 0, totalSavings = 0;
        (data.items || []).forEach(function (item) {
            var v = parseMoney(vals[item.slug] || 0);
            if (item.type === 'income')           totalIncome   += v;
            else if (item.type === 'fixed_expense')    totalFixed    += v;
            else if (item.type === 'variable_expense') totalVariable += v;
            else if (item.type === 'savings')          totalSavings  += v;
        });
        var totalExpenses = totalFixed + totalVariable + totalSavings;
        return {
            income:   totalIncome,
            expenses: totalExpenses,
            fixed:    totalFixed,
            variable: totalVariable,
            savings:  totalSavings,
            monthly:  totalIncome - totalExpenses,
        };
    }

    // ── Update UI ─────────────────────────────────────────────────────
    function updateUI(totals) {
        var monthly = totals.monthly;
        var isPositive = monthly >= 0;

        // Summary panel
        $('#bp-total-income').text(formatMoney(totals.income));
        $('#bp-total-expenses').text(formatMoney(totals.expenses));
        $('#bp-monthly-saving').text(formatMoney(monthly));
        $('#bp-saving-label').text(isPositive ? data.strings.monthlySaving : data.strings.monthlyDeficit);
        var savingColor = isPositive ? (monthly >= 5000 ? '#38a169' : '#dd6b20') : '#e53e3e';
        $('#bp-monthly-saving').css('color', savingColor);

        // Health
        var msg = getHealthMessage(monthly);
        var dot = $('#bp-health-dot');
        dot.removeClass('budget-planner__health-dot--red budget-planner__health-dot--orange budget-planner__health-dot--green');
        dot.addClass('budget-planner__health-dot--' + msg.status);
        $('#bp-health-title').text(msg.title);
        $('#bp-health-text').text(msg.text);

        // Sticky bar
        $('#bp-sticky-value').text(formatMoney(monthly));
        $('#bp-sticky-label').text(isPositive ? 'Měsíční úspora:' : 'Jste v mínusu:');
        var stickyDot = $('#bp-sticky-dot');
        stickyDot.removeClass('budget-planner__sticky-health--red budget-planner__sticky-health--orange budget-planner__sticky-health--green');
        stickyDot.addClass('budget-planner__sticky-health--' + msg.status);

        // Simulator
        var saving5y = totals.variable * 0.10 * 60;
        $('#bp-simulator-text').text(
            'Pokud byste snížili variabilní výdaje o 10 %, mohli byste za 5 let ušetřit přibližně ' +
            Math.round(saving5y).toLocaleString('cs-CZ') + ' Kč.'
        );

        // Chart
        updateChart(getFormValues());
    }

    // ── Chart ─────────────────────────────────────────────────────────
    function buildChartData(vals) {
        var categories  = {};
        var catNames    = {};

        (data.categories || []).forEach(function (cat) {
            catNames[cat.id] = cat.name;
        });

        (data.items || []).forEach(function (item) {
            if (item.type === 'income') return;
            var v = parseMoney(vals[item.slug] || 0);
            if (v <= 0) return;
            var cid = item.category;
            categories[cid] = (categories[cid] || 0) + v;
        });

        var labels = [], amounts = [];
        Object.keys(categories).forEach(function (cid) {
            if (categories[cid] > 0) {
                labels.push(catNames[cid] || cid);
                amounts.push(categories[cid]);
            }
        });
        return { labels: labels, amounts: amounts };
    }

    var CHART_COLORS = [
        '#3182ce','#38a169','#dd6b20','#805ad5','#e53e3e',
        '#d69e2e','#319795','#ed64a6','#667eea','#f6ad55',
        '#68d391','#fc8181',
    ];

    function updateChart(vals) {
        var cd = buildChartData(vals);
        var ctx = document.getElementById('bp-expenses-chart');
        if (!ctx) return;

        if (cd.labels.length === 0) {
            if (chartInst) { chartInst.destroy(); chartInst = null; }
            return;
        }

        if (chartInst) {
            chartInst.data.labels = cd.labels;
            chartInst.data.datasets[0].data = cd.amounts;
            chartInst.update();
        } else {
            chartInst = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: cd.labels,
                    datasets: [{
                        data: cd.amounts,
                        backgroundColor: CHART_COLORS.slice(0, cd.labels.length),
                        borderWidth: 2,
                        borderColor: '#fff',
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function (ctx) {
                                    return ' ' + Math.round(ctx.parsed).toLocaleString('cs-CZ') + ' Kč';
                                },
                            },
                        },
                    },
                },
            });
        }
    }

    // ── Persistence ───────────────────────────────────────────────────
    function loadSavedValues() {
        if (data.isLoggedIn && data.savedValues && Object.keys(data.savedValues).length > 0) {
            applyValues(data.savedValues);
        } else {
            try {
                var raw = sessionStorage.getItem(SESSION_KEY);
                if (raw) {
                    var parsed = JSON.parse(raw);
                    if (parsed && typeof parsed === 'object') applyValues(parsed);
                }
            } catch (e) { /* ignore */ }
        }
    }

    function applyValues(vals) {
        Object.keys(vals).forEach(function (slug) {
            var input = $('#budget-planner-form [data-item-slug="' + slug + '"]');
            if (input.length) {
                input.val(vals[slug] > 0 ? vals[slug] : '');
            }
        });
        recalculate();
    }

    function saveToSession(vals) {
        try {
            sessionStorage.setItem(SESSION_KEY, JSON.stringify(vals));
        } catch (e) { /* ignore */ }
    }

    function saveToServer(vals) {
        clearTimeout(saveTimer);
        saveTimer = setTimeout(function () {
            $.post(data.ajaxUrl, {
                action:          'budget_planner_save_user_data',
                nonce:           data.nonce,
                calculator_id:   data.calculatorId,
                calculator_slug: data.calculatorSlug,
                values:          vals,
            });
        }, 1200);
    }

    // ── Recalculate ───────────────────────────────────────────────────
    function recalculate() {
        var vals   = getFormValues();
        var totals = calcTotals(vals);
        updateUI(totals);

        if (data.isLoggedIn) {
            saveToServer(vals);
        } else {
            saveToSession(vals);
        }
    }

    // ── Email modal ───────────────────────────────────────────────────
    function openModal() {
        if (data.isLoggedIn && data.userEmail) {
            sendEmailReport(data.userEmail);
            return;
        }
        $('#bp-modal-overlay').addClass('is-open').attr('aria-hidden', 'false');
        $('#bp-email-input').val('').focus();
        $('#bp-email-error').text('');
        $('#bp-modal-feedback').text('').removeClass('is-success is-error');
    }

    function closeModal() {
        $('#bp-modal-overlay').removeClass('is-open').attr('aria-hidden', 'true');
    }

    function buildEmailPayload() {
        var vals   = getFormValues();
        var totals = calcTotals(vals);
        var msg    = getHealthMessage(totals.monthly);

        var itemsOut = [];
        (data.items || []).forEach(function (item) {
            var v = parseMoney(vals[item.slug] || 0);
            if (v > 0) {
                itemsOut.push({ slug: item.slug, name: item.name, value: v, type: item.type, category: item.category });
            }
        });

        return {
            total_income:   totals.income,
            total_expenses: totals.expenses,
            monthly_saving: totals.monthly,
            health_status:  msg.status,
            health_title:   msg.title,
            savings_5y:     totals.variable * 0.10 * 60,
            items:          itemsOut,
            categories:     data.categories,
        };
    }

    function sendEmailReport(email) {
        var payload = buildEmailPayload();
        var $btn    = $('#bp-modal-send-btn, #bp-send-email-btn');
        $btn.prop('disabled', true).text('Odesílám…');

        $.post(data.ajaxUrl, {
            action:        'budget_planner_send_email_report',
            nonce:         data.nonce,
            calculator_id: data.calculatorId,
            email:         email,
            payload:       JSON.stringify(payload),
        }, function (res) {
            $btn.prop('disabled', false).text('Poslat výsledky na e-mail');
            if (res.success) {
                if ($('#bp-modal-overlay').hasClass('is-open')) {
                    $('#bp-modal-feedback').text(res.data.message).addClass('is-success');
                    setTimeout(closeModal, 2500);
                } else {
                    alert(res.data.message);
                }
            } else {
                var errMsg = (res.data && res.data.message) ? res.data.message : data.strings.emailError;
                if ($('#bp-modal-overlay').hasClass('is-open')) {
                    $('#bp-modal-feedback').text(errMsg).addClass('is-error');
                } else {
                    alert(errMsg);
                }
            }
        }).fail(function () {
            $btn.prop('disabled', false).text('Poslat výsledky na e-mail');
        });
    }

    // ── Init ──────────────────────────────────────────────────────────
    $(function () {
        loadSavedValues();

        // Input change
        $(document).on('input change', '#budget-planner-form .budget-planner__input', function () {
            recalculate();
        });

        // Send email button
        $(document).on('click', '#bp-send-email-btn', function () {
            openModal();
        });

        // Modal send
        $(document).on('click', '#bp-modal-send-btn', function () {
            var email = $.trim($('#bp-email-input').val());
            if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                $('#bp-email-error').text(data.strings.invalidEmail);
                return;
            }
            $('#bp-email-error').text('');
            sendEmailReport(email);
        });

        // Modal close
        $(document).on('click', '#bp-modal-close, #bp-modal-cancel-btn', closeModal);
        $(document).on('click', '#bp-modal-overlay', function (e) {
            if ($(e.target).is('#bp-modal-overlay')) closeModal();
        });
        $(document).on('keydown', function (e) {
            if (e.key === 'Escape') closeModal();
        });
    });

}(jQuery));
