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

if( ! class_exists( 'd1g1Links' ) )
{
	class d1g1Links
	{

		public function __construct()
		{
			
		}

    /**
    * 	Account
    * 
    * 	@author Digihood
    * 	@return echo
    */
    public static function gdpr( ) {
        return get_permalink( 3 );
    }  

    /**
    * 	Adresa přihlášení a registrace
    *   
    * 	@author Digihood
    * 	@return echo
    */
    public static function login_registration( ) 
    {
        return get_permalink( );
    }    

    
    /**
    * 	Adresa přihlášení a registrace
    *   
    * 	@author Digihood
    * 	@return echo
    */
    public static function my_account( ) 
    {
        return get_permalink( );
    }   

        
	}

}
