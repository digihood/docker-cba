<?php
if (!defined('ABSPATH')) exit;

add_action('acf/init', function () {
    if (!function_exists('acf_add_local_field_group')) return;

    acf_add_local_field_group([
        'key'   => 'group_block_articles',
        'title' => 'Výběr článků',
        'fields' => [
            [
                'key'   => 'field_articles_heading',
                'label' => 'Nadpis sekce',
                'name'  => 'articles_heading',
                'type'  => 'text',
                'default_value' => 'Aktuální články',
            ],
            [
                'key'   => 'field_articles_subheading',
                'label' => 'Podnadpis',
                'name'  => 'articles_subheading',
                'type'  => 'textarea',
                'rows'  => 2,
            ],
            [
                'key'   => 'field_articles_count',
                'label' => 'Počet článků',
                'name'  => 'articles_count',
                'type'  => 'number',
                'default_value' => 4,
                'min'   => 2,
                'max'   => 8,
            ],
            [
                'key'   => 'field_articles_category',
                'label' => 'Kategorie (nepovinné)',
                'name'  => 'articles_category',
                'type'  => 'taxonomy',
                'taxonomy' => 'category',
                'field_type' => 'select',
                'return_format' => 'id',
                'allow_null' => 1,
                'instructions' => 'Vyberte konkrétní kategorii nebo nechte prázdné pro všechny kategorie.',
            ],
            [
                'key'   => 'field_articles_btn',
                'label' => 'Tlačítko "Všechny články"',
                'name'  => 'articles_btn',
                'type'  => 'link',
                'return_format' => 'array',
            ],
        ],
        'location' => [
            [['param' => 'block', 'operator' => '==', 'value' => 'acf/articles']],
        ],
        'menu_order' => 30,
    ]);

    // Pole délky výňatku článku
    acf_add_local_field_group([
        'key'   => 'group_post_excerpt_length',
        'title' => 'Nastavení článku',
        'fields' => [
            [
                'key'   => 'field_post_excerpt_length',
                'label' => 'Délka výňatku (počet slov)',
                'name'  => 'post_excerpt_length',
                'type'  => 'number',
                'default_value' => 20,
                'min'   => 5,
                'max'   => 100,
                'instructions' => 'Počet slov zobrazených v náhledu článku.',
            ],
        ],
        'location' => [
            [['param' => 'post_type', 'operator' => '==', 'value' => 'post']],
        ],
        'menu_order' => 5,
        'position' => 'side',
    ]);
});
