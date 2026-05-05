<?php
if (!defined('ABSPATH')) exit;

$heading    = get_field('articles_heading');
$subheading = get_field('articles_subheading');
$count      = get_field('articles_count') ?: 4;
$category   = get_field('articles_category');
$btn        = get_field('articles_btn');

$args = [
    'post_type'      => 'post',
    'post_status'    => 'publish',
    'posts_per_page' => max(4, intval($count)),
    'orderby'        => 'date',
    'order'          => 'DESC',
];

if ($category) {
    $args['cat'] = intval($category);
}

$query = new WP_Query($args);
if (!$query->have_posts()) return;

// Collect all posts data
$all_posts = [];
while ($query->have_posts()) {
    $query->the_post();
    $words = str_word_count(strip_tags(get_the_content()));
    $all_posts[] = [
        'title'     => get_the_title(),
        'permalink' => get_permalink(),
        'thumb_id'  => get_post_thumbnail_id(),
        'cats'      => get_the_category(),
        'excerpt'   => get_the_excerpt(),
        'read_time' => max(1, round($words / 200)),
    ];
}
wp_reset_postdata();

$featured   = $all_posts[0] ?? null;
$list_posts = array_slice($all_posts, 1, 3);

// Get categories for filter pills
$filter_cats = get_categories(['hide_empty' => true, 'number' => 7, 'orderby' => 'count', 'order' => 'DESC']);
?>
<section class="articles-section py-16 lg:py-24 bg-gray-light" aria-label="<?= esc_attr($heading ?: 'Články') ?>">
    <div class="container max-w-content mx-auto">

        <!-- Section header -->
        <div class="section-header text-center mb-10 lg:mb-14">
            <?php if ($heading) : ?>
                <h2 class="text-dark font-bold text-h2-sm md:text-h2-md mb-4"><?= esc_html($heading) ?></h2>
            <?php endif; ?>
            <?php if ($subheading) : ?>
                <p class="text-gray-dark text-lg max-w-2xl mx-auto mb-8"><?= esc_html($subheading) ?></p>
            <?php endif; ?>

            <!-- Category filter pills -->
            <?php if (!empty($filter_cats)) : ?>
                <div class="flex flex-wrap justify-center gap-2.5">
                    <?php foreach ($filter_cats as $cat) : ?>
                        <a
                            href="<?= esc_url(get_category_link($cat->term_id)) ?>"
                            class="inline-block px-5 py-2 rounded-full border border-dark/25 text-dark text-sm font-medium hover:border-primary hover:text-primary transition-colors duration-200 no-underline"
                        >
                            <?= esc_html($cat->name) ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Articles grid: 1 featured + list -->
        <?php if ($featured) : ?>
            <div class="grid grid-cols-1 lg:grid-cols-[1.15fr_1fr] gap-6 mb-10">

                <!-- Featured article: large photo card -->
                <article class="article-featured relative rounded-2xl overflow-hidden group min-h-[420px] lg:min-h-[500px] flex flex-col">
                    <a href="<?= esc_url($featured['permalink']) ?>" class="absolute inset-0 no-underline" tabindex="-1" aria-hidden="true">
                        <?php if ($featured['thumb_id']) : ?>
                            <?= wp_get_attachment_image($featured['thumb_id'], 'large', false, [
                                'class'   => 'w-full h-full object-cover group-hover:scale-105 transition-transform duration-700',
                                'alt'     => esc_attr($featured['title']),
                                'loading' => 'eager',
                            ]) ?>
                        <?php else : ?>
                            <div class="w-full h-full bg-gradient-to-br from-dark to-dark-card"></div>
                        <?php endif; ?>
                        <div class="absolute inset-0 bg-gradient-to-t from-dark via-dark/55 to-transparent"></div>
                    </a>

                    <!-- Reading time badge -->
                    <div class="absolute top-5 left-5 z-10 flex items-center gap-1.5 bg-black/25 backdrop-blur-sm rounded-full px-3 py-1.5 text-white text-xs font-medium">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <?= $featured['read_time'] ?> min. čtení
                    </div>

                    <!-- Content at bottom -->
                    <div class="relative z-10 mt-auto p-7 lg:p-9">
                        <?php if (!empty($featured['cats'])) : $cat = $featured['cats'][0]; ?>
                            <a href="<?= esc_url(get_category_link($cat->term_id)) ?>" class="text-xs font-semibold text-primary uppercase tracking-wide no-underline mb-2 inline-block">
                                <?= esc_html($cat->name) ?>
                            </a>
                        <?php endif; ?>
                        <h3 class="text-white font-bold text-xl lg:text-2xl leading-snug mb-5">
                            <a href="<?= esc_url($featured['permalink']) ?>" class="text-white no-underline hover:text-primary/80 transition-colors">
                                <?= esc_html($featured['title']) ?>
                            </a>
                        </h3>
                        <a href="<?= esc_url($featured['permalink']) ?>" class="flex items-center justify-center w-full bg-primary/80 hover:bg-primary text-white text-sm font-semibold py-3.5 rounded-xl no-underline transition-colors duration-300">
                            <?= esc_html__('Přečíst článek', 'cba') ?>
                        </a>
                    </div>
                </article>

                <!-- List articles -->
                <?php if (!empty($list_posts)) : ?>
                    <div class="flex flex-col gap-4">
                        <?php foreach ($list_posts as $post) : ?>
                            <article class="article-list bg-white rounded-xl p-5 lg:p-6 flex flex-col gap-2.5 hover:shadow-card transition-shadow duration-300 group flex-1">
                                <div class="flex items-center gap-3">
                                    <?php if (!empty($post['cats'])) : $cat = $post['cats'][0]; ?>
                                        <a href="<?= esc_url(get_category_link($cat->term_id)) ?>" class="text-xs font-semibold text-primary uppercase tracking-wide no-underline hover:underline">
                                            <?= esc_html($cat->name) ?>
                                        </a>
                                    <?php endif; ?>
                                    <span class="flex items-center gap-1 text-gray text-xs ml-auto flex-shrink-0">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <?= $post['read_time'] ?> min. čtení
                                    </span>
                                </div>
                                <h3 class="text-dark font-bold text-base lg:text-lg leading-snug group-hover:text-primary transition-colors duration-300">
                                    <a href="<?= esc_url($post['permalink']) ?>" class="text-dark no-underline hover:text-primary">
                                        <?= esc_html($post['title']) ?>
                                    </a>
                                </h3>
                                <p class="text-gray-dark text-sm leading-relaxed">
                                    <?= esc_html(wp_trim_words($post['excerpt'], 18, '...')) ?>
                                </p>
                            </article>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <!-- CTA button -->
        <?php if ($btn) : ?>
            <div class="text-center">
                <a
                    href="<?= esc_url($btn['url']) ?>"
                    class="button primary rounded-full !py-4 !px-10 font-semibold no-underline hover:no-underline inline-flex items-center gap-2 uppercase tracking-wide text-sm"
                    <?= !empty($btn['target']) ? 'target="' . esc_attr($btn['target']) . '"' : '' ?>
                >
                    <?= esc_html($btn['title']) ?>
                </a>
            </div>
        <?php endif; ?>
    </div>
</section>
