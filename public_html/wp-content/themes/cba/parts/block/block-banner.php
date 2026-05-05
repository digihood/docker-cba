<?php
if (!defined('ABSPATH')) exit;

$bg_image  = get_field('banner_bg_image');
$badge     = get_field('banner_badge');
$heading   = get_field('banner_heading');
$text      = get_field('banner_text');
$btn1      = get_field('banner_btn_primary');
$btn2      = get_field('banner_btn_secondary');
$features  = get_field('banner_features');

if (!$heading) return;
?>
<section class="banner-section relative overflow-hidden bg-primary" aria-label="<?= esc_attr($heading) ?>">

    <div class="grid grid-cols-1 lg:grid-cols-2 min-h-[440px] lg:min-h-[520px]">

        <!-- Left: V-shaped photo cutout -->
        <div class="relative overflow-hidden min-h-[280px] lg:min-h-0">
            <?php if ($bg_image) : ?>
                <!-- Photo clipped to right-pointing arrow/V shape -->
                <div class="absolute inset-0 overflow-hidden" style="clip-path: polygon(0 0, 78% 0, 100% 50%, 78% 100%, 0 100%)">
                    <?= wp_get_attachment_image($bg_image['ID'], 'large', false, [
                        'class'   => 'w-full h-full object-cover object-center',
                        'alt'     => esc_attr($bg_image['alt'] ?: ''),
                        'loading' => 'lazy',
                    ]) ?>
                    <!-- Salmon tint overlay for cohesion -->
                    <div class="absolute inset-0 bg-primary/20"></div>
                </div>
            <?php else : ?>
                <!-- Decorative diamond shape when no image -->
                <div class="absolute inset-0" style="clip-path: polygon(0 0, 78% 0, 100% 50%, 78% 100%, 0 100%)">
                    <div class="w-full h-full bg-primary-dark flex items-center justify-center">
                        <svg class="w-32 h-32 text-white/20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="0.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Right: Content -->
        <div class="relative z-10 flex flex-col justify-center py-14 lg:py-20 px-8 lg:px-12 xl:px-16">

            <?php if ($badge) : ?>
                <div class="inline-flex items-center gap-2 bg-white/20 text-white border border-white/30 rounded-full px-4 py-2 text-sm font-semibold mb-6 w-fit">
                    <?= esc_html($badge) ?>
                </div>
            <?php endif; ?>

            <h2 class="text-white font-bold leading-tight mb-5" style="font-size: clamp(1.9rem, 4vw, 3rem); line-height: 1.1;">
                <?= esc_html($heading) ?>
            </h2>

            <?php if ($text) : ?>
                <p class="text-white/80 text-lg leading-relaxed mb-8 max-w-md"><?= esc_html($text) ?></p>
            <?php endif; ?>

            <!-- Features list -->
            <?php if (!empty($features)) : ?>
                <ul class="space-y-3 mb-10">
                    <?php foreach ($features as $feature) :
                        if (empty($feature['title'])) continue;
                    ?>
                        <li class="flex items-center gap-3">
                            <div class="flex-shrink-0 w-6 h-6 rounded-full bg-white/25 flex items-center justify-center">
                                <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <span class="text-white font-semibold text-sm uppercase tracking-wide"><?= esc_html($feature['title']) ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>

            <!-- CTA buttons -->
            <?php if ($btn1 || $btn2) : ?>
                <div class="flex flex-wrap gap-4">
                    <?php if ($btn1) : ?>
                        <a
                            href="<?= esc_url($btn1['url']) ?>"
                            class="inline-flex items-center gap-2 bg-white text-primary rounded-full py-4 px-8 font-semibold text-base no-underline hover:bg-white/90 hover:no-underline transition-colors duration-300"
                            <?= !empty($btn1['target']) ? 'target="' . esc_attr($btn1['target']) . '"' : '' ?>
                        >
                            <?= esc_html($btn1['title']) ?>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                        </a>
                    <?php endif; ?>
                    <?php if ($btn2) : ?>
                        <a
                            href="<?= esc_url($btn2['url']) ?>"
                            class="inline-flex items-center gap-2 text-white border border-white/50 rounded-full py-4 px-8 font-semibold text-base no-underline hover:bg-white/15 transition-colors duration-300"
                            <?= !empty($btn2['target']) ? 'target="' . esc_attr($btn2['target']) . '"' : '' ?>
                        >
                            <?= esc_html($btn2['title']) ?>
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
