<?php
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'rest_api_init', 'foxo_register_course_routes' );

function foxo_register_course_routes(): void {
    $ns = 'foxo/v1';

    register_rest_route( $ns, '/courses', [
        'methods'             => 'GET',
        'callback'            => 'foxo_rest_get_courses',
        'permission_callback' => '__return_true',
    ] );

    register_rest_route( $ns, '/courses/(?P<id>\d+)', [
        'methods'             => 'GET',
        'callback'            => 'foxo_rest_get_course',
        'permission_callback' => '__return_true',
    ] );

    register_rest_route( $ns, '/courses/(?P<id>\d+)/lessons', [
        'methods'             => 'GET',
        'callback'            => 'foxo_rest_get_course_lessons',
        'permission_callback' => '__return_true',
    ] );

    register_rest_route( $ns, '/courses/(?P<id>\d+)/progress', [
        'methods'             => 'GET',
        'callback'            => 'foxo_rest_get_course_progress',
        'permission_callback' => 'is_user_logged_in',
    ] );

    register_rest_route( $ns, '/courses/(?P<id>\d+)/visit', [
        'methods'             => 'POST',
        'callback'            => 'foxo_rest_visit_course',
        'permission_callback' => 'is_user_logged_in',
    ] );
}

function foxo_rest_get_courses( WP_REST_Request $request ): WP_REST_Response {
    $user_id = get_current_user_id();
    $courses = FoxoCourseService::get_all_active( $user_id );

    $data = array_map( fn( $c ) => [
        'id'       => $c->id,
        'title'    => $c->title,
        'intro'    => $c->intro,
        'url'      => $c->url,
        'image'    => $c->image,
        'level'    => $c->level,
        'duration' => $c->durationText,
        'lessons'  => count( $c->lessons ),
        'progress' => $c->progress,
    ], $courses );

    return new WP_REST_Response( $data, 200 );
}

function foxo_rest_get_course( WP_REST_Request $request ): WP_REST_Response|WP_Error {
    $post = get_post( (int) $request['id'] );
    if ( ! $post || $post->post_type !== 'foxo_course' ) {
        return new WP_Error( 'not_found', __( 'Kurz nenalezen.', 'cba' ), [ 'status' => 404 ] );
    }
    $course = FoxoCourse::from_post( $post, get_current_user_id() );
    return new WP_REST_Response( foxo_course_to_array( $course ), 200 );
}

function foxo_rest_get_course_lessons( WP_REST_Request $request ): WP_REST_Response|WP_Error {
    $course_id = (int) $request['id'];
    if ( get_post_type( $course_id ) !== 'foxo_course' ) {
        return new WP_Error( 'not_found', __( 'Kurz nenalezen.', 'cba' ), [ 'status' => 404 ] );
    }
    $user_id    = get_current_user_id();
    $lessons    = FoxoCourseService::get_lessons( $course_id, $user_id );
    return new WP_REST_Response( array_map( 'foxo_lesson_item_to_array', $lessons ), 200 );
}

function foxo_rest_get_course_progress( WP_REST_Request $request ): WP_REST_Response {
    $course_id = (int) $request['id'];
    $user_id   = get_current_user_id();
    return new WP_REST_Response( [
        'progress' => FoxoCourseService::get_progress( $course_id, $user_id ),
    ], 200 );
}

function foxo_rest_visit_course( WP_REST_Request $request ): WP_REST_Response {
    FoxoCourseService::record_visit( get_current_user_id(), (int) $request['id'] );
    return new WP_REST_Response( [ 'success' => true ], 200 );
}

function foxo_course_to_array( FoxoCourse $c ): array {
    return [
        'id'          => $c->id,
        'title'       => $c->title,
        'url'         => $c->url,
        'intro'       => $c->intro,
        'image'       => $c->image,
        'level'       => $c->level,
        'duration'    => $c->durationText,
        'accessMode'  => $c->accessMode,
        'finalQuizId' => $c->finalQuizId,
        'progress'    => $c->progress,
        'lessons'     => array_map( 'foxo_lesson_item_to_array', $c->lessons ),
    ];
}

function foxo_lesson_item_to_array( FoxoCourseLessonItem $l ): array {
    return [
        'lessonId'         => $l->lessonId,
        'courseId'         => $l->courseId,
        'position'         => $l->position,
        'title'            => $l->title,
        'url'              => $l->url,
        'required'         => $l->required,
        'active'           => $l->active,
        'completed'        => $l->completed,
        'current'          => $l->current,
        'previousLessonId' => $l->previousLessonId,
        'nextLessonId'     => $l->nextLessonId,
    ];
}
