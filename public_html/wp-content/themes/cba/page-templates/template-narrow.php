<?php
/*
Template Name: Úzká stránka (Obchodní podmínky, GDPR)
Template Post Type: page
*/
if (!defined('ABSPATH')) exit;

get_header();
?>
<div class="narrow-page-wrap py-12 lg:py-20">
    <div class="container">
        <div class="max-w-[800px] mx-auto">

            <!-- Breadcrumbs -->
            <?php if (function_exists('breadcrumb_trail')) breadcrumb_trail(); ?>

            <?php if (have_posts()) : while (have_posts()) : the_post(); ?>

                <article class="narrow-page-content">
                    <header class="page-header mb-10">
                        <h1 class="text-dark font-bold mb-4"><?= esc_html(get_the_title()) ?></h1>
                        <?php if (get_the_date()) : ?>
                            <p class="text-gray text-sm">
                                <?= esc_html__('Aktualizováno:', 'cba') ?>
                                <time datetime="<?= esc_attr(get_the_modified_date('c')) ?>"><?= esc_html(get_the_modified_date()) ?></time>
                            </p>
                        <?php endif; ?>
                        <div class="w-16 h-1 bg-primary rounded-full mt-4"></div>
                    </header>

                    <div class="prose prose-lg max-w-none entry-content text-dark">
                        <?php the_content(); ?>
                    </div>
                </article>

            <?php endwhile; endif; ?>
        </div>
    </div>
</div>
<?php
get_footer();
