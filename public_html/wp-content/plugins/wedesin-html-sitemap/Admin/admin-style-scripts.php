<?php 

add_action( 'admin_enqueue_scripts', 'enqueue_admin_styles_sitemap' );


function enqueue_admin_styles_sitemap(){
    wp_enqueue_style( 'digihood-HTML-sitemap-admin', D1G1_URL_sitemap . 'assets/styles/digihood-HTML-sitemap-admin.css');
}

?>