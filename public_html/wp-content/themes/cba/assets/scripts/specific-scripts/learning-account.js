/* Foxo Learning – account page profile form */
(function ($) {
    'use strict';

    const D = window.FoxoAccountData || {};

    // Extended profile form (learning fields)
    $('#foxo-profile-form').on('submit', function (e) {
        e.preventDefault();
        const $btn = $('#foxo-profile-save');
        const $msg = $('#foxo-profile-msg');

        $btn.prop('disabled', true).text(D.i18n.saving || 'Ukládám…');
        $msg.attr('hidden', true);

        const data = {};
        $(this).serializeArray().forEach(function (f) {
            data[f.name] = f.value;
        });
        delete data._wpnonce;

        $.ajax({
            url: D.restUrl + 'account/profile',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(data),
            headers: { 'X-WP-Nonce': D.nonce },
            success: function (res) {
                $msg.text(res.message || (D.i18n.saved || 'Uloženo!'))
                    .removeClass('account-message--error')
                    .addClass('account-message--success')
                    .removeAttr('hidden');
                $btn.prop('disabled', false).text('Uložit údaje');
            },
            error: function () {
                $msg.text(D.i18n.error || 'Chyba.')
                    .addClass('account-message--error')
                    .removeAttr('hidden');
                $btn.prop('disabled', false).text('Uložit údaje');
            }
        });
    });

})(jQuery);
