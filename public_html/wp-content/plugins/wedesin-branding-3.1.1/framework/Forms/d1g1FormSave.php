<?php 
namespace pluginbrandslug\framework\Forms;
use pluginbrandslug\framework\d1g1Session;
use pluginbrandslug\framework\Forms\d1g1FormsBuilderFields;
use pluginbrandslug\framework\monolog\d1g1MonologFunction;
use pluginbrandslug\framework\Forms\d1g1FormsValidationForm;

/**
 * Ukládání formuláře
 *
 * 
 * @author Digihood
 */ 

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

if( ! class_exists( 'd1g1FormSave' ) )
{
	class d1g1FormSave
	{
        private static $form_id = '';
        private static $prefix_form = '';

        function __construct(){
           
        }
        
        // nastaví prefix pro ukládání dat do options
        private static function set_prefix_and_form_id($form_id) {
            self::$prefix_form = '_d1g1_'.D1G1_BRANDING.'_';
            self::$form_id = $form_id;
           
        }

        /**
        * 	Ukládání backendového formuláře
        *
        * 	@param $value = popis value
        * 
        * 	@author Digihood
        * 	@return true/false
        */
        public static function save_form()
        {
            $inputs = filter_input_array(INPUT_POST);
          
            if (!isset($inputs['form_id_d1g1']) || $inputs['form_id_d1g1'] == '') {
                return;
            }
         //   d1g1Session::start_session();
            self::set_prefix_and_form_id($inputs['form_id_d1g1']);
            $fields = d1g1FormsBuilderFields::get_fields_form(self::$form_id);
            if (isset($inputs['nonce_' . self::$form_id]) && wp_verify_nonce($inputs['nonce_' . self::$form_id], self::$form_id)) {
                //validace polí
                if (!d1g1FormsValidationForm::validate_fields($inputs,self::$form_id)) {
                    d1g1Session::add_session(self::$form_id . '_save', 'fail');
                  
                    wp_safe_redirect($inputs['_wp_http_referer']);
                    exit;
                }
                //honeypot pole
                if ((isset($inputs['d1g1_meta']['name']) && $inputs['d1g1_meta']['name'] !== "") || (isset($inputs['d1g1_meta']['surname']) && $inputs['d1g1_meta']['surname'] !== "")) {
                    exit;
                }
                if (isset($fields) && isset($fields[0])) {
                   
                    // zde můžeme upravovat jak se má soubor zpracovávat
                    if (array_key_exists('save_option',$fields[0])){
                      
                        do_action('d1g1_form_save_option_hook', self::$form_id , $inputs , $fields );
               
                    //defaultní zpracování formuláře
                    }else {
                         
                        $metas = $inputs;
                        $validation_data = d1g1Session::get_session( self::$form_id.'_validation' );
                        // Doplnění dat z odškrtnutých checkboxů
                        
                        foreach ($validation_data as $input_name => $input_data) {
                                if ( isset($input_data['type']) && (
                                    $input_data['type'] == 'checkbox' || 
                                    $input_data['type'] == 'switch' || 
                                    $input_data['type'] == 'checkbox_large' )
                                    
                                ){
                                    if (empty($input_data['value']) ) {
                                        
                                        $metas[$input_name] = 0;
                                    }
                                }
                        }
                        
                        //vyhodit hodnoty, které se nemají uložit
                        if(isset($metas['_wp_http_referer'])) unset($metas['_wp_http_referer']);
                        if(isset($metas['form_id_d1g1'])) unset($metas['form_id_d1g1']);
                        if(isset($metas['submit'])) unset($metas['submit']);
                        if(isset($metas['nonce_' . self::$form_id])) unset($metas['nonce_' . self::$form_id]);
                        //zde budeme zpracovávat ukládání hodnot
                            if ($metas && !empty($metas)) {
                               
                                foreach ($metas as $meta_key => $meta_value) {
                                
                                    if($meta_key) self::save_data($meta_key, $meta_value);
                                        
                                }
                               
                                // remove field validation data
                                d1g1Session::remove_session(self::$form_id . '_validation');
                                // add "all good and saved" notice
                                d1g1Session::add_session(self::$form_id . '_save', 'success');
                                (new d1g1MonologFunction)->d1g1_add_log(__METHOD__,'INFO',self::$form_id,[$metas]);
                                $newUrl = add_query_arg(['submit' => 1], $inputs['_wp_http_referer']);
                              
                               
                                wp_safe_redirect($newUrl);
                                exit;
                                } 
                    }  
                    
                }   
                
                d1g1Session::add_session(self::$form_id . '_save', 'fail');
              
                wp_safe_redirect($inputs['_wp_http_referer']);
                exit;
            }
        }
        
        /**
        * 	Uložení informace do option meta
        * 	@param $key = jméno inputu
        * 	@param $value = hodnota z inputu
        * 
        * 	@author Digihood
        * 	@return true/false
        */
        private static function save_data($key, $value) {
            $id = (self::$form_id ? self::$form_id : 'default');
            $pref = self::$prefix_form;
            $name = $pref. $id.'_'. $key;
            update_option($name, $value, false);
        }

    }

}