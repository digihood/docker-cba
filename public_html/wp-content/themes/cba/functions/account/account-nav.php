<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Hide "Přihlášení" for logged-in users and "Můj účet" for guests.
 * Applies to the primary nav location.
 */
add_filter( 'wp_nav_menu_objects', 'cba_filter_auth_menu_items', 10, 2 );

function cba_filter_auth_menu_items( array $items, $args ): array {
    if ( ! isset( $args->theme_location ) || $args->theme_location !== 'primary' ) {
        return $items;
    }

    $logged_in = is_user_logged_in();

    foreach ( $items as $key => $item ) {
        if ( $item->object !== 'page' ) {
            continue;
        }

        $slug = get_post_field( 'post_name', (int) $item->object_id );

        // Login page: only visible for guests
        if ( $slug === 'prihlaseni' && $logged_in ) {
            unset( $items[ $key ] );
        }

        // My account page: only visible for logged-in users
        if ( $slug === 'muj-ucet' && ! $logged_in ) {
            unset( $items[ $key ] );
        }
    }

    return $items;
}
