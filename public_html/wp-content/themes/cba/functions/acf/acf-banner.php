<?php
if (!defined('ABSPATH')) exit;

add_action('acf/init', function () {
    if (!function_exists('acf_add_local_field_group')) return;

    acf_add_local_field_group([
        'key'   => 'group_block_banner',
        'title' => 'Banner sekce',
        'fields' => [
            [
                'key'   => 'field_banner_bg_image',
                'label' => 'Obrázek na pozadí',
                'name'  => 'banner_bg_image',
                'type'  => 'image',
                'return_format' => 'array',
                'preview_size' => 'medium',
            ],
            [
                'key'   => 'field_banner_bg_overlay',
                'label' => 'Přidat tmavý overlay',
                'name'  => 'banner_bg_overlay',
                'type'  => 'true_false',
                'default_value' => 1,
                'ui'    => 1,
            ],
            [
                'key'   => 'field_banner_badge',
                'label' => 'Badge / Štítek',
                'name'  => 'banner_badge',
                'type'  => 'text',
            ],
            [
                'key'   => 'field_banner_heading',
                'label' => 'Nadpis',
                'name'  => 'banner_heading',
                'type'  => 'text',
                'required' => 1,
            ],
            [
                'key'   => 'field_banner_text',
                'label' => 'Text',
                'name'  => 'banner_text',
                'type'  => 'textarea',
                'rows'  => 3,
            ],
            [
                'key'   => 'field_banner_btn_primary',
                'label' => 'Primární tlačítko',
                'name'  => 'banner_btn_primary',
                'type'  => 'link',
                'return_format' => 'array',
            ],
            [
                'key'   => 'field_banner_btn_secondary',
                'label' => 'Sekundární tlačítko',
                'name'  => 'banner_btn_secondary',
                'type'  => 'link',
                'return_format' => 'array',
            ],
            [
                'key'   => 'field_banner_features',
                'label' => 'Výhody / Body',
                'name'  => 'banner_features',
                'type'  => 'repeater',
                'button_label' => 'Přidat výhodu',
                'layout' => 'table',
                'sub_fields' => [
                    [
                        'key'   => 'field_banner_feature_icon',
                        'label' => 'Ikona',
                        'name'  => 'icon',
                        'type'  => 'image',
                        'return_format' => 'array',
                        'preview_size' => 'thumbnail',
                        'wrapper' => ['width' => '20'],
                    ],
                    [
                        'key'   => 'field_banner_feature_title',
                        'label' => 'Název',
                        'name'  => 'title',
                        'type'  => 'text',
                        'wrapper' => ['width' => '40'],
                    ],
                    [
                        'key'   => 'field_banner_feature_text',
                        'label' => 'Popis',
                        'name'  => 'text',
                        'type'  => 'text',
                        'wrapper' => ['width' => '40'],
                    ],
                ],
            ],
        ],
        'location' => [
            [['param' => 'block', 'operator' => '==', 'value' => 'acf/banner']],
        ],
        'menu_order' => 50,
    ]);
});
