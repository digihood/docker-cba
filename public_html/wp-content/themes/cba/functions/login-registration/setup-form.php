<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

define( '__FORMPATH__', '/functions/login-registration' );

if( ! class_exists( 'custom_login_form_wed' ) )
{
	
	//include parts
	include_once( __DIR__ . '/parts/login.php' );
	include_once( __DIR__ . '/parts/registration.php' );
	include_once( __DIR__ . '/parts/forgotten-password.php' );

	class custom_login_form_wed
	{
		public function __construct()
		{

			add_shortcode( 'login-form', array( $this, 'login_form_html' ) );
		}

		/**
		 * Vrací html přihlačovací stránky
		 *
		 * @param none
		 * 
		 * @author Digihood
		 * @return html
		 */ 
		public function login_form_html(){ ?>

			<ul class="tabs" data-tabs id="reg-tabs" data-deep-link="true" data-update-history="true" data-deep-link-smudge="true" data-deep-link-smudge-delay="500">
				<li class="tabs-title is-active">
					<a href="#login" aria-selected="true"><?php _e( 'Přihlášení' );?></a>
				</li>
				<li class="tabs-title">
					<a data-tabs-target="register" href="#register"><?php _e( 'Registrace', 'cba' );?></a>
				</li>
				<li class="tabs-title">
					<a data-tabs-target="forgotten" href="#forgotten"><?php _e( 'Zapomenuté heslo', 'cba' );?></a>
				</li>
			</ul>

			<div class="tabs-content" data-tabs-content="reg-tabs" data-tabs-content="deeplinked-tabs">
				<div class="tabs-panel is-active" id="login">
					<?php 

					$LoginFormWed = new LoginFormWed();
					echo $LoginFormWed->login_tab(); 

					?>
				</div>
				<div class="tabs-panel" id="register">
					<?php 
					$RegistrationFormWed = new RegistrationFormWed();
					echo $RegistrationFormWed->registration_form();
					?>
				</div>
				<div class="tabs-panel" id="forgotten">
					<?php 
					$Forgotten = new ForgottenPasswordFormWed();
					echo $Forgotten->forgotten_tab();
					?>
				</div>
			</div>

		<?php }
				
	}

	new custom_login_form_wed;
}

?>