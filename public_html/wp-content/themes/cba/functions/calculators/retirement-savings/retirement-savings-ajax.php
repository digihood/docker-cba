<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Save user data (logged-in users only)
add_action( 'wp_ajax_retirement_savings_save_user_data', 'retirement_savings_ajax_save_user_data' );

function retirement_savings_ajax_save_user_data() {
    check_ajax_referer( 'retirement_savings_nonce', 'nonce' );

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
        $sanitized_values = retirement_savings_sanitize_values( $values_raw );
    }

    $data = array(
        'calculator_id'   => $calculator_id,
        'calculator_slug' => $calc_slug,
        'updated_at'      => current_time( 'mysql' ),
        'values'          => $sanitized_values,
    );

    update_user_meta( $user_id, '_retirement_savings_data', wp_json_encode( $data ) );

    wp_send_json_success( array(
        'message'    => __( 'Data uložena.', 'cba' ),
        'updated_at' => $data['updated_at'],
    ) );
}
