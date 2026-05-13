<?php
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'rest_api_init', 'foxo_register_account_routes' );

function foxo_register_account_routes(): void {
    $ns   = 'foxo/v1';
    $auth = 'is_user_logged_in';

    register_rest_route( $ns, '/account/profile', [
        [ 'methods' => 'GET',  'callback' => 'foxo_rest_get_profile',  'permission_callback' => $auth ],
        [ 'methods' => 'POST', 'callback' => 'foxo_rest_save_profile', 'permission_callback' => $auth ],
    ] );

    register_rest_route( $ns, '/account/activity', [
        'methods'             => 'GET',
        'callback'            => 'foxo_rest_get_activity',
        'permission_callback' => $auth,
    ] );

    register_rest_route( $ns, '/account/courses', [
        'methods'             => 'GET',
        'callback'            => 'foxo_rest_get_account_courses',
        'permission_callback' => $auth,
    ] );

    register_rest_route( $ns, '/account/quizzes', [
        'methods'             => 'GET',
        'callback'            => 'foxo_rest_get_account_quizzes',
        'permission_callback' => $auth,
    ] );
}

function foxo_rest_get_profile(): WP_REST_Response {
    $profile = FoxoUserLearningService::get_profile( get_current_user_id() );
    return new WP_REST_Response( [
        'userId'    => $profile->userId,
        'title'     => $profile->title,
        'firstName' => $profile->firstName,
        'lastName'  => $profile->lastName,
        'street'    => $profile->street,
        'city'      => $profile->city,
        'zip'       => $profile->zip,
        'country'   => $profile->country,
        'companyId' => $profile->companyId,
        'vatId'     => $profile->vatId,
        'email'     => wp_get_current_user()->user_email,
    ], 200 );
}

function foxo_rest_save_profile( WP_REST_Request $request ): WP_REST_Response {
    $input  = $request->get_json_params() ?: $request->get_params();
    $result = FoxoUserLearningService::save_profile( get_current_user_id(), $input );
    return new WP_REST_Response( $result, 200 );
}

function foxo_rest_get_activity(): WP_REST_Response {
    $data = FoxoUserLearningService::get_user_learning_data( get_current_user_id() );
    return new WP_REST_Response( $data['lastVisited'] ?? [], 200 );
}

function foxo_rest_get_account_courses(): WP_REST_Response {
    $user_id = get_current_user_id();
    $courses = FoxoCourseService::get_all_active( $user_id );

    $data = array_map( fn( $c ) => [
        'id'           => $c->id,
        'title'        => $c->title,
        'intro'        => $c->intro,
        'url'          => $c->url,
        'image'        => $c->image,
        'lessonsCount' => count( $c->lessons ),
        'progress'     => $c->progress,
        'completed'    => $c->progress >= 100,
    ], $courses );

    return new WP_REST_Response( $data, 200 );
}

function foxo_rest_get_account_quizzes(): WP_REST_Response {
    $user_id    = get_current_user_id();
    $quiz_stats = FoxoUserLearningService::get_quiz_stats( $user_id );

    $posts = get_posts( [
        'post_type'      => 'foxo_quiz',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'meta_query'     => [ [ 'key' => 'foxo_quiz_active', 'value' => '1' ] ],
    ] );

    $data = array_map( function ( $p ) use ( $quiz_stats ) {
        $key   = 'quiz_' . $p->ID;
        $stats = $quiz_stats[ $key ] ?? [];
        return [
            'id'           => $p->ID,
            'title'        => $p->post_title,
            'intro'        => (string) get_field( 'foxo_quiz_intro', $p->ID ),
            'url'          => get_permalink( $p->ID ),
            'bestScore'    => $stats['bestScore']    ?? null,
            'lastScore'    => $stats['lastScore']    ?? null,
            'passed'       => $stats['passed']       ?? false,
            'attempts'     => $stats['attempts']     ?? 0,
            'lastAttemptAt' => $stats['lastAttemptAt'] ?? null,
        ];
    }, $posts );

    return new WP_REST_Response( $data, 200 );
}
