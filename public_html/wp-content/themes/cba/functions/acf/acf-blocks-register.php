<?php
if (!defined('ABSPATH')) exit;

/**
 * Registrace ACF bloků pro domácí stránku
 */
add_action('acf/init', function () {
    if (!function_exists('acf_register_block_type')) return;

    $blocks = [
        [
            'name'            => 'hero',
            'title'           => 'HERO – Uvítací sekce',
            'description'     => 'Velká úvodní sekce s nadpisem, textem a obrázkem.',
            'icon'            => 'cover-image',
            'render_template' => get_template_directory() . '/parts/block/block-hero.php',
            'category'        => 'cba-blocks',
            'keywords'        => ['hero', 'uvod', 'banner'],
            'supports'        => ['align' => false, 'jsx' => true],
            'mode'            => 'preview',
            'example'         => ['attributes' => ['mode' => 'preview', 'data' => ['hero_heading' => 'Máš to spočítáno']]],
        ],
        [
            'name'            => 'info-slider',
            'title'           => 'Informační slider',
            'description'     => 'Slider s informačními kartami.',
            'icon'            => 'slides',
            'render_template' => get_template_directory() . '/parts/block/block-info-slider.php',
            'category'        => 'cba-blocks',
            'keywords'        => ['slider', 'karty', 'info'],
            'supports'        => ['align' => false, 'jsx' => true],
            'mode'            => 'preview',
            'example'         => ['attributes' => ['mode' => 'preview', 'data' => ['slider_heading' => 'Informační slider']]],
        ],
        [
            'name'            => 'articles',
            'title'           => 'Výběr článků',
            'description'     => 'Zobrazení nejnovějších WordPress článků.',
            'icon'            => 'admin-post',
            'render_template' => get_template_directory() . '/parts/block/block-articles.php',
            'category'        => 'cba-blocks',
            'keywords'        => ['clanky', 'blog', 'posty'],
            'supports'        => ['align' => false, 'jsx' => true],
            'mode'            => 'preview',
            'example'         => ['attributes' => ['mode' => 'preview', 'data' => ['articles_heading' => 'Aktuální články']]],
        ],
        [
            'name'            => 'calculators',
            'title'           => 'Výběr kalkulaček',
            'description'     => 'Výpis vybraných kalkulaček z repeater pole.',
            'icon'            => 'calculator',
            'render_template' => get_template_directory() . '/parts/block/block-calculators.php',
            'category'        => 'cba-blocks',
            'keywords'        => ['kalkulacky', 'nastroje'],
            'supports'        => ['align' => false, 'jsx' => true],
            'mode'            => 'preview',
            'example'         => ['attributes' => ['mode' => 'preview', 'data' => ['calc_heading' => 'Kalkulačky']]],
        ],
        [
            'name'            => 'banner',
            'title'           => 'Banner sekce',
            'description'     => 'Velký banner s obrázkem na pozadí, nadpisem a tlačítky.',
            'icon'            => 'format-image',
            'render_template' => get_template_directory() . '/parts/block/block-banner.php',
            'category'        => 'cba-blocks',
            'keywords'        => ['banner', 'hero', 'promo'],
            'supports'        => ['align' => false, 'jsx' => true],
            'mode'            => 'preview',
            'example'         => ['attributes' => ['mode' => 'preview', 'data' => ['banner_heading' => 'Banner sekce']]],
        ],
        [
            'name'            => 'stories',
            'title'           => 'Příběhy a inspirace',
            'description'     => 'Výběr příspěvků – příběhy a inspirace.',
            'icon'            => 'book-alt',
            'render_template' => get_template_directory() . '/parts/block/block-stories.php',
            'category'        => 'cba-blocks',
            'keywords'        => ['pribehy', 'inspirace', 'blog'],
            'supports'        => ['align' => false, 'jsx' => true],
            'mode'            => 'preview',
            'example'         => ['attributes' => ['mode' => 'preview', 'data' => ['stories_heading' => 'Příběhy a inspirace']]],
        ],
        [
            'name'            => 'faq',
            'title'           => 'FAQ – Časté otázky',
            'description'     => 'Akordeonový výpis otázek a odpovědí.',
            'icon'            => 'editor-help',
            'render_template' => get_template_directory() . '/parts/block/block-faq.php',
            'category'        => 'cba-blocks',
            'keywords'        => ['faq', 'otazky', 'akordeon'],
            'supports'        => ['align' => false, 'jsx' => true],
            'mode'            => 'preview',
            'example'         => ['attributes' => ['mode' => 'preview', 'data' => ['faq_heading' => 'Časté otázky']]],
        ],
    ];

    foreach ($blocks as $block) {
        acf_register_block_type($block);
    }
});

// Registrace vlastní kategorie bloků
add_filter('block_categories_all', function ($categories) {
    return array_merge(
        [['slug' => 'cba-blocks', 'title' => 'CBA Bloky', 'icon' => '']],
        $categories
    );
}, 10, 2);
