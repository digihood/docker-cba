<?php 

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

/**
   * Print result with pre tag
   *
   * @param $lang string
   * 
   * @author Digihood
   * @return echo string
   */ 

if ( !function_exists('preprint') ) {

  function preprint( $print, $die=false ) {
    if ($print){
      echo '<pre>';
      echo print_r( $print );
      echo '</pre>';
    } else {
      echo 'NIC K ZOBRAZENÍ';
    }
    if ($die) die('Konec preprintu');

  }
  
}
  
/**
 * Fix google map api
 *
 * @param none
 * 
 * @author Digihood
 * @return true/false
 */ 
if ( !function_exists('acf_google_map_api_digihood') ) { 

    function acf_google_map_api_digihood( $api ){
      $api['key'] = d1g1Settings::google_api_digihood();
      
      return $api;
      
    }
  
    add_filter('acf/fields/google_map/api', 'acf_google_map_api_digihood');
  
}



/**
 * Remove JQuery migrate
 *
 * @param none
 * 
 * @author Digihood
 * @return void
 */ 
function remove_jquery_migrate( $scripts ) {
  if ( ! is_admin() && isset( $scripts->registered['jquery'] ) ) {
    $script = $scripts->registered['jquery'];
    if ( $script->deps ) { 
      $script->deps = array_diff( $script->deps, array( 'jquery-migrate' ) );
    }
  }
}
add_action( 'wp_default_scripts', 'remove_jquery_migrate' );

/**
 * returns check if current language is as desired
 *
 * @param $lang string
 * 
 * @author Digihood
 * @return true/false
 */ 

if ( !function_exists('is_lang') ) {

    function is_lang( $lang ){
  
      $compare = "";
      if ( $lang == "en" ) {
        $compare ="en-GB";
      } else if ( $lang == "cz"  ) {
        $compare="cs-CZ";
      }
  
      $currentlang = get_bloginfo('language');
  
      if ( $currentlang == $compare) {
        return true;
      } else {
        return false;
      }
  
    }
  
}
  
/**
   * returns defined string for each language, first is czech, english second 
   *
   * @param $lang string
   * 
   * @author Digihood
   * @return echo string
   */ 
if ( !function_exists('add_new_mime_types_end') ) {
  
  function add_new_mime_types_end($mime_types){
    $mime_types['ogv'] = 'application/ogg'; //Adding svg extension
    return $mime_types;
  }
  add_filter('upload_mimes', 'add_new_mime_types_end', 1, 1);
  
}

/**
   * return icon url
   *
   * @param $lang string
   * 
   * @author Digihood
   * @return echo string
   */ 

if ( !function_exists('get_svg_url') ) {

    function get_svg_url( $value ) {
  
      return get_stylesheet_directory_uri() . '/assets/svg/' . $value . ".svg";
  
    }
  
  }
  
/**
   * Rename options menu
   *
   * @param $lang string
   * 
   * @author Digihood
   * @return echo string
   */ 
if (function_exists('acf_set_options_page_menu')){
  acf_set_options_page_menu('Nastavení webu');
}

/**
 * User redirect to refferer
 *
 * @param none
 * 
 * @author Digihood
 * @return true/false
 */ 
if ( !function_exists( 'redirect_back' ) ) {
  function redirect_back(){
    $location = $_SERVER['HTTP_REFERER'];
    wp_safe_redirect($location);
    exit();
  }
}

/**
 * Add robot.txt to website
 *
 * @param none
 * 
 * @author Digihood
 */ 
function ReplaceRobotsTxt($robotstext, $public) {
  $robotstext = "User-agent: SomeBot
                 Allow: /";
  return $robotstext;
}
add_filter('robots_txt', 'ReplaceRobotsTxt', 10, 2);

/**
 * Funkce Kontroluje, jestli jde o produkční web
 *
 * @param none
 * 
 * @author Digihood
 */ 

function is_production_d1g1() {
	$url = home_url( );

	if(strpos($url, "custom.cz") !== false) {
		return true;
	} else {
		return false;
	}
  
}

/**
 * Funkce Kontroluje, jestli jde o produkční web
 *
 * @param none
 * 
 * @author Digihood
 */ 

function body_class_d1g1() {

	//define language class
  if ( is_lang('en') ) {  
    $class = "english";
  } else { 
    $class = "czech"; 
  } 

  return $class;
  
}

/**
 * Funkce Kontroluje, jestli jde o produkční web
 *
 * @param none
 * 
 * @author Digihood
 */ 
function schema_org_d1g1() {

	switch (true) {
    case is_single():
      return ' itemscope itemtype="http://schema.org/BlogPosting"';
      break;
    
    default:
      return ' itemscope itemtype="http://schema.org/WebPage"';
      break;
  }

  return false;
  
}

/**
 * Funkce Kontroluje, jestli je v poli požadovaný key
 *
 * @param $array - pole s klíči
 * @param $key - hledaný klíč v poli
 * 
 * @author Digihood
 */
if ( !function_exists( 'valid_key_in_array' ) ) { 
  function valid_key_in_array($array, $key){
    if (is_array($array) && isset($array[$key]) && !empty($array[$key]) ) return true;
    return false;
  }
}

define('VITE_SERVER', 'http://localhost:3000');
define('VITE_ENTRY_POINT', '/vite-main.js');

function vite_head_module_hook() {
    echo '<script type="module" crossorigin src="' . VITE_SERVER . VITE_ENTRY_POINT . '"></script>';
}
add_action('wp_head', 'vite_head_module_hook'); 