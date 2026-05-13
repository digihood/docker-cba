<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action( 'wp_enqueue_scripts', 'cba_login_enqueue_assets' );

function cba_login_enqueue_assets() {
    if ( ! is_page_template( 'page-templates/template-login.php' ) ) {
        return;
    }

    $theme_dir = get_stylesheet_directory();
    $theme_uri = get_stylesheet_directory_uri();

    wp_enqueue_script(
        'cba-login-script',
        $theme_uri . '/assets/scripts/specific-scripts/login.js',
        [],
        filemtime( $theme_dir . '/assets/scripts/specific-scripts/login.js' ),
        true
    );
}
