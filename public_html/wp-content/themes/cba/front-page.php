<?php
/**
 * Šablona domácí stránky – ACF bloky
 */
if (!defined('ABSPATH')) exit;

get_header();

// Pokud je nastavena domácí stránka a má ACF bloky, zobraz je
if (have_posts()) :
    while (have_posts()) : the_post();
        // WordPress content (ACF bloky vložené přes editor)
        $content = get_the_content();
        if ($content) {
            echo '<div class="front-page-blocks">';
            the_content();
            echo '</div>';
        }
    endwhile;
endif;

get_footer();
