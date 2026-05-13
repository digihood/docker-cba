<?php
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'init', 'foxo_register_learning_cpts' );

function foxo_register_learning_cpts(): void {
    register_post_type( 'foxo_quiz', [
        'labels' => [
            'name'               => __( 'Kvízy', 'cba' ),
            'singular_name'      => __( 'Kvíz', 'cba' ),
            'add_new'            => __( 'Přidat kvíz', 'cba' ),
            'add_new_item'       => __( 'Přidat nový kvíz', 'cba' ),
            'edit_item'          => __( 'Upravit kvíz', 'cba' ),
            'view_item'          => __( 'Zobrazit kvíz', 'cba' ),
            'all_items'          => __( 'Všechny kvízy', 'cba' ),
            'search_items'       => __( 'Hledat kvízy', 'cba' ),
            'menu_name'          => __( 'Kvízy', 'cba' ),
        ],
        'public'        => true,
        'has_archive'   => true,
        'show_in_rest'  => true,
        'menu_icon'     => 'dashicons-feedback',
        'menu_position' => 30,
        'supports'      => [ 'title', 'editor', 'excerpt', 'thumbnail' ],
        'rewrite'       => [ 'slug' => 'kvizy' ],
    ] );

    register_post_type( 'foxo_course', [
        'labels' => [
            'name'               => __( 'Kurzy', 'cba' ),
            'singular_name'      => __( 'Kurz', 'cba' ),
            'add_new'            => __( 'Přidat kurz', 'cba' ),
            'add_new_item'       => __( 'Přidat nový kurz', 'cba' ),
            'edit_item'          => __( 'Upravit kurz', 'cba' ),
            'view_item'          => __( 'Zobrazit kurz', 'cba' ),
            'all_items'          => __( 'Všechny kurzy', 'cba' ),
            'menu_name'          => __( 'Kurzy', 'cba' ),
        ],
        'public'        => true,
        'has_archive'   => true,
        'show_in_rest'  => true,
        'menu_icon'     => 'dashicons-welcome-learn-more',
        'menu_position' => 31,
        'supports'      => [ 'title', 'editor', 'excerpt', 'thumbnail' ],
        'rewrite'       => [ 'slug' => 'kurzy' ],
    ] );

    register_post_type( 'foxo_lesson', [
        'labels' => [
            'name'               => __( 'Lekce', 'cba' ),
            'singular_name'      => __( 'Lekce', 'cba' ),
            'add_new'            => __( 'Přidat lekci', 'cba' ),
            'add_new_item'       => __( 'Přidat novou lekci', 'cba' ),
            'edit_item'          => __( 'Upravit lekci', 'cba' ),
            'view_item'          => __( 'Zobrazit lekci', 'cba' ),
            'all_items'          => __( 'Všechny lekce', 'cba' ),
            'menu_name'          => __( 'Lekce', 'cba' ),
        ],
        'public'        => true,
        'has_archive'   => false,
        'show_in_rest'  => true,
        'menu_icon'     => 'dashicons-book-alt',
        'menu_position' => 32,
        'supports'      => [ 'title', 'editor', 'excerpt', 'thumbnail' ],
        'rewrite'       => [ 'slug' => 'lekce' ],
    ] );

    register_post_type( 'foxo_decision_tree', [
        'labels' => [
            'name'          => __( 'Průvodci', 'cba' ),
            'singular_name' => __( 'Průvodce', 'cba' ),
            'add_new'       => __( 'Přidat průvodce', 'cba' ),
            'add_new_item'  => __( 'Přidat nového průvodce', 'cba' ),
            'edit_item'     => __( 'Upravit průvodce', 'cba' ),
            'view_item'     => __( 'Zobrazit průvodce', 'cba' ),
            'all_items'     => __( 'Všichni průvodci', 'cba' ),
            'menu_name'     => __( 'Průvodci', 'cba' ),
        ],
        'public'        => true,
        'has_archive'   => true,
        'show_in_rest'  => true,
        'menu_icon'     => 'dashicons-randomize',
        'menu_position' => 33,
        'supports'      => [ 'title', 'editor', 'excerpt', 'thumbnail' ],
        'rewrite'       => [ 'slug' => 'pruvodci' ],
    ] );

    // Taxonomies (prepared for future use)
    register_taxonomy( 'foxo_learning_category', [ 'foxo_quiz', 'foxo_course', 'foxo_lesson', 'foxo_decision_tree' ], [
        'label'        => __( 'Kategorie', 'cba' ),
        'hierarchical' => true,
        'public'       => true,
        'show_in_rest' => true,
        'rewrite'      => [ 'slug' => 'vzdelavani-kategorie' ],
    ] );

    register_taxonomy( 'foxo_learning_level', [ 'foxo_course', 'foxo_lesson' ], [
        'label'        => __( 'Úroveň', 'cba' ),
        'hierarchical' => true,
        'public'       => true,
        'show_in_rest' => true,
        'rewrite'      => [ 'slug' => 'uroven' ],
    ] );
}
