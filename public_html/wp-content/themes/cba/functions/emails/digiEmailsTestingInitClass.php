<?php 
/**
 * Testování emailů s háčkem
 *
 * 
 * @author Digihood
 */ 

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

if( ! class_exists( 'emailTestingInitClass' ) )
{
	class emailTestingInitClass
	{

        //private $variable; 

		public function __construct()
		{
      add_action('admin_init', [$this, 'handle_testing']);
    }
    public function handle_testing() {
      if ( !isset($_GET['testemail']) || $_GET['testemail']!=1 ) return;
      $order_id = 807;
      $email_id = 'new_order_admin';
      $emailObject = new digiEmailObject($email_id, $order_id);
      $emailObject->send_to('admin@digihood.cz');
      $emailObject->preview();
      die();
    }
  }
  new emailTestingInitClass;
}