<?php
if (!defined('ABSPATH')) exit;

$cta = function_exists('get_field') ? get_field('header_cta', 'option') : null;
?>
<div id="mobile-menu" class="slideout-menu bg-dark" aria-label="<?= esc_attr__('Mobilní menu', 'cba') ?>" role="navigation">

    <!-- Logo v mobilním menu -->
    <div class="mb-8">
        <?php
        $logo_id = get_theme_mod('custom_logo');
        if ($logo_id) {
            echo '<a href="' . esc_url(home_url('/')) . '" class="no-underline">';
            echo wp_get_attachment_image($logo_id, 'full', false, [
                'class'  => 'h-[40px] w-auto object-contain brightness-0 invert',
                'alt'    => esc_attr(get_bloginfo('name')),
            ]);
            echo '</a>';
        } else {
            echo '<a href="' . esc_url(home_url('/')) . '" class="text-white font-bold text-xl no-underline">' . esc_html(get_bloginfo('name')) . '</a>';
        }
        ?>
    </div>

    <!-- Mobilní navigace -->
    <?php
    wp_nav_menu([
        'theme_location' => 'primary',
        'container'      => false,
        'menu_id'        => 'mobile-nav-menu',
        'menu_class'     => 'mobile-nav-list space-y-1',
        'items_wrap'     => '<ul id="%1$s" class="%2$s" role="menubar">%3$s</ul>',
        'depth'          => 2,
        'fallback_cb'    => false,
    ]);
    ?>

    <!-- CTA tlačítko v mobilním menu -->
    <?php if ($cta) : ?>
        <div class="mt-8 pt-6 border-t border-dark-muted">
            <a
                href="<?= esc_url($cta['url']) ?>"
                class="button primary rounded-full w-full text-center !py-3.5 font-semibold no-underline"
                <?= !empty($cta['target']) ? 'target="' . esc_attr($cta['target']) . '"' : '' ?>
            >
                <?= esc_html($cta['title']) ?>
            </a>
        </div>
    <?php endif; ?>

    <!-- Sociální sítě -->
    <?php
    $social_icons = function_exists('get_field') ? get_field('social_icons', 'option') : [];
    if (!empty($social_icons)) : ?>
        <div class="mt-6 flex flex-wrap gap-3">
            <?php foreach ($social_icons as $social) :
                if (empty($social['url'])) continue;
            ?>
                <a
                    href="<?= esc_url($social['url']) ?>"
                    class="flex items-center justify-center w-9 h-9 rounded-full bg-dark-muted hover:bg-primary transition-colors no-underline"
                    target="_blank"
                    rel="noopener noreferrer"
                    aria-label="<?= esc_attr($social['name'] ?? '') ?>"
                >
                    <?php if (!empty($social['icon'])) : ?>
                        <?= wp_get_attachment_image($social['icon']['ID'], [18, 18], false, [
                            'class' => 'w-[18px] h-[18px] object-contain filter invert',
                            'alt'   => '',
                        ]) ?>
                    <?php else : ?>
                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/></svg>
                    <?php endif; ?>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
