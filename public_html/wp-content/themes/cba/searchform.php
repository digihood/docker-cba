<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<form method="get" class="searchform flex items-center gap-3" action="<?php echo esc_url( home_url( '/' ) ); ?>" role="search">
	<input type="search" class="field flex-1 border border-gray-mid/40 rounded-full px-5 py-3 text-sm focus:outline-none focus:border-dark/60 transition-colors" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" id="s" placeholder="<?php _e('Hledaný výraz...', 'cba'); ?>" style="font-family:Montserrat,sans-serif;">
	<button type="submit" class="inline-flex items-center justify-center bg-primary text-white rounded-full px-6 py-3 text-sm font-semibold no-underline hover:bg-primary-dark transition-colors" style="font-family:Montserrat,sans-serif;">
		<?php _e('Vyhledat', 'cba'); ?>
	</button>
</form>
