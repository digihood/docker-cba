<?php 
/**
 * registrace vlastních sloupců pro konkrétní 
 *
 * 
 * @author Digihood
 */ 

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

if( ! class_exists( 'd1g1CustomColumns' ) )
{
	class d1g1CustomColumns
	{

		public function __construct()
		{
			
			//register
            add_action( 'manage_page_posts_custom_column', [$this,'add_custom_coupon_column'] );
            add_filter( 'manage_edit-page_columns', [$this, 'page_custom_column'] );

		}

        //add custom columns to coupons
        function page_custom_column( $columns ) {
            $columns['page_template'] = __('Šablona', 'cba');
            return $columns;
        }

        //addd content to custom column
        function add_custom_coupon_column( $column ) {
        
            global $post;
            if ( 'page_template' === $column ) {

                $template = get_page_template_slug( $post );

                if ( $template ) {
                    echo $template;
                } else {
                    echo '---';
                }
                    
            }

        }	

	}

}

if( is_admin() )
	new d1g1CustomColumns;