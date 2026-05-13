<?php
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'rest_api_init', 'foxo_register_lesson_routes' );

function foxo_register_lesson_routes(): void {
    $ns = 'foxo/v1';

    register_rest_route( $ns, '/lessons/(?P<id>\d+)', [
        'methods'             => 'GET',
        'callback'            => 'foxo_rest_get_lesson',
        'permission_callback' => '__return_true',
    ] );

    register_rest_route( $ns, '/lessons/(?P<id>\d+)/complete', [
        'methods'             => 'POST',
        'callback'            => 'foxo_rest_complete_lesson',
        'permission_callback' => 'is_user_logged_in',
    ] );

    register_rest_route( $ns, '/lessons/(?P<id>\d+)/visit', [
        'methods'             => 'POST',
        'callback'            => 'foxo_rest_visit_lesson',
        'permission_callback' => 'is_user_logged_in',
    ] );

    register_rest_route( $ns, '/lessons/(?P<id>\d+)/dots', [
        'methods'             => 'GET',
        'callback'            => 'foxo_rest_get_lesson_dots',
        'permission_callback' => '__return_true',
    ] );
}

function foxo_rest_get_lesson( WP_REST_Request $request ): WP_REST_Response|WP_Error {
    $post = get_post( (int) $request['id'] );
    if ( ! $post || $post->post_type !== 'foxo_lesson' ) {
        return new WP_Error( 'not_found', __( 'Lekce nenalezena.', 'cba' ), [ 'status' => 404 ] );
    }
    $lesson    = FoxoLesson::from_post( $post );
    $course_id = (int) ( $request->get_param( 'course' ) ?? 0 );
    $user_id   = get_current_user_id();

    $response = [
        'id'          => $lesson->id,
        'title'       => $lesson->title,
        'type'        => $lesson->type,
        'videoUrl'    => $lesson->videoUrl,
        'durationText' => $lesson->durationText,
        'materials'   => $lesson->materials,
        'navigation'  => foxo_get_lesson_navigation( $lesson->id, $course_id, $user_id ),
    ];

    return new WP_REST_Response( $response, 200 );
}

function foxo_rest_complete_lesson( WP_REST_Request $request ): WP_REST_Response|WP_Error {
    $lesson_id = (int) $request['id'];
    $course_id = (int) ( $request->get_param( 'course_id' ) ?? 0 );
    $user_id   = get_current_user_id();

    $result = FoxoLessonService::complete( $lesson_id, $course_id, $user_id );

    if ( ! $result['success'] ) {
        return new WP_Error( 'error', $result['message'], [ 'status' => 403 ] );
    }

    return new WP_REST_Response( $result, 200 );
}

function foxo_rest_visit_lesson( WP_REST_Request $request ): WP_REST_Response {
    $lesson_id = (int) $request['id'];
    $course_id = (int) ( $request->get_param( 'course_id' ) ?? 0 );
    FoxoLessonService::record_visit( get_current_user_id(), $lesson_id, $course_id );
    return new WP_REST_Response( [ 'success' => true ], 200 );
}

function foxo_rest_get_lesson_dots( WP_REST_Request $request ): WP_REST_Response|WP_Error {
    $lesson_id = (int) $request['id'];
    $course_id = (int) ( $request->get_param( 'course' ) ?? 0 );

    if ( ! $course_id ) {
        return new WP_Error( 'missing_course', __( 'Chybí course parametr.', 'cba' ), [ 'status' => 400 ] );
    }

    $user_id = get_current_user_id();
    $lessons = FoxoCourseService::get_lessons( $course_id, $user_id, $lesson_id );

    $dots = array_map( fn( $l ) => [
        'lessonId' => $l->lessonId,
        'title'    => $l->title,
        'url'      => $l->url,
        'state'    => $l->current ? 'current' : ( $l->completed ? 'completed' : 'available' ),
    ], $lessons );

    return new WP_REST_Response( $dots, 200 );
}

function foxo_get_lesson_navigation( int $lesson_id, int $course_id, int $user_id ): array {
    if ( ! $course_id ) return [];

    $lessons = FoxoCourseService::get_lessons( $course_id, $user_id, $lesson_id );
    foreach ( $lessons as $item ) {
        if ( $item->lessonId !== $lesson_id ) continue;
        return [
            'courseId'         => $course_id,
            'courseUrl'        => get_permalink( $course_id ),
            'courseTitle'      => get_the_title( $course_id ),
            'previousLessonId' => $item->previousLessonId,
            'previousLessonUrl' => $item->previousLessonId
                ? add_query_arg( 'course', $course_id, get_permalink( $item->previousLessonId ) )
                : null,
            'nextLessonId'     => $item->nextLessonId,
            'nextLessonUrl'    => $item->nextLessonId
                ? add_query_arg( 'course', $course_id, get_permalink( $item->nextLessonId ) )
                : null,
            'position'         => $item->position,
            'total'            => count( $lessons ),
        ];
    }
    return [];
}
