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
			// Post types
            add_action( 'init', [$this, 'create_post_type'] );
            add_action( 'init', [$this, 'create_documentation_post_type'] );

            // Taxonomie
            add_action( 'init', [$this,'create_project_type_tax'] );
            add_action( 'init', [$this,'create_project_status_tax'] );
            add_action( 'init', [$this,'create_documentation_category_tax'] );
        }

		// Projekt post type
        function create_post_type() {
            register_post_type( 'project',
                array(
                  'labels' => array(
                    'name' => __( 'Projekt', 'cba' ),
                    'add_new' => __( 'Přidat projekt', 'cba' ),
                    'view_item'=> __( 'Zobrazit projekt', 'cba' ),
                    'edit_item' => __( 'Upravit projekt', 'cba' ),
                    'singular_name' => __( 'projekt', 'cba' ),
                    'menu_name' => __( 'Projekty', 'cba' ),
                  ),
                  'public' => true,
                  'menu_icon' => 'dashicons-media-spreadsheet',
                  'menu_position' => 57,
                  'has_archive' => true,
                  'show_in_rest' => true,
                  'supports' => array( 'title', 'editor', 'excerpt', 'page-attributes', 'thumbnail' , 'author' )
                )
            );
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
                    'menu_position' => 58,
                    'has_archive' => true,
                    'show_in_rest' => true,
                    'hierarchical' => true,
                    'supports' => array( 'title', 'editor', 'page-attributes', 'revisions' ),
                    'rewrite' => array('slug' => 'dokumentace')
                )
            );
        }

        // Taxonomie typ projektu
        function create_project_type_tax() {
            register_taxonomy(
                'project_type',
                'project',
                array(
                    'label' => __( 'Typ projektu', 'cba' ),
                    'rewrite' => array( 'slug' => 'typ' ),
                    'hierarchical' => true,
                )
            );
        }

        // Taxonomie status
        function create_project_status_tax() {
            register_taxonomy(
                'project_status',
                'project',
                array(
                    'label' => __( 'Status projektu', 'cba' ),
                    'rewrite' => array( 'slug' => 'status' ),
                    'hierarchical' => true,
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