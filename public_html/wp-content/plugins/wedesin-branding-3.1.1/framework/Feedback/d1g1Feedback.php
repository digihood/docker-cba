<?php 
namespace pluginbrandslug\framework\feedback;
use pluginbrandslug\framework\d1g1Session;
use pluginbrandslug\framework\monolog\d1g1MonologFunction;


/**
 * feedback
 *
 * 
 * 
 * 
 * @author Digihood
 */ 

 
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

if( ! class_exists( 'd1g1Feedback' ) ){

	class d1g1Feedback
	{

        private $fields;
        private $session;
      
		public function __construct($field_list = [])
		{
            $this->fields = $field_list;
            if(isset($_GET['tab']) && $_GET['tab'] == 'feedback'){
                add_action( 'd1g1_submit_button_form-'.D1G1_BRANDING, [$this,'button_feedback'], 10, 1 );
            }
            add_action( 'd1g1_form_save_option_hook', [$this,'feedback_send'],10 , 3);

        }

        public function feedback_send($formID , $inputs , $fields){
            if($inputs['plugin_id_d1g1'] === D1G1_BRANDING){
                $this->fields = $fields;
                if($this->fields[0]['save_option'] == 'send_feedback'){
                    
                    $plugin = get_plugin_data(D1G1_BRANDPATH . '/setup.php');
                
                    if(isset($this->fields[0][3]['value']) && $this->fields[0][3]['value']){
                        $replymail = $this->fields[0][3]['value'];
                    }else {
                        $replymail = '';
                    }
                    
                    $result = $this->send_feedback_email($inputs, $formID,$plugin,$replymail);    
                
                    if ($result) {
                        d1g1Session::add_session($formID . '_submit_res', 'true');
                                                                        
                    } else {
                    
                        d1g1Session::add_session($formID . '_submit_res', 'false');
                    }
                    d1g1Session::end_session();
                    exit();
                }
            }
        }
       private function send_feedback_email($inputs, $formID, $plugin , $replymail){
            $plugin_mail = 'plugins@digihood.cz';
            $settingscss = [];
            $content = $this->feedback_content($inputs,$plugin);
            $footer = __('', D1G1_BRANDING);
            $log = new d1g1MonologFunction;
            if ($inputs['feedback_subject']) {
                $subject = $inputs['feedback_subject'];
            }else{
                $subject = __('Feedback na plugin  ' . $plugin['Name'] . '', D1G1_BRANDING);
            }
            
            d1g1Session::start_session();
            $email = new \D1G1SendEmailNew($settingscss,$replymail);
            //zde zprovoznit emaily
            
            $text = $email->email_content('Feedback', array($content),$footer );
           
            //uložení aktualizace
           
           
           
            if ($inputs && $subject && $inputs['feedback_content']) {
                
                $array = [];
                $log->d1g1_add_log(__METHOD__,'INFO', $formID . 'success', $array); 
               
                $email->send_client_emails($plugin_mail, $subject ,$text);
                d1g1Session::remove_session($formID . '_validation');
                $T = d1g1Session::add_session($formID . '_save', 'success');
               
            
            } else {
                $array = [];
                $log->d1g1_add_log(__METHOD__,'ERROR', $formID . ' fail', $array); 
               
                d1g1Session::add_session($formID . '_save', 'fail');
            }
            d1g1Session::end_session();
            
            wp_safe_redirect($inputs['_wp_http_referer']);
            exit;
        }

        public function feedback_content($inputs,$plugin) {
            
            $content = __('Feedback z webu <a href="'.get_home_url().'">'.get_home_url().'</a> na plugin <b>'. $plugin['Name'] . '</b>'  , D1G1_BRANDING).'<br><br>';
            $content .= __('Od uživatele: '. $inputs['username'], 'D1G1BRAND').'<br><br>';
            $content .= __('Uživatelský email: '. $inputs['usermail'], 'D1G1BRAND').'<br><br>';
            if($inputs['userphone']){
                $content .= __('Telefon uživatele: '. $inputs['userphone'], 'D1G1BRAND').'<br><br>';
            }
            $content .= __('Uživatel se registroval: '. $inputs['dateregister'], 'D1G1BRAND').'<br><br>';
            $content .= __('Role uživatele: '. $inputs['userroles'], 'D1G1BRAND').'<br><br>';
            $content .= __('Plugin: '. $plugin['Name'], 'D1G1BRAND').'<br><br>';
            $content .= __('Plugin Version: '. $plugin['Version'], 'D1G1BRAND').'<br><br>';
            if(isset($inputs['licence']) && $inputs['licence']){
                $content .= __('Plugin Licence: '. $inputs['licence'], 'D1G1BRAND').'<br><br>';
            }
           
            $content .= '<h3>'.__('feedback: ', 'D1G1BRAND').'</h3>';
            $content .= $inputs['feedback_content'];
            return $content;

        }

        public static function feedback_session(){
            $sessins_list = [
                'feedback_save' => [
                    'fail' => __("Při odesílaní Zpětné vazby došlo k chybě, vyplnili jste obsah ??", D1G1_BRANDING), 
                    'success' => __("Zpětná vazba odeslána", D1G1_BRANDING)
                ],
            ];
            if(isset($_SESSION['feedback_save']) && $_SESSION['feedback_save'] ){
                d1g1Session::display_notice($sessins_list);
                settings_errors();
                d1g1Session::remove_session('feedback_save');
                
             }  
        }

        public function button_feedback($formID){
          
            $button_text = 'Odeslat zpětnou vazbu';
          return  submit_button($button_text) ;
        
    }

    }

 new d1g1Feedback;
}