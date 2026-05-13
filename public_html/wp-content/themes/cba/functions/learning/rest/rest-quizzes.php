<?php
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'rest_api_init', 'foxo_register_quiz_routes' );

function foxo_register_quiz_routes(): void {
    $ns = 'foxo/v1';

    register_rest_route( $ns, '/quizzes', [
        'methods'             => 'GET',
        'callback'            => 'foxo_rest_get_quizzes',
        'permission_callback' => '__return_true',
    ] );

    register_rest_route( $ns, '/quizzes/(?P<id>\d+)', [
        'methods'             => 'GET',
        'callback'            => 'foxo_rest_get_quiz',
        'permission_callback' => '__return_true',
        'args'                => [ 'id' => [ 'validate_callback' => fn( $v ) => is_numeric( $v ) ] ],
    ] );

    register_rest_route( $ns, '/quizzes/(?P<id>\d+)/evaluate', [
        'methods'             => 'POST',
        'callback'            => 'foxo_rest_evaluate_quiz',
        'permission_callback' => '__return_true',
        'args'                => [ 'id' => [ 'validate_callback' => fn( $v ) => is_numeric( $v ) ] ],
    ] );

    register_rest_route( $ns, '/quizzes/(?P<id>\d+)/visit', [
        'methods'             => 'POST',
        'callback'            => 'foxo_rest_visit_quiz',
        'permission_callback' => 'is_user_logged_in',
    ] );
}

function foxo_rest_get_quizzes( WP_REST_Request $request ): WP_REST_Response {
    $posts = get_posts( [
        'post_type'      => 'foxo_quiz',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'orderby'        => 'menu_order',
        'order'          => 'ASC',
    ] );

    $data = array_filter( array_map( function ( $p ) {
        $active = get_field( 'foxo_quiz_active', $p->ID );
        if ( ! $active ) return null;
        return [
            'id'    => $p->ID,
            'title' => $p->post_title,
            'intro' => (string) get_field( 'foxo_quiz_intro', $p->ID ),
            'url'   => get_permalink( $p->ID ),
        ];
    }, $posts ) );

    return new WP_REST_Response( array_values( $data ), 200 );
}

function foxo_rest_get_quiz( WP_REST_Request $request ): WP_REST_Response|WP_Error {
    $post = get_post( (int) $request['id'] );
    if ( ! $post || $post->post_type !== 'foxo_quiz' ) {
        return new WP_Error( 'not_found', __( 'Kvíz nenalezen.', 'cba' ), [ 'status' => 404 ] );
    }

    $quiz = FoxoQuiz::from_post( $post );

    // Strip correct answer flags — frontend must not know which are correct
    $questions = array_map( function ( $q ) {
        return [
            'uid'         => $q->uid,
            'text'        => $q->text,
            'type'        => $q->type,
            'image'       => $q->image,
            'explanation' => '', // hide until evaluated
            'answers'     => array_map( fn( $a ) => [
                'uid'  => $a->uid,
                'text' => $a->text,
            ], $q->answers ),
        ];
    }, $quiz->questions );

    return new WP_REST_Response( [
        'id'            => $quiz->id,
        'title'         => $quiz->title,
        'intro'         => $quiz->intro,
        'active'        => $quiz->active,
        'allowRepeat'   => $quiz->allowRepeat,
        'requiredScore' => $quiz->requiredScore,
        'questions'     => $questions,
    ], 200 );
}

function foxo_rest_evaluate_quiz( WP_REST_Request $request ): WP_REST_Response|WP_Error {
    $quiz_id = (int) $request['id'];
    $post    = get_post( $quiz_id );
    if ( ! $post || $post->post_type !== 'foxo_quiz' ) {
        return new WP_Error( 'not_found', __( 'Kvíz nenalezen.', 'cba' ), [ 'status' => 404 ] );
    }

    $submitted = $request->get_param( 'answers' );
    if ( ! is_array( $submitted ) ) {
        return new WP_Error( 'invalid_data', __( 'Chybí odpovědi.', 'cba' ), [ 'status' => 400 ] );
    }

    // Sanitize submitted answers
    $clean = [];
    foreach ( $submitted as $q_uid => $a_val ) {
        $q_uid = sanitize_text_field( $q_uid );
        if ( is_array( $a_val ) ) {
            $clean[ $q_uid ] = array_map( 'sanitize_text_field', $a_val );
        } else {
            $clean[ $q_uid ] = sanitize_text_field( $a_val );
        }
    }

    $user_id = get_current_user_id();
    $result  = FoxoQuizService::evaluate( $quiz_id, $clean, $user_id );
    $quiz    = FoxoQuiz::from_post( $post );

    $response_data = [
        'quizId'     => $result->quizId,
        'attemptUid' => $result->attemptUid,
        'score'      => $result->score,
        'maxScore'   => $result->maxScore,
        'percentage' => $result->percentage,
        'passed'     => $result->passed,
        'resultText' => $result->passed ? $quiz->resultPassText : $quiz->resultFailText,
        'questionResults' => [],
        'relatedContent'  => array_map( fn( $p ) => [
            'id'    => $p->ID,
            'title' => $p->post_title,
            'url'   => get_permalink( $p->ID ),
        ], $quiz->relatedContent ),
    ];

    if ( $quiz->showCorrectAnswers ) {
        $response_data['questionResults'] = $result->questionResults;
    } else {
        // Return per-question correct/incorrect without exposing correct answer UIDs
        foreach ( $result->questionResults as $uid => $qr ) {
            $response_data['questionResults'][ $uid ] = [
                'is_correct' => $qr['is_correct'],
                'feedback'   => $qr['answer_feedback'] ?? [],
            ];
        }
    }

    return new WP_REST_Response( $response_data, 200 );
}

function foxo_rest_visit_quiz( WP_REST_Request $request ): WP_REST_Response {
    FoxoQuizService::record_visit( get_current_user_id(), (int) $request['id'] );
    return new WP_REST_Response( [ 'success' => true ], 200 );
}
