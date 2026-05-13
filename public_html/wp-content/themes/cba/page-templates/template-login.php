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
<div class="bg-gray-light py-16 px-6 text-center">
    <div class="container flex flex-col items-center gap-5">
        <p class="text-base text-gray-dark m-0">
            <?php printf(
                esc_html__( 'Jste přihlášen jako %s.', 'cba' ),
                '<strong>' . esc_html( $user->get_display_name() ) . '</strong>'
            ); ?>
        </p>
        <div class="flex gap-3 flex-wrap justify-center">
            <a href="<?php echo esc_url( linksd1g1::my_account() ); ?>"
               class="inline-flex items-center justify-center py-3 px-6 rounded-lg text-sm font-semibold bg-primary text-white hover:bg-primary-dark transition-colors no-underline">
                <?php esc_html_e( 'Můj účet', 'cba' ); ?>
            </a>
            <a href="<?php echo esc_url( wp_logout_url( home_url() ) ); ?>"
               class="inline-flex items-center justify-center py-3 px-6 rounded-lg text-sm font-semibold bg-white text-gray-dark border border-gray-mid hover:border-gray transition-colors no-underline">
                <?php esc_html_e( 'Odhlásit se', 'cba' ); ?>
            </a>
        </div>
    </div>
</div>

<?php
else :

// Determine active tab – URL param má přednost před session stavem
$active_tab = 'login';
$tab_param  = isset( $_GET['tab'] ) ? sanitize_key( $_GET['tab'] ) : '';
if ( in_array( $tab_param, [ 'register', 'forgotten' ], true ) ) {
    $active_tab = $tab_param;
} elseif ( d1g1SessionClass::check_session( 'user_registration' ) ) {
    $active_tab = 'register';
} elseif ( d1g1SessionClass::check_session( 'forgotten_request' ) ) {
    $active_tab = 'forgotten';
}

// Use already-instantiated form objects
global $LoginFormWed, $RegistrationFormWed, $ForgottenPasswordFormWed;
?>

<!-- Hero -->
<div class="bg-dark py-10 pb-12 text-center">
    <div class="container">
        <h1 class="text-2xl md:text-3xl font-display font-extrabold text-white mb-2">
            <?php esc_html_e( 'Váš účet', 'cba' ); ?>
        </h1>
        <p class="text-sm text-white/75 m-0">
            <?php esc_html_e( 'Přihlaste se nebo si vytvořte nový účet.', 'cba' ); ?>
        </p>
    </div>
</div>

<!-- Page body -->
<section class="bg-gray-light min-h-[60vh] pb-16">
    <div class="container">

        <!-- Card -->
        <div class="max-w-[500px] mx-auto -mt-6 bg-white rounded-xl shadow-card overflow-hidden relative z-10">

            <!-- Tab navigation -->
            <div class="flex border-b-2 border-gray-mid" role="tablist" aria-label="<?php esc_attr_e( 'Typ formuláře', 'cba' ); ?>">

                <button type="button" role="tab"
                    class="auth-tab flex-1 py-4 px-2 bg-transparent border-0 text-sm font-semibold text-gray cursor-pointer whitespace-nowrap border-b-[3px] border-b-transparent -mb-0.5 transition-colors hover:text-dark<?php echo $active_tab === 'login' ? ' auth-tab--active' : ''; ?>"
                    data-tab="login"
                    aria-selected="<?php echo $active_tab === 'login' ? 'true' : 'false'; ?>"
                    aria-controls="auth-panel-login"
                ><?php esc_html_e( 'Přihlášení', 'cba' ); ?></button>

                <button type="button" role="tab"
                    class="auth-tab flex-1 py-4 px-2 bg-transparent border-0 text-sm font-semibold text-gray cursor-pointer whitespace-nowrap border-b-[3px] border-b-transparent -mb-0.5 transition-colors hover:text-dark<?php echo $active_tab === 'register' ? ' auth-tab--active' : ''; ?>"
                    data-tab="register"
                    aria-selected="<?php echo $active_tab === 'register' ? 'true' : 'false'; ?>"
                    aria-controls="auth-panel-register"
                ><?php esc_html_e( 'Registrace', 'cba' ); ?></button>

                <button type="button" role="tab"
                    class="auth-tab flex-1 py-4 px-2 bg-transparent border-0 text-sm font-semibold text-gray cursor-pointer whitespace-nowrap border-b-[3px] border-b-transparent -mb-0.5 transition-colors hover:text-dark<?php echo $active_tab === 'forgotten' ? ' auth-tab--active' : ''; ?>"
                    data-tab="forgotten"
                    aria-selected="<?php echo $active_tab === 'forgotten' ? 'true' : 'false'; ?>"
                    aria-controls="auth-panel-forgotten"
                ><?php esc_html_e( 'Zapomenuté heslo', 'cba' ); ?></button>

            </div>

            <!-- Login panel -->
            <div id="auth-panel-login" role="tabpanel"
                class="auth-panel p-7 pb-8<?php echo $active_tab === 'login' ? ' auth-panel--active' : ''; ?>"
                aria-hidden="<?php echo $active_tab === 'login' ? 'false' : 'true'; ?>">
                <?php if ( $LoginFormWed ) : $LoginFormWed->login_tab(); endif; ?>
            </div>

            <!-- Register panel -->
            <div id="auth-panel-register" role="tabpanel"
                class="auth-panel p-7 pb-8<?php echo $active_tab === 'register' ? ' auth-panel--active' : ''; ?>"
                aria-hidden="<?php echo $active_tab === 'register' ? 'false' : 'true'; ?>">
                <?php if ( $RegistrationFormWed ) : $RegistrationFormWed->registration_form(); endif; ?>
            </div>

            <!-- Forgotten password panel -->
            <div id="auth-panel-forgotten" role="tabpanel"
                class="auth-panel p-7 pb-8<?php echo $active_tab === 'forgotten' ? ' auth-panel--active' : ''; ?>"
                aria-hidden="<?php echo $active_tab === 'forgotten' ? 'false' : 'true'; ?>">
                <?php if ( $ForgottenPasswordFormWed ) : $ForgottenPasswordFormWed->forgotten_tab(); endif; ?>
            </div>

        </div><!-- card -->
    </div>
</section>

<?php endif; ?>

<?php get_footer(); ?>
