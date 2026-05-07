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
<section class="hero-section relative overflow-hidden bg-dark" style="min-height:747px;" aria-label="<?= esc_attr($heading) ?>">

    <!-- Decorative circle - Union shape -->
    <div class="absolute pointer-events-none overflow-hidden" style="left:-15%;top:-14%;width:65%;aspect-ratio:1;" aria-hidden="true">
        <div class="w-full h-full rounded-full opacity-[0.08]" style="background:radial-gradient(circle,rgba(255,255,255,0.5) 0%,rgba(255,255,255,0) 70%);"></div>
        <div class="absolute inset-0 rounded-full" style="border:1px solid rgba(255,255,255,0.06);"></div>
    </div>
    <div class="absolute pointer-events-none" style="left:-8%;top:-8%;width:52%;aspect-ratio:1;" aria-hidden="true">
        <div class="w-full h-full rounded-full" style="border:1px solid rgba(255,255,255,0.05);"></div>
    </div>

    <!-- Background image (right side) -->
    <?php if ($image) : ?>
    <div class="absolute right-0 top-0 bottom-0 w-[52%] pointer-events-none" aria-hidden="true">
        <?= wp_get_attachment_image($image['ID'], 'large', false, [
            'class'   => 'w-full h-full object-cover',
            'alt'     => '',
            'loading' => 'eager',
        ]) ?>
        <div class="absolute inset-0" style="background:linear-gradient(to right,#13576b 0%,rgba(19,87,107,0.75) 25%,rgba(19,87,107,0.2) 60%,transparent 85%);"></div>
    </div>
    <?php endif; ?>

    <!-- Content: text top-right -->
    <div class="container max-w-content mx-auto relative z-10 h-full" style="padding-top:99px;">
        <div class="flex justify-end">
            <div class="w-full lg:w-[55%]">
                <h1 class="font-bold text-vanilka leading-[0.9] mb-6 tracking-tight" style="font-size:clamp(3.5rem,6.9vw,100px);font-family:Montserrat,sans-serif;">
                    <?= esc_html($heading) ?>
                    <?php if ($highlight) : ?><br><?= esc_html($highlight) ?><?php endif; ?>
                </h1>
                <?php if ($text) : ?>
                    <p class="text-vanilka/90 leading-relaxed mb-2" style="font-size:22px;font-family:Montserrat,sans-serif;max-width:560px;">
                        <?= esc_html($text) ?>
                    </p>
                <?php endif; ?>
                <p class="text-vanilka/70 tracking-wide text-sm" style="font-family:Montserrat,sans-serif;">
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
                                <?= wp_get_attachment_image($stat['icon']['ID'], [54, 54], false, ['class' => 'w-full h-full object-contain', 'alt' => '']) ?>
                            </div>
                        <?php else : ?>
                            <div class="w-[54px] h-[54px] rounded-xl flex items-center justify-center" style="background:rgba(255,255,255,0.15);">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 7h6m-6 4h6m-3 4h.01M5 5a2 2 0 012-2h10a2 2 0 012 2v14a2 2 0 01-2 2H7a2 2 0 01-2-2V5z"/></svg>
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
