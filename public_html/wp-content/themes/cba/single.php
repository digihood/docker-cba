<?php 
/**
 * The template for displaying all single posts and attachments
 */

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

get_header(); 

get_template_part('parts/page', 'banner' );

d1g1B::container(true);

	d1g1B::cell( 8, 8, 12 );

	if (have_posts()) : while (have_posts()) : the_post(); 
	
		get_template_part( 'parts/post', 'content' ); 

	endwhile; endif; 

	d1g1B::end_cell( );
	
	get_sidebar(); 

d1g1B::end_container();

get_footer(); 