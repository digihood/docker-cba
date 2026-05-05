<?php
/*
Template Name: Celá šířka – ACF bloky
Template Post Type: page
*/
if (!defined('ABSPATH')) exit;

get_header();
?>
<div class="page-blocks-wrap">
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <?php the_content(); ?>
    <?php endwhile; endif; ?>
</div>
<?php
get_footer();
