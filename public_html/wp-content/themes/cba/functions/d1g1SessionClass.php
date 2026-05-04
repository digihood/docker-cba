<?php 
/**
 * class description
 *
 * 
 * @author Digihood
 */ 

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

if( ! class_exists( 'd1g1SessionClass' ) )
{
	class d1g1SessionClass
	{

		public function __construct()
		{

        }
        /**
         * Otevření všech session
         * 
         * @author Digihood
         * @return true/false
         */ 
        public static function start_session( ) 
        {
            if(!session_id()) {
                session_start();
            }
        }

        /**
         * Smazání všech session
         *
         * 
         * @author Digihood
         * @return true/false
         */ 
        public static function end_session() 
        {
            if(session_id()) {
                session_destroy ();
            }

        }


        /**
         * Vytvoření session
         *
         * @param $name = jméno session
         * @param $value = hodnota
         * 
         * @author Digihood
         * @return true/false
         */ 
        public static function add_session( $name, $value="" ){
            $_SESSION[$name] = $value;
            return true;
        }

        /**
         * Kontrola session
         *
         * @param $name = jméno session
         * @param $value = hodnota
         * 
         * @author Digihood
         * @return true/false
         */ 
        public static function check_session( $name, $value="" ) {
            if (!isset($_SESSION[$name])) return false;
            if ( $value !== "" ) 
            {
                if ( $_SESSION[$name] == $value ) 
                {
                    return true;
                }                    

            } else {
                if ( $_SESSION[$name] == 'error' || !empty($_SESSION[$name]) ) 
                {
                    return true;

                } else {
                    
                }
            }
        }

        /**
         * Vrátí hodnotu session
         *
         * @param $name = jméno session
         * @param $value = hodnota
         * 
         * @author Digihood
         * @return true/false
         */ 
        public static function get_session( $name ) 
        {
            
            if ( isset( $_SESSION[$name] ) ) 
            {
                if (!empty($_SESSION[$name]) ) 
                {
                    return $_SESSION[$name];

                } 
                else 
                {
                    return false;     
                }
            }

            return false;

        }
	}
}