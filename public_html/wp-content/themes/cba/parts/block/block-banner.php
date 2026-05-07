<?php
if (!defined('ABSPATH')) exit;

$bg_image  = get_field('banner_bg_image');
$heading   = get_field('banner_heading');
$text      = get_field('banner_text');
$btn1      = get_field('banner_btn_primary');
$features  = get_field('banner_features');

if (!$heading) return;
?>
<section class="banner-section relative overflow-hidden" style="height:920px;" aria-label="<?= esc_attr($heading) ?>">

    <!-- Background photo -->
    <?php if ($bg_image) : ?>
        <div class="absolute inset-0" aria-hidden="true">
            <?= wp_get_attachment_image($bg_image['ID'], 'large', false, [
                'class'   => 'w-full h-full object-cover',
                'alt'     => '',
                'loading' => 'lazy',
            ]) ?>
            <div class="absolute inset-0" style="background:rgba(19,87,107,0.55);"></div>
        </div>
    <?php else : ?>
        <div class="absolute inset-0 bg-dark" aria-hidden="true">
            <div class="absolute inset-0 opacity-10" style="background:radial-gradient(circle at 30% 50%,rgba(255,255,255,0.3) 0%,transparent 70%);"></div>
        </div>
    <?php endif; ?>

    <!-- Decorative Union circle (left side) -->
    <div class="absolute pointer-events-none" style="left:-19%;top:-12%;width:65%;aspect-ratio:1;" aria-hidden="true">
        <div class="w-full h-full rounded-full" style="border:1px solid rgba(255,255,255,0.08);"></div>
    </div>

    <!-- Content -->
    <div class="relative z-10 h-full flex flex-col items-center justify-center" style="padding-top:445px;">
        <h2 class="font-bold text-white text-center tracking-[-6px] w-full px-8" style="font-size:clamp(4rem,8vw,120px);font-family:Montserrat,sans-serif;line-height:1;">
            <?= esc_html($heading) ?>
        </h2>

        <!-- Frosted glass panel -->
        <div class="flex items-center gap-16 rounded-[10px] mt-8 px-12 py-9" style="background:rgba(255,255,255,0.1);width:1120px;max-width:100%;height:216px;">

            <!-- Description + CTA -->
            <div class="flex flex-col gap-5 flex-1 max-w-[392px]">
                <?php if ($text) : ?>
                    <p class="text-white text-lg leading-relaxed" style="font-family:Montserrat,sans-serif;">
                        <?= esc_html($text) ?>
                    </p>
                <?php endif; ?>
                <?php if ($btn1) : ?>
                    <a href="<?= esc_url($btn1['url']) ?>" class="inline-flex items-center justify-center bg-primary text-white rounded-[50px] px-5 py-2.5 text-xs font-bold uppercase tracking-[0.06em] no-underline hover:bg-primary-dark transition-colors w-fit" style="font-family:Montserrat,sans-serif;" <?= !empty($btn1['target']) ? 'target="' . esc_attr($btn1['target']) . '"' : '' ?>>
                        <?= esc_html($btn1['title']) ?>
                    </a>
                <?php else : ?>
                    <a href="<?= esc_url(home_url('/akademie')) ?>" class="inline-flex items-center justify-center bg-primary text-white rounded-[50px] px-5 py-2.5 text-xs font-bold uppercase tracking-[0.06em] no-underline hover:bg-primary-dark transition-colors w-fit" style="font-family:Montserrat,sans-serif;">
                        Chci se vzdělávat
                    </a>
                <?php endif; ?>
            </div>

            <!-- Vertical divider -->
            <div class="w-px self-stretch" style="background:rgba(255,255,255,0.2);"></div>

            <!-- Feature blocks -->
            <?php
            $default_features = [
                ['title' => 'PRÉMIOVÉ ČLÁNKY'],
                ['title' => 'VĚDOMOSTNÍ KVÍZY'],
                ['title' => 'CERTIFIKÁT DOKONČENÍ'],
            ];
            $feat_list = !empty($features) ? $features : $default_features;
            foreach ($feat_list as $feat) :
                if (empty($feat['title'])) continue;
            ?>
                <div class="flex flex-col items-center gap-2 text-white text-center" style="min-width:108px;">
                    <div class="w-[60px] h-[60px] rounded-[10px] bg-primary flex-shrink-0"></div>
                    <div class="font-semibold text-center uppercase tracking-[0.06em]" style="font-size:16px;font-family:Montserrat,sans-serif;line-height:1.2;">
                        <?= esc_html($feat['title']) ?>
                    </div>
                </div>
            <?php endforeach; ?>

        </div>
    </div>
</section>
