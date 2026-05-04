<?php 
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$fb = get_field( 'url_facebook_tip', 'options' );
$tw = get_field( 'url_twitter_tip', 'options' );
$in = get_field( 'url_instagram_tip', 'options' );


?>
<ul class="social-list menu">
	<?php if ( $fb ) : ?>
	<li>
		<a href="<?php echo $fb; ?>" target="_blank" title="<?php _e( 'Navštivte náš Facebook', 'cba' );?>">
			<?php d1g1B::icon('facebook-letter-logo'); ?>
		</a>
	</li>
	<?php endif; ?>
	<?php if ( $tw ) : ?>
	<li>
		<a href="<?php echo $tw; ?>" target="_blank" title="<?php _e( 'Navštivte náš Twitter', 'cba' );?>">
			<?php d1g1B::icon('twitter'); ?>
		</a>
	</li>
	<?php endif; ?>
	<?php if ( $in ) : ?>
	<li>
		<a href="<?php echo $in; ?>" target="_blank" title="<?php _e( 'Navštivte náš Instagram', 'cba' );?>">
			<?php d1g1B::icon('instagram'); ?>
		</a>
	</li>
	<?php endif; ?>
</ul>