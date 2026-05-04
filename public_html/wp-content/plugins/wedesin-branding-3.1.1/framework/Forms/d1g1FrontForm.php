<?php
namespace pluginbrandslug\framework\Forms;
use pluginbrandslug\framework\d1g1Session;
use pluginbrandslug\framework\Forms\d1g1GetHtmlForm;
use pluginbrandslug\framework\Forms\d1g1FormsValidationForm;
use pluginbrandslug\admin\fields\d1g1ThisPluginField;

if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('d1g1FrontForm')) {
    class d1g1FrontForm 
    {
        private static $form_id = '';
        private static $prefix_form = '';


        public function __construct()
        {
 
        }

        // vrátí prefix formuláře pro ukládání option dat
        private static function get_prefix() {
            return '_d1g1_'.D1G1_BRANDING.'_';
        }

        // nastaví prefix pro ukládání dat do options
        private static function set_prefix() {
            self::$prefix_form = '_d1g1_'.D1G1_BRANDING.'_';
        }

        /**
         * Vyhotovení formuláře
         *
         * @param none
         * 
         * @author digihood
         * @return true/false
         */
		public static function display_form($formID ) {
            self::$form_id = $formID;
            self::set_prefix();
			echo self::get_form( );
		}

        private static function get_form()
        {
            $form_html = "";
            if (!self::$form_id) return "";
            ob_start();
            ?>
            <div class="wrap d1g1-admin">
                <?php settings_errors(); ?>

                <div class="row">
                    <div class="content">

                        <!-- místo pro taby -->

                        <div class="content-box">

                            <span class="form-star"><small><?= __('* povinné pole', 'textdomain'); ?></small></span>

                            <form method="post" id="<?= self::$form_id ?>_form" class="d1g1-form" action="" enctype="multipart/form-data" novalidate data-abide>
                                <?php wp_nonce_field( self::$form_id, 'nonce_' . self::$form_id ); ?>
                                <input type="hidden" value="<?=self::$form_id?>" name="form_id_d1g1" />
                                <input type="hidden" value="<?= D1G1_BRANDING?>" name="plugin_id_d1g1" />                                                             
                                <?php self::get_form_fields(); 
                                    do_action( 'd1g1_submit_button_form-'.D1G1_BRANDING, self::$form_id );
                                    has_action( 'd1g1_submit_button_form-'.D1G1_BRANDING) ? '' : submit_button();
                                 ?>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            $form_html = ob_get_contents();
            ob_end_clean();
            return $form_html;
        }

        /**
         * 	vrátí pole všech inputů
         *
         * 	@param $fields = data inputu
         * 	@param $key = jméno
         *
         * 	@author digihood
         * 	@return echo
         */
        public function get_fields($fields, $key)
        {
            $return_fields = [];
            $indexes = d1g1FormsValidationForm::get_form_field_index($fields, $key);
            if (is_array($indexes) && !empty($indexes)) {
                foreach ($indexes as $index) {
                    $return_fields[] = $fields[$index];
                }
            }
            return $return_fields;
        }

        /**
         * 	Sestaví html všech inputů
         *
         * 	@author digihood
         * 	@return echo
         */
        public static function get_form_fields()
        {
            $fields_array = d1g1ThisPluginField::get_fields_form(self::$form_id);
            if (empty($fields_array)) {
                die('Fields must be set..');
            }

            // get index
            // run some tests, all this under should be only once
            d1g1FormsValidationForm::valid_new_cpt($fields_array);

            $validation_data = d1g1Session::get_session( self::$form_id.'_validation' );
            $save_progress = d1g1Session::get_session( self::$form_id.'_save' );
            if (isset($validation_data['allvalid']) && !$validation_data['allvalid']) {
                
                d1g1GetHtmlForm::d1g1_notice_form('error');
                d1g1Session::remove_session(self::$form_id.'_save');
               
            } else if ( $save_progress == 'success') {
              
                d1g1GetHtmlForm::d1g1_notice_form('success');
                d1g1Session::remove_session(self::$form_id.'_save');
            } else if ( $save_progress == 'send_email') {
              
                d1g1GetHtmlForm::d1g1_notice_form('send_email');
                d1g1Session::remove_session(self::$form_id.'_save');
            } else if ( $save_progress == 'fail') {
              
                d1g1GetHtmlForm::d1g1_notice_form('fail');
                d1g1Session::remove_session(self::$form_id.'_save');
            }
           
           // preprint($validation_data);
            foreach ($fields_array as $section) {

                $section_headline = isset($section['headline']) ? $section['headline'] : false;
                $section_description = isset($section['description']) ? $section['description'] : false;

                //Vše v array fields jsou automaticky sekce formuláře
                echo '<div class="form-section">';

                if ($section_headline) echo '<h2>' . $section_headline . '</h2>';
                if ($section_description) echo '<p>' . $section_description . '</p>';

                //Pole formuláře v sekcích
                foreach ($section as $item => $field) {
                    // Pokud je ve formuláři columns, zobrazí se zde, pokud je nařadě
                    if ($item === 'columns') {
                        echo '<div class="row xl">';
                        $row = $field;
                        foreach ($row as $column) {

                            //preprint($column);
                            echo '<div class="column">';

                            //Pole formuláře v gridu
                            foreach ($column as $subfield) {

                                $type = isset($subfield['type']) ? $subfield['type'] : false;
                                $name = isset($subfield['name']) ? $subfield['name'] : false;

                                if ($name && $type) {

                                    $is_valid = true;

                                    //nejdřív přidáme skrytá pole
                                    if ($type == 'hidden') {

                                        $value = isset($subfield['value']) ? $subfield['value'] : self::get_data($name);
                                       self::get_input_html($type, $name, $value, $subfield, $is_valid, self::$form_id);
                                    } else { 
                                        $is_valid = isset($validation_data[$name]['valid']) ? $validation_data[$name]['valid'] : $is_valid; ?>
                                        <div class="form-item <?php echo $name; ?>">

                                        <?php 
                                            if (self::get_data($name)) {
                                                $value = self::get_data($name);
                                            }elseif(isset($field['value'])) {
                                                $value = $field['value'];
                                            } else {
                                                $value = "";
                                            }
                                            self::get_input_html($type, $name, $value, $subfield, $is_valid, self::$form_id); ?>

                                        </div>

                                <?php
                                    }
                                }
                            }

                            echo '</div>';
                        }

                        echo '</div>';                       

                    }
                    $type = isset($field['type']) ? $field['type'] : false;
                    $name = isset($field['name']) ? $field['name'] : false;

                    if ($name && $type) {

                        $is_valid = true;

                        //nejdřív přidáme skrytá pole
                        if ($type == 'hidden') {
                            $value = isset($field['value']) ? $field['value'] : self::get_data($name);
                            self::get_input_html($type, $name, $value, $field, $is_valid, self::$form_id);
                        } else { 
                            $is_valid = isset($validation_data[$name]['valid']) ? $validation_data[$name]['valid'] : $is_valid; ?>

                            <div class="form-item <?php echo $name; ?>">

                                <?php 
                                if (isset($validation_data[$name]['value'])) {
                                    $value = $validation_data[$name]['value'];
                                }else if (self::get_data($name) || self::get_data($name) == 0) {
                                    $value = self::get_data($name);
                                }elseif(isset($field['value'])) {
                                    $value = $field['value'];
                                } else {
                                    $value = "";
                                }
                                self::get_input_html($type, $name, $value, $field, $is_valid, self::$form_id); ?>

                            </div>
                        <?php
                        }
                    }
                }

                echo '</div>';
            }

            d1g1Session::remove_session(self::$form_id . '_validation');
        }

      
        /**
        * 	Sestavení jednotlivých inputů v html
        *
        * 	@param $value = popis value
        * 
        * 	@author Digihood
        * 	@return true/false
        */
        public static function get_input_html($type, $name, $value, $args = [], $is_valid = true, $form="")
        {
            if ($form) self::$form_id = $form;
            $multiple = isset($args['multiple']) && $args['multiple'] == true ? 'multiple' : false;
            $atts = isset($args['required']) && $args['required'] == true ? 'required="required"' : '';
            $atts .= $multiple ? ' multiple' : '';
            $name = isset($args['name']) ? $args['name'] : false;
            $name = $name . ( ($type == 'image' && $multiple) ? '[]' : '');
            $is_required = isset($args['required']) && $args['required'] == true ? true : false;
            $label = isset($args['label']) ? $args['label'] : false;
            $placeholder = isset($args['placeholder']) ? $args['placeholder'] : $label;

            if ($is_required) {
                $placeholder = $placeholder . ' *';
            }

            switch ($type) {

                /**
                 * Input types
                 * -----------
                 * button           v rámci některých polí
                 * checkbox         nefunguje ukládání
                 * checkbox_large   nefunguje ukládání
                 * switch           nefunguje ukládání
                 * color            OK
                 * date             OK
                 * datetime-local   OK
                 * email            OK
                 * file
                 * hidden           OK
                 * image            OK upravit upload přes WP upload
                 * month            OK
                 * number           OK
                 * password         OK
                 * radio            OK
                 * radio_large      OK
                 * range            OK
                 * reset
                 * search
                 * select           OK doplnit o js
                 * submit           OK - wordpress
                 * tel              OK
                 * text             OK
                 * textarea         OK
                 * editor           OK
                 * time             OK
                 * url              OK
                 * week             OK
                 * info a warning box    OK
                 * custom html
                 */

                case 'email':
                case 'tel':
                case 'number':
                case 'password':
                case 'text':
                case 'hidden':
                    d1g1GetHtmlForm::get_case_form( $type ,$name, $value, $args , $is_valid, self::$form_id);

                    break;

                case 'select':
                    d1g1GetHtmlForm::get_case_form( $type,$name, $value, $args , $is_valid, self::$form_id);
                    break;

                case 'url':
                    d1g1GetHtmlForm::get_case_form($type,$name, $value, $args , $is_valid, self::$form_id );
                    break;

                case 'image':
                    d1g1GetHtmlForm::get_case_form( $type,$name, $value, $args , $is_valid, self::$form_id );

                    break;

                case 'radio':
                case 'radio_large':
                  d1g1GetHtmlForm::get_case_form( $type,$name, $value, $args, $is_valid, self::$form_id  );
                   
                    break;

                case 'checkbox':
                case 'checkbox_large':
                   d1g1GetHtmlForm::get_case_form( $type,$name, $value, $args , $is_valid, self::$form_id);
                    break;

                case 'switch':
                    d1g1GetHtmlForm::get_case_form( $type,$name, $value, $args , $is_valid, self::$form_id);
                    break;

                case 'textarea':
                    d1g1GetHtmlForm::get_case_form( $type,$name, $value, $args , $is_valid, self::$form_id );
                   
                    break;

                case 'range':
                    d1g1GetHtmlForm::get_case_form( $type,$name, $value, $args , $is_valid, self::$form_id );
                        break;

                case 'editor':
                    d1g1GetHtmlForm::get_case_form( $type,$name, $value, $args , $is_valid, self::$form_id );
                  
                    break;

                case 'date':
                case 'datetime-local':
                case 'time':
                case 'month':
                case 'week':
                    d1g1GetHtmlForm::get_case_form( $type,$name, $value, $args , $is_valid, self::$form_id );

                    break;

                case 'color':
                    d1g1GetHtmlForm::get_case_form( $type,$name, $value, $args , $is_valid, self::$form_id );
                    break;

                case 'info_box':
                case 'warning_box':
                    d1g1GetHtmlForm::get_case_form( $type,$name, $value, $args , $is_valid, self::$form_id );
                    break;

                case 'html':
                    d1g1GetHtmlForm::get_case_form( $type,$name, $value, $args , $is_valid, self::$form_id );
                    break;

                default:
                    # code...
                    break;
            }
        }

        /**
        * 	Získání uložení informace z option
        * 	@param $key = jméno inputu
        * 	@param $form = ID formuláře
        * 
        * 	@author Digihood
        * 	@return true/false
        */
        public static function get_data($key,$form = '') {
            if($form !== ''){
                $id = $form;
            }else{
                $id = (self::$form_id ? self::$form_id : 'default');
            }
            $pref = self::$prefix_form;
            $name = $pref. $id.'_'. $key;  
            $value = get_option($name, false);
            return $value;
        }
        
    }
}
