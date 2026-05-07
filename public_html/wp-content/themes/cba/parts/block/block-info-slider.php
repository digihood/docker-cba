<?php
if (!defined('ABSPATH')) exit;

$heading    = get_field('slider_heading');
$subheading = get_field('slider_subheading');
$items      = get_field('slider_items');

if (empty($items)) return;

$unique_id = 'stats-' . uniqid();
?>
<section class="stats-section py-16 lg:py-24 overflow-hidden" style="background:#fff3db;" aria-label="<?= esc_attr($heading ?: 'Statistiky') ?>">
    <div class="container max-w-content mx-auto">

        <?php if ($heading || $subheading) : ?>
            <div class="text-center mb-10 lg:mb-14">
                <?php if ($heading) : ?>
                    <h2 class="font-bold text-dark mb-4" style="font-size:44px;font-family:Montserrat,sans-serif;line-height:1;"><?= esc_html($heading) ?></h2>
                <?php endif; ?>
                <?php if ($subheading) : ?>
                    <p class="text-dark/70 text-lg" style="font-family:Montserrat,sans-serif;"><?= esc_html($subheading) ?></p>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <!-- Stats cards carousel -->
        <div class="stats-carousel relative" id="<?= esc_attr($unique_id) ?>">
            <div class="flex gap-6 items-center justify-center flex-wrap lg:flex-nowrap">
                <?php foreach ($items as $i => $item) :
                    if (empty($item['title'])) continue;
                    $is_active = ($i === 0);
                    // Extrahuj číslo z titulku pro vizuální zvýraznění
                    preg_match('/^(\d+)/', $item['title'], $num_match);
                    $number = $num_match[1] ?? '';
                    $rest   = $number ? ltrim(substr($item['title'], strlen($number))) : $item['title'];
                ?>
                    <div class="stats-card flex-1 min-w-[260px] max-w-[380px] rounded-[25px] p-10 transition-all duration-400 <?= $is_active ? 'bg-white shadow-card' : 'bg-white/40' ?>">
                        <?php if (!empty($item['icon'])) : ?>
                            <div class="mb-6">
                                <?= wp_get_attachment_image($item['icon']['ID'], [54, 54], false, ['class' => 'w-14 h-14 object-contain', 'alt' => '']) ?>
                            </div>
                        <?php endif; ?>

                        <div class="mb-4" style="font-family:Montserrat,sans-serif;">
                            <?php if ($number) : ?>
                                <span class="font-semibold" style="font-size:64px;line-height:1;letter-spacing:-3.2px;color:#ff6b6b;"><?= esc_html($number) ?></span>
                                <span class="font-semibold text-dark" style="font-size:44px;line-height:1;letter-spacing:-2.2px;"><?= esc_html($rest) ?></span>
                            <?php else : ?>
                                <span class="font-semibold text-dark" style="font-size:44px;line-height:1;"><?= esc_html($item['title']) ?></span>
                            <?php endif; ?>
                        </div>

                        <?php if (!empty($item['text'])) : ?>
                            <p class="text-dark/80 leading-relaxed mb-6" style="font-size:16px;font-family:Montserrat,sans-serif;">
                                <?= esc_html($item['text']) ?>
                            </p>
                        <?php endif; ?>

                        <?php if ($is_active && !empty($item['link'])) : ?>
                            <a href="<?= esc_url($item['link']['url']) ?>" class="inline-flex items-center justify-center bg-primary text-white rounded-[50px] px-7 py-3.5 text-xs font-semibold uppercase tracking-[0.05em] no-underline hover:bg-primary-dark transition-colors" style="font-family:Montserrat,sans-serif;">
                                <?= esc_html($item['link']['title'] ?: 'Jak na penzi') ?>
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Dots -->
            <?php if (count($items) > 1) : ?>
                <div class="flex justify-center gap-2 mt-8">
                    <?php foreach ($items as $i => $item) : ?>
                        <span class="inline-block rounded-full transition-all duration-300 <?= $i === 0 ? 'w-6 h-2.5 bg-dark' : 'w-2.5 h-2.5 bg-dark/30' ?>"></span>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

    </div>
</section>
