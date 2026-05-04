<?php
/**
 * Template part for displaying posts
 *
 * Used for single, index, archive, search.
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(''); ?>>					
	
	<header class="article-header">
		<h2><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
	</header> 
					
	<section class="entry-content" itemprop="articleBody">
		<?php if ( has_post_thumbnail( ) ) { ?>
			<a href="<?php the_permalink() ?>">
				<?php d1g1B::img( get_post_thumbnail_id( ), 'medium' )  ?>
			</a>
		<?php } 
		
		the_excerpt(); 
		
		?>
	</section>
						
	<footer class="article-footer">
    	<p class="tags"><?php the_tags('<span class="tags-title">' . __('Tags:', 'cba') . '</span> ', ', ', ''); ?></p>
	</footer> 
				    						
</article> 