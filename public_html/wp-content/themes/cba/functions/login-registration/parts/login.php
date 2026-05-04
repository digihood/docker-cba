<?php

if ( !class_exists('LoginFormWed') ) {

    class LoginFormWed {

        public function __construct () {

            add_action( 'wp_login_failed', [$this,'frontend_login_fail'] );
			add_action( 'login_form_middle', [$this,'add_lost_password_link']  );
			add_filter( 'authenticate', [$this,'authenticate_username_password'], 0, 3);
		}
		
		/**
		 * Handle error 
		 *
		 * @param none
		 * 
		 * @author Digihood
		 * @return true/false
		 */
		function authenticate_username_password( $user, $username, $password )
		{
			if ( is_a($user, 'WP_User') ) { 
				return $user; 
			}

			$query = filter_input_array( INPUT_POST );

			if ( $query && empty( $query['testcookie'] ) && (empty($username) || empty($password )) )
			{				

				//set session
				d1g1SessionClass::add_session( 'login_error', 'empty_password' );

				//redirect back
				redirect_back();

			}
		}

        /* Handle login errors
        ==================================================*/
        public function frontend_login_fail( $username ) {

        	if ( isset($_SERVER["HTTP_REFERER"]) )
				$referrer = $_SERVER["HTTP_REFERER"];
				   
           	if ( !empty($referrer) && !strstr($referrer,'wp-login') && !strstr($referrer,'wp-admin') ) {

				//set session
				d1g1SessionClass::add_session( 'login_error', true );

				//redirect
              	wp_redirect( $referrer );
              	exit;
           }

        }

        /* Forgotten password link
		==============================================================*/
   
	    function add_lost_password_link() {
	        return '<a href="'. get_permalink() .'#forgotten-password" class="forgotten-link">'.__('Zapoměli jste heslo?', 'cba') . '</a>';
	    }
        
        /*Login form tab
        =====================================================*/
		public function login_tab()
		
		{ 

			global $post;
			  
			//chyba přihlášení se zapomenutým heslem
			if ( d1g1SessionClass::check_session( 'login_error', 'success' ) ) 
			{	
				d1g1Build::message( __('Přihlášení se nezdařilo. Pokud jste zapomněli své přihlašovací údaje, můžete si v záložce 
				"Zapomenuté heslo" obnovit své přihlašovací údaje.', 'cba'), 'alert' );
			} else if ( d1g1SessionClass::check_session( 'login_error', 'empty_password' ) ) {
				d1g1Build::message( __('Zadejte prosím email a heslo.', 'cba'), 'alert' );
			//validace email, můžete se přihlásit
			} else if ( d1g1SessionClass::check_session( 'user_confirmation', 'success' ) ) {
				d1g1Build::message( __('Váš účet byl úspěšně verifikován. Můžete se přihlásit.', 'cba'), 'success' );
			} else if ( d1g1SessionClass::check_session( 'user_confirmation', 'no-update' ) ) {
				d1g1Build::message( __('Email byl již dříve verifikován. Můžete se přihlásit.', 'cba'), 'alert' );
			} else if ( d1g1SessionClass::check_session( 'user_confirmation', 'error' ) ) {
				d1g1Build::message( __('Při validaci došlo k chybě, prosím kontaktujte technickou podporu.', 'cba'), 'alert' );
			}			

			wp_login_form( array(
				'echo'           => true,
				'remember'       => true,
				'redirect'       => $this->get_user_redirect_cookie( $post ),
				'form_id'        => 'loginform',
				'id_username'    => 'user_log',
				'id_password'    => 'user_pass',
				'id_remember'    => 'rememberme',
				'id_submit'      => 'wp-submit',
				'label_username' => __( 'Váš email', 'cba' ),
				'label_password' => __( 'Heslo', 'cba' ),
				'label_remember' => __( 'Zapamatovat si mě', 'cba' ),
				'label_log_in'   => __( 'Přihlásit se', 'cba' ),
				'value_username' => '',
				'value_remember' => false
			) ); 

		}

		/* Get redirect cookie
		=========================================================*/
		function get_user_redirect_cookie( $post )
		{

		    if ( isset( $_COOKIE['first-login'] ) ) { 
		    	$redirect_url = home_url() . '?login=true';
		    } else {  
				$redirect_url = linksd1g1::my_account(); 
			}

		    return $redirect_url;

		}

    }

    $LoginFormWed = new LoginFormWed();

}