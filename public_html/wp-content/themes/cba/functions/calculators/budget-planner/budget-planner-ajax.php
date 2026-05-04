<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Uložení dat uživatele (pouze přihlášení)
add_action( 'wp_ajax_budget_planner_save_user_data', 'budget_planner_ajax_save_user_data' );

function budget_planner_ajax_save_user_data() {
    check_ajax_referer( 'budget_planner_nonce', 'nonce' );

    if ( ! is_user_logged_in() ) {
        wp_send_json_error( array( 'message' => __( 'Nejste přihlášen.', 'cba' ) ), 403 );
    }

    $user_id       = get_current_user_id();
    $calculator_id = isset( $_POST['calculator_id'] ) ? absint( $_POST['calculator_id'] ) : 0;
    $calc_slug     = isset( $_POST['calculator_slug'] ) ? sanitize_key( $_POST['calculator_slug'] ) : '';
    $values_raw    = isset( $_POST['values'] ) ? wp_unslash( $_POST['values'] ) : array();

    if ( ! $calculator_id ) {
        wp_send_json_error( array( 'message' => __( 'Neplatné ID kalkulačky.', 'cba' ) ) );
    }

    if ( ! get_post( $calculator_id ) || get_post_type( $calculator_id ) !== 'calculator' ) {
        wp_send_json_error( array( 'message' => __( 'Kalkulačka neexistuje.', 'cba' ) ) );
    }

    $sanitized_values = array();
    if ( is_array( $values_raw ) ) {
        foreach ( $values_raw as $key => $val ) {
            $clean_key = sanitize_key( $key );
            $sanitized_values[ $clean_key ] = budget_planner_sanitize_money_value( $val );
        }
    }

    $data = array(
        'calculator_id'   => $calculator_id,
        'calculator_slug' => $calc_slug,
        'updated_at'      => current_time( 'mysql' ),
        'values'          => $sanitized_values,
    );

    update_user_meta( $user_id, '_budget_planner_data', wp_json_encode( $data ) );

    wp_send_json_success( array(
        'message'    => __( 'Data uložena.', 'cba' ),
        'updated_at' => $data['updated_at'],
    ) );
}

// Odeslání e-mailového reportu (přihlášení i nepřihlášení)
add_action( 'wp_ajax_budget_planner_send_email_report',        'budget_planner_ajax_send_email_report' );
add_action( 'wp_ajax_nopriv_budget_planner_send_email_report', 'budget_planner_ajax_send_email_report' );

function budget_planner_ajax_send_email_report() {
    check_ajax_referer( 'budget_planner_nonce', 'nonce' );

    $calculator_id = isset( $_POST['calculator_id'] ) ? absint( $_POST['calculator_id'] ) : 0;
    $payload_raw   = isset( $_POST['payload'] ) ? wp_unslash( $_POST['payload'] ) : '';
    $email_raw     = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';

    if ( is_user_logged_in() ) {
        $user  = wp_get_current_user();
        $email = $user->user_email;
    } else {
        $email = $email_raw;
    }

    if ( ! is_email( $email ) ) {
        wp_send_json_error( array( 'message' => __( 'Zadejte platnou e-mailovou adresu.', 'cba' ) ) );
    }

    $payload = json_decode( $payload_raw, true );
    if ( ! is_array( $payload ) ) {
        wp_send_json_error( array( 'message' => __( 'Neplatná data reportu.', 'cba' ) ) );
    }

    // Sanitace payload
    $sanitized_payload = array(
        'calculator_id'   => $calculator_id,
        'total_income'    => budget_planner_sanitize_money_value( $payload['total_income'] ?? 0 ),
        'total_expenses'  => budget_planner_sanitize_money_value( $payload['total_expenses'] ?? 0 ),
        'monthly_saving'  => isset( $payload['monthly_saving'] ) ? (float) $payload['monthly_saving'] : 0,
        'health_status'   => sanitize_key( $payload['health_status'] ?? '' ),
        'health_title'    => sanitize_text_field( $payload['health_title'] ?? '' ),
        'savings_5y'      => budget_planner_sanitize_money_value( $payload['savings_5y'] ?? 0 ),
        'items'           => isset( $payload['items'] ) && is_array( $payload['items'] ) ? $payload['items'] : array(),
        'categories'      => isset( $payload['categories'] ) && is_array( $payload['categories'] ) ? $payload['categories'] : array(),
    );

    $result = budget_planner_send_report_email( $email, $sanitized_payload );

    if ( $result ) {
        wp_send_json_success( array( 'message' => __( 'Report byl odeslán na váš e-mail.', 'cba' ) ) );
    } else {
        wp_send_json_error( array( 'message' => __( 'Nepodařilo se odeslat e-mail. Zkuste to prosím znovu.', 'cba' ) ) );
    }
}
