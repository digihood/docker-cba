<?php 

if ( ! defined( 'ABSPATH' ) ) {

  exit;

}

if( ! class_exists( 'sendEmailContentd1g1' ) )
 
{

	class sendEmailContentd1g1
	{
		
		public function __construct(){
			// content param
			// $title, 
			// $subtitle="", 
			// $body="",
			// $footer="", 
			// $button_link = "", 
			// $button_text = ""
		}

		/*============================================

			Email - new registration (contact seller) 
			
			HOTOVO

		============================================*/
		public function email_registration( $mail, $user, $activation_url ) {
			$subject = __('Potvrzení registrace - Žijte ve své zahradě', 'cba');
			$message =  sendEmaild1g1::email_content(__('Potvrzení registrace', 'cba'),  __('Vítejte!', 'cba') ,array(__('Děkujeme za registraci na našem webu. Pro přihlášení klikněte na tlačítko Přihlásit se.', 'cba'), '<br>' ),
				__('PODPIS', 'cba'), $activation_url, __('Přihlásit se', 'cba')  
			);
			sendEmaild1g1::send_client_emails( $mail, $subject, $message );
			
			return true;
		}

		/*============================================

			Email - forgotten password
			
			HOTOVO
			email_content( $title, $title_mail, $subtitle, $subtitle_gray="", $content_title, $body, $footer, $signature="false", $button_link = "", $button_text = "", $top_image_url="")

		============================================*/
		public function forgotten_password( $mail, $link ) {
			$subject = __('Obnovení hesla', 'cba') . " - Žijte ve své zahradě";

			$message =  sendEmaild1g1::email_content(__('Zapomenuté heslo', 'cba'),  __('Obnovení hesla', 'cba'),
				array(  __('Dobrý den,', 'cba'),_('požádali jste o obnovení přístupu do kurzu Žijte ve své zahradě. Heslo obnovíte kliknutím na tlačítko "Obnovit přistup", nebo můžete do svého prohlížeče zkopírovat následující odkaz:', 'cba'). '<br>'. $link,  __('Po přihlášení si můžete heslo změnit v nastavení svého účtu.', 'cba'). '<br>' ),
				__('PODPIS', 'cba'), $link, __('Obnovit přístup', 'cba')  
			);
			/*echo $message;
			die();*/
			sendEmaild1g1::send_client_emails( $mail, $subject, $message );
			return true;
		}

	}
	
}
new sendEmailContentd1g1;
