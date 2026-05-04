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

					<?php get_template_part( 'parts/social', 'list'); ?>

				</div>

			</div>

		</div>

	</div>

</header>

<?php if ( has_nav_menu( 'primary' ) ) : ?>
<nav class="site-nav" id="site-nav" aria-label="<?php esc_attr_e( 'Hlavní navigace', 'cba' ); ?>">
	<div class="site-nav__inner">
		<?php
		wp_nav_menu( array(
			'theme_location' => 'primary',
			'container'      => false,
			'menu_class'     => 'medium-horizontal menu',
			'items_wrap'     => '<ul id="site-primary-menu" class="%2$s">%3$s</ul>',
			'depth'          => 3,
			'fallback_cb'    => false,
		) );
		?>
	</div>
</nav>
<?php endif; ?>
