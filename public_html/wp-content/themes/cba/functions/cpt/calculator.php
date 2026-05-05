<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function register_calculator_post_type() {
    $labels = array(
        'name'               => __( 'Kalkulačky', 'cba' ),
        'singular_name'      => __( 'Kalkulačka', 'cba' ),
        'menu_name'          => __( 'Kalkulačky', 'cba' ),
        'add_new'            => __( 'Přidat kalkulačku', 'cba' ),
        'add_new_item'       => __( 'Přidat novou kalkulačku', 'cba' ),
        'edit_item'          => __( 'Upravit kalkulačku', 'cba' ),
        'new_item'           => __( 'Nová kalkulačka', 'cba' ),
        'view_item'          => __( 'Zobrazit kalkulačku', 'cba' ),
        'search_items'       => __( 'Hledat kalkulačky', 'cba' ),
        'not_found'          => __( 'Žádné kalkulačky nenalezeny', 'cba' ),
        'not_found_in_trash' => __( 'V koši nejsou žádné kalkulačky', 'cba' ),
    );

    $args = array(
        'labels'        => $labels,
        'public'        => true,
        'has_archive'   => false,
        'show_in_rest'  => true,
        'menu_icon'     => 'dashicons-calculator',
        'menu_position' => 25,
        'rewrite'       => array( 'slug' => 'kalkulacky' ),
        'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
    );

    register_post_type( 'calculator', $args );
}

add_action( 'init', 'register_calculator_post_type' );
