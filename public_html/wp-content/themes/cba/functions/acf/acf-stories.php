<?php
if (!defined('ABSPATH')) exit;

add_action('acf/init', function () {
    if (!function_exists('acf_add_local_field_group')) return;

    acf_add_local_field_group([
        'key'   => 'group_block_stories',
        'title' => 'Příběhy a inspirace',
        'fields' => [
            [
                'key'   => 'field_stories_heading',
                'label' => 'Nadpis sekce',
                'name'  => 'stories_heading',
                'type'  => 'text',
                'default_value' => 'Příběhy a inspirace',
            ],
            [
                'key'   => 'field_stories_subheading',
                'label' => 'Podnadpis',
                'name'  => 'stories_subheading',
                'type'  => 'textarea',
                'rows'  => 2,
            ],
            [
                'key'   => 'field_stories_count',
                'label' => 'Počet příspěvků',
                'name'  => 'stories_count',
                'type'  => 'number',
                'default_value' => 3,
                'min'   => 2,
                'max'   => 6,
            ],
            [
                'key'   => 'field_stories_category',
                'label' => 'Kategorie (nepovinné)',
                'name'  => 'stories_category',
                'type'  => 'taxonomy',
                'taxonomy' => 'category',
                'field_type' => 'select',
                'return_format' => 'id',
                'allow_null' => 1,
            ],
            [
                'key'   => 'field_stories_btn',
                'label' => 'Tlačítko "Více příběhů"',
                'name'  => 'stories_btn',
                'type'  => 'link',
                'return_format' => 'array',
            ],
        ],
        'location' => [
            [['param' => 'block', 'operator' => '==', 'value' => 'acf/stories']],
        ],
        'menu_order' => 60,
    ]);
});
