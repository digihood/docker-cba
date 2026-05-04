<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action( 'wp_ajax_cba_save_profile', 'cba_ajax_save_profile' );

function cba_ajax_save_profile() {
    check_ajax_referer( 'cba_account_nonce', 'nonce' );

    if ( ! is_user_logged_in() ) {
        wp_send_json_error( [ 'message' => __( 'Nejste přihlášen.', 'cba' ) ], 403 );
    }

    $user = CbaUser::current();

    $raw_email = isset( $_POST['email'] ) ? trim( wp_unslash( $_POST['email'] ) ) : '';

    if ( ! empty( $raw_email ) && ! is_email( $raw_email ) ) {
        wp_send_json_error( [ 'message' => __( 'Zadejte platnou e-mailovou adresu.', 'cba' ) ] );
    }

    $data = [
        'first_name' => isset( $_POST['first_name'] ) ? sanitize_text_field( wp_unslash( $_POST['first_name'] ) ) : '',
        'last_name'  => isset( $_POST['last_name'] )  ? sanitize_text_field( wp_unslash( $_POST['last_name'] ) )  : '',
        'email'      => $raw_email,
        'password'   => isset( $_POST['password'] )   ? wp_unslash( $_POST['password'] )                          : '',
    ];

    // Only attempt password change if field was filled
    if ( empty( $data['password'] ) ) {
        unset( $data['password'] );
    }

    $result = $user->save( $data );

    if ( $result['success'] ) {
        wp_send_json_success( [ 'message' => $result['message'] ] );
    } else {
        wp_send_json_error( [ 'message' => $result['message'] ] );
    }
}
