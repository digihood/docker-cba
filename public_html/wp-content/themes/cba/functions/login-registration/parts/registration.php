<?php

if ( !class_exists('RegistrationFormWed') ) {

    class RegistrationFormWed {

        public function __construct () {

            //add_action( 'wp_login_failed', [$this,'frontend_login_fail'] );
            add_action( 'init', [$this,'account_verification'] );
            add_action( 'init', [$this, 'complete_name_filling_form'] );
            //form shortcode
            add_shortcode( 'registration', [ $this, 'registration_form' ] );

        }

        /* Create registration tab
        =============================================*/
        public function registration_form(){

            if ( d1g1SessionClass::check_session( 'user_registration', 'success' ) ) {
				d1g1Build::message( __('Děkujeme za Vaši registraci, potvrďte svou registraci kliknutím na odkaz zaslaný na Váš e-mail', 'cba'), 'success' );
            } else if ( d1g1SessionClass::check_session( 'user_registration', 'empty_email' ) ) {
                d1g1Build::message( __('Vyplňte prosím email.', 'cba'), 'alert' );
            } else if ( d1g1SessionClass::check_session( 'user_registration', 'password_short' ) ) {
                d1g1Build::message( __('Heslo musí mít alespoň 8 znaků', 'cba'), 'alert' );
            } else if ( d1g1SessionClass::check_session( 'user_registration', 'password_empty' ) ) {
                d1g1Build::message( __('Vyplňte prosím heslo.', 'cba'), 'alert' );
            } else if ( d1g1SessionClass::check_session( 'user_registration', 'email_wrong' ) ) {
                d1g1Build::message( __('Zkontrolujte vyplněný email', 'cba'), 'alert' );
            } else if ( d1g1SessionClass::check_session( 'user_registration', 'email_known' ) ) {
                d1g1Build::message( __('Tento email již známe. Přihlašte se prosím na záložce "Přihlášení".', 'cba'), 'alert' );
            }   
            
        ?>
            <div id="registration-form">

                <form action="<?php echo get_permalink(); ?>" method="post">

                    <div class="hide">
                        <input type="text" name="email" value="">
                    </div>

                    <p><?=__('Zadejte prosím váš přihlašovací email a heslo. Do emailové schránky vám přijde email, pro potvrzení vašeho účtu', 'cba'); ?>
                    <?php 
                    wp_nonce_field( 'reg_nonce_action_1', 'register_nonce_1' );        
                    $email = d1g1Cookies::get_cookie('_registration_email');         
                    ?>
                    <p>
                        <label><?php _e('Váš email', 'cba' ); ?> <span class="blue-text">*</span></label> 
                        <input type="text" name="our_email" value="<?=$email?>" required>
                        <span class="form-error" data-form-error-on="pattern">
                            <?php _e('Email není platný', 'cba' );?>
                        </span>
                    </p> 
                    <p>
                        <label for="password"><?php _e('Vaše heslo (minimální délka 8 znaků)', 'cba'); ?> <span class="blue-text">*</span></a></label> 
                        <input type="password" name="password" value="" id="password1" autocomplete="off" required>
                        <span class="form-error" data-form-error-on="required">
                            <?php _e('Toto pole je vyžadované', 'cba' );?>
                        </span>
                    </p>
                    <p>
                        <fieldset class="col-span-12 lg:col-span-6 wrapper-checkbox">
                            <input type="checkbox" name="pokemon" value="Red" id="pokemonRed" required><label for="pokemonRed"><?php _e('Souhlasím se správou osobních údajů', 'cba'); ?></label>
                        </fieldset>
                    </p>
                    <input type="submit" class="button" value="<?php _e('Registrovat', 'cba');?>">
                </form>

            </div>    

        <?php }

        /**
         * Začne
         *
         * @param none
         * 
         * @author Digihood
         * @return true/false
         */ 
        public function complete_name_filling_form() {   

            $query = filter_input_array(INPUT_POST);     

            if ( !empty($query) ) {

                if( isset($query['register_nonce_1']) && wp_verify_nonce( $query['register_nonce_1'], 'reg_nonce_action_1' ) ) {


                    //email validation
                    if ( !empty( $query['email'] ) ) {
                        return false;
                    } 
                    //vytvoření uživatelského jména
                    $email = sanitize_text_field( $query['our_email'] );
                    $password = sanitize_text_field( $query['password'] );
                    $username = $this->concentate_username( $email );
                    
                    //set cookies
                    d1g1Cookies::set_cookie( [
                        'name' => '_registration_email',
                        'expire' => CL,
                        'value' => $email
                    ] );

                    //validate fields
                    if ( $this->registration_step_one_validation( $username, $email, $password ) )  { 
                        //success redirect
                        $this->complete_registration_form($username, $email, $password );
                    }

                }  
                
            }

            return false;

        }

        /* Function to complete registration form
        =================================================*/
        public function complete_registration_form($username, $reg_mail, $reg_pass) {       

            $userdata = array(
                'user_login' => $username,
                'user_email' => $reg_mail,
                'user_pass'  => $reg_pass,
                'role' => 'unconfirmed_client'
            );
                
            $user = wp_insert_user( $userdata );

            if ( !is_wp_error( $user ) ){

                //update user metadata
                update_user_meta( $user, 'terms-conditions', 1 );

                /* Send confirmation email
                ==================================================*/
                $user_info = get_userdata($user);

                if (  $user_info->user_email && $user ) {
                    $to = $reg_mail;
                    $activation_url = wp_nonce_url( home_url() . '/?request_account_verification=1&user=' . $user . '', 'account_verification_action', 'account_verification_name');
                    $class_email = new sendEmailContentd1g1();
                    $class_email->email_registration($to, $user, $activation_url);

                    //set message data
                    
                    d1g1SessionClass::add_session( 'user_registration', 'success' );

                    //remove cookies
                    d1g1Cookies::delete_cookie('_registration_email');

                    //redirect
                    redirect_back("#register");
                } 

            } 
        }           

        //validate step one of the registration process
        public function registration_step_one_validation( $username, $email, $password )  {

            if ( empty( $email ) ) {
                            
                d1g1SessionClass::add_session( 'user_registration', 'empty_email' );
                redirect_back("#register");
                    
            }

            //password validation
            if ( !empty( $password ) ) { 
            
                if ( 7 >= strlen( $password ) ) {

                    d1g1SessionClass::add_session( 'user_registration', 'password_short' );
                    redirect_back("#register");
                } 

            } else {

                d1g1SessionClass::add_session( 'user_registration', 'password_empty' );
                redirect_back("#register");
            }

            if ( !is_email( $email ) ) {

                d1g1SessionClass::add_session( 'user_registration', 'email_wrong' );
                redirect_back("#register");

            }

            if ( email_exists( $email ) ) {

                d1g1SessionClass::add_session( 'user_registration', 'email_known' );
                redirect_back("#register");

            }

            return true;
        }

        
        /* Return new usermane
        =====================================================*/
        private function concentate_username( $email ) {
        global $wpdb;
        $conformity = 1;
        $user_name = strstr($email, '@', true); // As of PHP 5.3.0
            if ($this->check_exist_user_name($user_name) == true) {

                while($this->check_exist_user_name( $user_name . $conformity) == true) {
                    $conformity++;
                }
                $user_login = $user_name . $conformity;
            } else {
                $user_login = $user_name;
            }

            return $user_login;
        }
        /* Return if usermane exist
        =====================================================*/
        function check_exist_user_name($user_name){
            $users = get_users(array( 'fields' => array( 'display_name' ) ) );
            $conformity = 0;
            foreach ( $users as $user ) {
                if ($user_name == $user->display_name ) $conformity++;
            }
            if ( $conformity > 0) {
                return true;
            } else {
                return false;
            }
        }

        /*
        * rerify the user when he registers
        =================================*/
        public function account_verification(){

            $filter = filter_input_array(INPUT_GET);

            if ( !empty( $filter ) ) {

                //check nonce
                if (!isset($filter['my_nonce']) || !wp_verify_nonce($filter['account_verification_name'], 'account_verification_action') ) {

                    if ( isset( $filter['request_account_verification'] ) && $filter['request_account_verification'] == 1 && !empty( $filter['user'] ) ) {

                        
                        $reg = new custom_login_form_wed;

                        $user_meta=get_userdata( $filter['user'] );
                        $user_roles=$user_meta->roles;
                        $role = 'client';
                        

                        //pokud už účet je verifikovaný
                        if ( is_array( $user_roles ) && in_array( $role,  $user_roles  )  ) {

                            //set session for no update
                            d1g1SessionClass::add_session( 'user_confirmation', 'no-update' );

                        } else {

                            //get user role                            
                            $user_id = wp_update_user( array( 'ID' => $filter['user'], 'role' =>  $role ) );

                            //there is an error
                            if ( is_wp_error( $user_id ) ) {

                                //set session for no update
                                d1g1SessionClass::add_session( 'user_confirmation', 'error' );
                                
                            //all si well
                            } else {
                                d1g1SessionClass::add_session( 'user_confirmation', 'success' );              
                            }

                        }

                        //redirect to login page
                        wp_redirect( linksd1g1::login_registration() );
                        exit; 

                    }

                } 
                
                return false;  
            }

        }

    }

    $RegistrationFormWed = new RegistrationFormWed();

}