<?php
if (!defined('ABSPATH')) exit;

$heading    = get_field('calc_heading');
$subheading = get_field('calc_subheading');
$items      = get_field('calc_items');
$btn        = get_field('calc_btn');
$bg_image   = get_field('calc_image');

if (empty($items)) return;

$fallback_icons = [
    'duchod'   => 'calc-duchod',
    'sporeni'  => 'calc-sporeni',
    'inflace'  => 'calc-inflace',
    'rozpocet' => 'calc-rozpocet',
    'hypoteka' => 'calc-rozpocet',
];
?>
<section class="calculators-section relative overflow-hidden" style="padding-top:100px;padding-bottom:0;" aria-label="<?= esc_attr($heading ?: 'Kalkulačky') ?>">

    <!-- Sekce pozadí -->
    <div class="absolute inset-0 pointer-events-none" aria-hidden="true">
        <div class="absolute inset-0 bg-white"></div>
        <?php if ($bg_image) : ?>
            <div class="absolute inset-0 hidden lg:flex items-end justify-center overflow-hidden">
                <?= wp_get_attachment_image($bg_image['ID'], 'large', false, [
                    'class'   => 'max-h-full w-auto object-contain',
                    'alt'     => '',
                    'loading' => 'lazy',
                ]) ?>
            </div>
        <?php else : ?>
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

    <!-- Karty kalkulaček -->
    <div class="relative z-10 calc-grid container max-w-content mx-auto py-10 lg:py-[140px]">
        <?php foreach ($items as $i => $item) :
            if (empty($item['calculator'])) continue;
            $calc_post  = $item['calculator'];
            $calc_url   = get_permalink($calc_post->ID);
            $calc_title = get_the_title($calc_post->ID);
            $calc_desc  = !empty($item['custom_desc']) ? $item['custom_desc'] : get_the_excerpt($calc_post->ID);
            $calc_icon  = $item['custom_icon'] ?? null;
            $thumb_id   = get_post_thumbnail_id($calc_post->ID);
            $icon_order = ['calc-duchod', 'calc-sporeni', 'calc-inflace', 'calc-rozpocet'];
            $fallback   = $icon_order[$i] ?? 'calc-duchod';
        ?>
        <div class="calc-item bg-white rounded-[20px] shadow-card p-5 lg:bg-transparent lg:shadow-none lg:rounded-none lg:p-0">
            <div class="flex flex-col items-center gap-4 lg:flex-row lg:items-center lg:gap-5">
                <div class="flex-shrink-0 w-[60px] h-[60px] lg:w-[120px] lg:h-[120px] lg:bg-white lg:rounded-[20px] lg:shadow-card flex items-center justify-center relative overflow-hidden">
                    <?php if ($calc_icon) : ?>
                        <?= wp_get_attachment_image($calc_icon['ID'], [80, 80], false, [
                            'class'   => 'w-full h-full lg:w-20 lg:h-20 object-contain',
                            'alt'     => '',
                            'loading' => 'lazy',
                        ]) ?>
                    <?php elseif ($thumb_id) : ?>
                        <?= wp_get_attachment_image($thumb_id, [80, 80], false, [
                            'class'   => 'w-full h-full lg:w-20 lg:h-20 object-contain',
                            'alt'     => '',
                            'loading' => 'lazy',
                        ]) ?>
                    <?php else : ?>
                        <?php d1g1B::icon($fallback, 'w-full h-full lg:w-[60px] lg:h-[60px]'); ?>
                    <?php endif; ?>
                </div>

                <div class="flex flex-col gap-[15px] [&_p]:m-0 items-center text-center lg:items-start lg:text-left flex-1 lg:w-[248px] lg:flex-none">
                    <h3 class="font-semibold text-dark leading-[1.4]" style="font-size:20px;font-family:Montserrat,sans-serif;">
                        <?= esc_html($calc_title) ?>
                    </h3>
                    <?php if ($calc_desc) : ?>
                        <p class="text-dark/65 leading-[1.4]" style="font-size:14px;font-family:Montserrat,sans-serif;max-width:218px;">
                            <?= esc_html(wp_trim_words($calc_desc, 12, '...')) ?>
                        </p>
                    <?php endif; ?>
                    <?php d1g1B::primary_link( esc_html__( 'Vypočítat', 'cba' ), esc_url( $calc_url ) ); ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

</section>

<style>
.calc-grid { display:grid; grid-template-columns:1fr; gap:16px; }
@media (min-width:1024px) {
    .calc-grid { grid-template-columns:1fr 1fr; grid-template-rows:auto auto; grid-auto-flow:column; gap:80px clamp(60px,20vw,400px); }
}
</style>
