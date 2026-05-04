<?php
/**
 * The template for displaying the header
 *
 * This is the template that displays all of the <head> section
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}
?>
<!doctype html>

<html <?php language_attributes(); ?> class="no-js">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<meta name="web_author" content="Digihood.cz">
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">
	<?php wp_head(); ?>
</head>			
<body <?php body_class( body_class_d1g1() ) ?> <?= schema_org_d1g1() ?>>
<?php do_action( 'wp_body_open' ); ?>

<?php get_template_part( 'parts/theme/header-content' ) ?>

<main id="panel" class="panel slideout-panel slideout-panel-right overflow-x-hidden" itemscope itemprop="mainEntityOfPage">