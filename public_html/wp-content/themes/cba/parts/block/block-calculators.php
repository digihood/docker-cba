<?php
if (!defined('ABSPATH')) exit;

$heading    = get_field('calc_heading');
$subheading = get_field('calc_subheading');
$items      = get_field('calc_items');
$btn        = get_field('calc_btn');
$center_img = get_field('calc_image');

if (empty($items)) return;

// Split items into left/right groups around center photo
$total  = count($items);
$left   = array_slice($items, 0, (int) ceil($total / 2));
$right  = array_slice($items, (int) ceil($total / 2));
?>
<section class="calculators-section py-16 lg:py-24 bg-white" aria-label="<?= esc_attr($heading ?: 'Kalkulačky') ?>">
    <div class="container max-w-content mx-auto">

        <!-- Section header -->
        <div class="section-header text-center mb-12 lg:mb-16">
            <?php if ($heading) : ?>
                <h2 class="text-dark font-bold text-h2-sm md:text-h2-md mb-3"><?= esc_html($heading) ?></h2>
            <?php endif; ?>
            <?php if ($subheading) : ?>
                <p class="text-gray-dark text-lg max-w-xl mx-auto"><?= esc_html($subheading) ?></p>
            <?php endif; ?>
        </div>

        <!-- Layout: [left cards] [center photo] [right cards] -->
        <div class="grid grid-cols-1 lg:grid-cols-[1fr_280px_1fr] xl:grid-cols-[1fr_320px_1fr] gap-6 items-stretch">

            <!-- Left cards -->
            <div class="flex flex-col gap-5">
                <?php foreach ($left as $item) :
                    $calc_post  = $item['calculator'] ?? null;
                    if (empty($calc_post)) continue;
                    $calc_url   = get_permalink($calc_post->ID);
                    $calc_title = get_the_title($calc_post->ID);
                    $calc_desc  = !empty($item['custom_desc']) ? $item['custom_desc'] : get_the_excerpt($calc_post->ID);
                    $calc_icon  = $item['custom_icon'] ?? null;
                    $thumb_id   = get_post_thumbnail_id($calc_post->ID);
                ?>
                    <article class="calc-card bg-white border border-gray-mid/50 rounded-2xl p-6 lg:p-8 shadow-card hover:shadow-card-hover transition-all duration-300 flex flex-col gap-4">
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 w-14 h-14 rounded-xl bg-primary/10 flex items-center justify-center">
                                <?php if ($calc_icon) : ?>
                                    <?= wp_get_attachment_image($calc_icon['ID'], [36, 36], false, ['class' => 'w-9 h-9 object-contain', 'alt' => '']) ?>
                                <?php elseif ($thumb_id) : ?>
                                    <?= wp_get_attachment_image($thumb_id, [36, 36], false, ['class' => 'w-9 h-9 object-contain', 'alt' => '']) ?>
                                <?php else : ?>
                                    <svg class="w-7 h-7 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 11h.01M12 11h.01M15 11h.01M5 7a2 2 0 012-2h10a2 2 0 012 2v10a2 2 0 01-2 2H7a2 2 0 01-2-2V7z"/></svg>
                                <?php endif; ?>
                            </div>
                            <div>
                                <h3 class="text-dark font-bold text-base lg:text-lg leading-snug mb-1">
                                    <?= esc_html($calc_title) ?>
                                </h3>
                                <?php if ($calc_desc) : ?>
                                    <p class="text-gray-dark text-sm leading-relaxed">
                                        <?= esc_html(wp_trim_words($calc_desc, 14, '...')) ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                        </div>
                        <a
                            href="<?= esc_url($calc_url) ?>"
                            class="button primary rounded-full !py-2.5 !px-6 text-sm font-semibold no-underline hover:no-underline inline-flex items-center gap-2 w-fit uppercase tracking-wide"
                        >
                            <?= esc_html__('Vypočítat', 'cba') ?>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                        </a>
                    </article>
                <?php endforeach; ?>
            </div>

            <!-- Center: photo or decorative element -->
            <div class="hidden lg:flex items-center justify-center">
                <?php if ($center_img) : ?>
                    <div class="w-full h-full min-h-[300px] rounded-2xl overflow-hidden">
                        <?= wp_get_attachment_image($center_img['ID'], 'medium_large', false, [
                            'class'   => 'w-full h-full object-cover object-top',
                            'alt'     => esc_attr($center_img['alt'] ?: ''),
                            'loading' => 'lazy',
                        ]) ?>
                    </div>
                <?php else : ?>
                    <!-- Decorative illustration when no photo is set -->
                    <div class="w-full min-h-[300px] rounded-2xl bg-gradient-to-b from-primary/10 to-primary/5 flex flex-col items-center justify-center gap-4 border border-primary/15">
                        <div class="w-20 h-20 rounded-2xl bg-primary/15 flex items-center justify-center">
                            <svg class="w-10 h-10 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 11h.01M12 11h.01M15 11h.01M5 7a2 2 0 012-2h10a2 2 0 012 2v10a2 2 0 01-2 2H7a2 2 0 01-2-2V7z"/></svg>
                        </div>
                        <p class="text-gray text-sm text-center px-4"><?= esc_html__('Kalkulačky zdarma', 'cba') ?></p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Right cards -->
            <div class="flex flex-col gap-5">
                <?php foreach ($right as $item) :
                    $calc_post  = $item['calculator'] ?? null;
                    if (empty($calc_post)) continue;
                    $calc_url   = get_permalink($calc_post->ID);
                    $calc_title = get_the_title($calc_post->ID);
                    $calc_desc  = !empty($item['custom_desc']) ? $item['custom_desc'] : get_the_excerpt($calc_post->ID);
                    $calc_icon  = $item['custom_icon'] ?? null;
                    $thumb_id   = get_post_thumbnail_id($calc_post->ID);
                ?>
                    <article class="calc-card bg-white border border-gray-mid/50 rounded-2xl p-6 lg:p-8 shadow-card hover:shadow-card-hover transition-all duration-300 flex flex-col gap-4">
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 w-14 h-14 rounded-xl bg-primary/10 flex items-center justify-center">
                                <?php if ($calc_icon) : ?>
                                    <?= wp_get_attachment_image($calc_icon['ID'], [36, 36], false, ['class' => 'w-9 h-9 object-contain', 'alt' => '']) ?>
                                <?php elseif ($thumb_id) : ?>
                                    <?= wp_get_attachment_image($thumb_id, [36, 36], false, ['class' => 'w-9 h-9 object-contain', 'alt' => '']) ?>
                                <?php else : ?>
                                    <svg class="w-7 h-7 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 11h.01M12 11h.01M15 11h.01M5 7a2 2 0 012-2h10a2 2 0 012 2v10a2 2 0 01-2 2H7a2 2 0 01-2-2V7z"/></svg>
                                <?php endif; ?>
                            </div>
                            <div>
                                <h3 class="text-dark font-bold text-base lg:text-lg leading-snug mb-1">
                                    <?= esc_html($calc_title) ?>
                                </h3>
                                <?php if ($calc_desc) : ?>
                                    <p class="text-gray-dark text-sm leading-relaxed">
                                        <?= esc_html(wp_trim_words($calc_desc, 14, '...')) ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                        </div>
                        <a
                            href="<?= esc_url($calc_url) ?>"
                            class="button primary rounded-full !py-2.5 !px-6 text-sm font-semibold no-underline hover:no-underline inline-flex items-center gap-2 w-fit uppercase tracking-wide"
                        >
                            <?= esc_html__('Vypočítat', 'cba') ?>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                        </a>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Optional bottom CTA -->
        <?php if ($btn) : ?>
            <div class="text-center mt-10">
                <a
                    href="<?= esc_url($btn['url']) ?>"
                    class="inline-flex items-center gap-2 text-primary border border-primary rounded-full px-8 py-3.5 font-semibold text-sm hover:bg-primary hover:text-white transition-all duration-300 no-underline"
                    <?= !empty($btn['target']) ? 'target="' . esc_attr($btn['target']) . '"' : '' ?>
                >
                    <?= esc_html($btn['title']) ?>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                </a>
            </div>
        <?php endif; ?>
    </div>
</section>
