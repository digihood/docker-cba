<?php
/*
Template Name: Seznam kalkulaček
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();

// Page hero banner
if ( have_posts() ) :
    while ( have_posts() ) : the_post(); ?>

    <div class="calc-list-hero">
        <div class="calc-list-hero__inner">
            <h1 class="calc-list-hero__title"><?php the_title(); ?></h1>
            <?php if ( has_excerpt() ) : ?>
                <p class="calc-list-hero__desc"><?php the_excerpt(); ?></p>
            <?php else : ?>
                <p class="calc-list-hero__desc">Vyberte si kalkulačku a získejte okamžitý přehled o svých financích.</p>
            <?php endif; ?>
        </div>
    </div>

    <?php endwhile;
endif;

// Calculator list
get_template_part( 'template-parts/kalkulacky/calculator-list' );

get_footer();
