<?php
if (!defined('ABSPATH')) exit;
?>
<div class="footer-colophon bg-dark border-t border-white/[0.07] py-5">
    <div class="container max-w-content mx-auto">
        <div class="flex flex-col md:flex-row items-center justify-between gap-4 text-white/55 text-[13px]">

            <!-- Copyright -->
            <div class="footer-copyright">
                &copy; <?= date('Y') ?> <?= esc_html(get_bloginfo('name')) ?>.
                <?= esc_html__('Všechna práva vyhrazena.', 'cba') ?>
            </div>

            <!-- Footer menu -->
            <nav class="footer-colophon-nav" aria-label="<?= esc_attr__('Navigace zápatí', 'cba') ?>">
                <?php
                wp_nav_menu([
                    'theme_location' => 'footer-1',
                    'container'      => false,
                    'menu_class'     => 'flex flex-wrap gap-4 justify-center',
                    'items_wrap'     => '<ul class="%2$s">%3$s</ul>',
                    'depth'          => 1,
                    'fallback_cb'    => false,
                    'link_before'    => '<span class="text-white/55 hover:text-white transition-colors">',
                    'link_after'     => '</span>',
                ]);
                ?>
            </nav>

            <!-- Digihood credit -->
            <div class="footer-credit">
                <a href="https://www.digihood.cz/" rel="noreferrer noopener" target="_blank" class="text-white/55 hover:text-white no-underline transition-colors" title="<?= esc_attr__('Profesionální webdesign na míru', 'cba') ?>">
                    Webdesign Digihood
                </a>
            </div>
        </div>
    </div>
</div>
