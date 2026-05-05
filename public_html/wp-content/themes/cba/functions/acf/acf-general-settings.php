<?php
if (!defined('ABSPATH')) exit;

add_action('acf/init', function () {
    if (!function_exists('acf_add_local_field_group')) return;

    // Záhlaví + zápatí nastavení (Options page)
    acf_add_local_field_group([
        'key'      => 'group_general_settings',
        'title'    => 'Záhlaví & zápatí',
        'fields'   => [

            // === ZÁHLAVÍ ===
            [
                'key'   => 'field_header_tab',
                'label' => 'Záhlaví',
                'name'  => '',
                'type'  => 'tab',
            ],
            [
                'key'   => 'field_header_cta',
                'label' => 'CTA tlačítko v záhlaví',
                'name'  => 'header_cta',
                'type'  => 'link',
                'return_format' => 'array',
                'instructions' => 'Odkaz pro hlavní CTA tlačítko v pravém horním rohu záhlaví.',
            ],

            // === ZÁPATÍ ===
            [
                'key'   => 'field_footer_tab',
                'label' => 'Zápatí',
                'name'  => '',
                'type'  => 'tab',
            ],
            [
                'key'   => 'field_footer_desc',
                'label' => 'Popis/slogan zápatí',
                'name'  => 'footer_desc',
                'type'  => 'textarea',
                'rows'  => 3,
            ],
            [
                'key'   => 'field_social_icons',
                'label' => 'Sociální sítě',
                'name'  => 'social_icons',
                'type'  => 'repeater',
                'button_label' => 'Přidat sociální síť',
                'layout' => 'table',
                'sub_fields' => [
                    [
                        'key'   => 'field_social_name',
                        'label' => 'Název',
                        'name'  => 'name',
                        'type'  => 'text',
                        'wrapper' => ['width' => '25'],
                    ],
                    [
                        'key'   => 'field_social_url',
                        'label' => 'URL',
                        'name'  => 'url',
                        'type'  => 'url',
                        'wrapper' => ['width' => '35'],
                    ],
                    [
                        'key'   => 'field_social_icon',
                        'label' => 'Ikona (SVG/obrázek)',
                        'name'  => 'icon',
                        'type'  => 'image',
                        'return_format' => 'array',
                        'preview_size' => 'thumbnail',
                        'wrapper' => ['width' => '40'],
                    ],
                ],
            ],

            // === SEKCE ODBORNÍKŮ ===
            [
                'key'   => 'field_experts_tab',
                'label' => 'Odborníci',
                'name'  => '',
                'type'  => 'tab',
            ],
            [
                'key'   => 'field_experts_heading',
                'label' => 'Nadpis sekce',
                'name'  => 'experts_heading',
                'type'  => 'text',
                'default_value' => 'Zeptejte se odborníků',
            ],
            [
                'key'   => 'field_experts_desc',
                'label' => 'Popis',
                'name'  => 'experts_desc',
                'type'  => 'textarea',
                'rows'  => 3,
            ],
            [
                'key'   => 'field_experts_list',
                'label' => 'Odborníci',
                'name'  => 'experts_list',
                'type'  => 'repeater',
                'button_label' => 'Přidat odborníka',
                'layout' => 'block',
                'sub_fields' => [
                    [
                        'key'   => 'field_expert_photo',
                        'label' => 'Fotografie',
                        'name'  => 'photo',
                        'type'  => 'image',
                        'return_format' => 'array',
                        'preview_size' => 'thumbnail',
                        'wrapper' => ['width' => '25'],
                    ],
                    [
                        'key'   => 'field_expert_name',
                        'label' => 'Jméno',
                        'name'  => 'name',
                        'type'  => 'text',
                        'wrapper' => ['width' => '35'],
                    ],
                    [
                        'key'   => 'field_expert_role',
                        'label' => 'Role/Specializace',
                        'name'  => 'role',
                        'type'  => 'text',
                        'wrapper' => ['width' => '40'],
                    ],
                ],
            ],
            [
                'key'   => 'field_experts_form_heading',
                'label' => 'Nadpis formuláře',
                'name'  => 'experts_form_heading',
                'type'  => 'text',
                'default_value' => 'Zeptejte se',
            ],
            [
                'key'   => 'field_experts_cf7_shortcode',
                'label' => 'Contact Form 7 shortcode',
                'name'  => 'experts_cf7_shortcode',
                'type'  => 'text',
                'instructions' => 'Vložte shortcode CF7, např: [contact-form-7 id="123"]',
            ],
        ],
        'location' => [
            [['param' => 'options_page', 'operator' => '==', 'value' => 'acf-options-zahlavi']],
        ],
        'menu_order' => 0,
    ]);
});
