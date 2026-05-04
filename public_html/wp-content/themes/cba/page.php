<?php 
/**
 * Šablona pro zobrazení běžných stránek
 */

get_header(); 

get_template_part('parts/page', 'banner' );

d1g1B::container(true);

	d1g1B::cell( 8, 8, 12 );

	if (have_posts()) : while (have_posts()) : the_post(); 
	
		get_template_part( 'parts/repeats/loop', 'page' ); 

	endwhile; endif; 

	d1g1B::end_cell( );
	
	get_sidebar(); 

d1g1B::end_container();

get_footer(); 