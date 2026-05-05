<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function net_worth_enqueue_assets() {
    if ( ! is_singular( 'calculator' ) ) {
        return;
    }

    $calc_post_id = get_queried_object_id();
    $post_slug    = get_post_field( 'post_name', $calc_post_id );

    if ( $post_slug !== 'ciste-jmeni' ) {
        return;
    }

    $theme_uri = get_template_directory_uri();
    $version   = wp_get_theme()->get( 'Version' ) ?: '1.0.0';

    // Chart.js 4.4.3 from CDN
    wp_enqueue_script(
        'chart-js-net-worth',
        'https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js',
        array(),
        '4.4.3',
        true
    );

    // Calculator CSS
    wp_enqueue_style(
        'net-worth-style',
        $theme_uri . '/assets/calculators/net-worth/css/net-worth.css',
        array(),
        $version
    );

    // Calculator JS
    wp_enqueue_script(
        'net-worth-script',
        $theme_uri . '/assets/calculators/net-worth/js/net-worth.js',
        array( 'jquery', 'chart-js-net-worth' ),
        $version,
        true
    );

    // Prepare localized data
    $user_id   = get_current_user_id();
    $logged_in = (bool) is_user_logged_in();

    $saved_values = $logged_in ? net_worth_get_saved_user_data( $calc_post_id, $user_id ) : null;
    $snapshots    = $logged_in ? net_worth_get_user_snapshots( $calc_post_id, $user_id ) : array();

    wp_localize_script( 'net-worth-script', 'NetWorthData', array(
        'ajaxurl'        => admin_url( 'admin-ajax.php' ),
        'nonce'          => wp_create_nonce( 'net_worth_nonce' ),
        'isLoggedIn'     => $logged_in,
        'userEmail'      => $logged_in ? wp_get_current_user()->user_email : '',
        'calculatorId'   => $calc_post_id,
        'calculatorSlug' => 'ciste-jmeni',
        'config'         => net_worth_get_config( $calc_post_id ),
        'savedValues'    => $saved_values,
        'snapshots'      => $snapshots,
    ) );
}
add_action( 'wp_enqueue_scripts', 'net_worth_enqueue_assets' );
