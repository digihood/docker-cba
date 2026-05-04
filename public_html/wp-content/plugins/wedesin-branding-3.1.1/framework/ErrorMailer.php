<?php
class ErrorMailer {
    
    public function __construct() {
        add_action( 'init', array( $this, 'init' ) );
    }
    
    public function init() {
 
        set_error_handler( function ( $errno, $errstr, $errfile, $errline ) {
            $this->send_error_email( $errno . ' -- '.$errstr. ' v souboru ' . $errfile . ' na řádku ' . $errline );
        });
    }
    
   
    function send_error_email( $error_message ) {
        $to = 'vas@email.com';
        $subject = 'Chyba na webu';
        $message = 'Vyskytla se chyba na webu: <br>' . $error_message;
        wp_mail( $to, $subject, $message );
    }
}

new ErrorMailer();
