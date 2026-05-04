<?php 
/**
 * Šablona pro zobrazení domácí stránky
 */

get_header(); 

get_template_part('parts/page', 'banner' );

d1g1B::container(true);

	d1g1B::cell( 12, 12, 12 );

		if (have_posts()) : while (have_posts()) : the_post(); 

			get_template_part( 'parts/repeats/loop', 'page' ); 

		endwhile; endif; 
		
		echo d1g1TypographyDemo::demo(); 

	d1g1B::end_cell( );

d1g1B::end_container();

get_footer(); 