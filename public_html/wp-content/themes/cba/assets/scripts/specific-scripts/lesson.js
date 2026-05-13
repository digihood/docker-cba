/* Foxo Lesson – complete button + dots */
(function ($) {
    'use strict';

    const D = window.FoxoLessonData || {};
    if (!D.lessonId) return;

    const $btn = $('#foxo-complete-btn');
    const $msg = $('#foxo-complete-msg');

    $btn.on('click', function () {
        if ($btn.prop('disabled')) return;

        $btn.prop('disabled', true).text(D.i18n.completing || 'Ukládám…');
        $msg.attr('hidden', true);

        $.ajax({
            url: D.restUrl + 'lessons/' + D.lessonId + '/complete',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({ course_id: D.courseId }),
            headers: { 'X-WP-Nonce': D.nonce },
            success: function (res) {
                $btn.text(D.i18n.completed || 'Lekce dokončena ✓').addClass('button--completed');
                $msg.text(res.progress !== undefined ? res.progress + ' % kurzu dokončeno' : '').removeAttr('hidden');

                // Update progress bar if on page
                $('.foxo-progress-bar__fill').css('width', (res.progress || 0) + '%');

                // Update dots
                $('.foxo-lesson-dot--current')
                    .removeClass('foxo-lesson-dot--current')
                    .addClass('foxo-lesson-dot--completed');

                // Offer redirect after short delay
                setTimeout(function () {
                    if (res.next_lesson_url) {
                        redirectWithBanner(res.next_lesson_url, D.i18n.nextLesson || 'Pokračovat');
                    } else if (res.final_quiz_url) {
                        redirectWithBanner(res.final_quiz_url, D.i18n.finalQuiz || 'Závěrečný kvíz');
                    } else if (res.course_url) {
                        redirectWithBanner(res.course_url, D.i18n.backToCourse || 'Zpět na kurz');
                    }
                }, 1200);
            },
            error: function () {
                $btn.prop('disabled', false).text('Dokončit lekci');
                $msg.text(D.i18n.error || 'Chyba. Zkuste to znovu.').removeAttr('hidden');
            }
        });
    });

    // Primary button classes – must match d1g1BuilderButton::BASE + PRIMARY constants so Tailwind compiles them
    const BTN_CLASS_PRIMARY = 'inline-flex items-center justify-center w-fit text-sm font-bold leading-none py-3.5 px-7 border-2 border-transparent rounded-full no-underline uppercase tracking-[0.07em] font-display transition-colors cursor-pointer whitespace-nowrap hover:no-underline focus:no-underline focus:outline-none bg-primary border-primary text-white hover:bg-primary-dark hover:border-primary-dark hover:text-white';

    function redirectWithBanner(url, label) {
        $msg.html('<a href="' + url + '" class="' + BTN_CLASS_PRIMARY + '">' + label + ' →</a>').removeAttr('hidden');
    }

})(jQuery);
