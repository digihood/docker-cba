<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Save current user data (logged-in only)
add_action( 'wp_ajax_net_worth_save_user_data', 'net_worth_ajax_save_user_data' );

function net_worth_ajax_save_user_data() {
    check_ajax_referer( 'net_worth_nonce', 'nonce' );

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
        $sanitized_values = net_worth_sanitize_values( $values_raw );
    }

    $data = array(
        'calculator_id'   => $calculator_id,
        'calculator_slug' => $calc_slug,
        'updated_at'      => current_time( 'mysql' ),
        'values'          => $sanitized_values,
    );

    update_user_meta( $user_id, '_net_worth_current_data', wp_json_encode( $data ) );

    wp_send_json_success( array(
        'message'    => __( 'Data uložena.', 'cba' ),
        'updated_at' => $data['updated_at'],
    ) );
}

// Create snapshot (logged-in only)
add_action( 'wp_ajax_net_worth_create_snapshot', 'net_worth_ajax_create_snapshot' );

function net_worth_ajax_create_snapshot() {
    check_ajax_referer( 'net_worth_nonce', 'nonce' );

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
        $sanitized_values = net_worth_sanitize_values( $values_raw );
    }

    $items      = net_worth_get_items( $calculator_id );
    $benchmarks = net_worth_get_benchmarks( $calculator_id );
    $results    = net_worth_calculate_results( $sanitized_values, $items, $benchmarks );

    $snapshot_id = 'snapshot_' . current_time( 'YmdHis' );

    $snapshot = array(
        'id'             => $snapshot_id,
        'calculator_id'  => $calculator_id,
        'calculator_slug'=> $calc_slug,
        'created_at'     => current_time( 'mysql' ),
        'values'         => $sanitized_values,
        'results'        => $results,
    );

    // Load existing snapshots
    $raw_snapshots = get_user_meta( $user_id, '_net_worth_snapshots', true );
    $snapshots     = array();
    if ( ! empty( $raw_snapshots ) ) {
        $decoded = json_decode( $raw_snapshots, true );
        if ( is_array( $decoded ) ) {
            $snapshots = $decoded;
        }
    }

    $snapshots[] = $snapshot;

    update_user_meta( $user_id, '_net_worth_snapshots', wp_json_encode( $snapshots ) );

    wp_send_json_success( array(
        'message'    => __( 'Snapshot uložen.', 'cba' ),
        'snapshot'   => $snapshot,
    ) );
}

// Send email report (public + logged-in)
add_action( 'wp_ajax_net_worth_send_email_report',        'net_worth_ajax_send_email_report' );
add_action( 'wp_ajax_nopriv_net_worth_send_email_report', 'net_worth_ajax_send_email_report' );

function net_worth_ajax_send_email_report() {
    check_ajax_referer( 'net_worth_nonce', 'nonce' );

    $email         = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
    $calculator_id = isset( $_POST['calculator_id'] ) ? absint( $_POST['calculator_id'] ) : 0;
    $values_raw    = isset( $_POST['values'] ) ? wp_unslash( $_POST['values'] ) : array();

    if ( ! is_email( $email ) ) {
        wp_send_json_error( array( 'message' => __( 'Neplatná e-mailová adresa.', 'cba' ) ) );
    }

    if ( ! $calculator_id ) {
        wp_send_json_error( array( 'message' => __( 'Neplatné ID kalkulačky.', 'cba' ) ) );
    }

    $sanitized_values = array();
    if ( is_array( $values_raw ) ) {
        $sanitized_values = net_worth_sanitize_values( $values_raw );
    }

    $config     = net_worth_get_config( $calculator_id );
    $items      = $config['items'];
    $benchmarks = $config['benchmarks'];
    $results    = net_worth_calculate_results( $sanitized_values, $items, $benchmarks );

    $payload = array(
        'values'               => $sanitized_values,
        'results'              => $results,
        'items'                => $items,
        'categories'           => $config['categories'],
        'benchmarks'           => $benchmarks,
        'result_messages'      => $config['result_messages'],
        'recommended_content'  => $config['recommended_content'],
    );

    if ( function_exists( 'net_worth_send_report_email' ) ) {
        $sent = net_worth_send_report_email( $email, $payload );
        if ( $sent ) {
            wp_send_json_success( array( 'message' => __( 'E-mail byl odeslán.', 'cba' ) ) );
        } else {
            wp_send_json_error( array( 'message' => __( 'E-mail se nepodařilo odeslat.', 'cba' ) ) );
        }
    } else {
        wp_send_json_error( array( 'message' => __( 'E-mailová funkce není dostupná.', 'cba' ) ) );
    }
}
