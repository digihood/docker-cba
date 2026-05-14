<?php
if (!defined('ABSPATH')) exit;
$thumb_id = get_post_thumbnail_id();
$excerpt  = get_the_excerpt();
?>
<article class="blog-card bg-[#fff3db] rounded-[20px] overflow-hidden flex flex-col">
    <a href="<?= esc_url(get_permalink()) ?>" class="block h-[200px] lg:h-[300px] overflow-hidden">
        <?php if ($thumb_id) : ?>
            <?= wp_get_attachment_image($thumb_id, 'medium_large', false, [
                'class'   => 'w-full h-full object-cover',
                'alt'     => esc_attr(get_the_title()),
                'loading' => 'lazy',
            ]) ?>
        <?php else : ?>
            <div class="w-full h-full bg-secondary/20"></div>
        <?php endif; ?>
    </a>
    <div class="flex flex-col gap-5 p-[30px] flex-1">
        <h3 class="font-semibold text-dark leading-[1.2]" style="font-size:18px;font-family:Montserrat,sans-serif;">
            <a href="<?= esc_url(get_permalink()) ?>" class="text-dark no-underline hover:text-primary transition-colors">
                <?= esc_html(get_the_title()) ?>
            </a>
        </h3>
        <?php if ($excerpt) : ?>
            <p class="text-dark leading-[1.4] line-clamp-3" style="font-size:12px;font-family:Montserrat,sans-serif;">
                <?= esc_html(wp_trim_words($excerpt, 30, '...')) ?>
            </p>
        <?php endif; ?>
        <div class="mt-auto">
            <a href="<?= esc_url(get_permalink()) ?>" class="inline-flex items-center justify-center bg-secondary px-5 py-2.5 rounded-full text-white text-xs font-bold uppercase tracking-[0.6px] no-underline hover:opacity-80 transition-opacity" style="font-family:Montserrat,sans-serif;">
                <?= esc_html__('Přečíst', 'cba') ?>
            </a>
        </div>
    </div>
</article>
