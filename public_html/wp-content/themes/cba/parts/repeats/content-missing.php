<?php
/**
 * The template part for displaying a message that posts cannot be found
 */
?>


<div class="post-not-found">
	
	<?php if ( is_search() ) : ?>
		
		<header class="article-header">
			<h1><?php _e( 'Bohužel nic nemůžeme najít.', 'cba' );?></h1>
		</header>
		
		<section class="entry-content">
			<p><?php _e( 'Zkuste požadovanou stránku znovu vyhledat.', 'cba' );?></p>
		</section>
		
		<section class="search">
		    <p><?php get_search_form(); ?></p>
		</section> 
				
	<?php else: ?>
	
		<header class="article-header">
			<h1><?php _e( 'Bohužel stránku nelze najít.', 'cba' ); ?></h1>
		</header>
		
		<section class="entry-content">
			<p><?php _e( 'Zkuste jí vyhledat.', 'cba' ); ?></p>
		</section>
		
		<section class="search">
		    <p><?php get_search_form(); ?></p>
		</section> 
					
	<?php endif; ?>
	
</div>
