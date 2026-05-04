<?php
/**
 * The template for displaying the footer. 
 *
 * Comtains closing divs for header.php.
 *
 * For more info: https://developer.wordpress.org/themes/basics/template-files/#template-partials
 */		

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

?>
				
		<footer id="footerwrap" class="footer" itemscope itemtype="http://schema.org/WPFooter">
				
			<?php get_template_part( 'parts/theme/sub-footer' ); ?>		

			<?php get_template_part( 'parts/theme/colophon' ); ?>	
							
		</footer>

	</main>

	<?php get_template_part('parts/theme/mobile-menu'); ?>

	<?php wp_footer(); ?>
	
	</body>
	
</html>