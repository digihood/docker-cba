<?php 

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
  
/**
 * Print result with pre tag
 *
 * @param $lang string
 * 
 * @author digihood
 * @return echo string
 */ 

if ( !function_exists('preprint') ) {

    function preprint( $print ) {

        echo '<pre>';

        echo print_r( $print );

        echo '</pre>';

    }

}

/**
 * Print result with pre tag with die();
 *
 * @param $lang string
 * 
 * @author digihood
 * @return echo string
 */ 

if ( !function_exists('preprintd') ) {

    function preprintd( $print ) {

        echo '<pre>';

        echo print_r( $print );

        echo '</pre>';
        die();

    }

}

    /**
     * d1g1 get option 
     *
     * @param $form_prefix 
     * @param $meta_name 
     * @author digihood
     * @return echo string
     */ 
if ( !function_exists('d1g1_get_option') ) {
    function d1g1_get_option($form_prefix, $meta_name){
        $option = get_option ('_d1g1_' . D1G1_BRANDING . '_' . $form_prefix . '_' . $meta_name);
        return $option;
    }
}

/**
 * ziskává SVG soubor 
 *
 * @param url
 * 
 * @author digihood
 * @return 
 */ 
 if( ! function_exists('d1g1_get_svg') ){
    function d1g1_get_svg($url) {
        $arrContextOptions=array(
            "ssl"=>array(
                "verify_peer"=>false,
                "verify_peer_name"=>false,
            ),
        );  
        
        $response = file_get_contents($url , false, stream_context_create($arrContextOptions));
        return $response;

    }
}
if( !function_exists('get_plugin_data') ){
    require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}

if(!function_exists('is_licensing')){
   function is_licensing(){
    return D1G1_IS_LICENSING_BRAND;        
    }
}

if(!function_exists('digi_date')){
    function digi_date($format,$date){
        if(function_exists('wp_date')){
            return wp_date($format,$date);
        }elseif(version_compare(PHP_VERSION, '7.0', '<=')){
            return date($format,$date);
        }
    }
}
if(function_exists('wp_maintenance_mode')){
    add_action('init', 'wp_maintenance_mode');
    function wp_maintenance_mode() {
    
        if (d1g1_get_option('plugins_tools','digi_maintenance_mode') && !current_user_can('edit_themes') && !is_user_logged_in()) {
            if(d1g1_get_option('plugins_tools','digi_maintenance_mode_time') !== null){
        
                if( strtotime(d1g1_get_option('plugins_tools','digi_maintenance_mode_time')) > strtotime(date('H:i'))){
            
                    wp_die((d1g1_get_option('plugins_tools','digi_maintenance_mode_text') ? d1g1_get_option('plugins_tools','digi_maintenance_mode_text') : ''),(d1g1_get_option('plugins_tools','digi_maintenance_mode_title') ? d1g1_get_option('plugins_tools','digi_maintenance_mode_title') : ''));
                }
            }else{
            
                    wp_die((d1g1_get_option('plugins_tools','digi_maintenance_mode_text') ? d1g1_get_option('plugins_tools','digi_maintenance_mode_text') : ''),(d1g1_get_option('plugins_tools','digi_maintenance_mode_title') ? d1g1_get_option('plugins_tools','digi_maintenance_mode_title') : ''));
            }
            
        
        }
    }
  
}
