<?php
/*
Template Name: Můj účet
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();

$cba_user = CbaUser::current();

if ( ! $cba_user->is_logged_in() ) {
    wp_redirect( linksd1g1::login_registration() );
    exit;
}
?>

<div class="account-hero">
    <div class="account-hero__inner container">
        <h1 class="account-hero__title">
            <?php
            $name = trim( $cba_user->get_first_name() . ' ' . $cba_user->get_last_name() );
            printf(
                esc_html__( 'Dobrý den, %s', 'cba' ),
                esc_html( $name ?: $cba_user->get_display_name() )
            );
            ?>
        </h1>
        <p class="account-hero__email"><?php echo esc_html( $cba_user->get_email() ); ?></p>
        <a href="<?php echo esc_url( wp_logout_url( home_url() ) ); ?>" class="account-btn account-btn--ghost">
            <?php esc_html_e( 'Odhlásit se', 'cba' ); ?>
        </a>
    </div>
</div>

<div class="account-page">
    <div class="container">
        <div class="account-layout">

            <main class="account-main">

                <?php get_template_part( 'template-parts/account/profile-form', null, [ 'cba_user' => $cba_user ] ); ?>

                <?php get_template_part( 'template-parts/account/calculator-list', null, [ 'cba_user' => $cba_user ] ); ?>

            </main>

            <aside class="account-sidebar">

                <div class="account-card account-sidebar-info">
                    <div class="account-card__header">
                        <h2 class="account-card__title"><?php esc_html_e( 'Rychlé akce', 'cba' ); ?></h2>
                    </div>
                    <div class="account-card__body">
                        <nav class="account-quick-nav">
                            <a href="<?php echo esc_url( home_url( '/kalkulacky/' ) ); ?>" class="account-quick-nav__item">
                                <span class="account-quick-nav__icon">🧮</span>
                                <?php esc_html_e( 'Všechny kalkulačky', 'cba' ); ?>
                            </a>
                            <a href="<?php echo esc_url( wp_logout_url( home_url() ) ); ?>" class="account-quick-nav__item account-quick-nav__item--danger">
                                <span class="account-quick-nav__icon">→</span>
                                <?php esc_html_e( 'Odhlásit se', 'cba' ); ?>
                            </a>
                        </nav>
                    </div>
                </div>

            </aside>

        </div>
    </div>
</div>

<?php get_footer(); ?>
