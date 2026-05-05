<?php
if (!defined('ABSPATH')) exit;

$heading    = get_field('stories_heading');
$subheading = get_field('stories_subheading');
$count      = intval(get_field('stories_count') ?: 3);
$category   = get_field('stories_category');
$btn        = get_field('stories_btn');

$args = [
    'post_type'      => 'post',
    'post_status'    => 'publish',
    'posts_per_page' => $count,
    'orderby'        => 'date',
    'order'          => 'DESC',
];

if ($category) {
    $args['cat'] = intval($category);
}

$posts = new WP_Query($args);
if (!$posts->have_posts()) return;
?>
<section class="stories-section py-16 lg:py-24 bg-gray-light" aria-label="<?= esc_attr($heading ?: 'Příběhy a inspirace') ?>">
    <div class="container max-w-content mx-auto">

        <!-- Hlavička -->
        <div class="section-header flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12">
            <div>
                <?php if ($heading) : ?>
                    <h2 class="text-dark font-bold text-h2-sm md:text-h2-md mb-3"><?= esc_html($heading) ?></h2>
                <?php endif; ?>
                <?php if ($subheading) : ?>
                    <p class="text-gray-dark text-lg max-w-xl"><?= esc_html($subheading) ?></p>
                <?php endif; ?>
            </div>
            <?php if ($btn) : ?>
                <a
                    href="<?= esc_url($btn['url']) ?>"
                    class="flex-shrink-0 inline-flex items-center gap-2 text-primary border border-primary rounded-full px-6 py-3 font-semibold text-sm hover:bg-primary hover:text-white transition-all duration-300 no-underline"
                    <?= !empty($btn['target']) ? 'target="' . esc_attr($btn['target']) . '"' : '' ?>
                >
                    <?= esc_html($btn['title']) ?>
                </a>
            <?php endif; ?>
        </div>

        <!-- Příběhy: první velký + ostatní menší -->
        <div class="stories-grid grid grid-cols-1 lg:grid-cols-3 gap-6">
            <?php
            $story_index = 0;
            while ($posts->have_posts()) : $posts->the_post();
                $is_featured = ($story_index === 0);
                $thumb_id    = get_post_thumbnail_id();
                $cats        = get_the_category();
                $first_cat   = !empty($cats) ? $cats[0] : null;
            ?>
                <article class="story-card <?= $is_featured ? 'lg:col-span-2 lg:row-span-2' : '' ?> relative overflow-hidden rounded-2xl group cursor-pointer bg-white hover:shadow-card-hover transition-shadow duration-300 flex flex-col">

                    <!-- Obrázek -->
                    <a href="<?= esc_url(get_permalink()) ?>" class="story-image block <?= $is_featured ? 'aspect-[16/9] lg:aspect-auto lg:min-h-[400px]' : 'aspect-[16/10]' ?> overflow-hidden no-underline flex-shrink-0">
                        <?php if ($thumb_id) : ?>
                            <?= wp_get_attachment_image($thumb_id, $is_featured ? 'large' : 'medium_large', false, [
                                'class'   => 'w-full h-full object-cover group-hover:scale-105 transition-transform duration-500',
                                'alt'     => esc_attr(get_the_title()),
                                'loading' => 'lazy',
                            ]) ?>
                        <?php else : ?>
                            <div class="w-full h-full bg-gradient-to-br from-primary/10 to-secondary/10 flex items-center justify-center min-h-[200px]">
                                <svg class="w-16 h-16 text-gray/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        <?php endif; ?>
                    </a>

                    <!-- Obsah -->
                    <div class="p-6 flex flex-col flex-grow">
                        <?php if ($first_cat) : ?>
                            <a href="<?= esc_url(get_category_link($first_cat->term_id)) ?>" class="text-xs font-semibold text-primary uppercase tracking-wide mb-2 no-underline hover:underline">
                                <?= esc_html($first_cat->name) ?>
                            </a>
                        <?php endif; ?>

                        <h3 class="text-dark font-bold <?= $is_featured ? 'text-xl lg:text-2xl' : 'text-lg' ?> leading-snug mb-2">
                            <a href="<?= esc_url(get_permalink()) ?>" class="text-dark no-underline hover:text-primary transition-colors">
                                <?= esc_html(get_the_title()) ?>
                            </a>
                        </h3>

                        <?php if ($is_featured) : ?>
                            <p class="text-gray-dark text-sm leading-relaxed mb-4 flex-grow">
                                <?= esc_html(wp_trim_words(get_the_excerpt(), 25, '...')) ?>
                            </p>
                        <?php endif; ?>

                        <div class="flex items-center gap-3 mt-auto pt-3 border-t border-gray-mid">
                            <time class="text-xs text-gray" datetime="<?= esc_attr(get_the_date('c')) ?>">
                                <?= esc_html(get_the_date()) ?>
                            </time>
                            <a href="<?= esc_url(get_permalink()) ?>" class="ml-auto text-primary text-xs font-semibold no-underline hover:underline">
                                <?= esc_html__('Číst', 'cba') ?> →
                            </a>
                        </div>
                    </div>
                </article>
            <?php
            $story_index++;
            endwhile;
            wp_reset_postdata();
            ?>
        </div>
    </div>
</section>
