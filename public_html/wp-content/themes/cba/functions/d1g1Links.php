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
    * 	@return string
    */
    public static function login_registration(): string
    {
        $page = get_page_by_path( 'prihlaseni' );
        return $page ? get_permalink( $page->ID ) : home_url( '/prihlaseni/' );
    }

    /**
    * 	Adresa stránky Můj účet
    *
    * 	@author Digihood
    * 	@return string
    */
    public static function my_account(): string
    {
        $page = get_page_by_path( 'muj-ucet' );
        return $page ? get_permalink( $page->ID ) : home_url( '/muj-ucet/' );
    }

    public static function courses(): string {
        return home_url( '/kurzy/' );
    }

    public static function quizzes(): string {
        return home_url( '/kvizy/' );
    }

    public static function lessons(): string {
        return home_url( '/lekce/' );
    }

    public static function register(): string {
        return add_query_arg( 'tab', 'register', self::login_registration() );
    }

	}

}

// Backward-compatible aliases used throughout the theme
if ( ! class_exists( 'linksd1g1' ) ) {
    class_alias( 'd1g1Links', 'linksd1g1' );
}
if ( ! class_exists( 'digiLinks' ) ) {
    class_alias( 'd1g1Links', 'digiLinks' );
}
