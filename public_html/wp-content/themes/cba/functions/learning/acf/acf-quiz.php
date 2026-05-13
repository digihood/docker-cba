<?php
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'acf/init', 'foxo_register_quiz_fields' );

function foxo_register_quiz_fields(): void {
    if ( ! function_exists( 'acf_add_local_field_group' ) ) return;

    acf_add_local_field_group( [
        'key'      => 'group_foxo_quiz',
        'title'    => 'Nastavení kvízu',
        'fields'   => [
            [
                'key'   => 'field_foxo_quiz_active',
                'label' => 'Kvíz je aktivní',
                'name'  => 'foxo_quiz_active',
                'type'  => 'true_false',
                'default_value' => 1,
                'ui'    => 1,
                'wrapper' => [ 'width' => '25' ],
            ],
            [
                'key'   => 'field_foxo_quiz_allow_repeat',
                'label' => 'Lze opakovat',
                'name'  => 'foxo_quiz_allow_repeat',
                'type'  => 'true_false',
                'default_value' => 1,
                'ui'    => 1,
                'wrapper' => [ 'width' => '25' ],
            ],
            [
                'key'   => 'field_foxo_quiz_show_correct_answers',
                'label' => 'Zobrazit správné odpovědi po vyhodnocení',
                'name'  => 'foxo_quiz_show_correct_answers',
                'type'  => 'true_false',
                'default_value' => 0,
                'ui'    => 1,
                'wrapper' => [ 'width' => '50' ],
            ],
            [
                'key'          => 'field_foxo_quiz_required_score',
                'label'        => 'Minimální úspěšnost (%)',
                'name'         => 'foxo_quiz_required_score',
                'type'         => 'number',
                'default_value' => 60,
                'min'          => 0,
                'max'          => 100,
                'append'       => '%',
                'wrapper'      => [ 'width' => '30' ],
            ],
            [
                'key'   => 'field_foxo_quiz_intro',
                'label' => 'Úvodní text kvízu',
                'name'  => 'foxo_quiz_intro',
                'type'  => 'textarea',
                'rows'  => 3,
            ],
            [
                'key'   => 'field_foxo_quiz_result_pass_text',
                'label' => 'Text při úspěšném splnění',
                'name'  => 'foxo_quiz_result_pass_text',
                'type'  => 'wysiwyg',
                'tabs'  => 'text',
                'toolbar' => 'basic',
                'media_upload' => 0,
                'wrapper' => [ 'width' => '50' ],
            ],
            [
                'key'   => 'field_foxo_quiz_result_fail_text',
                'label' => 'Text při nesplnění',
                'name'  => 'foxo_quiz_result_fail_text',
                'type'  => 'wysiwyg',
                'tabs'  => 'text',
                'toolbar' => 'basic',
                'media_upload' => 0,
                'wrapper' => [ 'width' => '50' ],
            ],
            [
                'key'          => 'field_foxo_quiz_related_content',
                'label'        => 'Doporučený obsah po dokončení',
                'name'         => 'foxo_quiz_related_content',
                'type'         => 'relationship',
                'post_type'    => [ 'post', 'page', 'foxo_course', 'foxo_lesson' ],
                'filters'      => [ 'search' ],
                'return_format' => 'object',
            ],
            // Questions repeater
            [
                'key'          => 'field_foxo_quiz_questions',
                'label'        => 'Kvíz – otázky a odpovědi',
                'name'         => 'foxo_quiz_questions',
                'type'         => 'repeater',
                'button_label' => 'Přidat otázku',
                'layout'       => 'block',
                'sub_fields'   => [
                    [
                        'key'          => 'field_foxo_question_unique_id',
                        'label'        => 'ID otázky',
                        'name'         => 'question_unique_id',
                        'type'         => 'unique_id',
                        'instructions' => 'Generuje se automaticky. Neměnit – zajišťuje stabilní vazbu s uloženými odpověďmi.',
                        'wrapper'      => [ 'width' => '20' ],
                    ],
                    [
                        'key'     => 'field_foxo_question_type',
                        'label'   => 'Typ otázky',
                        'name'    => 'question_type',
                        'type'    => 'select',
                        'choices' => [
                            'single_choice'   => 'Jedna správná odpověď',
                            'multiple_choice' => 'Více správných odpovědí',
                        ],
                        'default_value' => 'single_choice',
                        'required'      => 1,
                        'wrapper'       => [ 'width' => '30' ],
                    ],
                    [
                        'key'      => 'field_foxo_question_text',
                        'label'    => 'Text otázky',
                        'name'     => 'question_text',
                        'type'     => 'textarea',
                        'rows'     => 2,
                        'required' => 1,
                        'wrapper'  => [ 'width' => '50' ],
                    ],
                    [
                        'key'          => 'field_foxo_question_image',
                        'label'        => 'Obrázek k otázce (nepovinné)',
                        'name'         => 'question_image',
                        'type'         => 'image',
                        'return_format' => 'array',
                        'preview_size' => 'thumbnail',
                        'wrapper'      => [ 'width' => '50' ],
                    ],
                    [
                        'key'     => 'field_foxo_question_explanation',
                        'label'   => 'Vysvětlení (zobrazí se po vyhodnocení)',
                        'name'    => 'question_explanation',
                        'type'    => 'textarea',
                        'rows'    => 2,
                    ],
                    // Nested answers repeater
                    [
                        'key'          => 'field_foxo_question_answers',
                        'label'        => 'Odpovědi',
                        'name'         => 'question_answers',
                        'type'         => 'repeater',
                        'button_label' => 'Přidat odpověď',
                        'layout'       => 'table',
                        'min'          => 2,
                        'sub_fields'   => [
                            [
                                'key'          => 'field_foxo_answer_unique_id',
                                'label'        => 'ID odpovědi',
                                'name'         => 'answer_unique_id',
                                'type'         => 'unique_id',
                                'instructions' => 'Neměnit.',
                                'wrapper'      => [ 'width' => '15' ],
                            ],
                            [
                                'key'      => 'field_foxo_answer_text',
                                'label'    => 'Text odpovědi',
                                'name'     => 'answer_text',
                                'type'     => 'textarea',
                                'rows'     => 2,
                                'required' => 1,
                                'wrapper'  => [ 'width' => '45' ],
                            ],
                            [
                                'key'     => 'field_foxo_answer_is_correct',
                                'label'   => 'Správná?',
                                'name'    => 'answer_is_correct',
                                'type'    => 'true_false',
                                'ui'      => 1,
                                'wrapper' => [ 'width' => '15' ],
                            ],
                            [
                                'key'     => 'field_foxo_answer_feedback',
                                'label'   => 'Zpětná vazba k odpovědi',
                                'name'    => 'answer_feedback',
                                'type'    => 'textarea',
                                'rows'    => 2,
                                'wrapper' => [ 'width' => '25' ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
        'location' => [
            [ [ 'param' => 'post_type', 'operator' => '==', 'value' => 'foxo_quiz' ] ],
        ],
        'menu_order' => 10,
    ] );
}

// Admin validation – runs before ACF saves the post
add_action( 'acf/validate_save_post', 'foxo_validate_quiz_on_save' );

function foxo_validate_quiz_on_save(): void {
    if ( ( $_POST['post_type'] ?? '' ) !== 'foxo_quiz' ) return;

    $questions = $_POST['acf']['field_foxo_quiz_questions'] ?? [];
    if ( ! is_array( $questions ) ) return;

    $question_uids = [];

    foreach ( $questions as $qi => $question ) {
        $q_num = (int) $qi + 1;
        $q_uid = trim( $question['field_foxo_question_unique_id'] ?? '' );
        $q_text = trim( $question['field_foxo_question_text'] ?? '' );
        $q_type = $question['field_foxo_question_type'] ?? '';
        $answers = $question['field_foxo_question_answers'] ?? [];

        if ( empty( $q_text ) ) {
            acf_add_validation_error(
                'acf[field_foxo_quiz_questions][' . $qi . '][field_foxo_question_text]',
                sprintf( __( 'Otázka %d nemá vyplněný text.', 'cba' ), $q_num )
            );
        }

        if ( empty( $q_type ) ) {
            acf_add_validation_error(
                'acf[field_foxo_quiz_questions][' . $qi . '][field_foxo_question_type]',
                sprintf( __( 'Otázka %d nemá vybraný typ otázky.', 'cba' ), $q_num )
            );
        }

        if ( $q_uid && in_array( $q_uid, $question_uids, true ) ) {
            acf_add_validation_error(
                '',
                sprintf( __( 'Otázka %d má duplicitní ID. Kontaktujte administrátora.', 'cba' ), $q_num )
            );
        }
        if ( $q_uid ) {
            $question_uids[] = $q_uid;
        }

        if ( ! is_array( $answers ) || count( $answers ) < 2 ) {
            acf_add_validation_error(
                '',
                sprintf( __( 'Otázka %d musí mít alespoň dvě odpovědi.', 'cba' ), $q_num )
            );
            continue;
        }

        $correct_count = 0;
        $answer_uids   = [];

        foreach ( $answers as $ai => $answer ) {
            $a_num      = (int) $ai + 1;
            $a_uid      = trim( $answer['field_foxo_answer_unique_id'] ?? '' );
            $a_text     = trim( $answer['field_foxo_answer_text'] ?? '' );
            $is_correct = ! empty( $answer['field_foxo_answer_is_correct'] );

            if ( empty( $a_text ) ) {
                acf_add_validation_error(
                    'acf[field_foxo_quiz_questions][' . $qi . '][field_foxo_question_answers][' . $ai . '][field_foxo_answer_text]',
                    sprintf( __( 'Odpověď %d u otázky %d nemá vyplněný text.', 'cba' ), $a_num, $q_num )
                );
            }

            if ( $a_uid && in_array( $a_uid, $answer_uids, true ) ) {
                acf_add_validation_error(
                    '',
                    sprintf( __( 'Odpověď %d u otázky %d má duplicitní ID.', 'cba' ), $a_num, $q_num )
                );
            }
            if ( $a_uid ) {
                $answer_uids[] = $a_uid;
            }

            if ( $is_correct ) {
                $correct_count++;
            }
        }

        if ( $q_type === 'single_choice' && $correct_count !== 1 ) {
            acf_add_validation_error(
                '',
                sprintf(
                    _n(
                        'Otázka %1$d je typu „jedna správná odpověď", ale má označenu %2$d správnou odpověď. Musí být označena právě jedna.',
                        'Otázka %1$d je typu „jedna správná odpověď", ale má označeny %2$d správné odpovědi. Musí být označena právě jedna.',
                        $correct_count,
                        'cba'
                    ),
                    $q_num,
                    $correct_count
                )
            );
        } elseif ( $q_type === 'multiple_choice' && $correct_count < 1 ) {
            acf_add_validation_error(
                '',
                sprintf( __( 'Otázka %d nemá žádnou správnou odpověď.', 'cba' ), $q_num )
            );
        }
    }
}
