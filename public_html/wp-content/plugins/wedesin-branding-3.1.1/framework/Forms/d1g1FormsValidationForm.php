<?php 
namespace pluginbrandslug\framework\Forms;
use pluginbrandslug\framework\d1g1Session;
use pluginbrandslug\framework\Forms\d1g1FormsBuilderFields;

/**
 * Validace polí a formuláře
 *
 * 
 * @author Digihood
 */ 
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

if( ! class_exists( 'd1g1FormsValidationForm' ) )
{
	class d1g1FormsValidationForm
	{

/**
        * 	Validace jednotlivých polí formuláře
        *
        * 	@param $inputs = pole všech inputů ke kontrole
        * 
        * 	@author Digihood
        * 	@return true/false
        */
        public static function validate_fields($inputs, $form_id)
        {
            if (!$fields_all = d1g1FormsBuilderFields::get_fields_form($form_id)) {
                die('Fields must be set...');
            }
            $ffv = []; // pole určené k validaci
           
            $allvalid = true;

            $validated = [];
            foreach ($fields_all as $sections) {
                //data jsou v sekcích, ty postupně budu kontrolovat
                foreach ($sections as $key => $section_field) {
                    
                    if (is_numeric($key) ) { // pokud je pole s hodnotami ( mohou obsahovat pole k validaci, ale nemusí)

                        if (is_array($section_field) && array_key_exists('saveAs', $section_field))

                            $ffv[] = $section_field; // pokud je pole určené k uložení, přidám ho do pole pro validaci

                    } else if($key == 'columns') { //pokud se jedná o sloupce
                        if(is_array($section_field)) {
                            foreach ($section_field as $column) {
                                if (is_array($column)){
                                    foreach ($column as $key => $col_field) {
                                        if (is_array($col_field) && array_key_exists('saveAs', $col_field)) {
                                            $ffv[] = $col_field; // pokud je pole určené k uložení, přidám ho do pole pro validaci
                                        }
                                    }
                                }
                            }
                        }
                    } else { 
                        //v tutu chvíli nepodstatné - nejsou tam pole k validaci, třeba se to změní v budoucnu
                    }
                }
            }
            foreach ($ffv as $index => $field) {
                $value = '';
                $valid = true;
                $msg = '';

                $type = isset($field['type']) ? $field['type'] : false;
                $name = isset($field['name']) ? $field['name'] : false;
                $name = $type == 'gallery' ? 'gallery_' . $name : $name;
                $required = isset($field['required']) ? $field['required'] : false;

                switch ($field['saveAs']) {
                    case 'meta':
                        $value = isset($inputs[$name]) ? $inputs[$name] : null;
                        break;
                    /*case 'taxonomy':
                        $value = isset($inputs['d1g1_taxonomy'][$name]) ? $inputs['d1g1_taxonomy'][$name] : null;
                        break;
                    case 'post_title':
                    case 'post_content':
                    case 'post_excerpt':
                        $value = isset($inputs[$field['saveAs']]) ? $inputs[$field['saveAs']] : null;
                        break;*/
                    default:
                        $value = isset($inputs[$name]) ? $inputs[$name] : null;
                }

                if ($required && !self::d1g1_if_required($value)) {
                    $valid = false;
                    $msg = 'Povinné pole';
                    $allvalid = false;
                   
                }

                if ( isset($field['length']) && $field['length'] < !self::valid_leght_string($value) ) {
                    $valid = false;
                    $msg = 'Pole je příliž dlouhé';
                    $allvalid = false;
                }

                //validate emails
                if ($type == 'email' && !self::d1g1_email_valid($value)) {
                    $valid = false;
                    $msg = 'Pole není email';
                    $allvalid = false;

                }
               
                //validate range unit
                if ($type == 'range' && !self::d1g1_range_valid( $value, $field)) {
                    $valid = false;
                    $msg = 'Hodnota nesplňuje požadavky';
                    $allvalid = false;
                
                }
                //pokud range obsahuje jednotky tady je přidávám
                if ($type == 'range' && self::d1g1_range_unit_valid( $inputs, $field)) {
                    $validated[$name.'_unit'] = [
                        'value' => $inputs[$name.'_unit'],
                        'valid' => true,
                        'msg' => "",
                    ];
                }
                $validated[$name] = [
                    'value' => $value,
                    'valid' => $valid,
                    'msg' => $msg,
                    'type' => $type
                ];
            }
            $validated['allvalid'] = $allvalid;
            d1g1Session::add_session($form_id . '_validation', $validated);
            return $allvalid;
        }

         /**
         * validace range pole
         *
         * @param $inputs
         *        $name
         * 
         * @author digihood
         * 
         */ 

        private static function d1g1_range_valid($value,$field){
            if ( !is_numeric($value) || 
                !isset($field['max'], $field['min']) ||
                $value > $field['max'] || 
                $value < $field['min']
            ) return false;
            return true;
        }

        /**
         * validace range pole
         *
         * @param $inputs
         *        $name
         * 
         * @author digihood
         * 
         */ 

        private static function d1g1_range_unit_valid($inputs,$field){
            $range_name = isset($field['name']) ? $field['name'] : "";
            $unit = (isset($inputs[$range_name.'_unit']) ? $inputs[$range_name.'_unit'] : '');
            if (!empty($unit)) return true;

            return false;
        }

        /**
         * validace email pole
         *
         * @param $value
         *        
         * 
         * @author digihood
         * 
         */ 

        private static function d1g1_email_valid($value){
            if (!empty($value)) {
                if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    return false;
                } else return true;
            }
            return false;
        }

        /**
         * validace povinosti pole
         *
         * @param $value
         *        
         * 
         * @author digihood
         * 
         */ 
        private static function d1g1_if_required($value){
            if (is_array($value) && empty($value)) {
                return false;
            } else if ($value == '' || !$value) 
                    return false;
            return true;
        }

        /**
         * validace textu pole
         *
         * @param $value
         *        
         * 
         * @author digihood
         * 
         */ 
        public static function valid_leght_string($value) {
            $strip = (strip_tags($value));
            $strip = trim($strip);
            $conversion_table = Array(
                'ä'=>'a',
                'Ä'=>'A',
                'á'=>'a',
                'Á'=>'A',
                'à'=>'a',
                'À'=>'A',
                'ã'=>'a',
                'Ã'=>'A',
                'â'=>'a',
                'Â'=>'A',
                'č'=>'c',
                'Č'=>'C',
                'ć'=>'c',
                'Ć'=>'C',
                'ď'=>'d',
                'Ď'=>'D',
                'ě'=>'e',
                'Ě'=>'E',
                'é'=>'e',
                'É'=>'E',
                'ë'=>'e',
                'Ë'=>'E',
                'è'=>'e',
                'È'=>'E',
                'ê'=>'e',
                'Ê'=>'E',
                'í'=>'i',
                'Í'=>'I',
                'ï'=>'i',
                'Ï'=>'I',
                'ì'=>'i',
                'Ì'=>'I',
                'î'=>'i',
                'Î'=>'I',
                'ľ'=>'l',
                'Ľ'=>'L',
                'ĺ'=>'l',
                'Ĺ'=>'L',
                'ń'=>'n',
                'Ń'=>'N',
                'ň'=>'n',
                'Ň'=>'N',
                'ñ'=>'n',
                'Ñ'=>'N',
                'ó'=>'o',
                'Ó'=>'O',
                'ö'=>'o',
                'Ö'=>'O',
                'ô'=>'o',
                'Ô'=>'O',
                'ò'=>'o',
                'Ò'=>'O',
                'õ'=>'o',
                'Õ'=>'O',
                'ő'=>'o',
                'Ő'=>'O',
                'ř'=>'r',
                'Ř'=>'R',
                'ŕ'=>'r',
                'Ŕ'=>'R',
                'š'=>'s',
                'Š'=>'S',
                'ś'=>'s',
                'Ś'=>'S',
                'ť'=>'t',
                'Ť'=>'T',
                'ú'=>'u',
                'Ú'=>'U',
                'ů'=>'u',
                'Ů'=>'U',
                'ü'=>'u',
                'Ü'=>'U',
                'ù'=>'u',
                'Ù'=>'U',
                'ũ'=>'u',
                'Ũ'=>'U',
                'û'=>'u',
                'Û'=>'U',
                'ý'=>'y',
                'Ý'=>'Y',
                'ž'=>'z',
                'Ž'=>'Z',
                'ź'=>'z',
                'Ź'=>'Z'
            );
            $return = strtr($strip, $conversion_table);
            $return = str_replace( array("\r", "\n"), '', $return );
            $num = strlen($return);

            return $num;
        }

        // run some tests, all this under should be only once
        public static function valid_new_cpt($fields_array) {
            // get index
            
            if (self::get_form_field_count($fields_array, 'post_title') > 1) {
                die('Fields contain more than 1 post_title.');
            }
            if (self::get_form_field_count($fields_array, 'post_content') > 1) {
                die('Fields contain more than 1 post_content.');
            }
            if (self::get_form_field_count($fields_array, 'post_excerpt') > 1) {
                die('Fields contain more than 1 post_excerpt.');
            }
            if (self::get_form_field_featuredImages_count($fields_array) > 1) {
                die('Fields contain more than 1 featured image.');
            }
        }
        public static function get_form_field_index($fields, $key)
        {
            return array_keys(array_column($fields, 'saveAs'), $key);
        }

        private static function get_form_field_count($fields, $key)
        {
            return count(self::get_form_field_index($fields, $key));
        }

        private static function get_form_field_featuredImages_index($fields)
        {
            return array_keys(array_column($fields, 'useAsFeatured'), 1);
        }

        private static function get_form_field_featuredImages_count($fields)
        {
            return count(self::get_form_field_featuredImages_index($fields));
        }

    }
}