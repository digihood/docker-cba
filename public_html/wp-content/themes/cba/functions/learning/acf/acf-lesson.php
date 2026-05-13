<?php
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'acf/init', 'foxo_register_lesson_fields' );

function foxo_register_lesson_fields(): void {
    if ( ! function_exists( 'acf_add_local_field_group' ) ) return;

    acf_add_local_field_group( [
        'key'    => 'group_foxo_lesson',
        'title'  => 'Nastavení lekce',
        'fields' => [
            [
                'key'   => 'field_foxo_lesson_active',
                'label' => 'Lekce je aktivní',
                'name'  => 'foxo_lesson_active',
                'type'  => 'true_false',
                'default_value' => 1,
                'ui'    => 1,
                'wrapper' => [ 'width' => '25' ],
            ],
            [
                'key'     => 'field_foxo_lesson_type',
                'label'   => 'Typ lekce',
                'name'    => 'foxo_lesson_type',
                'type'    => 'radio',
                'choices' => [
                    'text'     => 'Textová',
                    'video'    => 'Video',
                    'combined' => 'Kombinovaná',
                ],
                'default_value' => 'text',
                'layout'  => 'horizontal',
                'wrapper' => [ 'width' => '50' ],
            ],
            [
                'key'     => 'field_foxo_lesson_duration',
                'label'   => 'Orientační délka lekce',
                'name'    => 'foxo_lesson_duration',
                'type'    => 'text',
                'placeholder' => 'např. 15 min',
                'wrapper' => [ 'width' => '25' ],
            ],
            [
                'key'          => 'field_foxo_lesson_video_url',
                'label'        => 'Video URL (YouTube, Vimeo nebo přímý odkaz)',
                'name'         => 'foxo_lesson_video_url',
                'type'         => 'url',
                'conditional_logic' => [
                    [
                        [ 'field' => 'field_foxo_lesson_type', 'operator' => '!=', 'value' => 'text' ],
                    ],
                ],
            ],
            [
                'key'          => 'field_foxo_lesson_materials',
                'label'        => 'Materiály ke stažení',
                'name'         => 'foxo_lesson_materials',
                'type'         => 'repeater',
                'button_label' => 'Přidat materiál',
                'layout'       => 'table',
                'sub_fields'   => [
                    [
                        'key'     => 'field_foxo_material_file',
                        'label'   => 'Soubor',
                        'name'    => 'material_file',
                        'type'    => 'file',
                        'return_format' => 'array',
                        'wrapper' => [ 'width' => '60' ],
                    ],
                    [
                        'key'     => 'field_foxo_material_title',
                        'label'   => 'Název ke stažení',
                        'name'    => 'material_title',
                        'type'    => 'text',
                        'wrapper' => [ 'width' => '40' ],
                    ],
                ],
            ],
        ],
        'location' => [
            [ [ 'param' => 'post_type', 'operator' => '==', 'value' => 'foxo_lesson' ] ],
        ],
        'menu_order' => 10,
    ] );
}
