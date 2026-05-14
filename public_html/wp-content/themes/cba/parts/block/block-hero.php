<?php
if (!defined('ABSPATH')) exit;

$heading   = get_field('hero_heading');
$highlight = get_field('hero_heading_highlight');
$text      = get_field('hero_text');
$source    = get_field('hero_source') ?: 'od České bankovní asociace';
$image     = get_field('hero_image');
$stats     = get_field('hero_stats');

if (!$heading) return;

$image_url = $image ? wp_get_attachment_image_url($image['ID'], 'full') : '';
?>
<section class="hero-section relative overflow-hidden bg-dark" aria-label="<?= esc_attr($heading) ?>">

    <!-- Union shape with hero image -->
    <div class="absolute bottom-0 left-0 h-full pointer-events-none" style="aspect-ratio:860/747;" aria-hidden="true">
        <svg viewBox="0 0 860 747" class="w-full h-full" fill="none" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
            <defs>
                <clipPath id="union-clip">
                    <path d="M-159.078 -55.1933C-90.0007 -124.269 21.9954 -124.269 91.0725 -55.1924L324.514 178.246L557.93 -55.166C627.007 -124.242 739.003 -124.242 808.08 -55.166L808.195 -55.0527C877.272 14.0237 877.272 126.019 808.195 195.096L574.779 428.508L808.179 661.905C877.256 730.982 877.256 842.976 808.179 912.053L808.066 912.167C738.988 981.243 626.992 981.243 557.914 912.167L324.514 678.77L91.0881 912.193C22.0109 981.27 -89.986 981.27 -159.063 912.193L-159.177 912.08C-228.254 843.004 -228.254 731.008 -159.177 661.932L74.2492 428.508L-159.192 195.069C-228.269 125.993 -228.269 13.9973 -159.192 -55.0791L-159.078 -55.1933Z"/>
                </clipPath>
            </defs>
            <?php if ($image_url) : ?>
                <image href="<?= esc_url($image_url) ?>" x="0" y="0" width="860" height="747" preserveAspectRatio="xMidYMid slice" clip-path="url(#union-clip)"/>
            <?php else : ?>
                <path d="M-159.078 -55.1933C-90.0007 -124.269 21.9954 -124.269 91.0725 -55.1924L324.514 178.246L557.93 -55.166C627.007 -124.242 739.003 -124.242 808.08 -55.166L808.195 -55.0527C877.272 14.0237 877.272 126.019 808.195 195.096L574.779 428.508L808.179 661.905C877.256 730.982 877.256 842.976 808.179 912.053L808.066 912.167C738.988 981.243 626.992 981.243 557.914 912.167L324.514 678.77L91.0881 912.193C22.0109 981.27 -89.986 981.27 -159.063 912.193L-159.177 912.08C-228.254 843.004 -228.254 731.008 -159.177 661.932L74.2492 428.508L-159.192 195.069C-228.269 125.993 -228.269 13.9973 -159.192 -55.0791L-159.078 -55.1933Z" fill="rgba(255,255,255,0.05)"/>
            <?php endif; ?>
        </svg>
    </div>

    <!-- Text content -->
    <div class="container max-w-content mx-auto relative z-10 flex flex-col" style="min-height:747px;">
        <div class="flex justify-end flex-grow" style="padding-top:99px;">
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

        <!-- Bottom category cards -->
        <?php if (!empty($stats)) : ?>
        <div class="pb-[160px] lg:pb-[84px]">
            <div class="flex flex-col lg:flex-row items-stretch justify-center gap-4 lg:gap-6">
                <?php foreach (array_slice($stats, 0, 4) as $stat) : ?>
                    <div class="flex flex-row lg:flex-col items-center gap-4 lg:gap-5 rounded-xl lg:rounded-t-[10px] lg:rounded-b-none backdrop-blur-md bg-white/10 px-5 py-4 lg:w-[287px] lg:h-[216px] lg:px-[45px] lg:py-[37px] lg:justify-center">
                        <?php if (!empty($stat['icon'])) : ?>
                            <div class="hidden lg:flex w-[54px] h-[54px] items-center justify-center flex-shrink-0">
                                <?= wp_get_attachment_image($stat['icon']['ID'], [54, 54], false, [
                                    'class' => 'w-full h-full object-contain',
                                    'alt'   => '',
                                ]) ?>
                            </div>
                        <?php else : ?>
                            <div class="hidden lg:flex w-[54px] h-[54px] items-center justify-center flex-shrink-0" aria-hidden="true">
                                <svg class="w-10 h-10 text-white/60" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 7h6m-6 4h6m-3 4h.01M5 5a2 2 0 012-2h10a2 2 0 012 2v14a2 2 0 01-2 2H7a2 2 0 01-2-2V5z"/></svg>
                            </div>
                        <?php endif; ?>
                        <div class="font-semibold text-white uppercase tracking-[0.05em] text-center" style="font-size:16px;font-family:Montserrat,sans-serif;line-height:1.2;">
                            <?= esc_html($stat['label']) ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>

</section>
