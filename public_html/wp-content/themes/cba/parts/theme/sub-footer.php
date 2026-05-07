<?php
if (!defined('ABSPATH')) exit;

$experts_heading = function_exists('get_field') ? get_field('experts_heading', 'option') : '';
$experts_desc    = function_exists('get_field') ? get_field('experts_desc', 'option') : '';
$experts_btn_url = function_exists('get_field') ? get_field('experts_btn_url', 'option') : home_url('/otazky');
?>

<!-- Sekce: Zeptejte se odborníků — lososové pozadí -->
<section class="experts-cta-section py-16 lg:py-20 text-center" style="background:#ff6b6b;" aria-label="Zeptejte se odborníků">
    <div class="container max-w-content mx-auto">
        <h2 class="font-semibold text-white mb-4" style="font-size:44px;font-family:Montserrat,sans-serif;line-height:1;">
            <?= esc_html($experts_heading ?: 'Zeptejte se odborníků') ?>
        </h2>
        <p class="text-white/85 text-lg mb-8" style="font-family:Montserrat,sans-serif;">
            <?= esc_html($experts_desc ?: 'Na vaše otázky odpovídají odborníci z ČBA a z bankovních institucí') ?>
        </p>
        <a href="<?= esc_url($experts_btn_url ?: home_url('/otazky')) ?>" class="inline-flex items-center justify-center bg-white text-primary rounded-[50px] px-8 py-4 text-sm font-semibold uppercase tracking-[0.07em] no-underline hover:bg-white/90 transition-colors" style="font-family:Montserrat,sans-serif;">
            Přejít do Q&amp;A
        </a>
    </div>
</section>

<!-- Zápatí — bronzové pozadí -->
<footer class="site-footer" style="background:#a9936d;" itemscope itemtype="http://schema.org/WPFooter">
    <div class="container max-w-content mx-auto py-16 lg:py-20">
        <div class="grid grid-cols-1 lg:grid-cols-[220px_1fr_1fr_1fr_1fr] gap-10 lg:gap-12">

            <!-- Logo column -->
            <div class="footer-brand flex flex-col gap-10">
                <?php
                $logo_id = get_theme_mod('custom_logo');
                if ($logo_id) :
                    echo wp_get_attachment_image($logo_id, 'full', false, ['class' => 'h-16 w-auto object-contain', 'alt' => esc_attr(get_bloginfo('name')), 'loading' => 'lazy']);
                else : ?>
                    <div class="text-vanilka font-bold text-2xl tracking-tight leading-none" style="font-family:Montserrat,sans-serif;">MÁŠ TO<br>SPOČÍTANÝ?</div>
                <?php endif; ?>

                <p class="text-vanilka/60 text-sm leading-relaxed" style="font-family:Montserrat,sans-serif;">
                    &copy; <?= date('Y') ?><br>All Rights Reserved
                </p>
            </div>

            <!-- Footer sections (widgets or static) -->
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
                    <h4 class="font-bold text-vanilka mb-7" style="font-size:20px;font-family:Montserrat,sans-serif;">
                        <?= esc_html($section['title']) ?>
                    </h4>
                    <?php if (is_active_sidebar($section['location'])) : ?>
                        <div class="footer-widget-wrap" style="--tw-text-opacity:0.8;">
                            <?php dynamic_sidebar($section['location']); ?>
                        </div>
                    <?php else : ?>
                        <!-- Fallback placeholder links -->
                        <nav>
                            <ul class="space-y-4">
                                <li><a href="#" class="text-vanilka/80 text-lg no-underline hover:text-vanilka transition-colors" style="font-family:Montserrat,sans-serif;">—</a></li>
                            </ul>
                        </nav>
                    <?php endif; ?>

                    <?php if ($section['location'] === 'footer-4') :
                        $social_icons_acf = function_exists('get_field') ? get_field('social_icons', 'option') : [];
                        $social_glyphs = [
                            'facebook'  => '<path d="M22 12a10 10 0 10-11.56 9.88v-7H8v-2.88h2.44V9.86c0-2.41 1.43-3.74 3.62-3.74 1.05 0 2.15.19 2.15.19v2.36h-1.21c-1.19 0-1.56.74-1.56 1.5v1.8h2.65l-.42 2.88h-2.23v7A10 10 0 0022 12z"/>',
                            'twitter'   => '<path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>',
                            'instagram' => '<path d="M12 2.16c3.2 0 3.58.01 4.85.07 1.17.05 1.8.25 2.23.41.56.22.96.48 1.38.9.42.42.68.82.9 1.38.16.42.36 1.06.41 2.23.06 1.27.07 1.65.07 4.85s-.01 3.58-.07 4.85c-.05 1.17-.25 1.8-.41 2.23-.22.56-.48.96-.9 1.38-.42.42-.82.68-1.38.9-.42.16-1.06.36-2.23.41-1.27.06-1.65.07-4.85.07s-3.58-.01-4.85-.07c-1.17-.05-1.8-.25-2.23-.41a3.7 3.7 0 01-1.38-.9 3.7 3.7 0 01-.9-1.38c-.16-.42-.36-1.06-.41-2.23C2.17 15.58 2.16 15.2 2.16 12s.01-3.58.07-4.85c.05-1.17.25-1.8.41-2.23.22-.56.48-.96.9-1.38.42-.42.82-.68 1.38-.9.42-.16 1.06-.36 2.23-.41C8.42 2.17 8.8 2.16 12 2.16zm0 5.4a4.44 4.44 0 100 8.88 4.44 4.44 0 000-8.88zm5.66-.34a1.04 1.04 0 11-2.08 0 1.04 1.04 0 012.08 0zM12 9.44a2.56 2.56 0 110 5.12 2.56 2.56 0 010-5.12z"/>',
                            'linkedin'  => '<path d="M19 3H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2V5a2 2 0 00-2-2zM8.34 18.34H5.67V9.67h2.67v8.67zM7 8.51a1.55 1.55 0 11.01-3.1A1.55 1.55 0 017 8.51zm11.34 9.83h-2.66v-4.22c0-1.01-.02-2.3-1.4-2.3s-1.62 1.1-1.62 2.23v4.29h-2.66V9.67h2.55v1.18h.04c.36-.67 1.22-1.38 2.51-1.38 2.69 0 3.19 1.77 3.19 4.07v4.8z"/>',
                            'youtube'   => '<path d="M23 7.2a3 3 0 00-2.1-2.1C19.1 4.6 12 4.6 12 4.6s-7.1 0-8.9.5A3 3 0 001 7.2C.5 9 .5 12 .5 12s0 3 .5 4.8a3 3 0 002.1 2.1c1.8.5 8.9.5 8.9.5s7.1 0 8.9-.5a3 3 0 002.1-2.1C23.5 15 23.5 12 23.5 12s0-3-.5-4.8zM9.6 15.4V8.6L15.5 12l-5.9 3.4z"/>',
                        ];
                        if (!empty($social_icons_acf)) :
                    ?>
                        <div class="flex flex-wrap gap-2.5 mt-4">
                            <?php foreach ($social_icons_acf as $social) :
                                if (empty($social['url'])) continue;
                                $name_l = strtolower($social['name'] ?? '');
                                $url_l  = strtolower($social['url']);
                                $glyph  = null;
                                foreach ($social_glyphs as $key => $g) {
                                    if (strpos($name_l, $key) !== false || strpos($url_l, $key) !== false) { $glyph = $g; break; }
                                }
                            ?>
                                <a href="<?= esc_url($social['url']) ?>" class="flex items-center justify-center w-7 h-7 rounded-sm text-vanilka/80 hover:text-vanilka transition-colors no-underline" target="_blank" rel="noopener noreferrer" aria-label="<?= esc_attr($social['name'] ?? '') ?>">
                                    <?php if ($glyph) : ?>
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><?= $glyph ?></svg>
                                    <?php else : ?>
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 14a3.5 3.5 0 005 0l4-4a3.5 3.5 0 00-5-5l-1 1m1 7a3.5 3.5 0 00-5 0l-4 4a3.5 3.5 0 005 5l1-1"/></svg>
                                    <?php endif; ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; endif; ?>
                </div>
            <?php endforeach; ?>

        </div>
    </div>
</footer>
