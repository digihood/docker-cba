<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action( 'wp_enqueue_scripts', 'cba_account_enqueue_assets' );

function cba_account_enqueue_assets() {
    if ( ! is_page_template( 'page-templates/template-muj-ucet.php' ) ) {
        return;
    }

    $theme_dir = get_stylesheet_directory();
    $theme_uri = get_stylesheet_directory_uri();

    wp_enqueue_style(
        'cba-account',
        $theme_uri . '/assets/styles/specific-css/account.css',
        [],
        filemtime( $theme_dir . '/assets/styles/specific-css/account.css' )
    );

    wp_enqueue_script(
        'cba-account-script',
        $theme_uri . '/assets/scripts/specific-scripts/account.js',
        [ 'jquery' ],
        filemtime( $theme_dir . '/assets/scripts/specific-scripts/account.js' ),
        true
    );

    wp_localize_script( 'cba-account-script', 'CbaAccountData', [
        'ajaxurl' => admin_url( 'admin-ajax.php' ),
        'nonce'   => wp_create_nonce( 'cba_account_nonce' ),
        'i18n'    => [
            'saving'  => __( 'Ukládám…', 'cba' ),
            'saved'   => __( 'Uloženo!', 'cba' ),
            'error'   => __( 'Chyba při ukládání.', 'cba' ),
        ],
    ] );
}
