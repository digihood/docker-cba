<?php 

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
$url = get_home_url();
$width = 300;
$height = 100;

?>
<header id="header-content" itemscope itemtype="http://schema.org/WPHeader">
		
	<div class="container">
		
		<div class="grid grid-cols-12 gap-x-theme">

			<div class="md:col-span-3 col-span-12">
				<a href="<?= home_url() ?>">
					<?= d1g1B::logo($url, $width, $height); ?>
				</a>				
			</div>
			
			<div class="md:col-span-9 col-span-12">
				
				<div class="show-for-medium">

					<?php 
					
					do_action( 'd1g1_menu_top' ); 

					get_template_part( 'parts/social', 'list'); 
			
					?>

				</div>
				
				
			</div>

		</div>

	</div>

</header>
