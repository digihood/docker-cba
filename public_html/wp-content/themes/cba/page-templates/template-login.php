<?php
/*
Template Name: Přihlášení
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();

// Already logged in
if ( is_user_logged_in() ) :
    $user = CbaUser::current();
?>
<div class="auth-logged-in">
    <div class="auth-logged-in__inner container">
        <p class="auth-logged-in__msg">
            <?php printf(
                esc_html__( 'Jste přihlášen jako %s.', 'cba' ),
                '<strong>' . esc_html( $user->get_display_name() ) . '</strong>'
            ); ?>
        </p>
        <div class="auth-logged-in__actions">
            <a href="<?php echo esc_url( linksd1g1::my_account() ); ?>" class="auth-btn auth-btn--primary">
                <?php esc_html_e( 'Můj účet', 'cba' ); ?>
            </a>
            <a href="<?php echo esc_url( wp_logout_url( home_url() ) ); ?>" class="auth-btn auth-btn--outline">
                <?php esc_html_e( 'Odhlásit se', 'cba' ); ?>
            </a>
        </div>
    </div>
</div>

<?php
else :

// Determine active tab from session state
$active_tab = 'login';
if ( d1g1SessionClass::check_session( 'user_registration' ) ) {
    $active_tab = 'register';
} elseif ( d1g1SessionClass::check_session( 'forgotten_request' ) ) {
    $active_tab = 'forgotten';
}

// Use already-instantiated form objects
global $LoginFormWed, $RegistrationFormWed, $ForgottenPasswordFormWed;
?>

<div class="auth-hero">
    <div class="container">
        <h1 class="auth-hero__title"><?php esc_html_e( 'Váš účet', 'cba' ); ?></h1>
        <p class="auth-hero__sub"><?php esc_html_e( 'Přihlaste se nebo si vytvořte nový účet.', 'cba' ); ?></p>
    </div>
</div>

<div class="auth-page">
    <div class="container">
        <div class="auth-card">

            <!-- Tab navigation -->
            <div class="auth-tabs" role="tablist" aria-label="<?php esc_attr_e( 'Typ formuláře', 'cba' ); ?>">
                <button
                    type="button"
                    role="tab"
                    class="auth-tab <?php echo $active_tab === 'login'     ? 'auth-tab--active' : ''; ?>"
                    data-tab="login"
                    aria-selected="<?php echo $active_tab === 'login'     ? 'true' : 'false'; ?>"
                    aria-controls="auth-panel-login"
                ><?php esc_html_e( 'Přihlášení', 'cba' ); ?></button>

                <button
                    type="button"
                    role="tab"
                    class="auth-tab <?php echo $active_tab === 'register'  ? 'auth-tab--active' : ''; ?>"
                    data-tab="register"
                    aria-selected="<?php echo $active_tab === 'register'  ? 'true' : 'false'; ?>"
                    aria-controls="auth-panel-register"
                ><?php esc_html_e( 'Registrace', 'cba' ); ?></button>

                <button
                    type="button"
                    role="tab"
                    class="auth-tab <?php echo $active_tab === 'forgotten' ? 'auth-tab--active' : ''; ?>"
                    data-tab="forgotten"
                    aria-selected="<?php echo $active_tab === 'forgotten' ? 'true' : 'false'; ?>"
                    aria-controls="auth-panel-forgotten"
                ><?php esc_html_e( 'Zapomenuté heslo', 'cba' ); ?></button>
            </div>

            <!-- Login panel -->
            <div
                id="auth-panel-login"
                role="tabpanel"
                class="auth-panel <?php echo $active_tab === 'login' ? 'auth-panel--active' : ''; ?>"
                aria-hidden="<?php echo $active_tab === 'login' ? 'false' : 'true'; ?>"
            >
                <?php if ( $LoginFormWed ) : $LoginFormWed->login_tab(); endif; ?>
            </div>

            <!-- Register panel -->
            <div
                id="auth-panel-register"
                role="tabpanel"
                class="auth-panel <?php echo $active_tab === 'register' ? 'auth-panel--active' : ''; ?>"
                aria-hidden="<?php echo $active_tab === 'register' ? 'false' : 'true'; ?>"
            >
                <?php if ( $RegistrationFormWed ) : $RegistrationFormWed->registration_form(); endif; ?>
            </div>

            <!-- Forgotten password panel -->
            <div
                id="auth-panel-forgotten"
                role="tabpanel"
                class="auth-panel <?php echo $active_tab === 'forgotten' ? 'auth-panel--active' : ''; ?>"
                aria-hidden="<?php echo $active_tab === 'forgotten' ? 'false' : 'true'; ?>"
            >
                <?php if ( $ForgottenPasswordFormWed ) : $ForgottenPasswordFormWed->forgotten_tab(); endif; ?>
            </div>

        </div><!-- .auth-card -->
    </div>
</div>

<?php endif; ?>

<?php get_footer(); ?>
