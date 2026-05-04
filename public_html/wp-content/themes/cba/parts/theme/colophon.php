<?php 

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

d1g1B::container(true);

	d1g1B::cell( 4, 4, 12 ); 

        bloginfo('name'); echo ' ' . __('Copyright', 'cba'). ' ' . date('Y');

    d1g1B::end_cell( );   
    
    d1g1B::cell( 4, 4, 12 ); 

        do_action( 'd1g1_menu_footer' );

    d1g1B::end_cell( );   

    d1g1B::cell( 4, 4, 12 ); 

        echo 
            '<a href="https://www.digihood.cz/" rel="noreferrer noopener" target="_blank" title="' . __('Profesionální webdesign na míru', 'cba') . '"> ' . 
                 __('Webdesign Digihood','cba' ) . 
            '</a>';

    d1g1B::end_cell( ); 
	
d1g1B::end_container();