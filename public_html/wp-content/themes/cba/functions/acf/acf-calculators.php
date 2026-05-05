<?php
if (!defined('ABSPATH')) exit;

add_action('acf/init', function () {
    if (!function_exists('acf_add_local_field_group')) return;

    acf_add_local_field_group([
        'key'   => 'group_block_calculators',
        'title' => 'Výběr kalkulaček',
        'fields' => [
            [
                'key'   => 'field_calc_heading',
                'label' => 'Nadpis sekce',
                'name'  => 'calc_heading',
                'type'  => 'text',
                'default_value' => 'Kalkulačky',
            ],
            [
                'key'   => 'field_calc_subheading',
                'label' => 'Podnadpis',
                'name'  => 'calc_subheading',
                'type'  => 'textarea',
                'rows'  => 2,
            ],
            [
                'key'   => 'field_calc_items',
                'label' => 'Kalkulačky',
                'name'  => 'calc_items',
                'type'  => 'repeater',
                'button_label' => 'Přidat kalkulačku',
                'layout' => 'block',
                'max'   => 4,
                'sub_fields' => [
                    [
                        'key'   => 'field_calc_post',
                        'label' => 'Kalkulačka',
                        'name'  => 'calculator',
                        'type'  => 'post_object',
                        'post_type' => ['calculator'],
                        'return_format' => 'object',
                        'allow_null' => 0,
                        'wrapper' => ['width' => '50'],
                    ],
                    [
                        'key'   => 'field_calc_custom_icon',
                        'label' => 'Vlastní ikona (přepíše výchozí)',
                        'name'  => 'custom_icon',
                        'type'  => 'image',
                        'return_format' => 'array',
                        'preview_size' => 'thumbnail',
                        'wrapper' => ['width' => '50'],
                    ],
                    [
                        'key'   => 'field_calc_custom_desc',
                        'label' => 'Vlastní popis (přepíše výchozí)',
                        'name'  => 'custom_desc',
                        'type'  => 'textarea',
                        'rows'  => 2,
                    ],
                ],
            ],
            [
                'key'   => 'field_calc_btn',
                'label' => 'Tlačítko "Všechny kalkulačky"',
                'name'  => 'calc_btn',
                'type'  => 'link',
                'return_format' => 'array',
            ],
        ],
        'location' => [
            [['param' => 'block', 'operator' => '==', 'value' => 'acf/calculators']],
        ],
        'menu_order' => 40,
    ]);
});
