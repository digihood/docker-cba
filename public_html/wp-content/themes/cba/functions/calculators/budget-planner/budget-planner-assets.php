<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function budget_planner_enqueue_assets() {
    if ( ! is_singular( 'calculator' ) ) {
        return;
    }

    $post_id   = get_the_ID();
    $post_slug = get_post_field( 'post_name', $post_id );

    if ( $post_slug !== 'planovac-rozpoctu' ) {
        return;
    }

    $theme_uri = get_template_directory_uri();
    $version   = wp_get_theme()->get( 'Version' ) ?: '1.0.0';

    // Chart.js
    wp_enqueue_script(
        'chartjs',
        'https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js',
        array(),
        '4.4.3',
        true
    );

    // Calculator CSS
    wp_enqueue_style(
        'budget-planner-style',
        $theme_uri . '/assets/calculators/budget-planner/css/budget-planner.css',
        array(),
        $version
    );

    // Calculator JS
    wp_enqueue_script(
        'budget-planner-script',
        $theme_uri . '/assets/calculators/budget-planner/js/budget-planner.js',
        array( 'jquery', 'chartjs' ),
        $version,
        true
    );

    // Prepare data for JS
    $config      = budget_planner_get_config( $post_id );
    $user_id     = get_current_user_id();
    $saved_vals  = budget_planner_get_saved_user_data( $post_id, $user_id );

    $current_user = wp_get_current_user();
    $user_email   = ( is_user_logged_in() && $current_user->ID ) ? $current_user->user_email : '';

    wp_localize_script( 'budget-planner-script', 'BudgetPlannerData', array(
        'ajaxUrl'        => admin_url( 'admin-ajax.php' ),
        'nonce'          => wp_create_nonce( 'budget_planner_nonce' ),
        'isLoggedIn'     => is_user_logged_in(),
        'userEmail'      => $user_email,
        'calculatorId'   => $post_id,
        'calculatorSlug' => $post_slug,
        'categories'     => array_values( array_filter( $config['categories'], function( $c ) {
            return ! empty( $c['active'] );
        } ) ),
        'items'          => array_values( array_filter( $config['items'], function( $i ) {
            return ! empty( $i['active'] );
        } ) ),
        'resultMessages' => $config['result_messages'],
        'savedValues'    => $saved_vals,
        'strings'        => array(
            'saveSuccess'   => __( 'Data uložena.', 'cba' ),
            'saveError'     => __( 'Nepodařilo se uložit data.', 'cba' ),
            'emailSuccess'  => __( 'Report byl odeslán na váš e-mail.', 'cba' ),
            'emailError'    => __( 'Nepodařilo se odeslat e-mail.', 'cba' ),
            'invalidEmail'  => __( 'Zadejte platnou e-mailovou adresu.', 'cba' ),
            'sessionNote'   => __( 'Chcete si výsledky uložit i pro příště? Vytvořte si účet.', 'cba' ),
            'monthlySaving' => __( 'Vaše měsíční úspora:', 'cba' ),
            'monthlyDeficit'=> __( 'Měsíčně jste v mínusu:', 'cba' ),
        ),
    ) );
}
add_action( 'wp_enqueue_scripts', 'budget_planner_enqueue_assets' );
