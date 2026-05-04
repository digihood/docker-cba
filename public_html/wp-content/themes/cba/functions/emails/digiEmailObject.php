<?php 
/**
 * Objekt jednotlivého emailu, podle parametrů sestaví objekt a ten pak bude zpracovávat
 *
 * 
 * @author Digihood
 */ 

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

if( ! class_exists( 'digiEmailObject' ) )
{
	class digiEmailObject
	{

        private $title, $message, $subject, $mailto, $order_id, $content, $attach;

		public function __construct($email_id, $order_id="")
		{
            if($order_id)$this->order_id = $order_id;
            $params = digiEmailsCustomEmail::digi_mail_trigger($email_id, $order_id);
            $this->set_object($params);
        }

        /**
        * 	Na základě parametrů sestaví objekt pro práci s emailem
        *
        * 	@param $value = popis value
        * 
        * 	@author Digihood
        * 	@return true/false
        */
        private function set_object($params) {
            if (isset($params['title'])) $this->title = $params['title'];
            if (isset($params['message'])) $this->message = $params['message'];
            if (isset($params['subject'])) $this->subject = $params['subject'];
            if (isset($params['mailto'])) $this->mailto = $params['mailto'];
            if (isset($params['order_id'])) $this->order_id = $params['order_id'];
            if (isset($params['attach'])) $this->attach = $params['attach'];
            if ($this->message && $this->title) $this->content = digiEmailsController::email_content($this->title, $this->message);
        }

        /**
        * 	Vrátí nadpis email
        * 
        * 	@author Digihood
        * 	@return string
        */
        public function get_title() {
            return $this->title;
        }

        /**
        * 	Vrátí předmět email
        * 
        * 	@author Digihood
        * 	@return string
        */
        public function get_subject() {
            return $this->subject;
        }

        /**
        * 	Vrátí předmět email
        * 
        * 	@author Digihood
        * 	@return string
        */
        public function get_message() {
            return $this->message;
        }

        /**
        * 	Vrátí předmět email
        * 
        * 	@author Digihood
        * 	@return string
        */
        public function get_mailto() {
            return $this->mailto;
        }
        
        /**
        * 	Vrátí předmět email
        * 
        * 	@author Digihood
        * 	@return string
        */
        public function get_attachment() {
            $attach = $this->attach;
            return $attach;
        }

        /**
        * 	Vrátí předmět email
        * 
        * 	@author Digihood
        * 	@return string
        */
        public function display_mailto() {
            $mailto = $this->get_mailto();
            if (empty($mailto)) return '';
            if (is_string($mailto)) return $mailto;
            if (is_array($mailto)){
                $return = "";
                foreach ($mailto as $mail) {
                   $return .= $mail . '<br>';
                }
                return $return;
            }
            return null;
        }

        /**
        * 	Zobrazí náhled emailu, včetně popisu informací
        *
        * 	@param $value = popis value
        * 
        * 	@author Digihood
        * 	@return html
        */
        public function preview() {
            echo '<h1>Parametry emailu:</h1>';
            echo '<div>Předmět: '.$this->get_subject().'</div>'.
            '<div>Nadpis: '.$this->get_title().'</div>';
            echo '<div>ID objednávky: '.$this->order_id.'</div>'.
            '<div>Příjemc(e/i): '.$this->display_mailto().'</div>';
            if ($this->attach){
                echo '<div>Přílohy: </div>';
                preprint($this->attach);
            }
            echo '<hr>';
            echo $this->content;
            die();
        }

        /**
        * 	Odešle zadaný email 
        * 
        * 	@author digihood
        * 	@return echo
        */
        public function send() { 
            if ($this->presend_valid()){
                digiEmailsController::send_client_emails($this->mailto, $this->subject, $this->content, $this->attach);
                return true;
            }return false;
            
        }
        /**
        * 	Odešle zadaný email na konkrétní adresu
        * 
        * 	@author digihood
        * 	@return echo
        */
        public function send_to($mailto) { 
            if ($this->presend_valid($mailto)){
                digiEmailsController::send_client_emails($mailto, $this->subject, $this->content, $this->attach);
                return true;
            }return false; 
        }

        /**
        * 	Odešle zadaný email na konkrétní adresu
        * 
        * 	@author digihood
        * 	@return echo
        */
        public function send_admin() { 
            digiEmailsController::send_admin_email($this->subject, $this->content);
            return true;
        }

        private function presend_valid($mailto="") {
            $mailto = $mailto ? $mailto : $this->mailto;
            if ( $mailto && $this->subject && $this->content) return true;
            return false;
        }

    }
}