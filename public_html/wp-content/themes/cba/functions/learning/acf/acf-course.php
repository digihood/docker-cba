<?php
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'acf/init', 'foxo_register_course_fields' );

function foxo_register_course_fields(): void {
    if ( ! function_exists( 'acf_add_local_field_group' ) ) return;

    acf_add_local_field_group( [
        'key'    => 'group_foxo_course',
        'title'  => 'Nastavení kurzu',
        'fields' => [
            [
                'key'   => 'field_foxo_course_active',
                'label' => 'Kurz je aktivní',
                'name'  => 'foxo_course_active',
                'type'  => 'true_false',
                'default_value' => 1,
                'ui'    => 1,
                'wrapper' => [ 'width' => '25' ],
            ],
            [
                'key'     => 'field_foxo_course_access_mode',
                'label'   => 'Přístup',
                'name'    => 'foxo_course_access_mode',
                'type'    => 'radio',
                'choices' => [
                    'public'          => 'Veřejný',
                    'login_required'  => 'Jen po přihlášení',
                    'locked'          => 'Uzamčený',
                ],
                'default_value' => 'public',
                'layout'  => 'horizontal',
                'wrapper' => [ 'width' => '50' ],
            ],
            [
                'key'     => 'field_foxo_course_level',
                'label'   => 'Úroveň kurzu',
                'name'    => 'foxo_course_level',
                'type'    => 'select',
                'choices' => [
                    ''           => '— Nevybráno —',
                    'beginner'   => 'Pro začátečníky',
                    'intermediate' => 'Středně pokročilý',
                    'advanced'   => 'Pokročilý',
                ],
                'wrapper' => [ 'width' => '25' ],
            ],
            [
                'key'     => 'field_foxo_course_duration',
                'label'   => 'Orientační délka kurzu',
                'name'    => 'foxo_course_duration',
                'type'    => 'text',
                'placeholder' => 'např. 3 hodiny',
                'wrapper' => [ 'width' => '25' ],
            ],
            [
                'key'   => 'field_foxo_course_intro',
                'label' => 'Anotace (krátký popis)',
                'name'  => 'foxo_course_intro',
                'type'  => 'textarea',
                'rows'  => 3,
            ],
            [
                'key'          => 'field_foxo_course_final_quiz',
                'label'        => 'Závěrečný kvíz',
                'name'         => 'foxo_course_final_quiz',
                'type'         => 'post_object',
                'post_type'    => [ 'foxo_quiz' ],
                'return_format' => 'object',
                'allow_null'   => 1,
                'wrapper'      => [ 'width' => '50' ],
            ],
            [
                'key'     => 'field_foxo_course_required_score',
                'label'   => 'Minimální skóre závěrečného kvízu (%)',
                'name'    => 'foxo_course_required_score',
                'type'    => 'number',
                'min'     => 0,
                'max'     => 100,
                'append'  => '%',
                'wrapper' => [ 'width' => '25' ],
            ],
            // Lessons repeater
            [
                'key'          => 'field_foxo_course_lessons',
                'label'        => 'Lekce kurzu (pořadí je závazné)',
                'name'         => 'foxo_course_lessons',
                'type'         => 'repeater',
                'button_label' => 'Přidat lekci',
                'layout'       => 'block',
                'sub_fields'   => [
                    [
                        'key'          => 'field_foxo_course_lesson_id',
                        'label'        => 'Lekce',
                        'name'         => 'lesson_id',
                        'type'         => 'post_object',
                        'post_type'    => [ 'foxo_lesson' ],
                        'return_format' => 'object',
                        'required'     => 1,
                        'allow_null'   => 0,
                        'wrapper'      => [ 'width' => '50' ],
                    ],
                    [
                        'key'          => 'field_foxo_course_lesson_custom_title',
                        'label'        => 'Vlastní název lekce v tomto kurzu (nepovinné)',
                        'name'         => 'lesson_custom_title',
                        'type'         => 'text',
                        'wrapper'      => [ 'width' => '35' ],
                    ],
                    [
                        'key'          => 'field_foxo_course_lesson_required',
                        'label'        => 'Povinná',
                        'name'         => 'lesson_required',
                        'type'         => 'true_false',
                        'default_value' => 1,
                        'ui'           => 1,
                        'wrapper'      => [ 'width' => '15' ],
                    ],
                ],
            ],
        ],
        'location' => [
            [ [ 'param' => 'post_type', 'operator' => '==', 'value' => 'foxo_course' ] ],
        ],
        'menu_order' => 10,
    ] );
}
