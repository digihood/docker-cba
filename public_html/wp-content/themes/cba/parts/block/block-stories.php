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
    'posts_per_page' => max(3, $count),
    'orderby'        => 'date',
    'order'          => 'DESC',
];
if ($category) $args['cat'] = intval($category);

$posts = new WP_Query($args);
if (!$posts->have_posts()) return;

$all_stories = [];
while ($posts->have_posts()) {
    $posts->the_post();
    $cats = get_the_category();
    $all_stories[] = [
        'id'        => get_the_ID(),
        'title'     => get_the_title(),
        'permalink' => get_permalink(),
        'thumb_id'  => get_post_thumbnail_id(),
        'cat'       => !empty($cats) ? $cats[0] : null,
        'author'    => get_the_author(),
        'author_id' => get_the_author_meta('ID'),
        'date'      => get_the_date(),
    ];
}
wp_reset_postdata();

$featured = $all_stories[1] ?? $all_stories[0] ?? null;
$side_l   = $all_stories[0] ?? null;
$side_r   = $all_stories[2] ?? null;
?>
<section class="stories-section py-16 lg:py-24" style="background:#fff3db;" aria-label="<?= esc_attr($heading ?: 'Příběhy a inspirace') ?>">
    <div class="container max-w-content mx-auto">

        <!-- Hlavička -->
        <div class="text-center mb-12 lg:mb-16">
            <?php if ($heading) : ?>
                <h2 class="font-bold text-dark mb-4" style="font-size:44px;font-family:Montserrat,sans-serif;line-height:1;"><?= esc_html($heading) ?></h2>
            <?php endif; ?>
            <?php if ($subheading) : ?>
                <p class="text-dark/70 text-lg" style="font-family:Montserrat,sans-serif;"><?= esc_html($subheading) ?></p>
            <?php endif; ?>
        </div>

        <!-- Stories carousel: arrow + small + LARGE featured + small + arrow -->
        <div class="flex items-center justify-center gap-6 py-10">

            <!-- Left arrow -->
            <button class="flex items-center justify-center w-5 h-5 opacity-60 hover:opacity-100 transition-opacity flex-shrink-0" aria-label="Předchozí" onclick="this.closest('section').querySelector('.stories-track').scrollBy({left:-400,behavior:'smooth'})">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none"><path d="M13 4L7 10L13 16" stroke="#13576b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </button>

            <!-- Stories track -->
            <div class="stories-track flex items-center gap-6 overflow-hidden" style="max-width:1160px;">

                <!-- Side card left -->
                <?php if ($side_l) : ?>
                    <article class="story-card-small flex-shrink-0 flex flex-col justify-end gap-5 p-10 rounded-[20px] bg-dark" style="width:287px;height:440px;">
                        <?php if ($side_l['thumb_id']) : ?>
                            <div class="w-[100px] h-[100px] rounded-full overflow-hidden flex-shrink-0">
                                <?= wp_get_attachment_image($side_l['thumb_id'], [100, 100], false, ['class' => 'w-full h-full object-cover', 'alt' => esc_attr($side_l['author']), 'loading' => 'lazy']) ?>
                            </div>
                        <?php else : ?>
                            <div class="w-[100px] h-[100px] rounded-full flex items-center justify-center flex-shrink-0" style="background:rgba(255,255,255,0.12);">
                                <span class="text-white font-bold text-2xl"><?= mb_substr($side_l['author'], 0, 1) ?></span>
                            </div>
                        <?php endif; ?>
                        <h3 class="font-bold text-white leading-tight" style="font-size:24px;font-family:Montserrat,sans-serif;">
                            <a href="<?= esc_url($side_l['permalink']) ?>" class="text-white no-underline hover:opacity-80"><?= esc_html($side_l['title']) ?></a>
                        </h3>
                        <div class="inline-flex items-center justify-center bg-primary px-4 py-1.5 text-white text-sm font-semibold w-fit" style="font-family:Montserrat,sans-serif;">
                            <?= esc_html($side_l['author']) ?>
                        </div>
                        <?php if ($side_l['cat']) : ?>
                            <p class="text-white/70 text-sm leading-snug" style="font-family:Montserrat,sans-serif;"><?= esc_html($side_l['cat']->name) ?></p>
                        <?php endif; ?>
                    </article>
                <?php endif; ?>

                <!-- Featured card -->
                <?php if ($featured) : ?>
                    <article class="story-card-featured relative flex-shrink-0 flex flex-col justify-end gap-6 p-10 rounded-[20px] overflow-hidden" style="width:391px;height:600px;">
                        <?php if ($featured['thumb_id']) : ?>
                            <div class="absolute inset-0" aria-hidden="true">
                                <?= wp_get_attachment_image($featured['thumb_id'], 'medium_large', false, ['class' => 'w-full h-full object-cover', 'alt' => '', 'loading' => 'lazy']) ?>
                                <div class="absolute inset-0" style="background:linear-gradient(to top,#13576b 0%,rgba(19,87,107,0) 60%);"></div>
                            </div>
                        <?php else : ?>
                            <div class="absolute inset-0 bg-dark" aria-hidden="true"></div>
                        <?php endif; ?>
                        <div class="relative z-10 flex flex-col gap-5">
                            <h3 class="font-bold text-white leading-tight" style="font-size:34px;font-family:Montserrat,sans-serif;">
                                <a href="<?= esc_url($featured['permalink']) ?>" class="text-white no-underline hover:opacity-80"><?= esc_html($featured['title']) ?></a>
                            </h3>
                            <div class="inline-flex items-center justify-center bg-primary px-4 py-1.5 text-white font-semibold w-fit" style="font-size:16px;font-family:Montserrat,sans-serif;">
                                <?= esc_html($featured['author']) ?>
                            </div>
                            <?php if ($featured['cat']) : ?>
                                <p class="text-white/80 leading-snug" style="font-size:16px;font-family:Montserrat,sans-serif;"><?= esc_html($featured['cat']->name) ?></p>
                            <?php endif; ?>
                        </div>
                    </article>
                <?php endif; ?>

                <!-- Side card right -->
                <?php if ($side_r) : ?>
                    <article class="story-card-small flex-shrink-0 flex flex-col justify-end gap-5 p-10 rounded-[20px] bg-dark" style="width:287px;height:440px;">
                        <?php if ($side_r['thumb_id']) : ?>
                            <div class="w-[100px] h-[100px] rounded-full overflow-hidden flex-shrink-0">
                                <?= wp_get_attachment_image($side_r['thumb_id'], [100, 100], false, ['class' => 'w-full h-full object-cover', 'alt' => esc_attr($side_r['author']), 'loading' => 'lazy']) ?>
                            </div>
                        <?php else : ?>
                            <div class="w-[100px] h-[100px] rounded-full flex items-center justify-center flex-shrink-0" style="background:rgba(255,255,255,0.12);">
                                <span class="text-white font-bold text-2xl"><?= mb_substr($side_r['author'], 0, 1) ?></span>
                            </div>
                        <?php endif; ?>
                        <h3 class="font-bold text-white leading-tight" style="font-size:24px;font-family:Montserrat,sans-serif;">
                            <a href="<?= esc_url($side_r['permalink']) ?>" class="text-white no-underline hover:opacity-80"><?= esc_html($side_r['title']) ?></a>
                        </h3>
                        <div class="inline-flex items-center justify-center bg-primary px-4 py-1.5 text-white text-sm font-semibold w-fit" style="font-family:Montserrat,sans-serif;">
                            <?= esc_html($side_r['author']) ?>
                        </div>
                        <?php if ($side_r['cat']) : ?>
                            <p class="text-white/70 text-sm leading-snug" style="font-family:Montserrat,sans-serif;"><?= esc_html($side_r['cat']->name) ?></p>
                        <?php endif; ?>
                    </article>
                <?php endif; ?>

            </div>

            <!-- Right arrow -->
            <button class="flex items-center justify-center w-5 h-5 opacity-60 hover:opacity-100 transition-opacity flex-shrink-0" aria-label="Další" onclick="this.closest('section').querySelector('.stories-track').scrollBy({left:400,behavior:'smooth'})">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none"><path d="M7 4L13 10L7 16" stroke="#13576b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </button>

        </div>

        <!-- CTA -->
        <?php if ($btn) : ?>
            <div class="text-center mt-4">
                <a href="<?= esc_url($btn['url']) ?>" class="inline-flex items-center justify-center bg-primary text-white rounded-[50px] px-8 py-4 text-sm font-semibold uppercase tracking-[0.07em] no-underline hover:bg-primary-dark transition-colors" style="font-family:Montserrat,sans-serif;" <?= !empty($btn['target']) ? 'target="' . esc_attr($btn['target']) . '"' : '' ?>>
                    <?= esc_html($btn['title']) ?>
                </a>
            </div>
        <?php endif; ?>

    </div>
</section>
