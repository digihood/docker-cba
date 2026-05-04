(function ($) {
    'use strict';

    if (typeof CbaAccountData === 'undefined') return;

    // --- Password toggle section ---
    const $toggleBtn      = $('#toggle-password-section');
    const $passwordSection = $('#password-section');
    const $passwordInput  = $('#account-password');
    const $showPassBtn    = $('.account-form__show-pass');
    const $strengthLabel  = $('#password-strength');

    $toggleBtn.on('click', function () {
        const expanded = $(this).attr('aria-expanded') === 'true';
        $(this).attr('aria-expanded', String(!expanded));
        $passwordSection.prop('hidden', expanded);
        if (expanded) $passwordInput.val('');
    });

    $showPassBtn.on('click', function () {
        const type = $passwordInput.attr('type') === 'password' ? 'text' : 'password';
        $passwordInput.attr('type', type);
    });

    // --- Password strength ---
    $passwordInput.on('input', function () {
        const val = $(this).val();
        if (!val) { $strengthLabel.text('').attr('class', 'account-form__strength'); return; }
        let score = 0;
        if (val.length >= 8)  score++;
        if (val.length >= 12) score++;
        if (/[A-Z]/.test(val) && /[a-z]/.test(val)) score++;
        if (/\d/.test(val))   score++;
        if (/[^a-zA-Z0-9]/.test(val)) score++;

        const levels = [
            { min: 0, cls: '', label: '' },
            { min: 1, cls: 'account-form__strength--weak',   label: 'Slabé heslo' },
            { min: 3, cls: 'account-form__strength--medium', label: 'Středně silné heslo' },
            { min: 4, cls: 'account-form__strength--strong', label: 'Silné heslo' },
        ];
        const level = levels.slice().reverse().find(l => score >= l.min);
        $strengthLabel.text(level.label).attr('class', 'account-form__strength ' + level.cls);
    });

    // --- Profile form submission ---
    const $form    = $('#account-profile-form');
    const $saveBtn = $('#account-save-btn');
    const $message = $('#account-profile-message');

    function showMessage(text, type) {
        $message
            .text(text)
            .attr('class', 'account-message account-message--' + type)
            .prop('hidden', false);
        $('html, body').animate({ scrollTop: $message.offset().top - 100 }, 300);
    }

    $form.on('submit', function (e) {
        e.preventDefault();

        const email = $('#account-email').val().trim();
        if (!email) {
            showMessage('Vyplňte e-mail.', 'error');
            return;
        }

        const password = $passwordInput.val();
        if (password && password.length < 8) {
            showMessage('Heslo musí mít alespoň 8 znaků.', 'error');
            return;
        }

        $saveBtn.prop('disabled', true).text(CbaAccountData.i18n.saving);
        $message.prop('hidden', true);

        $.ajax({
            url:    CbaAccountData.ajaxurl,
            method: 'POST',
            data: {
                action:     'cba_save_profile',
                nonce:      CbaAccountData.nonce,
                first_name: $('#account-first-name').val().trim(),
                last_name:  $('#account-last-name').val().trim(),
                email:      email,
                password:   password,
            },
            success: function (res) {
                if (res.success) {
                    showMessage(res.data.message, 'success');
                    $passwordInput.val('');
                    if ($toggleBtn.attr('aria-expanded') === 'true') {
                        $toggleBtn.attr('aria-expanded', 'false');
                        $passwordSection.prop('hidden', true);
                    }
                } else {
                    showMessage(res.data.message || CbaAccountData.i18n.error, 'error');
                }
            },
            error: function () {
                showMessage(CbaAccountData.i18n.error, 'error');
            },
            complete: function () {
                $saveBtn.prop('disabled', false).text('Uložit změny');
            },
        });
    });

}(jQuery));
