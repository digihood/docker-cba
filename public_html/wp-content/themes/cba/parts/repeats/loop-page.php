<?php
/**
 * Template part for displaying page content in page.php
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<article <?php post_class(''); ?> itemscope itemprop="mainContentOfPage">
							
    <section class="entry-content" itemprop="articleBody">
		<?php the_content(); ?>
	</section> 
	
</article> 