<?php

/**
* Plugin Name: Digihood HTML Sitemap
* Plugin URI: https://digihood.co.uk/plugins/html-sitemap/
* Description: Digihood HTML Sitemap is simple plugin for creating simple HTML sitemaps (entire web, post-types, terms/posts under taxonomies, attachments ...) using shortcodes. Documentation included in wp-admin plugin settings.
* Version: 3.1.1
* Author: digihood
* Author URI: https://digihood.co.uk/
* Requires at least: 3.5
* Tested up to: 6.5.5
* Text Domain: digiSM
* License: GPL2 or higher
*/

namespace sitemap;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}


define( 'D1G1_SITEMAP', 'sitemap' );
define( 'D1G1_PLUGSLUG_'.D1G1_SITEMAP, 'd1g1_plugins');
define( 'D1G1_PLUGNAME_'.D1G1_SITEMAP, 'Digihood HTML sitemap');
define( 'D1G1_PATHS_'.D1G1_SITEMAP, plugin_dir_path( __FILE__ ) );
define( 'D1G1_PATHTOFWASSET_'.D1G1_SITEMAP, plugin_dir_path( __FILE__ ).'plugin-framework/FrameworkAssets/' );
define( 'D1G1_URL_'.D1G1_SITEMAP, plugin_dir_url( __FILE__ ) );

// require __DIR__.'/plugin-framework/kint.phar';

include_once __DIR__ . '/includes.php';