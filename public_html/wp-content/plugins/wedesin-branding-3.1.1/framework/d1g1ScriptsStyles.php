<?php

/**
 * class description
 *
 * 
 * @author digihood
 */ 

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

if ( !class_exists('d1g1ScriptsStyles') ) {

  class d1g1ScriptsStyles {

    /**
     * 
     * Constructor
     *
     * @date	21/1/21
     * @since	1.0
     *
     * @return	void
     */
    public function __construct()
    {

  
      //scripty v administraci
      add_action( 'admin_enqueue_scripts', [$this, 'add_admin_scripts'] );
  
    }
  

  

  
    public function add_admin_scripts( ) {
  
      //Register style
      $admin_style = filemtime(  D1G1_BRANDPATH . 'assets/styles/d1g1-admin.css' );
      wp_enqueue_style( 'star-comments', D1G1_BRANDURL . 'assets/styles/d1g1-admin.css', array( ), $admin_style, 'all' );
    
      wp_enqueue_style( 'fonts-gstatic', 'https://fonts.gstatic.com' );
      wp_enqueue_style( 'font-Lato', 'https://fonts.googleapis.com/css2?family=Lato:ital@0;1&display=swap' );
  
      wp_enqueue_style( 'wp-color-picker' );
  
      //register javascript
      $admin_script = filemtime(  D1G1_BRANDPATH . 'assets/scripts/d1g1-admin.js' );
      wp_enqueue_script( 'star-comments', D1G1_BRANDURL . 'assets/scripts/d1g1-admin.js', array( 'jquery' ), $admin_script, true );
      
      //WP media uploader
      wp_enqueue_media();
  
      //color picker scripts
      $colorpicker_script = filemtime(  D1G1_BRANDPATH . 'assets/scripts/wp-color-picker-alpha.min.js' );
      wp_enqueue_script( 'wp-color-picker-alpha', D1G1_BRANDURL . 'assets/scripts/wp-color-picker-alpha.min.js', array( 'wp-color-picker' ), $colorpicker_script, true );
  
      wp_add_inline_script(
        'wp-color-picker-alpha',
        'jQuery( function() { jQuery( ".color-picker" ).wpColorPicker(); } );'
      );
  
      //Selectize.js
      $selectize_script = filemtime(  D1G1_BRANDPATH . 'assets/scripts/selectize.js' );
      wp_enqueue_script( 'selectize', D1G1_BRANDURL . 'assets/scripts/selectize.js', array( 'jquery' ), $selectize_script, true );
  
      $selectize_style = filemtime(  D1G1_BRANDPATH . 'assets/styles/selectize.css' );
      wp_enqueue_style( 'selectize', D1G1_BRANDURL . 'assets/styles/selectize.css', array( ), $selectize_style, 'all' );
      
    }
  
  }
  new d1g1ScriptsStyles;

}
