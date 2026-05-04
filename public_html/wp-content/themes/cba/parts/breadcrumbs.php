<?php 

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

//function to display breadcrumbs on whole site
if ( function_exists('yoast_breadcrumb') ) { ?>

	<div id="breadcrumbs-wrap" itemprop="breadcrumb">

		<?php  yoast_breadcrumb('<nav id="breadcrumbs">','</nav>'); ?>

	</div>

<?php } ?>
