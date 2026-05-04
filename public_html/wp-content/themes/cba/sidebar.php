<?php 
/**
 * The sidebar containing the main widget area
 */

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}
?>
<aside class="sidebar col-span-8 md:col-span-3" itemscope itemtype="http://schema.org/WPSideBar">

	<?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('Sidebar Widgets')) : ?>
       		
  <?php endif; ?> 
  
</aside>