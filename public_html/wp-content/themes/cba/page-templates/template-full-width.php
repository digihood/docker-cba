<?php
/*
Template Name: Stránka na celou šířku
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header(); 

d1g1B::container(true);

	if (have_posts()) : while (have_posts()) : the_post(); 
		get_template_part( 'parts/repeats/loop', 'page' ); 
	endwhile; endif; 

d1g1B::end_container();

get_footer(); 
