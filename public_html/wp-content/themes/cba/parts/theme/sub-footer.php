<?php
if (!defined('ABSPATH')) exit;

$experts_heading = function_exists('get_field') ? get_field('experts_heading', 'option') : '';
$experts_desc    = function_exists('get_field') ? get_field('experts_desc', 'option') : '';
$experts_btn_url = function_exists('get_field') ? get_field('experts_btn_url', 'option') : home_url('/otazky');
?>

<section class="experts-cta-section text-center" style="background:#ff6b6b;padding:80px 0;" aria-label="Zeptejte se odborníků">
    <div class="container max-w-content mx-auto">
        <h2 class="font-semibold text-white mb-4" style="font-size:44px;font-family:Montserrat,sans-serif;line-height:1;">
            <?= esc_html($experts_heading ?: 'Zeptejte se odborníků') ?>
        </h2>
        <p class="text-white/85 text-lg mb-8" style="font-family:Montserrat,sans-serif;">
            <?= esc_html($experts_desc ?: 'Na vaše otázky odpovídají odborníci z ČBA a z bankovních institucí') ?>
        </p>
        <?php d1g1B::white_link( 'Přejít do Q&amp;A', esc_url($experts_btn_url ?: home_url('/otazky')) ); ?>
    </div>
</section>

<footer class="site-footer" style="background:#a9936d;" itemscope itemtype="http://schema.org/WPFooter">
    <div class="container max-w-content mx-auto" style="padding:100px 0;">
        <div class="grid grid-cols-1 lg:grid-cols-[220px_1fr_1fr_1fr_1fr] gap-10 lg:gap-12">

            <!-- Logo column -->
            <div class="footer-brand flex flex-col gap-8">
                <div class="flex items-center gap-3">
                    <div class="flex flex-col gap-0.5" aria-hidden="true">
                        <div class="flex gap-0.5">
                            <span class="w-[18px] h-[18px] rounded-[3px] bg-primary flex items-center justify-center text-white text-[10px] font-bold leading-none">+</span>
                            <span class="w-[18px] h-[18px] rounded-[3px] bg-primary flex items-center justify-center text-white text-[10px] font-bold leading-none">-</span>
                        </div>
                        <div class="flex gap-0.5">
                            <span class="w-[18px] h-[18px] rounded-[3px] bg-primary flex items-center justify-center text-white text-[10px] font-bold leading-none">&times;</span>
                            <span class="w-[18px] h-[18px] rounded-[3px] bg-primary flex items-center justify-center text-white text-[10px] font-bold leading-none">=</span>
                        </div>
                    </div>
                    <div class="text-vanilka font-bold leading-none tracking-tight" style="font-size:18px;font-family:Montserrat,sans-serif;">MÁŠ<br>TO<br>SPOČÍTANÝ?</div>
                </div>

                <?php
                $cba_logo = function_exists('get_field') ? get_field('footer_cba_logo', 'option') : null;
                if (!empty($cba_logo['ID'])) :
                    echo wp_get_attachment_image($cba_logo['ID'], 'medium', false, ['class' => 'h-16 w-auto object-contain', 'alt' => 'Česká bankovní asociace', 'loading' => 'lazy']);
                else :
                    $cba_logo_id = get_posts(['post_type' => 'attachment', 'name' => 'logo-cba-icon', 'posts_per_page' => 1, 'fields' => 'ids']);
                    if (!empty($cba_logo_id)) :
                        echo wp_get_attachment_image($cba_logo_id[0], 'medium', false, ['class' => 'h-16 w-auto object-contain', 'alt' => 'Česká bankovní asociace', 'loading' => 'lazy']);
                    endif;
                endif; ?>

                <p class="text-vanilka/60 leading-relaxed" style="font-family:'DM Sans',sans-serif;font-size:18px;line-height:30px;">
                    Copyright &copy; <?= date('Y') ?><br>All Rights Reserved
                </p>
            </div>

            <?php
            $footer_sections = [
                ['title' => 'Témata',              'location' => 'footer-1'],
                ['title' => 'Kalkulačky',           'location' => 'footer-2'],
                ['title' => 'Máš to spočítaný?',   'location' => 'footer-3'],
                ['title' => 'Follow us',            'location' => 'footer-4'],
            ];
            foreach ($footer_sections as $section) :
            ?>
                <div class="footer-col">
                    <h4 class="font-bold text-vanilka mb-6" style="font-size:20px;line-height:20px;font-family:'DM Sans',sans-serif;">
                        <?= esc_html($section['title']) ?>
                    </h4>
                    <?php if ($section['location'] !== 'footer-4') :
                        if (has_nav_menu($section['location'])) : ?>
                            <nav>
                                <?php wp_nav_menu([
                                    'theme_location' => $section['location'],
                                    'container'      => false,
                                    'items_wrap'     => '<ul class="space-y-4">%3$s</ul>',
                                    'depth'          => 1,
                                    'fallback_cb'    => false,
                                    'link_before'    => '',
                                    'link_after'     => '',
                                ]); ?>
                            </nav>
                        <?php elseif (is_active_sidebar($section['location'])) : ?>
                            <div class="footer-widget-wrap" style="font-family:'DM Sans',sans-serif;font-size:18px;line-height:18px;">
                                <?php dynamic_sidebar($section['location']); ?>
                            </div>
                        <?php else : ?>
                            <nav>
                                <ul class="space-y-4">
                                    <li><a href="#" class="text-vanilka/80 no-underline hover:text-vanilka transition-colors" style="font-family:'DM Sans',sans-serif;font-size:18px;line-height:18px;">—</a></li>
                                </ul>
                            </nav>
                        <?php endif; ?>
                    <?php endif; ?>

                    <?php if ($section['location'] === 'footer-4') :
                        $social_icons_acf = function_exists('get_field') ? get_field('social_icons', 'option') : [];
                        $social_defaults = [
                            ['name' => 'Facebook',  'url' => '#'],
                            ['name' => 'Twitter',   'url' => '#'],
                            ['name' => 'Instagram', 'url' => '#'],
                            ['name' => 'LinkedIn',  'url' => '#'],
                            ['name' => 'YouTube',   'url' => '#'],
                        ];
                        $social_list = !empty($social_icons_acf) ? $social_icons_acf : $social_defaults;
                        $social_glyphs = [
                            'facebook'  => '<path d="M22 12a10 10 0 10-11.56 9.88v-7H8v-2.88h2.44V9.86c0-2.41 1.43-3.74 3.62-3.74 1.05 0 2.15.19 2.15.19v2.36h-1.21c-1.19 0-1.56.74-1.56 1.5v1.8h2.65l-.42 2.88h-2.23v7A10 10 0 0022 12z"/>',
                            'twitter'   => '<path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>',
                            'instagram' => '<path d="M12 2.16c3.2 0 3.58.01 4.85.07 1.17.05 1.8.25 2.23.41.56.22.96.48 1.38.9.42.42.68.82.9 1.38.16.42.36 1.06.41 2.23.06 1.27.07 1.65.07 4.85s-.01 3.58-.07 4.85c-.05 1.17-.25 1.8-.41 2.23-.22.56-.48.96-.9 1.38-.42.42-.82.68-1.38.9-.42.16-1.06.36-2.23.41-1.27.06-1.65.07-4.85.07s-3.58-.01-4.85-.07c-1.17-.05-1.8-.25-2.23-.41a3.7 3.7 0 01-1.38-.9 3.7 3.7 0 01-.9-1.38c-.16-.42-.36-1.06-.41-2.23C2.17 15.58 2.16 15.2 2.16 12s.01-3.58.07-4.85c.05-1.17.25-1.8.41-2.23.22-.56.48-.96.9-1.38.42-.42.82-.68 1.38-.9.42-.16 1.06-.36 2.23-.41C8.42 2.17 8.8 2.16 12 2.16zm0 5.4a4.44 4.44 0 100 8.88 4.44 4.44 0 000-8.88zm5.66-.34a1.04 1.04 0 11-2.08 0 1.04 1.04 0 012.08 0zM12 9.44a2.56 2.56 0 110 5.12 2.56 2.56 0 010-5.12z"/>',
                            'linkedin'  => '<path d="M19 3H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2V5a2 2 0 00-2-2zM8.34 18.34H5.67V9.67h2.67v8.67zM7 8.51a1.55 1.55 0 11.01-3.1A1.55 1.55 0 017 8.51zm11.34 9.83h-2.66v-4.22c0-1.01-.02-2.3-1.4-2.3s-1.62 1.1-1.62 2.23v4.29h-2.66V9.67h2.55v1.18h.04c.36-.67 1.22-1.38 2.51-1.38 2.69 0 3.19 1.77 3.19 4.07v4.8z"/>',
                            'youtube'   => '<path d="M23 7.2a3 3 0 00-2.1-2.1C19.1 4.6 12 4.6 12 4.6s-7.1 0-8.9.5A3 3 0 001 7.2C.5 9 .5 12 .5 12s0 3 .5 4.8a3 3 0 002.1 2.1c1.8.5 8.9.5 8.9.5s7.1 0 8.9-.5a3 3 0 002.1-2.1C23.5 15 23.5 12 23.5 12s0-3-.5-4.8zM9.6 15.4V8.6L15.5 12l-5.9 3.4z"/>',
                        ];
                    ?>
                        <div class="flex flex-col gap-4 mt-2">
                            <?php foreach ($social_list as $social) :
                                if (empty($social['name'])) continue;
                                $name_l = strtolower($social['name'] ?? '');
                                $url_l  = strtolower($social['url'] ?? '#');
                                $glyph  = null;
                                foreach ($social_glyphs as $key => $g) {
                                    if (strpos($name_l, $key) !== false || strpos($url_l, $key) !== false) {
                                        $glyph = $g;
                                        break;
                                    }
                                }
                            ?>
                                <a href="<?= esc_url($social['url'] ?? '#') ?>" class="flex items-center gap-3 no-underline group" target="_blank" rel="noopener noreferrer" aria-label="<?= esc_attr($social['name'] ?? '') ?>">
                                    <div class="flex items-center justify-center w-7 h-7 rounded flex-shrink-0" style="background:#ff6b6b;">
                                        <?php if ($glyph) : ?>
                                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24"><?= $glyph ?></svg>
                                        <?php else : ?>
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 14a3.5 3.5 0 005 0l4-4a3.5 3.5 0 00-5-5l-1 1m1 7a3.5 3.5 0 00-5 0l-4 4a3.5 3.5 0 005 5l1-1"/></svg>
                                        <?php endif; ?>
                                    </div>
                                    <span class="text-vanilka/80 group-hover:text-vanilka transition-colors" style="font-family:'DM Sans',sans-serif;font-size:18px;line-height:18px;">
                                        <?= esc_html($social['name'] ?? '') ?>
                                    </span>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>

        </div>
    </div>
</footer>

<style>
.site-footer .footer-col nav ul { list-style:none; padding:0; margin:0; }
.site-footer .footer-col nav ul li { margin-bottom:12px; }
.site-footer .footer-col nav ul li a {
    color:rgba(255,243,219,0.8);
    text-decoration:none;
    font-family:'DM Sans',sans-serif;
    font-size:18px;
    line-height:18px;
    transition:color 0.2s;
}
.site-footer .footer-col nav ul li a:hover { color:#fff3db; }
</style>
