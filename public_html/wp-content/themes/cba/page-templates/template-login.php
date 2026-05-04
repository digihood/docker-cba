<?php
/*
Template Name: Přihlášení
*/

get_header(); ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

<?php endwhile; endif; ?>
<div class="main lg:col-span-4 md:col-span-8 col-span-12 lg:col-start-4 md:col-start-2" role="main" itemscope itemprop="mainEntityOfPage">
    <section class="section">
        <?php 

        if ( !is_user_logged_in(  ) ) {

            echo do_shortcode( '[login-form]');
            
        } else {

            echo '<div class="text-center"><p>' . __('Už jste přihlášení, přejděte do svého účtu.') . '</p>
            <div><a href="'. wp_logout_url( get_permalink( ) ) .'" class="button">'.__('Odhlásit se', 'cba').'</a></div></div>';
        }
            
        ?>
    </section>			           
</div>

<?php get_footer(); ?>