<?php

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

if( ! class_exists( 'd1g1RegisterCustomPostTypes' ) )
{
    class d1g1RegisterCustomPostTypes
    {

        public function __construct()
        {
            add_action( 'init', [$this, 'create_documentation_post_type'] );
            add_action( 'init', [$this,'create_documentation_category_tax'] );
        }

		// Dokumentace post type
        function create_documentation_post_type() {
            register_post_type( 'documentation',
                array(
                    'labels' => array(
                        'name' => __( 'Dokumentace', 'cba' ),
                        'add_new' => __( 'Přidat dokumentaci', 'cba' ),
                        'view_item'=> __( 'Zobrazit dokumentaci', 'cba' ),
                        'edit_item' => __( 'Upravit dokumentaci', 'cba' ),
                        'singular_name' => __( 'dokumentace', 'cba' ),
                        'menu_name' => __( 'Dokumentace', 'cba' ),
                    ),
                    'public' => true,
                    'menu_icon' => 'dashicons-media-document',
                    'menu_position' => 100,
                    'has_archive' => true,
                    'show_in_rest' => true,
                    'hierarchical' => true,
                    'supports' => array( 'title', 'editor', 'page-attributes', 'revisions' ),
                    'rewrite' => array('slug' => 'dokumentace')
                )
            );
        }

        // Taxonomie kategorie dokumentace
        function create_documentation_category_tax() {
            register_taxonomy(
                'documentation_category',
                'documentation',
                array(
                    'label' => __( 'Kategorie dokumentace', 'cba' ),
                    'rewrite' => array( 'slug' => 'kategorie-dokumentace' ),
                    'hierarchical' => true,
                    'show_in_rest' => true,
                    'labels' => array(
                        'name' => __( 'Kategorie dokumentace', 'cba' ),
                        'singular_name' => __( 'Kategorie dokumentace', 'cba' ),
                        'add_new_item' => __( 'Přidat novou kategorii', 'cba' ),
                        'edit_item' => __( 'Upravit kategorii', 'cba' )
                    )
                )
            );
        }
    }
}

new d1g1RegisterCustomPostTypes;
