<?php
/**
 * The template for displaying 404 (page not found) pages.
 *
 * For more info: https://codex.wordpress.org/Creating_an_Error_404_Page
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header(); 

get_template_part('parts/page', 'banner' ); 

d1g1B::container(true);

	d1g1B::cell( 12, 12, 12 );

	if (have_posts()) : while (have_posts()) : the_post(); 
	
		?>
		<article class="content-not-found">
			
			<div class="entry-content">
				<p><?php _e( 'Hledaná stránka neexistuje. Zkuste jí vyhledat níže!', 'cba' ); ?></p>
				<p><?php get_search_form(); ?></p>
			</div>

		</article> 
		<?php 

	endwhile; endif; 

	d1g1B::end_cell( );
	
d1g1B::end_container();

get_footer(); 