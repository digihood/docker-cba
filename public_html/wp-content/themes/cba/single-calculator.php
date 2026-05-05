<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();

if ( have_posts() ) {
    while ( have_posts() ) {
        the_post();

        $current_slug = get_post_field( 'post_name', get_the_ID() );

        if ( $current_slug === 'planovac-rozpoctu' ) {
            get_template_part( 'template-parts/calculators/budget-planner/budget-planner' );
        } elseif ( $current_slug === 'sporeni-na-duchod' ) {
            get_template_part( 'template-parts/calculators/retirement-savings/retirement-savings' );
        } elseif ( $current_slug === 'ciste-jmeni' ) {
            get_template_part( 'template-parts/calculators/net-worth/net-worth' );
        } else {
            echo '<div class="container mx-auto px-4 py-12">';
            echo '<h1 class="text-3xl font-bold mb-6">' . esc_html( get_the_title() ) . '</h1>';
            the_content();
            echo '</div>';
        }
    }
}

get_footer();
