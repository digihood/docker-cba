<?php
/**
 * The template for displaying search forms 
 *
 * @package WordPress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<form method="get" class="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>" role="search">
	<input type="text" class="field" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" id="s" placeholder="<?php _e('Hledaný výraz', 'cba'); ?>">
	<input type="submit" class="submit" name="submit" id="searchsubmit" value="<?php _e('Vyhledat', 'cba'); ?>" />
</form>