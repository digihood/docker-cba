<?php
if (!defined('ABSPATH')) exit;

$badge          = get_field('hero_badge');
$heading        = get_field('hero_heading');
$highlight      = get_field('hero_heading_highlight');
$text           = get_field('hero_text');
$btn_primary    = get_field('hero_btn_primary');
$btn_secondary  = get_field('hero_btn_secondary');
$image          = get_field('hero_image');
$stats          = get_field('hero_stats');

if (!$heading) return;

$heading_html = esc_html($heading);
if ($highlight) {
    $heading_html .= ' <span class="text-primary">' . esc_html($highlight) . '</span>';
}
?>
<section class="hero-section relative overflow-hidden bg-dark flex flex-col" style="min-height: clamp(560px, 90vh, 800px);" aria-label="<?= esc_attr($heading) ?>">

    <!-- Diamond-clipped image (left portion) -->
    <?php if ($image) : ?>
        <div class="absolute left-0 top-0 bottom-0 w-[58%] pointer-events-none">
            <div class="absolute inset-0 overflow-hidden" style="clip-path: polygon(0 0, 78% 0, 100% 50%, 78% 100%, 0 100%)">
                <?= wp_get_attachment_image($image['ID'], 'large', false, [
                    'class'   => 'w-full h-full object-cover',
                    'alt'     => esc_attr($image['alt'] ?: $heading),
                    'loading' => 'eager',
                ]) ?>
                <div class="absolute inset-0 bg-dark/25"></div>
            </div>
        </div>
    <?php else : ?>
        <!-- Decorative shape without image -->
        <div class="absolute left-0 top-0 bottom-0 w-[58%] pointer-events-none">
            <div class="absolute inset-0 overflow-hidden" style="clip-path: polygon(0 0, 78% 0, 100% 50%, 78% 100%, 0 100%)">
                <div class="w-full h-full bg-dark-muted"></div>
                <div class="absolute inset-0 flex items-center justify-center">
                    <svg class="w-32 h-32 text-white/10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="0.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Subtle background decorations -->
    <div class="absolute inset-0 pointer-events-none overflow-hidden">
        <div class="absolute top-0 right-0 w-80 h-80 bg-primary opacity-[0.06] rounded-full blur-3xl"></div>
    </div>

    <!-- Content: right side -->
    <div class="container max-w-content mx-auto relative z-10 flex-1 flex items-center">
        <div class="w-full flex justify-end">
            <div class="w-full lg:w-[48%] py-20 lg:py-28 lg:pl-8">

                <?php if ($badge) : ?>
                    <div class="inline-flex items-center gap-2 bg-primary/15 text-primary border border-primary/25 rounded-full px-4 py-2 text-sm font-semibold mb-6">
                        <span class="w-2 h-2 bg-primary rounded-full"></span>
                        <?= esc_html($badge) ?>
                    </div>
                <?php endif; ?>

                <h1 class="text-white font-bold leading-none mb-5" style="font-size: clamp(2.8rem, 6.5vw, 5.5rem); line-height: 1.04;">
                    <?= $heading_html ?>
                </h1>

                <?php if ($text) : ?>
                    <p class="text-white/70 text-lg leading-relaxed mb-3 max-w-md">
                        <?= esc_html($text) ?>
                    </p>
                <?php endif; ?>

                <?php if ($btn_primary || $btn_secondary) : ?>
                    <div class="flex flex-wrap gap-4 mt-8">
                        <?php if ($btn_primary) : ?>
                            <a
                                href="<?= esc_url($btn_primary['url']) ?>"
                                class="button primary rounded-full !py-4 !px-8 text-base font-semibold no-underline hover:no-underline inline-flex items-center gap-2"
                                <?= !empty($btn_primary['target']) ? 'target="' . esc_attr($btn_primary['target']) . '"' : '' ?>
                            >
                                <?= esc_html($btn_primary['title']) ?>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                            </a>
                        <?php endif; ?>
                        <?php if ($btn_secondary) : ?>
                            <a
                                href="<?= esc_url($btn_secondary['url']) ?>"
                                class="inline-flex items-center gap-2 text-white border border-white/30 rounded-full py-4 px-8 text-base font-semibold hover:bg-white/10 transition-colors duration-300 no-underline"
                                <?= !empty($btn_secondary['target']) ? 'target="' . esc_attr($btn_secondary['target']) . '"' : '' ?>
                            >
                                <?= esc_html($btn_secondary['title']) ?>
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Bottom: stats as icon cards -->
    <?php if (!empty($stats)) : ?>
        <div class="relative z-10 border-t border-white/10 bg-dark-card/60">
            <div class="container max-w-content mx-auto">
                <div class="grid grid-cols-2 lg:grid-cols-4 divide-x divide-white/10">
                    <?php foreach ($stats as $stat) : ?>
                        <div class="flex items-center gap-4 px-6 py-5">
                            <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-primary/20 flex items-center justify-center">
                                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                            </div>
                            <div>
                                <div class="text-xl font-bold text-primary leading-none"><?= esc_html($stat['number']) ?></div>
                                <div class="text-white/55 text-xs mt-0.5 leading-snug"><?= esc_html($stat['label']) ?></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
</section>
