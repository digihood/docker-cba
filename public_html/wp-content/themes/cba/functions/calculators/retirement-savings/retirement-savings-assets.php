<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function retirement_savings_enqueue_assets() {
    if ( ! is_singular( 'calculator' ) ) {
        return;
    }

    $calc_post_id = get_queried_object_id();
    $post_slug    = get_post_field( 'post_name', $calc_post_id );

    if ( $post_slug !== 'sporeni-na-duchod' ) {
        return;
    }

    $theme_uri = get_template_directory_uri();
    $version   = wp_get_theme()->get( 'Version' ) ?: '1.0.0';

    // Chart.js from CDN
    wp_enqueue_script(
        'chart-js-retirement',
        'https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js',
        array(),
        '4.4.3',
        true
    );

    // Calculator CSS
    wp_enqueue_style(
        'retirement-savings-style',
        $theme_uri . '/assets/calculators/retirement-savings/css/retirement-savings.css',
        array(),
        $version
    );

    // Calculator JS
    wp_enqueue_script(
        'retirement-savings-script',
        $theme_uri . '/assets/calculators/retirement-savings/js/retirement-savings.js',
        array( 'jquery', 'chart-js-retirement' ),
        $version,
        true
    );

    // Prepare data
    $user_id     = get_current_user_id();
    $saved_data  = is_user_logged_in() ? retirement_savings_get_saved_user_data( $calc_post_id, $user_id ) : null;

    wp_localize_script( 'retirement-savings-script', 'RetirementSavingsData', array(
        'ajaxurl'        => admin_url( 'admin-ajax.php' ),
        'nonce'          => wp_create_nonce( 'retirement_savings_nonce' ),
        'isLoggedIn'     => (bool) is_user_logged_in(),
        'calculatorId'   => $calc_post_id,
        'calculatorSlug' => 'sporeni-na-duchod',
        'defaultInputs'  => retirement_savings_get_default_inputs( $calc_post_id ),
        'resultMessages' => retirement_savings_get_result_messages( $calc_post_id ),
        'savedValues'    => $saved_data,
        'shareBaseUrl'   => get_permalink( $calc_post_id ),
    ) );
}
add_action( 'wp_enqueue_scripts', 'retirement_savings_enqueue_assets' );
