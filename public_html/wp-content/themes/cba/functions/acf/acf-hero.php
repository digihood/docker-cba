<?php
if (!defined('ABSPATH')) exit;

add_action('acf/init', function () {
    if (!function_exists('acf_add_local_field_group')) return;

    acf_add_local_field_group([
        'key'   => 'group_block_hero',
        'title' => 'HERO – Uvítací sekce',
        'fields' => [
            [
                'key'   => 'field_hero_badge',
                'label' => 'Badge / Štítek',
                'name'  => 'hero_badge',
                'type'  => 'text',
                'instructions' => 'Krátký text nad nadpisem (nepovinné)',
            ],
            [
                'key'   => 'field_hero_heading',
                'label' => 'Nadpis',
                'name'  => 'hero_heading',
                'type'  => 'text',
                'required' => 1,
            ],
            [
                'key'   => 'field_hero_heading_highlight',
                'label' => 'Zvýrazněná část nadpisu',
                'name'  => 'hero_heading_highlight',
                'type'  => 'text',
                'instructions' => 'Část nadpisu zobrazená barevně (nepovinné)',
            ],
            [
                'key'   => 'field_hero_text',
                'label' => 'Popisný text',
                'name'  => 'hero_text',
                'type'  => 'textarea',
                'rows'  => 3,
            ],
            [
                'key'   => 'field_hero_btn_primary',
                'label' => 'Primární tlačítko',
                'name'  => 'hero_btn_primary',
                'type'  => 'link',
                'return_format' => 'array',
            ],
            [
                'key'   => 'field_hero_btn_secondary',
                'label' => 'Sekundární tlačítko',
                'name'  => 'hero_btn_secondary',
                'type'  => 'link',
                'return_format' => 'array',
            ],
            [
                'key'   => 'field_hero_image',
                'label' => 'Obrázek vpravo',
                'name'  => 'hero_image',
                'type'  => 'image',
                'return_format' => 'array',
                'preview_size' => 'medium',
            ],
            [
                'key'   => 'field_hero_stats',
                'label' => 'Statistiky / Čísla',
                'name'  => 'hero_stats',
                'type'  => 'repeater',
                'button_label' => 'Přidat statistiku',
                'layout' => 'table',
                'sub_fields' => [
                    [
                        'key'   => 'field_hero_stat_number',
                        'label' => 'Číslo',
                        'name'  => 'number',
                        'type'  => 'text',
                        'wrapper' => ['width' => '30'],
                    ],
                    [
                        'key'   => 'field_hero_stat_label',
                        'label' => 'Popis',
                        'name'  => 'label',
                        'type'  => 'text',
                        'wrapper' => ['width' => '70'],
                    ],
                ],
            ],
        ],
        'location' => [
            [['param' => 'block', 'operator' => '==', 'value' => 'acf/hero']],
        ],
        'menu_order' => 10,
    ]);
});
