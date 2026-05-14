<?php
if (!defined('ABSPATH')) exit;
$cta = function_exists('get_field') ? get_field('header_cta', 'option') : null;
?>
<header id="header-content" class="header-main sticky top-0 z-50 bg-white border-b border-gray-mid/20 transition-all duration-400" itemscope itemtype="http://schema.org/WPHeader">
    <div class="container max-w-content mx-auto">
        <div class="flex items-center justify-between" style="height:120px;">

            <!-- Logo -->
            <div class="header-logo flex-shrink-0">
                <a href="<?= esc_url(home_url('/')) ?>" class="flex items-center gap-4 no-underline" aria-label="<?= esc_attr(get_bloginfo('name')) ?>">
                    <?php d1g1B::icon('cba-logo', 'h-[54px] w-auto'); ?>
                    <?php d1g1B::icon('mts-logo', 'h-[54px] w-auto'); ?>
                </a>
            </div>

            <!-- Hlavní navigace (desktop) -->
            <nav class="header-nav hidden lg:flex items-center" aria-label="<?= esc_attr__('Hlavní navigace', 'cba') ?>">
                <?php
                wp_nav_menu([
                    'theme_location' => 'primary',
                    'container'      => false,
                    'menu_class'     => 'menu flex items-center gap-8',
                    'items_wrap'     => '<ul id="primary-menu" class="%2$s" role="menubar">%3$s</ul>',
                    'depth'          => 3,
                    'fallback_cb'    => false,
                    'walker'         => class_exists('CBA_Nav_Walker') ? new CBA_Nav_Walker() : null,
                ]);
                ?>
            </nav>

            <!-- Pravá část: Vyhledávání + CTA -->
            <div class="header-actions flex items-center gap-5">
                <button type="button" class="flex items-center justify-center w-10 h-10 text-dark/60 hover:text-dark transition-colors flex-shrink-0" aria-label="<?= esc_attr__('Vyhledávání', 'cba') ?>" id="js-search-toggle">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                </button>
                <?php if (!empty($cta)) : ?>
                    <a href="<?= esc_url($cta['url']) ?>" class="hidden sm:inline-flex items-center bg-primary text-white rounded-[30px] px-6 py-3.5 text-sm font-semibold no-underline hover:no-underline hover:bg-primary-dark transition-colors font-display" <?= !empty($cta['target']) ? 'target="' . esc_attr($cta['target']) . '"' : '' ?>>
                        <?= esc_html($cta['title']) ?>
                    </a>
                <?php else : ?>
                    <a href="<?= esc_url(home_url('/akademie')) ?>" class="hidden sm:inline-flex items-center bg-primary text-white rounded-[30px] px-6 py-3.5 text-sm font-semibold no-underline hover:no-underline hover:bg-primary-dark transition-colors font-display">
                        Online akademie
                    </a>
                <?php endif; ?>
                <button type="button" class="js-slideout-toggle lg:hidden flex items-center justify-center w-10 h-10 ml-1" aria-label="<?= esc_attr__('Otevřít menu', 'cba') ?>" aria-expanded="false" aria-controls="mobile-menu">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
            </div>
        </div>
    </div>
</header>

<!-- Fullscreen search overlay -->
<div id="search-overlay" class="fixed inset-0 z-[100] flex flex-col items-center justify-center opacity-0 pointer-events-none transition-opacity duration-300" style="backdrop-filter:blur(16px);-webkit-backdrop-filter:blur(16px);background:rgba(19,87,107,0.6);">
    <button type="button" id="search-overlay-close" class="absolute top-8 right-8 text-white/70 hover:text-white transition-colors" aria-label="<?= esc_attr__('Zavřít', 'cba') ?>">
        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
    </button>
    <h2 class="text-white font-semibold text-center mb-4" style="font-size:32px;font-family:Montserrat,sans-serif;"><?= esc_html__('Co hledáte?', 'cba') ?></h2>
    <form action="<?= esc_url(home_url('/')) ?>" method="get" class="w-full px-6" style="max-width:650px;">
        <div class="flex" style="height:50px;">
            <input type="search" name="s" value="" placeholder="<?= esc_attr__('Hledejte články, kurzy, kalkulačky...', 'cba') ?>" class="flex-1 bg-white rounded-l-full px-6 text-base text-dark outline-none placeholder:text-dark/30" style="font-family:Montserrat,sans-serif;height:50px;" id="search-overlay-input">
            <button type="submit" class="bg-primary text-white rounded-r-full px-6 text-sm font-semibold hover:opacity-80 transition-opacity flex-shrink-0" style="font-family:Montserrat,sans-serif;height:50px;">
                <?= esc_html__('Hledat', 'cba') ?>
            </button>
        </div>
    </form>
</div>

<script>
(function() {
    var toggle = document.getElementById('js-search-toggle');
    var overlay = document.getElementById('search-overlay');
    var close = document.getElementById('search-overlay-close');
    var input = document.getElementById('search-overlay-input');
    if (!toggle || !overlay) return;

    function open() {
        overlay.classList.remove('pointer-events-none', 'opacity-0');
        overlay.classList.add('pointer-events-auto', 'opacity-100');
        document.body.style.overflow = 'hidden';
        setTimeout(function() { input.focus(); }, 100);
    }
    function shut() {
        overlay.classList.remove('pointer-events-auto', 'opacity-100');
        overlay.classList.add('pointer-events-none', 'opacity-0');
        document.body.style.overflow = '';
        input.value = '';
    }

    toggle.addEventListener('click', open);
    close.addEventListener('click', shut);
    overlay.addEventListener('click', function(e) {
        if (e.target === overlay) shut();
    });
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && overlay.classList.contains('opacity-100')) shut();
    });
})();
</script>
