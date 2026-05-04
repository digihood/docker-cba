<?php
/**
 * Profile edit form section for the "Můj účet" page.
 * Expects $cba_user (CbaUser) to be set in the calling template.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Receives $cba_user via get_template_part() args (WP 5.5+)
$cba_user = $args['cba_user'] ?? null;
if ( ! $cba_user instanceof CbaUser ) return;
?>

<div class="account-card account-profile">

    <div class="account-card__header">
        <h2 class="account-card__title"><?php esc_html_e( 'Moje údaje', 'cba' ); ?></h2>
    </div>

    <div class="account-card__body">

        <div id="account-profile-message" class="account-message" role="alert" aria-live="polite" hidden></div>

        <form id="account-profile-form" class="account-form" novalidate>

            <div class="account-form__row">
                <div class="account-form__field">
                    <label for="account-first-name"><?php esc_html_e( 'Jméno', 'cba' ); ?></label>
                    <input
                        type="text"
                        id="account-first-name"
                        name="first_name"
                        value="<?php echo esc_attr( $cba_user->get_first_name() ); ?>"
                        autocomplete="given-name"
                    >
                </div>
                <div class="account-form__field">
                    <label for="account-last-name"><?php esc_html_e( 'Příjmení', 'cba' ); ?></label>
                    <input
                        type="text"
                        id="account-last-name"
                        name="last_name"
                        value="<?php echo esc_attr( $cba_user->get_last_name() ); ?>"
                        autocomplete="family-name"
                    >
                </div>
            </div>

            <div class="account-form__field">
                <label for="account-email"><?php esc_html_e( 'E-mail', 'cba' ); ?> <span class="account-form__required">*</span></label>
                <input
                    type="email"
                    id="account-email"
                    name="email"
                    value="<?php echo esc_attr( $cba_user->get_email() ); ?>"
                    required
                    autocomplete="email"
                >
            </div>

            <div class="account-form__separator">
                <button type="button" class="account-form__toggle-password" id="toggle-password-section" aria-expanded="false">
                    <?php esc_html_e( 'Změnit heslo', 'cba' ); ?>
                    <span class="account-form__toggle-icon" aria-hidden="true">+</span>
                </button>
            </div>

            <div id="password-section" class="account-form__password-section" hidden>
                <div class="account-form__field">
                    <label for="account-password"><?php esc_html_e( 'Nové heslo (min. 8 znaků)', 'cba' ); ?></label>
                    <div class="account-form__password-wrap">
                        <input
                            type="password"
                            id="account-password"
                            name="password"
                            minlength="8"
                            autocomplete="new-password"
                        >
                        <button type="button" class="account-form__show-pass" aria-label="<?php esc_attr_e( 'Zobrazit heslo', 'cba' ); ?>">
                            <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </button>
                    </div>
                    <div id="password-strength" class="account-form__strength" aria-live="polite"></div>
                </div>
            </div>

            <div class="account-form__actions">
                <button type="submit" class="account-btn account-btn--primary" id="account-save-btn">
                    <?php esc_html_e( 'Uložit změny', 'cba' ); ?>
                </button>
            </div>

        </form>

    </div>

</div>
