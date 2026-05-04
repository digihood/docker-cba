<?php 
add_action('init', 'register_languages_digiSM');

function register_languages_digiSM() {
    // relative path from plugin folder, case sensitive
    load_plugin_textdomain( 'digiSM', false, 'digihood-HTML-sitemap/languages');
}
