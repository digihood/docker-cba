<?php
/**
 * The template for displaying single post main content
 *
 * @package WordPress
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<article <?php post_class(); ?> itemscope itemtype="http://schema.org/BlogPosting">

	<?php 
	if ( has_post_thumbnail() ){
		$thumbnail_id = get_post_thumbnail_id();
		d1g1B::img($thumbnail_id, 'full', 'thumbnail');
	} 
	?>

	<div class="entry-meta">
		<time><?php echo the_time("d M Y"); ?></time>
	</div>

	<header class="entry-header">
		<a href="<?php the_permalink(); ?>"><h2 class="entry-title"><?php the_title() ?></h2></a>
	</header>

	<div class="entry-content">
		<?php the_content() ?>
	</div>

	<footer class="article-footer">

		<?php wp_link_pages( array( 'before' => '<div class="page-links">' . esc_html__( 'Stránky:', 'cba' ), 'after'  => '</div>' ) ); ?>
		
		<p class="tags"><?php the_tags('<span class="tags-title">' . __( 'Tagy:', 'cba' ) . '</span> ', ', ', ''); ?></p>	

		<?php get_template_part('parts/structured', 'data'); ?>
	</footer> 

	<?php comments_template(); ?>
	
</article>