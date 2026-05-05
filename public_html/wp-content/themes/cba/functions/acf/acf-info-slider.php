<?php
if (!defined('ABSPATH')) exit;

add_action('acf/init', function () {
    if (!function_exists('acf_add_local_field_group')) return;

    acf_add_local_field_group([
        'key'   => 'group_block_info_slider',
        'title' => 'Informační slider',
        'fields' => [
            [
                'key'   => 'field_slider_heading',
                'label' => 'Nadpis sekce',
                'name'  => 'slider_heading',
                'type'  => 'text',
            ],
            [
                'key'   => 'field_slider_subheading',
                'label' => 'Podnadpis',
                'name'  => 'slider_subheading',
                'type'  => 'text',
            ],
            [
                'key'   => 'field_slider_items',
                'label' => 'Položky slideru',
                'name'  => 'slider_items',
                'type'  => 'repeater',
                'button_label' => 'Přidat položku',
                'layout' => 'block',
                'sub_fields' => [
                    [
                        'key'   => 'field_slider_item_icon',
                        'label' => 'Ikona',
                        'name'  => 'icon',
                        'type'  => 'image',
                        'return_format' => 'array',
                        'preview_size' => 'thumbnail',
                        'wrapper' => ['width' => '25'],
                    ],
                    [
                        'key'   => 'field_slider_item_title',
                        'label' => 'Název',
                        'name'  => 'title',
                        'type'  => 'text',
                        'required' => 1,
                        'wrapper' => ['width' => '75'],
                    ],
                    [
                        'key'   => 'field_slider_item_text',
                        'label' => 'Popis',
                        'name'  => 'text',
                        'type'  => 'textarea',
                        'rows'  => 3,
                    ],
                    [
                        'key'   => 'field_slider_item_link',
                        'label' => 'Odkaz',
                        'name'  => 'link',
                        'type'  => 'link',
                        'return_format' => 'array',
                    ],
                ],
            ],
        ],
        'location' => [
            [['param' => 'block', 'operator' => '==', 'value' => 'acf/info-slider']],
        ],
        'menu_order' => 20,
    ]);
});
