<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 */

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

global $paged;
global $wp_query;
if ( $paged == 0 || !$paged ) $paged = 1;

get_header(); 

get_template_part('parts/page', 'banner' );

d1g1B::container(true); 

	d1g1B::cell( 8, 8, 12 );

    if (have_posts()) { 
		
		while (have_posts()) { 
			
			the_post(); 
	
			get_template_part( 'parts/repeats/loop', 'post' ); 
		
		} 
	
		if ( $wp_query->max_num_pages > 1 ) { ?>
			<div id="posts">

			</div>
			<div class="cell text-center section" id="button-wrap">
				<button class="button primary no-margin" id="load-more" data-page="<?php echo $paged; ?>" data-max="<?php echo $wp_query->max_num_pages; ?>">
					<?= __('Další příspěvky', 'cba'); ?>
				</button>
			</div>

		<?php } 
		
	} else { 
	
		get_template_part( 'parts/repeats/content', 'missing' ); 
		
	}	
	
	d1g1B::end_cell( );
	
	get_sidebar(); 

d1g1B::end_container();

get_footer();
