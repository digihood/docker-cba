<?php 
/**
 * The template for displaying page
 *
 * @package WordPress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$imgurl = "";

if ( !is_404() ) {

	$post_thumbnail_id = get_post_thumbnail_id( $post->ID);
	$imgurl = wp_get_attachment_image_src( $post_thumbnail_id, 'page-banner' );

}

if ( !$imgurl ) { 
	$imgurl =wp_get_attachment_image_src( get_option( 'basic_banner_image', 'options' ), 'page-banner' );
}

d1g1B::container(true);

	d1g1B::cell( 12, 12, 12 ); ?>	

	<header class="entry-header" id="page-banner" <?php if ( !empty( $imgurl ) ) { echo 'style="background: url('. $imgurl[0].')"'; } ?>>
		<h1 class="entry-title" itemprop="name headline"><?php 
		if ( is_404() ) {
			echo __('Stránka neexistuje', 'cba' );
		} else if ( is_search() ) {
			echo __('Výsledky vyhledávání', 'cba' );
		} else if ( is_home() ) {
			echo __('Blog', 'cba' );
		} else if ( is_archive() ) {
			the_archive_title();
		} else {
			the_title();
		}	
	?></h1>
	</header>

	<?php get_template_part('parts/breadcrumbs'); 

	d1g1B::end_cell( );
	
d1g1B::end_container();