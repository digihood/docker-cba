<?php
if (!defined('ABSPATH')) exit;

add_action('acf/init', function () {
    if (!function_exists('acf_add_local_field_group')) return;

    acf_add_local_field_group([
        'key'   => 'group_block_faq',
        'title' => 'FAQ – Časté otázky',
        'fields' => [
            [
                'key'   => 'field_faq_heading',
                'label' => 'Nadpis sekce',
                'name'  => 'faq_heading',
                'type'  => 'text',
                'default_value' => 'Časté otázky',
            ],
            [
                'key'   => 'field_faq_subheading',
                'label' => 'Podnadpis',
                'name'  => 'faq_subheading',
                'type'  => 'textarea',
                'rows'  => 2,
            ],
            [
                'key'   => 'field_faq_items',
                'label' => 'Otázky a odpovědi',
                'name'  => 'faq_items',
                'type'  => 'repeater',
                'button_label' => 'Přidat otázku',
                'layout' => 'block',
                'sub_fields' => [
                    [
                        'key'   => 'field_faq_question',
                        'label' => 'Otázka',
                        'name'  => 'question',
                        'type'  => 'text',
                        'required' => 1,
                    ],
                    [
                        'key'   => 'field_faq_answer',
                        'label' => 'Odpověď',
                        'name'  => 'answer',
                        'type'  => 'wysiwyg',
                        'toolbar' => 'basic',
                        'media_upload' => 0,
                    ],
                ],
            ],
        ],
        'location' => [
            [['param' => 'block', 'operator' => '==', 'value' => 'acf/faq']],
        ],
        'menu_order' => 70,
    ]);
});
