<?php 
namespace pluginbrandslug\framework\Forms;
use pluginbrandslug\framework\Forms\d1g1FormSave;
use pluginbrandslug\framework\Forms\d1g1FormsBuilderFields;
use pluginbrandslug\framework\d1g1Session;

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

if( ! class_exists( 'd1g1FormsHandle' ) )
{
    /**
     * Classes
     *
     * 
     * @author Digihood
     */
    class d1g1FormsHandle {


        function __construct() {
   
                add_action( 'admin_init', [$this, 'save_form'] );
            add_action('save_post', [$this, 'save_cpt_meta']);
            if ((isset($inputs['form_id_d1g1']) && !empty($inputs['form_id_d1g1']) && !empty($inputs['plugin_id_d1g1']) && $inputs['plugin_id_d1g1'] == D1G1_BRANDING ) || isset($_GET['page']) && $_GET['page'] == D1G1_BRANDING)
            d1g1Session::start_session();

        }
        // akce spouštějící uložení formuláře
        public function save_form() {	

			$inputs = filter_input_array(INPUT_POST);
            if (isset($inputs['form_id_d1g1']) && !empty($inputs['form_id_d1g1']) && !empty($inputs['plugin_id_d1g1']) && $inputs['plugin_id_d1g1'] == D1G1_BRANDING ) {
               
               
				return d1g1FormSave::save_form();
            }
		}

        /**
         * Ukládání polí do meta v cpt
         *
         * 
         * @author digihood
         * @return true/false
         */ 
        public function save_cpt_meta(){
            global $post;
            if ($post && isset($post->post_type) && !empty($post->post_type)){
                $all_fields = d1g1FormsBuilderFields::get_fields_cpt_form($post->post_type);
                if (!empty($all_fields)) {
                    foreach ($all_fields as $field) {
                        $name = $field['name'];
                        if ($field && isset($name) && isset($post->ID) && isset($_POST[$name])) {
                            update_post_meta($post->ID, $name, $_POST[$name]);
                        }
                    }
                }

            }
        }

    }
    new d1g1FormsHandle;
}