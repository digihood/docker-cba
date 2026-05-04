<?php
/**
 * The template for displaying single post
 *
 * @package WordPress
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if (  $wp_query->max_num_pages > 1 ) { ?>

<div class="pagination">		
<?php
	global $wp_query;

	$numpages = $wp_query->max_num_pages;

	echo paginate_links( array(
		'base' => get_pagenum_link(1) . '%_%',
		'format' => 'page/%#%',
		'current' => max( 1, get_query_var('paged') ),
		'total' => $numpages,
		'prev_text' => '<',
		'next_text' => '>'
	) );
?>
</div>
<?php } ?>