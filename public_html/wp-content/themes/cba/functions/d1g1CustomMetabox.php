<?php 
/**
 * Popis třídy
 *
 * 
 * @author Digihood
 */ 

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}
//původní název customMetaboxD1g1
if( ! class_exists( 'd1g1CustomMetabox' ) )
{
	class d1g1CustomMetabox
	{

        //konstruktor
		public function __construct()
		{
            //set variable
            add_action( 'add_meta_boxes', [$this, 'add_custom_metaboxes'] );
        }

        //vlastní metaboxy
        function add_custom_metaboxes() {
            $post_types = [ 'post' ];
            foreach ( $post_types as $type ) {
                add_meta_box(
                    'digi-metabox',                         // Unique ID
                    __('Vlastní metabox', 'cba'),        // Box title
                    [$this,'content_metabox'],              // Content callback, must be of type callable
                    $type                                   // Post type
                );
            }
        }

        function content_metabox( ) {
            echo "ahoj";
        }

    }

    new d1g1CustomMetabox;
}