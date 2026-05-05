<?php
if (!defined('ABSPATH')) exit;

$cta = function_exists('get_field') ? get_field('header_cta', 'option') : null;
?>
<header id="header-content" class="header-main sticky top-0 z-50 bg-white shadow-sm transition-all duration-400" itemscope itemtype="http://schema.org/WPHeader">
    <div class="container max-w-content mx-auto">
        <div class="flex items-center justify-between h-[80px] lg:h-[88px]">

            <!-- Logo -->
            <div class="header-logo flex-shrink-0">
                <a href="<?= esc_url(home_url('/')) ?>" class="flex items-center no-underline" aria-label="<?= esc_attr(get_bloginfo('name')) ?>">
                    <?php
                    $logo_id = get_theme_mod('custom_logo');
                    if ($logo_id) {
                        echo wp_get_attachment_image($logo_id, 'full', false, [
                            'class'  => 'header-logo__img h-[48px] w-auto object-contain',
                            'alt'    => esc_attr(get_bloginfo('name')),
                            'loading' => 'eager',
                        ]);
                    } else {
                        echo '<span class="text-primary font-bold text-xl">' . esc_html(get_bloginfo('name')) . '</span>';
                    }
                    ?>
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
            <div class="header-actions flex items-center gap-3 lg:gap-4">

                <!-- Vyhledávání -->
                <button
                    type="button"
                    class="header-search-btn flex items-center justify-center w-10 h-10 rounded-full hover:bg-gray-light transition-colors duration-300 text-dark"
                    aria-label="<?= esc_attr__('Vyhledávání', 'cba') ?>"
                    id="js-search-toggle"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                    </svg>
                </button>

                <!-- CTA Tlačítko -->
                <?php if (!empty($cta)) : ?>
                    <a
                        href="<?= esc_url($cta['url']) ?>"
                        class="hidden sm:inline-flex items-center button primary rounded-full !py-3 !px-6 text-base font-semibold no-underline hover:no-underline"
                        <?= !empty($cta['target']) ? 'target="' . esc_attr($cta['target']) . '"' : '' ?>
                    >
                        <?= esc_html($cta['title']) ?>
                    </a>
                <?php else : ?>
                    <a href="<?= esc_url(home_url('/kalkulacky')) ?>" class="hidden sm:inline-flex items-center button primary rounded-full !py-3 !px-6 text-base font-semibold no-underline hover:no-underline">
                        <?= esc_html__('Kalkulačky zdarma', 'cba') ?>
                    </a>
                <?php endif; ?>

                <!-- Hamburger (mobil) -->
                <button
                    type="button"
                    class="js-slideout-toggle lg:hidden flex items-center justify-center w-10 h-10 ml-1"
                    aria-label="<?= esc_attr__('Otevřít menu', 'cba') ?>"
                    aria-expanded="false"
                    aria-controls="mobile-menu"
                >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Vyhledávací formulář (expandovatelný) -->
    <div id="header-search-panel" class="hidden bg-white border-t border-gray-mid py-4 shadow-md">
        <div class="container max-w-content mx-auto">
            <?php get_search_form(); ?>
        </div>
    </div>
</header>
