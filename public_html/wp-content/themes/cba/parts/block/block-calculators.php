<?php
if (!defined('ABSPATH')) exit;

$heading    = get_field('calc_heading');
$subheading = get_field('calc_subheading');
$items      = get_field('calc_items');
$btn        = get_field('calc_btn');
$bg_image   = get_field('calc_image'); // sekce background foto (ne center sloupec)

if (empty($items)) return;

$svg_icons = [
    'duchod'   => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 8c-1.657 0-3 1.343-3 3s1.343 3 3 3 3-1.343 3-3-1.343-3-3-3zm0-5v2m0 14v2m7-9h2M3 12H1m15.364-6.364l-1.414 1.414M6.05 17.95l-1.414 1.414m12.728 0l-1.414-1.414M6.05 6.05L4.636 7.464"/>',
    'sporeni'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M17 9V7a5 5 0 00-10 0v2m-2 0h14l1 10H4L5 9z"/>',
    'inflace'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>',
    'rozpocet' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M3 7a2 2 0 012-2h14a2 2 0 012 2v3H3V7zm0 5h18v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5zm13 2.5a1 1 0 102 0 1 1 0 00-2 0z"/>',
    'hypoteka' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M3 12l9-9 9 9M5 10v9a1 1 0 001 1h3v-6h6v6h3a1 1 0 001-1v-9"/>',
    'default'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 7h6m-6 4h6m-3 4h.01M5 5a2 2 0 012-2h10a2 2 0 012 2v14a2 2 0 01-2 2H7a2 2 0 01-2-2V5z"/>',
];

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
        <!-- Icon box: bílý čtverec se stínem -->
        <div class="flex-shrink-0 w-[120px] h-[120px] bg-white rounded-[20px] shadow-card flex items-center justify-center relative overflow-hidden">
            <?php if ($calc_icon) : ?>
                <?= wp_get_attachment_image($calc_icon['ID'], [80, 80], false, [
                    'class'   => 'w-20 h-20 object-contain',
                    'alt'     => '',
                    'loading' => 'lazy',
                ]) ?>
            <?php elseif ($thumb_id) : ?>
                <?= wp_get_attachment_image($thumb_id, [80, 80], false, [
                    'class'   => 'w-20 h-20 object-contain',
                    'alt'     => '',
                    'loading' => 'lazy',
                ]) ?>
            <?php else : ?>
                <!-- SVG fallback ikona -->
                <svg class="w-10 h-10 text-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24"><?= $svg_path ?></svg>
            <?php endif; ?>
        </div>

        <!-- Textový obsah -->
        <div class="flex flex-col gap-[15px]" style="width:248px;">
            <h3 class="font-semibold text-dark leading-[1.4]" style="font-size:20px;font-family:Montserrat,sans-serif;">
                <?= esc_html($calc_title) ?>
            </h3>
            <?php if ($calc_desc) : ?>
                <p class="text-dark/65 leading-[1.4]" style="font-size:14px;font-family:Montserrat,sans-serif;max-width:218px;">
                    <?= esc_html(wp_trim_words($calc_desc, 12, '...')) ?>
                </p>
            <?php endif; ?>
            <a href="<?= esc_url($calc_url) ?>"
               class="inline-flex items-center justify-center bg-primary text-white rounded-[50px] px-5 py-2.5 font-bold uppercase tracking-[0.06em] no-underline hover:bg-primary-dark transition-colors w-fit"
               style="font-size:12px;font-family:Montserrat,sans-serif;">
                Vypočítat
            </a>
        </div>
    </div>
    <?php
};
?>
<section class="calculators-section relative overflow-hidden" style="padding-top:100px;padding-bottom:0;" aria-label="<?= esc_attr($heading ?: 'Kalkulačky') ?>">

    <!-- Sekce pozadí: bílá + foto (průhledná přes gap kalkulaček) -->
    <div class="absolute inset-0 pointer-events-none" aria-hidden="true">
        <div class="absolute inset-0 bg-white"></div>
        <?php if ($bg_image) : ?>
            <div class="absolute inset-0 overflow-hidden">
                <?= wp_get_attachment_image($bg_image['ID'], 'large', false, [
                    'class'   => 'absolute left-0 top-0 w-full h-full object-cover',
                    'alt'     => '',
                    'loading' => 'lazy',
                ]) ?>
            </div>
        <?php else : ?>
            <!-- Fallback subtilní bodkovaný vzor -->
            <div class="absolute inset-0 opacity-[0.035]"
                 style="background-image:radial-gradient(circle,#13576b 1px,transparent 1px);background-size:32px 32px;"></div>
        <?php endif; ?>
    </div>

    <!-- Hlavička sekce -->
    <div class="container max-w-content mx-auto relative z-10 text-center mb-12 lg:mb-16">
        <?php if ($heading) : ?>
            <h2 class="font-semibold text-dark leading-none mb-4" style="font-size:44px;font-family:Montserrat,sans-serif;"><?= esc_html($heading) ?></h2>
        <?php endif; ?>
        <?php if ($subheading) : ?>
            <p class="text-dark/70 text-lg" style="font-family:Montserrat,sans-serif;"><?= esc_html($subheading) ?></p>
        <?php endif; ?>
    </div>

    <!-- 2 sloupce s velkou mezerou – pozadí prosvítá skrz mezeru (Figma: gap-[400px]) -->
    <div class="relative z-10 flex items-center justify-center px-[110px]"
         style="gap:clamp(60px,20vw,400px);padding-top:60px;padding-bottom:100px;">

        <!-- Levý sloupec -->
        <div class="flex flex-col gap-20 items-start">
            <?php foreach ($left_items as $item) : ?>
                <?php $render_item($item); ?>
            <?php endforeach; ?>
        </div>

        <!-- Pravý sloupec -->
        <div class="flex flex-col gap-20 items-start">
            <?php foreach ($right_items as $item) : ?>
                <?php $render_item($item); ?>
            <?php endforeach; ?>
        </div>

    </div>

</section>
