<?php
if ( ! defined( 'ABSPATH' ) ) exit;

$user_id = get_current_user_id();
$profile = FoxoUserLearningService::get_profile( $user_id );
?>

<div class="account-card foxo-profile-extended">
    <div class="account-card__header">
        <h2 class="account-card__title"><?php esc_html_e( 'Osobní a fakturační údaje', 'cba' ); ?></h2>
    </div>
    <div class="account-card__body">
        <div id="foxo-profile-msg" class="account-message" role="alert" aria-live="polite" hidden></div>

        <form id="foxo-profile-form" class="account-form" novalidate>
            <?php wp_nonce_field( 'wp_rest', '_wpnonce' ); ?>

            <div class="account-form__row">
                <div class="account-form__field">
                    <label for="foxo-title"><?php esc_html_e( 'Titul', 'cba' ); ?></label>
                    <input type="text" id="foxo-title" name="title" value="<?php echo esc_attr( $profile->title ); ?>" autocomplete="honorific-prefix">
                </div>
                <div class="account-form__field">
                    <label for="foxo-first-name"><?php esc_html_e( 'Jméno', 'cba' ); ?></label>
                    <input type="text" id="foxo-first-name" name="firstName" value="<?php echo esc_attr( $profile->firstName ); ?>" autocomplete="given-name">
                </div>
                <div class="account-form__field">
                    <label for="foxo-last-name"><?php esc_html_e( 'Příjmení', 'cba' ); ?></label>
                    <input type="text" id="foxo-last-name" name="lastName" value="<?php echo esc_attr( $profile->lastName ); ?>" autocomplete="family-name">
                </div>
            </div>

            <div class="account-form__row">
                <div class="account-form__field">
                    <label for="foxo-street"><?php esc_html_e( 'Ulice a číslo popisné', 'cba' ); ?></label>
                    <input type="text" id="foxo-street" name="street" value="<?php echo esc_attr( $profile->street ); ?>" autocomplete="street-address">
                </div>
                <div class="account-form__field">
                    <label for="foxo-city"><?php esc_html_e( 'Město', 'cba' ); ?></label>
                    <input type="text" id="foxo-city" name="city" value="<?php echo esc_attr( $profile->city ); ?>" autocomplete="address-level2">
                </div>
            </div>

            <div class="account-form__row">
                <div class="account-form__field">
                    <label for="foxo-zip"><?php esc_html_e( 'PSČ', 'cba' ); ?></label>
                    <input type="text" id="foxo-zip" name="zip" value="<?php echo esc_attr( $profile->zip ); ?>" autocomplete="postal-code">
                </div>
                <div class="account-form__field">
                    <label for="foxo-country"><?php esc_html_e( 'Stát', 'cba' ); ?></label>
                    <input type="text" id="foxo-country" name="country" value="<?php echo esc_attr( $profile->country ); ?>" autocomplete="country-name">
                </div>
            </div>

            <div class="account-form__row">
                <div class="account-form__field">
                    <label for="foxo-company-id"><?php esc_html_e( 'IČO', 'cba' ); ?></label>
                    <input type="text" id="foxo-company-id" name="companyId" value="<?php echo esc_attr( $profile->companyId ); ?>">
                </div>
                <div class="account-form__field">
                    <label for="foxo-vat-id"><?php esc_html_e( 'DIČ', 'cba' ); ?></label>
                    <input type="text" id="foxo-vat-id" name="vatId" value="<?php echo esc_attr( $profile->vatId ); ?>">
                </div>
            </div>

            <div class="account-form__actions">
                <button type="submit" class="account-btn account-btn--primary" id="foxo-profile-save">
                    <?php esc_html_e( 'Uložit údaje', 'cba' ); ?>
                </button>
            </div>
        </form>
    </div>
</div>
