<?php
if (!defined('ABSPATH')) exit;

$heading    = get_field('calc_heading');
$subheading = get_field('calc_subheading');
$items      = get_field('calc_items');
$btn        = get_field('calc_btn');
$center_img = get_field('calc_image');

if (empty($items)) return;

$svg_icons = [
    'duchod'   => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 3v3m0 12v3m9-9h-3M6 12H3m14.5-6.5l-2.1 2.1m-8.8 8.8l-2.1 2.1m13 0l-2.1-2.1m-8.8-8.8L5.5 5.5M12 7a5 5 0 100 10 5 5 0 000-10z"/>',
    'sporeni'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 3v3m0 12v3m9-9h-3M6 12H3m14.5-6.5l-2.1 2.1m-8.8 8.8l-2.1 2.1m13 0l-2.1-2.1m-8.8-8.8L5.5 5.5M12 7a5 5 0 100 10 5 5 0 000-10z"/>',
    'inflace'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>',
    'rozpocet' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M3 7a2 2 0 012-2h14a2 2 0 012 2v3H3V7zm0 5h18v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5zm13 2.5a1 1 0 102 0 1 1 0 00-2 0z"/>',
    'hypoteka' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M3 12l9-9 9 9M5 10v9a1 1 0 001 1h3v-6h6v6h3a1 1 0 001-1v-9"/>',
    'default'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 7h6m-6 4h6m-3 4h.01M5 5a2 2 0 012-2h10a2 2 0 012 2v14a2 2 0 01-2 2H7a2 2 0 01-2-2V5z"/>',
];

// Split items: max 4 (2 left, 2 right)
$left_items  = array_slice($items, 0, 2);
$right_items = array_slice($items, 2, 2);

$render_item = function($item) use ($svg_icons) {
    if (empty($item['calculator'])) return;
    $calc_post  = $item['calculator'];
    $calc_url   = get_permalink($calc_post->ID);
    $calc_title = get_the_title($calc_post->ID);
    $calc_desc  = !empty($item['custom_desc']) ? $item['custom_desc'] : get_the_excerpt($calc_post->ID);
    $calc_icon  = $item['custom_icon'] ?? null;
    $thumb_id   = get_post_thumbnail_id($calc_post->ID);
    $slug       = strtolower(remove_accents($calc_post->post_name . ' ' . $calc_title));
    $svg_path   = $svg_icons['default'];
    foreach ($svg_icons as $key => $path) {
        if ($key !== 'default' && strpos($slug, $key) !== false) { $svg_path = $path; break; }
    }
    ?>
    <div class="flex items-center gap-5">
        <!-- Icon -->
        <div class="flex-shrink-0 w-[120px] h-[120px] bg-white rounded-[20px] shadow-card flex items-center justify-center">
            <?php if ($calc_icon) : ?>
                <?= wp_get_attachment_image($calc_icon['ID'], [60, 60], false, ['class' => 'w-16 h-16 object-contain', 'alt' => '']) ?>
            <?php elseif ($thumb_id) : ?>
                <?= wp_get_attachment_image($thumb_id, [60, 60], false, ['class' => 'w-16 h-16 object-contain', 'alt' => '']) ?>
            <?php else : ?>
                <svg class="w-10 h-10 text-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24"><?= $svg_path ?></svg>
            <?php endif; ?>
        </div>
        <!-- Content -->
        <div class="flex flex-col gap-3.5" style="width:248px;">
            <h3 class="font-semibold text-dark text-xl leading-snug" style="font-family:Montserrat,sans-serif;">
                <?= esc_html($calc_title) ?>
            </h3>
            <?php if ($calc_desc) : ?>
                <p class="text-dark/65 text-sm leading-relaxed" style="font-family:Montserrat,sans-serif;max-width:218px;">
                    <?= esc_html(wp_trim_words($calc_desc, 12, '...')) ?>
                </p>
            <?php endif; ?>
            <a href="<?= esc_url($calc_url) ?>" class="inline-flex items-center justify-center bg-primary text-white rounded-[50px] px-5 py-2.5 text-xs font-bold uppercase tracking-[0.06em] no-underline hover:bg-primary-dark transition-colors w-fit" style="font-family:Montserrat,sans-serif;">
                Vypočítat
            </a>
        </div>
    </div>
    <?php
};
?>
<section class="calculators-section py-16 lg:py-24 bg-white relative overflow-hidden" aria-label="<?= esc_attr($heading ?: 'Kalkulačky') ?>">

    <!-- Subtle dot pattern background -->
    <div class="absolute inset-0 pointer-events-none opacity-[0.03]" style="background-image:radial-gradient(circle,#13576b 1px,transparent 1px);background-size:32px 32px;" aria-hidden="true"></div>

    <div class="container max-w-content mx-auto relative z-10">

        <!-- Section header -->
        <div class="text-center mb-12 lg:mb-16">
            <?php if ($heading) : ?>
                <h2 class="font-bold text-dark mb-4" style="font-size:44px;font-family:Montserrat,sans-serif;line-height:1;"><?= esc_html($heading) ?></h2>
            <?php endif; ?>
            <?php if ($subheading) : ?>
                <p class="text-dark/70 text-lg" style="font-family:Montserrat,sans-serif;"><?= esc_html($subheading) ?></p>
            <?php endif; ?>
        </div>

        <!-- 3-column layout: items | center image | items -->
        <div class="grid items-center gap-12 lg:gap-0" style="grid-template-columns:1fr 360px 1fr;padding:80px 0 140px;">

            <!-- Left items -->
            <div class="flex flex-col gap-20">
                <?php foreach ($left_items as $item) : ?>
                    <?php $render_item($item); ?>
                <?php endforeach; ?>
            </div>

            <!-- Center image -->
            <div class="flex items-center justify-center px-8">
                <?php if ($center_img) : ?>
                    <?= wp_get_attachment_image($center_img['ID'], 'medium', false, [
                        'class' => 'w-full max-w-[320px] object-contain',
                        'alt'   => '',
                        'loading' => 'lazy',
                    ]) ?>
                <?php else : ?>
                    <!-- Decorative placeholder -->
                    <div class="w-[300px] h-[400px] rounded-[20px] bg-gray-light flex items-center justify-center">
                        <svg class="w-24 h-24 text-dark/20" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Right items -->
            <div class="flex flex-col gap-20 items-start">
                <?php foreach ($right_items as $item) : ?>
                    <?php $render_item($item); ?>
                <?php endforeach; ?>
            </div>

        </div>

    </div>
</section>
