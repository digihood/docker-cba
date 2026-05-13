/* Foxo Quiz – frontend logic */
(function ($) {
    'use strict';

    const D = window.FoxoQuizData || {};
    const quizId = $('.foxo-quiz').data('quiz-id');

    if (!quizId) return;

    const $form    = $('#foxo-quiz-form');
    const $result  = $('#foxo-quiz-result');
    const $submit  = $('#foxo-quiz-submit');
    const $valMsg  = $('.foxo-quiz__validation-msg');

    // --- Submit quiz ---
    $form.on('submit', function (e) {
        e.preventDefault();
        if (!validateAnswers()) return;

        $submit.prop('disabled', true).text(D.i18n.submitting || 'Vyhodnocuji…');
        $valMsg.attr('hidden', true);

        const answers = collectAnswers();

        $.ajax({
            url: D.restUrl + quizId + '/evaluate',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({ answers }),
            headers: { 'X-WP-Nonce': D.nonce },
            success: function (res) {
                showResult(res);
                if (D.userId) {
                    recordVisit();
                }
            },
            error: function () {
                $valMsg.text(D.i18n.error || 'Chyba.').removeAttr('hidden');
                $submit.prop('disabled', false).text(D.i18n.submit || 'Vyhodnotit');
            }
        });
    });

    function collectAnswers() {
        const answers = {};
        $('.foxo-quiz__question').each(function () {
            const uid  = $(this).data('question-uid');
            const type = $(this).data('type');
            if (type === 'single_choice') {
                const val = $(this).find('input[type=radio]:checked').val();
                if (val) answers[uid] = val;
            } else {
                const vals = [];
                $(this).find('input[type=checkbox]:checked').each(function () {
                    vals.push($(this).val());
                });
                if (vals.length) answers[uid] = vals;
            }
        });
        return answers;
    }

    function validateAnswers() {
        let valid = true;
        $('.foxo-quiz__question').each(function () {
            const type    = $(this).data('type');
            const checked = type === 'single_choice'
                ? $(this).find('input[type=radio]:checked').length
                : $(this).find('input[type=checkbox]:checked').length;
            if (!checked) { valid = false; }
        });
        if (!valid) {
            $valMsg.text(D.i18n.unanswered || 'Odpovězte prosím na všechny otázky.').removeAttr('hidden');
        }
        return valid;
    }

    function showResult(res) {
        $form.hide();

        $('#foxo-result-pct').text(res.percentage + ' %');
        const label = res.passed ? (D.i18n.passed || 'Splněno!') : (D.i18n.failed || 'Nesplněno.');
        $('#foxo-result-label').text(label);
        $('#foxo-result-detail').text(
            res.score + ' ' + (D.i18n.outOf || 'z') + ' ' + res.maxScore + ' ' + (D.i18n.points || 'bodů')
        );

        if (res.resultText) {
            $('#foxo-result-text').html(res.resultText);
        }

        // Mark questions if answers included
        if (res.questionResults && Object.keys(res.questionResults).length) {
            markQuestions(res.questionResults);
        }

        $result.removeAttr('hidden');
        $result[0].scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    function markQuestions(qResults) {
        $('.foxo-quiz__question').each(function () {
            const uid = $(this).data('question-uid');
            const qr  = qResults[uid];
            if (!qr) return;

            const $resultEl = $(this).find('.foxo-quiz__question-result');
            $resultEl.find('.foxo-quiz__question-result-icon').text(qr.is_correct ? '✓' : '✗');
            $resultEl.find('.foxo-quiz__question-result-text').text(
                qr.is_correct ? (D.i18n.correct || 'Správně') : (D.i18n.incorrect || 'Špatně')
            );
            $resultEl.removeAttr('hidden');

            $(this).addClass(qr.is_correct ? 'foxo-quiz__question--correct' : 'foxo-quiz__question--incorrect');

            // Show answer feedback
            if (qr.feedback && qr.feedback.length) {
                $(this).find('.foxo-quiz__answer-feedback').each(function (i) {
                    if (qr.feedback[i]) {
                        $(this).text(qr.feedback[i]).removeAttr('hidden');
                    }
                });
            }
        });
    }

    function recordVisit() {
        $.ajax({
            url: D.restUrl + quizId + '/visit',
            method: 'POST',
            headers: { 'X-WP-Nonce': D.nonce }
        });
    }

    // --- Retry ---
    $('#foxo-quiz-retry').on('click', function () {
        $result.attr('hidden', true);
        $form.show();
        $form[0].reset();
        $('.foxo-quiz__question').removeClass('foxo-quiz__question--correct foxo-quiz__question--incorrect');
        $('.foxo-quiz__question-result').attr('hidden', true);
        $submit.prop('disabled', false).text(D.i18n.submit || 'Vyhodnotit');
        $form[0].scrollIntoView({ behavior: 'smooth', block: 'start' });
    });

})(jQuery);
