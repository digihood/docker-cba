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
if ($category) $args['cat'] = intval($category);

$query = new WP_Query($args);
if (!$query->have_posts()) return;

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

$filter_cats = get_categories([
    'hide_empty' => true,
    'number'     => 7,
    'orderby'    => 'count',
    'order'      => 'DESC',
    'exclude'    => [get_option('default_category')],
]);
$filter_cats = array_values(array_filter($filter_cats, function($c) {
    return !in_array($c->slug, ['uncategorized', 'nezarazene', 'bez-kategorie'], true);
}));
?>
<section class="articles-section" style="background:#fff3db;padding:100px 0;" aria-label="<?= esc_attr($heading ?: 'Články') ?>">
    <div class="container max-w-content mx-auto">

        <!-- Section header -->
        <div class="text-center mb-10 lg:mb-14">
            <?php if ($heading) : ?>
                <h2 class="font-semibold text-dark mb-4" style="font-size:44px;font-family:Montserrat,sans-serif;line-height:1;"><?= esc_html($heading) ?></h2>
            <?php endif; ?>
            <?php if ($subheading) : ?>
                <p class="text-dark/70 text-lg mb-8" style="font-family:Montserrat,sans-serif;"><?= esc_html($subheading) ?></p>
            <?php endif; ?>

            <!-- Category filter pills -->
            <?php if (!empty($filter_cats)) : ?>
                <div class="flex flex-wrap justify-center gap-2.5">
                    <?php foreach ($filter_cats as $cat) : ?>
                        <a href="<?= esc_url(get_category_link($cat->term_id)) ?>" class="inline-block px-5 py-2.5 rounded-full border border-dark text-dark text-sm no-underline hover:border-primary hover:text-primary transition-colors duration-200" style="font-family:Montserrat,sans-serif;">
                            <?= esc_html($cat->name) ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Articles grid: 1 featured left + 3 list right -->
        <?php if ($featured) : ?>
            <div class="flex flex-col lg:flex-row items-stretch justify-between gap-6 lg:gap-5 py-10">

                <!-- Featured article -->
                <?php $has_thumb = !empty($featured['thumb_id']); ?>
                <article class="article-featured relative rounded-[20px] overflow-hidden group flex flex-col  <?= $has_thumb ? '' : 'bg-dark' ?>" style="min-width:598px;">
                    <?php if ($has_thumb) : ?>
                        <a href="<?= esc_url($featured['permalink']) ?>" class="absolute inset-0 no-underline" tabindex="-1" aria-hidden="true">
                            <?= wp_get_attachment_image($featured['thumb_id'], 'large', false, [
                                'class'   => 'w-full h-full object-cover',
                                'alt'     => esc_attr($featured['title']),
                                'loading' => 'eager',
                            ]) ?>
                            <div class="absolute inset-0" style="background:linear-gradient(to top,rgba(169,147,109,0.95) 0%,rgba(255,243,219,0) 58%);"></div>
                        </a>
                    <?php else : ?>
                        <div class="absolute inset-0 bg-dark" aria-hidden="true"></div>
                    <?php endif; ?>
                    <!-- Reading time badge -->
                    <div class="absolute top-7 left-[56px] z-10 flex items-center gap-1.5 text-white text-sm font-bold" style="font-family:Montserrat,sans-serif;">
                        <?php d1g1B::icon('doba-cteni', 'w-[19px] h-[19px]  [&_path]:stroke-white'); ?>
                        <?= $featured['read_time'] ?> min. čtení
                    </div>
                    <!-- Content at bottom -->
                    <div class="relative z-10 mt-auto p-10 lg:p-14">
                        <h3 class="font-bold text-white text-2xl lg:text-[34px] leading-tight mb-4" style="font-family:Montserrat,sans-serif;">
                            <a href="<?= esc_url($featured['permalink']) ?>" class="text-white no-underline hover:opacity-80 transition-opacity">
                                <?= esc_html($featured['title']) ?>
                            </a>
                        </h3>
                        <?php if (!empty($featured['excerpt'])) : ?>
                            <p class="text-white/85 text-lg leading-relaxed font-medium" style="font-family:Montserrat,sans-serif;max-width:450px;">
                                <?= esc_html(wp_trim_words($featured['excerpt'], 18, '...')) ?>
                            </p>
                        <?php endif; ?>
                    </div>
                </article>

                <!-- List articles -->
                <?php if (!empty($list_posts)) : ?>
                    <div class="flex flex-col gap-[25px] max-w-[600px]">
                        <?php foreach ($list_posts as $post) : ?>
                            <article class="article-list bg-white rounded-[20px] overflow-hidden flex flex-col hover:shadow-card transition-shadow duration-300 group" >
                                <div class="p-7 flex flex-col justify-between h-full">
                                    <h3 class="font-semibold text-dark text-xl leading-snug group-hover:text-primary transition-colors" style="font-family:Montserrat,sans-serif;">
                                        <a href="<?= esc_url($post['permalink']) ?>" class="text-dark no-underline hover:text-primary">
                                            <?= esc_html($post['title']) ?>
                                        </a>
                                    </h3>
                                    <?php if (!empty($post['excerpt'])) : ?>
                                        <p class="text-dark/60 text-sm leading-relaxed line-clamp-2" style="font-family:Montserrat,sans-serif;">
                                            <?= esc_html(wp_trim_words($post['excerpt'], 22, '...')) ?>
                                        </p>
                                    <?php endif; ?>
                                    <div class="flex items-center gap-1.5 text-dark text-xs" style="font-family:Montserrat,sans-serif;">
                                        <?php d1g1B::icon('doba-cteni', 'w-3 h-3 '); ?>
                                        <?= $post['read_time'] ?> min. čtení
                                    </div>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

            </div>
        <?php endif; ?>

        <!-- CTA button -->
        <?php if ($btn) : ?>
            <div class="text-center mt-6">
                <?php d1g1B::primary_link(
                    esc_html( $btn['title'] ),
                    esc_url( $btn['url'] ),
                    ! empty( $btn['target'] ) ? [ 'target' => esc_attr( $btn['target'] ) ] : []
                ); ?>
            </div>
        <?php elseif (get_permalink(get_option('page_for_posts'))) : ?>
            <div class="text-center mt-6">
                <?php d1g1B::primary_link( esc_html__( 'Všechny články', 'cba' ), esc_url( get_permalink( get_option( 'page_for_posts' ) ) ) ); ?>
            </div>
        <?php endif; ?>

    </div>
</section>
