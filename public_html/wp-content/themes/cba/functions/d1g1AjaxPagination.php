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

/*Ajax pagination function
// https://premium.wpmudev.org/blog/load-posts-ajax/
=========================================================*/
//původní jméno classy ajaxPagination
if( ! class_exists( 'd1g1AjaxPagination' ) )
{
	class d1g1AjaxPagination
	{

		public function __construct()
		{
			
			//ajax registration
            add_action( 'wp_ajax_nopriv_ajax_pagination', [$this,'my_ajax_pagination'] );
            add_action( 'wp_ajax_ajax_pagination', [$this,'my_ajax_pagination'] );

        }
        
        public function my_ajax_pagination() {

            $query_vars = json_decode( stripslashes( filter_input( INPUT_POST, 'query_vars') ), true );        
            $query_vars['paged'] = filter_input( INPUT_POST, 'page');
            $posts = new WP_Query( $query_vars );
            
            $GLOBALS['wp_query'] = $posts;
        
            if( $posts->have_posts() ) { 
                
                while ( $posts->have_posts() ) { 

                    $posts->the_post();       

                    get_template_part( 'parts/repeats/loop', 'post' );       
                    
                }
        
            } 
        
            die();

        }		

	}

}

new d1g1AjaxPagination;