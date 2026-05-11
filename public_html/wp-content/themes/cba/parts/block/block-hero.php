<?php
if (!defined('ABSPATH')) exit;

$heading       = get_field('hero_heading');
$highlight     = get_field('hero_heading_highlight');
$text          = get_field('hero_text');
$source        = get_field('hero_source') ?: 'od České bankovní asociace';
$image         = get_field('hero_image');
$stats         = get_field('hero_stats');

if (!$heading) return;
?>
<section class="hero-section relative overflow-hidden" style="background:#13576b;min-height:747px;" aria-label="<?= esc_attr($heading) ?>">

    <!-- Decorative Union circles – LEFT side, matching Figma -->
    <div class="absolute pointer-events-none" style="left:-14.65%;top:-14.32%;width:59%;aspect-ratio:1;" aria-hidden="true">
        <div class="w-full h-full rounded-full" style="border:1px solid rgba(255,255,255,0.07);"></div>
    </div>
    <div class="absolute pointer-events-none" style="left:-10%;top:-8%;width:47%;aspect-ratio:1;" aria-hidden="true">
        <div class="w-full h-full rounded-full" style="border:1px solid rgba(255,255,255,0.05);"></div>
    </div>

    <!-- Background image – LEFT side (max ~52 % šířky) -->
    <?php if ($image) : ?>
    <div class="absolute left-0 top-0 bottom-0 w-[52%] pointer-events-none" aria-hidden="true">
        <?= wp_get_attachment_image($image['ID'], 'large', false, [
            'class'   => 'w-full h-full object-cover',
            'alt'     => '',
            'loading' => 'eager',
        ]) ?>
        <!-- Gradient: průhledná vlevo → teal vpravo (blend přes střed) -->
        <div class="absolute inset-0" style="background:linear-gradient(to right,rgba(19,87,107,0) 0%,rgba(19,87,107,0.15) 40%,rgba(19,87,107,0.7) 75%,#13576b 100%);"></div>
    </div>
    <?php endif; ?>

    <!-- Text content – RIGHT side -->
    <div class="container max-w-content mx-auto relative z-10 h-full" style="padding-top:99px;">
        <div class="flex justify-end">
            <div class="w-full lg:w-[55%]">
                <h1 class="font-bold text-vanilka leading-[0.9] mb-6 tracking-tight" style="font-size:clamp(3.5rem,6.9vw,100px);font-family:Montserrat,sans-serif;">
                    <?= esc_html($heading) ?>
                    <?php if ($highlight) : ?><br><?= esc_html($highlight) ?><?php endif; ?>
                </h1>
                <?php if ($text) : ?>
                    <p class="text-vanilka/90 leading-[1.2] mb-2" style="font-size:22px;font-family:Montserrat,sans-serif;max-width:560px;">
                        <?= esc_html($text) ?>
                    </p>
                <?php endif; ?>
                <p class="text-vanilka/70 tracking-wide text-sm mt-3" style="font-family:Montserrat,sans-serif;letter-spacing:0.14px;">
                    <?= esc_html($source) ?>
                </p>
            </div>
        </div>
    </div>

    <!-- Bottom 4 category cards -->
    <?php if (!empty($stats)) : ?>
    <div class="absolute bottom-0 left-0 right-0 z-10">
        <div class="container max-w-content mx-auto">
            <div class="flex items-stretch gap-6">
                <?php foreach (array_slice($stats, 0, 4) as $stat) : ?>
                    <div class="flex-1 flex flex-col items-center justify-center gap-5 py-9 px-4 rounded-t-[10px]" style="background:rgba(255,255,255,0.1);">
                        <?php if (!empty($stat['icon'])) : ?>
                            <div class="w-[54px] h-[54px] flex items-center justify-center">
                                <?= wp_get_attachment_image($stat['icon']['ID'], [54, 54], false, [
                                    'class'   => 'w-full h-full object-contain',
                                    'alt'     => '',
                                ]) ?>
                            </div>
                        <?php else : ?>
                            <div class="w-[54px] h-[54px] flex items-center justify-center" aria-hidden="true">
                                <svg class="w-10 h-10 text-white/60" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 7h6m-6 4h6m-3 4h.01M5 5a2 2 0 012-2h10a2 2 0 012 2v14a2 2 0 01-2 2H7a2 2 0 01-2-2V5z"/></svg>
                            </div>
                        <?php endif; ?>
                        <div class="font-semibold text-white text-center uppercase tracking-[0.08em]" style="font-size:16px;font-family:Montserrat,sans-serif;line-height:1.2;">
                            <?= esc_html($stat['label']) ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

</section>
