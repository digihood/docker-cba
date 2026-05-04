/* global RetirementSavingsData, Chart, jQuery */
(function ($) {
    'use strict';

    if (typeof RetirementSavingsData === 'undefined') { return; }

    var RSD       = RetirementSavingsData;
    var chartInst = null;
    var saveTimer = null;
    var COOKIE_KEY = 'retirement_savings_values_sporeni_na_duchod';
    var COOKIE_DAYS = 30;
    var SAVE_DEBOUNCE_MS = 1200;

    // ── Cookie helpers ────────────────────────────────────────────────────
    function setCookie(name, value, days) {
        var expires = '';
        if (days) {
            var d = new Date();
            d.setTime(d.getTime() + days * 24 * 60 * 60 * 1000);
            expires = '; expires=' + d.toUTCString();
        }
        document.cookie = encodeURIComponent(name) + '=' + encodeURIComponent(JSON.stringify(value)) + expires + '; path=/; SameSite=Lax';
    }

    function getCookie(name) {
        var nameEQ = encodeURIComponent(name) + '=';
        var parts  = document.cookie.split(';');
        for (var i = 0; i < parts.length; i++) {
            var c = parts[i].trim();
            if (c.indexOf(nameEQ) === 0) {
                try {
                    return JSON.parse(decodeURIComponent(c.substring(nameEQ.length)));
                } catch (e) {
                    return null;
                }
            }
        }
        return null;
    }

    // ── Debounce ──────────────────────────────────────────────────────────
    function debounce(fn, delay) {
        var timer;
        return function () {
            var args = arguments;
            clearTimeout(timer);
            timer = setTimeout(function () { fn.apply(null, args); }, delay);
        };
    }

    // ── Format helpers ────────────────────────────────────────────────────
    function formatMoney(n) {
        return Math.round(Math.abs(n)).toLocaleString('cs-CZ') + ' Kč';
    }

    function parseNum(val) {
        var n = parseFloat(String(val).replace(/,/g, '.').replace(/\s/g, ''));
        return isFinite(n) ? n : 0;
    }

    // ── Read current form values ──────────────────────────────────────────
    function getValues() {
        return {
            current_age:           parseNum($('#rs-current-age').val()),
            retirement_age:        parseNum($('#rs-retirement-age').val()),
            payout_years:          parseNum($('#rs-payout-years').val()),
            target_pension_now:    parseNum($('#rs-target-pension-now').val()),
            current_savings:       parseNum($('#rs-current-savings').val()),
            monthly_contribution:  parseNum($('#rs-monthly-contribution').val()),
            annual_return:         parseNum($('#rs-annual-return').val()),
            inflation_rate:        parseNum($('#rs-inflation-rate').val()),
        };
    }

    // ── Apply values to form ──────────────────────────────────────────────
    function applyValues(vals) {
        var fieldMap = {
            current_age:          { num: '#rs-current-age',          slider: '#rs-current-age-slider' },
            retirement_age:       { num: '#rs-retirement-age',       slider: '#rs-retirement-age-slider' },
            payout_years:         { num: '#rs-payout-years',         slider: null },
            target_pension_now:   { num: '#rs-target-pension-now',   slider: null },
            current_savings:      { num: '#rs-current-savings',      slider: null },
            monthly_contribution: { num: '#rs-monthly-contribution', slider: null },
            annual_return:        { num: '#rs-annual-return',        slider: null },
            inflation_rate:       { num: '#rs-inflation-rate',       slider: null },
        };

        Object.keys(fieldMap).forEach(function (key) {
            if (vals[key] === undefined || vals[key] === null) { return; }
            var f = fieldMap[key];
            $(f.num).val(vals[key]);
            if (f.slider) { $(f.slider).val(vals[key]); }
        });
    }

    // ── Validate ──────────────────────────────────────────────────────────
    function validate(vals) {
        var errors = [];
        var inputs = RSD.defaultInputs || {};

        var currentAge    = vals.current_age;
        var retirementAge = vals.retirement_age;

        var currentAgeConf = inputs.current_age || {};
        var retAgeConf     = inputs.retirement_age || {};
        var payoutConf     = inputs.payout_years || {};
        var returnConf     = inputs.annual_return || {};
        var inflConf       = inputs.inflation_rate || {};

        if (currentAge < (currentAgeConf.min || 18) || currentAge > (currentAgeConf.max || 80)) {
            errors.push('Současný věk musí být mezi ' + (currentAgeConf.min || 18) + ' a ' + (currentAgeConf.max || 80) + ' lety.');
        }
        if (retirementAge < (retAgeConf.min || 40) || retirementAge > (retAgeConf.max || 90)) {
            errors.push('Věk odchodu do důchodu musí být mezi ' + (retAgeConf.min || 40) + ' a ' + (retAgeConf.max || 90) + ' lety.');
        }
        if (retirementAge <= currentAge) {
            errors.push('Věk odchodu do důchodu musí být vyšší než současný věk.');
        }
        if (vals.payout_years < (payoutConf.min || 1) || vals.payout_years > (payoutConf.max || 40)) {
            errors.push('Délka čerpání renty musí být mezi ' + (payoutConf.min || 1) + ' a ' + (payoutConf.max || 40) + ' lety.');
        }
        if (vals.target_pension_now < 0) {
            errors.push('Cílová renta nemůže být záporná.');
        }
        if (vals.current_savings < 0) {
            errors.push('Aktuálně naspořená částka nemůže být záporná.');
        }
        if (vals.monthly_contribution < 0) {
            errors.push('Měsíční příspěvek nemůže být záporný.');
        }
        if (vals.annual_return < (returnConf.min || 0) || vals.annual_return > (returnConf.max || 30)) {
            errors.push('Roční výnos musí být mezi ' + (returnConf.min || 0) + ' a ' + (returnConf.max || 30) + ' %.');
        }
        if (vals.inflation_rate < (inflConf.min || 0) || vals.inflation_rate > (inflConf.max || 20)) {
            errors.push('Inflace musí být mezi ' + (inflConf.min || 0) + ' a ' + (inflConf.max || 20) + ' %.');
        }

        return { valid: errors.length === 0, errors: errors };
    }

    // ── Core calculation ──────────────────────────────────────────────────
    function calculate(vals) {
        var currentAge           = Math.round(vals.current_age);
        var retirementAge        = Math.round(vals.retirement_age);
        var payoutYears          = Math.round(vals.payout_years);
        var targetPensionNow     = vals.target_pension_now;
        var currentSavings       = vals.current_savings;
        var monthlyContribution  = vals.monthly_contribution;
        var annualReturn         = vals.annual_return;
        var inflationRate        = vals.inflation_rate;

        var n = retirementAge - currentAge;
        if (n <= 0) { return null; }

        var EPS    = 0.000001;
        var rReal  = ((1 + annualReturn / 100) / (1 + inflationRate / 100)) - 1;

        // Target capital (real terms)
        var sTarget;
        if (Math.abs(rReal) < EPS) {
            sTarget = targetPensionNow * 12 * payoutYears;
        } else {
            sTarget = targetPensionNow * 12 * ((1 - Math.pow(1 + rReal, -payoutYears)) / rReal);
        }

        // Projected capital (real terms)
        var v1, v2;
        if (Math.abs(rReal) < EPS) {
            v1 = currentSavings;
            v2 = monthlyContribution * 12 * n;
        } else {
            v1 = currentSavings * Math.pow(1 + rReal, n);
            v2 = monthlyContribution * 12 * ((Math.pow(1 + rReal, n) - 1) / rReal);
        }
        var sReal = v1 + v2;

        var gap = sTarget - sReal;

        // Future nominal monthly pension
        var futureMonthlyPensionNominal = targetPensionNow * Math.pow(1 + inflationRate / 100, n);

        // Additional monthly contribution to close gap
        var additionalMonthly = 0;
        if (gap > 0) {
            if (Math.abs(rReal) < EPS) {
                additionalMonthly = gap / (12 * n);
            } else {
                additionalMonthly = (gap / ((Math.pow(1 + rReal, n) - 1) / rReal)) / 12;
            }
        }

        // Status
        var status;
        if (gap <= 0) {
            status = 'goal_reached';
        } else if (sTarget > 0 && (gap / sTarget) > 0.25) {
            status = 'critical_gap';
        } else {
            status = 'moderate_gap';
        }

        return {
            target_amount:                   Math.round(sTarget),
            projected_amount:                Math.round(sReal),
            retirement_gap:                  Math.round(gap),
            years_to_retirement:             n,
            future_monthly_pension_nominal:  Math.round(futureMonthlyPensionNominal),
            additional_monthly_contribution: Math.round(additionalMonthly),
            status:                          status,
            r_real:                          rReal,
        };
    }

    // ── Get result message ────────────────────────────────────────────────
    function getResultMessage(status) {
        var msgs = RSD.resultMessages || {};
        return msgs[status] || { status: 'orange', title: '—', text: '' };
    }

    // ── Update UI ─────────────────────────────────────────────────────────
    function updateUI(results) {
        if (!results) { return; }

        var statusColors = {
            goal_reached: '#38a169',
            moderate_gap: '#dd6b20',
            critical_gap: '#e53e3e',
        };

        // Years to retirement
        $('#rs-years-to-retirement').text(results.years_to_retirement);

        // Target amount
        $('#rs-target-amount').text(formatMoney(results.target_amount));

        // Projected amount
        $('#rs-projected-amount').text(formatMoney(results.projected_amount));

        // Gap block
        var $gapBlock = $('#rs-gap-block');
        $gapBlock.removeClass('retirement-savings__gap--positive retirement-savings__gap--negative retirement-savings__gap--moderate');

        if (results.retirement_gap <= 0) {
            $('#rs-gap-amount').text('+ ' + formatMoney(Math.abs(results.retirement_gap)));
            $gapBlock.addClass('retirement-savings__gap--positive');
            $('#rs-gap-status-text').text('Přebytek oproti cíli');
        } else {
            $('#rs-gap-amount').text('- ' + formatMoney(results.retirement_gap));
            if (results.status === 'critical_gap') {
                $gapBlock.addClass('retirement-savings__gap--negative');
                $('#rs-gap-status-text').text('Kritická mezera');
            } else {
                $gapBlock.addClass('retirement-savings__gap--moderate');
                $('#rs-gap-status-text').text('Mírná mezera');
            }
        }

        // Future value text
        $('#rs-future-value-text').text(
            'Vaše cílová renta ' + Math.round(results.years_to_retirement) +
            ' let odpovídá nominálně přibližně ' +
            formatMoney(results.future_monthly_pension_nominal) + ' za měsíc.'
        );

        // CTA block
        var msg    = getResultMessage(results.status);
        var $cta   = $('#rs-cta-block');
        $cta.removeClass('retirement-savings__cta--positive retirement-savings__cta--negative retirement-savings__cta--moderate');

        var ctaClass = 'retirement-savings__cta--positive';
        if (results.status === 'critical_gap') { ctaClass = 'retirement-savings__cta--negative'; }
        if (results.status === 'moderate_gap') { ctaClass = 'retirement-savings__cta--moderate'; }
        $cta.addClass(ctaClass);

        $('#rs-cta-title').text(msg.title || '');
        $('#rs-cta-text').text(msg.text || '');

        var $addContrib = $('#rs-additional-contribution');
        if (results.additional_monthly_contribution > 0) {
            $addContrib.text(
                'Pro uzavření mezery byste měli přispívat o ' +
                formatMoney(results.additional_monthly_contribution) +
                ' měsíčně navíc.'
            ).prop('hidden', false);
        } else {
            $addContrib.prop('hidden', true);
        }

        // Chart
        updateChart(results);
    }

    // ── Chart ─────────────────────────────────────────────────────────────
    function updateChart(results) {
        var ctx = document.getElementById('rs-chart');
        if (!ctx) { return; }

        var statusColors = {
            goal_reached: '#38a169',
            moderate_gap: '#dd6b20',
            critical_gap: '#e53e3e',
        };
        var projectedColor = statusColors[results.status] || '#3182ce';

        var labels   = ['Potřebujete mít', 'Pravděpodobně budete mít'];
        var amounts  = [results.target_amount, results.projected_amount];
        var bgColors = ['#3182ce', projectedColor];

        if (chartInst) {
            chartInst.destroy();
            chartInst = null;
        }

        chartInst = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    data:            amounts,
                    backgroundColor: bgColors,
                    borderRadius:    6,
                    borderSkipped:   false,
                }],
            },
            options: {
                responsive:          true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                return ' ' + Math.round(context.parsed.y).toLocaleString('cs-CZ') + ' Kč';
                            },
                        },
                    },
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function (value) {
                                if (value >= 1000000) { return (value / 1000000).toFixed(1) + ' M'; }
                                if (value >= 1000)    { return (value / 1000).toFixed(0) + ' tis.'; }
                                return value;
                            },
                        },
                    },
                },
            },
        });
    }

    // ── Error banner ──────────────────────────────────────────────────────
    function showErrors(errors) {
        if (!errors || errors.length === 0) {
            $('#rs-error-banner').prop('hidden', true);
            return;
        }
        $('#rs-error-text').text(errors.join(' '));
        $('#rs-error-banner').prop('hidden', false);
    }

    function hideErrors() {
        $('#rs-error-banner').prop('hidden', true);
    }

    // ── Persist ───────────────────────────────────────────────────────────
    function saveToSession(vals) {
        setCookie(COOKIE_KEY, vals, COOKIE_DAYS);
    }

    var saveToServerDebounced = debounce(function (vals) {
        $.post(RSD.ajaxurl, {
            action:          'retirement_savings_save_user_data',
            nonce:           RSD.nonce,
            calculator_id:   RSD.calculatorId,
            calculator_slug: RSD.calculatorSlug,
            values:          vals,
        });
    }, SAVE_DEBOUNCE_MS);

    function saveValues(vals) {
        if (RSD.isLoggedIn) {
            saveToServerDebounced(vals);
        } else {
            saveToSession(vals);
        }
    }

    // ── Main recalculate ──────────────────────────────────────────────────
    function recalculate() {
        var vals       = getValues();
        var validation = validate(vals);

        if (!validation.valid) {
            showErrors(validation.errors);
            return;
        }

        hideErrors();

        var results = calculate(vals);
        if (!results) {
            showErrors(['Věk odchodu do důchodu musí být vyšší než současný věk.']);
            return;
        }

        updateUI(results);
        saveValues(vals);
    }

    // ── Slider ↔ number two-way sync ──────────────────────────────────────
    function bindSliderSync(sliderId, numberId) {
        var $slider = $(sliderId);
        var $number = $(numberId);

        $slider.on('input change', function () {
            $number.val($(this).val());
            recalculate();
        });

        $number.on('input change', function () {
            var val = parseNum($(this).val());
            var min = parseNum($slider.attr('min'));
            var max = parseNum($slider.attr('max'));
            if (val >= min && val <= max) {
                $slider.val(val);
            }
            recalculate();
        });
    }

    // ── Share URL ─────────────────────────────────────────────────────────
    function buildShareUrl() {
        var vals   = getValues();
        var base   = RSD.shareBaseUrl || window.location.href.split('?')[0];
        var params = new URLSearchParams();

        Object.keys(vals).forEach(function (key) {
            params.set(key, vals[key]);
        });

        return base + '?' + params.toString();
    }

    function handleShare() {
        var url = buildShareUrl();
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(url).then(function () {
                showShareMsg();
            }).catch(function () {
                fallbackCopyText(url);
            });
        } else {
            fallbackCopyText(url);
        }
    }

    function fallbackCopyText(text) {
        var ta = document.createElement('textarea');
        ta.value = text;
        ta.style.position = 'fixed';
        ta.style.opacity  = '0';
        document.body.appendChild(ta);
        ta.focus();
        ta.select();
        try { document.execCommand('copy'); } catch (e) { /* ignore */ }
        document.body.removeChild(ta);
        showShareMsg();
    }

    function showShareMsg() {
        var $msg = $('#rs-share-msg');
        $msg.prop('hidden', false);
        setTimeout(function () { $msg.prop('hidden', true); }, 2000);
    }

    // ── Load URL params ───────────────────────────────────────────────────
    function loadFromUrl() {
        var params   = new URLSearchParams(window.location.search);
        var keys     = ['current_age', 'retirement_age', 'payout_years', 'target_pension_now',
                        'current_savings', 'monthly_contribution', 'annual_return', 'inflation_rate'];
        var found    = false;
        var urlVals  = {};

        keys.forEach(function (key) {
            if (params.has(key)) {
                urlVals[key] = parseNum(params.get(key));
                found = true;
            }
        });

        if (found) {
            applyValues(urlVals);
            return true;
        }
        return false;
    }

    // ── Tooltip ───────────────────────────────────────────────────────────
    function initTooltips() {
        $(document).on('click', '.retirement-savings__tooltip-btn', function (e) {
            e.stopPropagation();
            var $btn = $(this);
            var tip  = $btn.attr('data-tip');

            // Close any open tooltips
            $('.retirement-savings__tooltip-popup').remove();

            var $popup = $('<div class="retirement-savings__tooltip-popup"></div>').text(tip);
            $btn.after($popup);

            // Auto-close on outside click
            $(document).one('click.rsTip', function () {
                $popup.remove();
            });
        });
    }

    // ── Build defaults from config ────────────────────────────────────────
    function getDefaultValues() {
        var inputs   = RSD.defaultInputs || {};
        var defaults = {};
        var keys = ['current_age', 'retirement_age', 'payout_years', 'target_pension_now',
                    'current_savings', 'monthly_contribution', 'annual_return', 'inflation_rate'];
        keys.forEach(function (key) {
            defaults[key] = (inputs[key] && inputs[key]['default'] !== undefined)
                ? inputs[key]['default']
                : 0;
        });
        return defaults;
    }

    // ── Init ──────────────────────────────────────────────────────────────
    $(function () {

        // Hide guest CTA if logged in
        if (RSD.isLoggedIn) {
            $('#rs-guest-cta').hide();
        }

        // Load priority: URL params > savedValues > cookie > defaults
        var loaded = loadFromUrl();

        if (!loaded) {
            if (RSD.isLoggedIn && RSD.savedValues && typeof RSD.savedValues === 'object' && Object.keys(RSD.savedValues).length > 0) {
                applyValues(RSD.savedValues);
                loaded = true;
            }
        }

        if (!loaded) {
            var cookieVals = getCookie(COOKIE_KEY);
            if (cookieVals && typeof cookieVals === 'object') {
                applyValues(cookieVals);
                loaded = true;
            }
        }

        if (!loaded) {
            applyValues(getDefaultValues());
        }

        // Slider two-way sync
        bindSliderSync('#rs-current-age-slider', '#rs-current-age');
        bindSliderSync('#rs-retirement-age-slider', '#rs-retirement-age');

        // Plain number inputs (non-slider)
        $('#rs-payout-years, #rs-target-pension-now, #rs-current-savings, #rs-monthly-contribution, #rs-annual-return, #rs-inflation-rate')
            .on('input change', function () {
                recalculate();
            });

        // Share button
        $('#rs-share-btn').on('click', function () {
            handleShare();
        });

        // Init tooltips
        initTooltips();

        // Initial calculation
        recalculate();
    });

}(jQuery));
