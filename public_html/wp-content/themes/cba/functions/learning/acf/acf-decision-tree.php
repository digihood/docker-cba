<?php
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'acf/init', 'foxo_register_decision_tree_fields' );

function foxo_register_decision_tree_fields(): void {
    if ( ! function_exists( 'acf_add_local_field_group' ) ) return;

    acf_add_local_field_group( [
        'key'    => 'group_foxo_dt',
        'title'  => 'Rozhodovací strom – uzly a logika',
        'fields' => [

            // ── Nastavení stromu ──────────────────────────────────────────────
            [
                'key'           => 'field_foxo_dt_active',
                'label'         => 'Strom je aktivní',
                'name'          => 'tree_active',
                'type'          => 'true_false',
                'default_value' => 1,
                'ui'            => 1,
                'wrapper'       => [ 'width' => '25' ],
            ],
            [
                'key'           => 'field_foxo_dt_progress_enabled',
                'label'         => 'Zobrazit progress bar',
                'name'          => 'tree_progress_enabled',
                'type'          => 'true_false',
                'default_value' => 1,
                'ui'            => 1,
                'wrapper'       => [ 'width' => '25' ],
            ],
            [
                'key'           => 'field_foxo_dt_email_report_enabled',
                'label'         => 'Povolit e-mailový report (budoucí fáze)',
                'name'          => 'tree_email_report_enabled',
                'type'          => 'true_false',
                'default_value' => 0,
                'ui'            => 1,
                'wrapper'       => [ 'width' => '50' ],
            ],
            [
                'key'     => 'field_foxo_dt_intro_text',
                'label'   => 'Úvodní text',
                'name'    => 'tree_intro_text',
                'type'    => 'textarea',
                'rows'    => 3,
            ],
            [
                'key'          => 'field_foxo_dt_start_node_id',
                'label'        => 'Počáteční uzel',
                'name'         => 'tree_start_node_id',
                'type'         => 'select',
                'choices'      => [],
                'allow_null'   => 1,
                'placeholder'  => '— Použít první uzel —',
                'instructions' => 'Pokud nevyberete, použije se první uzel v repeateru. Nejprve uložte strom s uzly.',
                'wrapper'      => [ 'width' => '50' ],
            ],
            [
                'key'           => 'field_foxo_dt_related_content',
                'label'         => 'Doporučený obsah po dokončení',
                'name'          => 'tree_related_content',
                'type'          => 'relationship',
                'post_type'     => [ 'post', 'page', 'foxo_course', 'foxo_quiz' ],
                'filters'       => [ 'search' ],
                'return_format' => 'object',
            ],

            // ── Repeater uzlů ─────────────────────────────────────────────────
            [
                'key'          => 'field_foxo_dt_nodes',
                'label'        => 'Uzly stromu',
                'name'         => 'tree_nodes',
                'type'         => 'repeater',
                'button_label' => 'Přidat uzel',
                'layout'       => 'block',
                'sub_fields'   => [

                    // Identita uzlu
                    [
                        'key'          => 'field_foxo_dt_node_unique_id',
                        'label'        => 'ID uzlu',
                        'name'         => 'node_unique_id',
                        'type'         => 'unique_id',
                        'instructions' => 'Generuje se automaticky. Neměnit – zajišťuje stabilní propojení odpovědí.',
                        'wrapper'      => [ 'width' => '20' ],
                    ],
                    [
                        'key'           => 'field_foxo_dt_node_type',
                        'label'         => 'Typ uzlu',
                        'name'          => 'node_type',
                        'type'          => 'select',
                        'choices'       => [
                            'question' => 'Otázka',
                            'result'   => 'Výsledek',
                        ],
                        'default_value' => 'question',
                        'required'      => 1,
                        'wrapper'       => [ 'width' => '20' ],
                    ],
                    [
                        'key'          => 'field_foxo_dt_node_title',
                        'label'        => 'Název uzlu (interní)',
                        'name'         => 'node_title',
                        'type'         => 'text',
                        'required'     => 1,
                        'instructions' => 'Název pro orientaci v administraci a výběr cílových uzlů.',
                        'wrapper'      => [ 'width' => '60' ],
                    ],
                    [
                        'key'          => 'field_foxo_dt_node_text',
                        'label'        => 'Text otázky',
                        'name'         => 'node_text',
                        'type'         => 'textarea',
                        'rows'         => 3,
                        'instructions' => 'U otázky: text otázky. U výsledku: doplňkový text (hlavní doporučení je níže).',
                    ],
                    [
                        'key'     => 'field_foxo_dt_node_description',
                        'label'   => 'Doplňující popis (nepovinné)',
                        'name'    => 'node_description',
                        'type'    => 'textarea',
                        'rows'    => 2,
                        'wrapper' => [ 'width' => '60' ],
                    ],
                    [
                        'key'           => 'field_foxo_dt_node_image',
                        'label'         => 'Obrázek k uzlu (nepovinné)',
                        'name'          => 'node_image',
                        'type'          => 'image',
                        'return_format' => 'array',
                        'preview_size'  => 'thumbnail',
                        'wrapper'       => [ 'width' => '40' ],
                    ],

                    // ── Odpovědi (conditional: question) ──────────────────────
                    [
                        'key'               => 'field_foxo_dt_node_answers',
                        'label'             => 'Odpovědi',
                        'name'              => 'node_answers',
                        'type'              => 'repeater',
                        'button_label'      => 'Přidat odpověď',
                        'layout'            => 'table',
                        'min'               => 2,
                        'instructions'      => 'Cílový uzel vyberte po prvním uložení stromu, kdy se vygenerují ID uzlů.',
                        'conditional_logic' => [
                            [ [ 'field' => 'field_foxo_dt_node_type', 'operator' => '==', 'value' => 'question' ] ],
                        ],
                        'sub_fields'        => [
                            [
                                'key'          => 'field_foxo_dt_answer_unique_id',
                                'label'        => 'ID odpovědi',
                                'name'         => 'answer_unique_id',
                                'type'         => 'unique_id',
                                'instructions' => 'Neměnit.',
                                'wrapper'      => [ 'width' => '12' ],
                            ],
                            [
                                'key'      => 'field_foxo_dt_answer_text',
                                'label'    => 'Text odpovědi',
                                'name'     => 'answer_text',
                                'type'     => 'text',
                                'required' => 1,
                                'wrapper'  => [ 'width' => '30' ],
                            ],
                            [
                                'key'     => 'field_foxo_dt_answer_description',
                                'label'   => 'Popis',
                                'name'    => 'answer_description',
                                'type'    => 'text',
                                'wrapper' => [ 'width' => '20' ],
                            ],
                            [
                                'key'          => 'field_foxo_dt_target_node_uid',
                                'label'        => 'Cílový uzel',
                                'name'         => 'target_node_unique_id',
                                'type'         => 'select',
                                'choices'      => [],
                                'allow_null'   => 1,
                                'placeholder'  => '— Vyberte uzel —',
                                'instructions' => 'Uložte strom, vraťte se a vyberte.',
                                'wrapper'      => [ 'width' => '28' ],
                            ],
                            [
                                'key'     => 'field_foxo_dt_answer_cta_label',
                                'label'   => 'CTA text',
                                'name'    => 'answer_cta_label',
                                'type'    => 'text',
                                'wrapper' => [ 'width' => '10' ],
                            ],
                        ],
                    ],

                    // ── Výsledkový uzel (conditional: result) ─────────────────
                    [
                        'key'               => 'field_foxo_dt_result_title',
                        'label'             => 'Nadpis výsledku',
                        'name'              => 'result_title',
                        'type'              => 'text',
                        'conditional_logic' => [
                            [ [ 'field' => 'field_foxo_dt_node_type', 'operator' => '==', 'value' => 'result' ] ],
                        ],
                    ],
                    [
                        'key'               => 'field_foxo_dt_result_text',
                        'label'             => 'Text doporučení',
                        'name'              => 'result_text',
                        'type'              => 'wysiwyg',
                        'tabs'              => 'text',
                        'toolbar'           => 'basic',
                        'media_upload'      => 0,
                        'conditional_logic' => [
                            [ [ 'field' => 'field_foxo_dt_node_type', 'operator' => '==', 'value' => 'result' ] ],
                        ],
                    ],
                    [
                        'key'               => 'field_foxo_dt_result_cta_label',
                        'label'             => 'CTA – text tlačítka',
                        'name'              => 'result_cta_label',
                        'type'              => 'text',
                        'wrapper'           => [ 'width' => '50' ],
                        'conditional_logic' => [
                            [ [ 'field' => 'field_foxo_dt_node_type', 'operator' => '==', 'value' => 'result' ] ],
                        ],
                    ],
                    [
                        'key'               => 'field_foxo_dt_result_cta_url',
                        'label'             => 'CTA – URL',
                        'name'              => 'result_cta_url',
                        'type'              => 'url',
                        'wrapper'           => [ 'width' => '50' ],
                        'conditional_logic' => [
                            [ [ 'field' => 'field_foxo_dt_node_type', 'operator' => '==', 'value' => 'result' ] ],
                        ],
                    ],
                    [
                        'key'               => 'field_foxo_dt_result_related_content',
                        'label'             => 'Doporučený obsah výsledku',
                        'name'              => 'result_related_content',
                        'type'              => 'relationship',
                        'post_type'         => [ 'post', 'page', 'foxo_course', 'foxo_quiz' ],
                        'filters'           => [ 'search' ],
                        'return_format'     => 'object',
                        'conditional_logic' => [
                            [ [ 'field' => 'field_foxo_dt_node_type', 'operator' => '==', 'value' => 'result' ] ],
                        ],
                    ],
                    [
                        'key'               => 'field_foxo_dt_result_email_summary',
                        'label'             => 'Text pro budoucí e-mailový report',
                        'name'              => 'result_email_summary_text',
                        'type'              => 'textarea',
                        'rows'              => 2,
                        'conditional_logic' => [
                            [ [ 'field' => 'field_foxo_dt_node_type', 'operator' => '==', 'value' => 'result' ] ],
                        ],
                    ],
                ],
            ],
        ],
        'location'   => [
            [ [ 'param' => 'post_type', 'operator' => '==', 'value' => 'foxo_decision_tree' ] ],
        ],
        'menu_order' => 10,
    ] );
}

// Dynamicky naplní select pro výběr cílového uzlu odpovědi
add_filter( 'acf/load_field/name=target_node_unique_id', 'foxo_dt_load_node_choices' );
add_filter( 'acf/load_field/name=tree_start_node_id',    'foxo_dt_load_node_choices' );

function foxo_dt_load_node_choices( array $field ): array {
    $post_id = (int) ( $_GET['post'] ?? $_POST['post_ID'] ?? 0 );
    if ( ! $post_id || get_post_type( $post_id ) !== 'foxo_decision_tree' ) return $field;

    $nodes   = get_field( 'tree_nodes', $post_id );
    $choices = [ '' => '— Vyberte uzel —' ];

    if ( is_array( $nodes ) ) {
        foreach ( $nodes as $node ) {
            $uid  = $node['node_unique_id'] ?? '';
            $type = $node['node_type'] ?? '';
            if ( ! $uid ) continue;

            $title      = trim( $node['node_title'] ?? '' );
            $type_label = match ( $type ) {
                'question' => 'Otázka',
                'result'   => 'Výsledek',
                default    => $type,
            };
            $choices[ $uid ] = $title ? "[$type_label] $title" : "[$type_label] ($uid)";
        }
    }

    $field['choices'] = $choices;
    return $field;
}

// Validace při uložení
add_action( 'acf/validate_save_post', 'foxo_validate_decision_tree_on_save' );

function foxo_validate_decision_tree_on_save(): void {
    if ( ( $_POST['post_type'] ?? '' ) !== 'foxo_decision_tree' ) return;

    $nodes = $_POST['acf']['field_foxo_dt_nodes'] ?? [];
    if ( ! is_array( $nodes ) ) return;

    // Sbírám UID uzlů pro cross-referenci
    $node_uids    = [];
    $has_question = false;
    $has_result   = false;

    foreach ( $nodes as $qi => $node ) {
        $uid  = trim( $node['field_foxo_dt_node_unique_id'] ?? '' );
        $type = $node['field_foxo_dt_node_type'] ?? '';
        if ( $uid ) $node_uids[ $uid ] = (int) $qi + 1;
        if ( $type === 'question' ) $has_question = true;
        if ( $type === 'result' )   $has_result   = true;
    }

    if ( ! $has_question ) {
        acf_add_validation_error( '', __( 'Strom musí obsahovat alespoň jeden otázkový uzel.', 'cba' ) );
    }
    if ( ! $has_result ) {
        acf_add_validation_error( '', __( 'Strom musí obsahovat alespoň jeden výsledkový uzel.', 'cba' ) );
    }

    foreach ( $nodes as $qi => $node ) {
        $n_num = (int) $qi + 1;
        $type  = $node['field_foxo_dt_node_type'] ?? '';
        $title = trim( $node['field_foxo_dt_node_title'] ?? '' );

        if ( empty( $title ) ) {
            acf_add_validation_error(
                'acf[field_foxo_dt_nodes][' . $qi . '][field_foxo_dt_node_title]',
                sprintf( __( 'Uzel %d nemá název.', 'cba' ), $n_num )
            );
        }

        if ( $type === 'question' ) {
            $answers = $node['field_foxo_dt_node_answers'] ?? [];

            if ( ! is_array( $answers ) || count( $answers ) < 2 ) {
                acf_add_validation_error( '', sprintf( __( 'Uzel %d nemá alespoň dvě odpovědi.', 'cba' ), $n_num ) );
                continue;
            }

            foreach ( $answers as $ai => $answer ) {
                $a_num  = (int) $ai + 1;
                $a_text = trim( $answer['field_foxo_dt_answer_text'] ?? '' );
                $target = trim( $answer['field_foxo_dt_target_node_uid'] ?? '' );

                if ( empty( $a_text ) ) {
                    acf_add_validation_error(
                        'acf[field_foxo_dt_nodes][' . $qi . '][field_foxo_dt_node_answers][' . $ai . '][field_foxo_dt_answer_text]',
                        sprintf( __( 'Odpověď %d u uzlu %d nemá text.', 'cba' ), $a_num, $n_num )
                    );
                }

                if ( empty( $target ) ) {
                    acf_add_validation_error( '', sprintf(
                        __( 'Odpověď %d u uzlu %d nevede na žádný cílový uzel.', 'cba' ),
                        $a_num, $n_num
                    ) );
                } elseif ( ! empty( $node_uids ) && ! isset( $node_uids[ $target ] ) ) {
                    acf_add_validation_error( '', sprintf(
                        __( 'Cílový uzel odpovědi %d u uzlu %d neexistuje ve stromu.', 'cba' ),
                        $a_num, $n_num
                    ) );
                }
            }
        }

        if ( $type === 'result' ) {
            $result_text = trim( $node['field_foxo_dt_result_text'] ?? '' );
            if ( empty( $result_text ) ) {
                acf_add_validation_error( '', sprintf(
                    __( 'Výsledkový uzel %d nemá vyplněný text doporučení.', 'cba' ),
                    $n_num
                ) );
            }
        }
    }
}
