<?php
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'rest_api_init', 'foxo_register_decision_tree_routes' );

function foxo_register_decision_tree_routes(): void {
    $ns = 'foxo/v1';

    register_rest_route( $ns, '/decision-trees', [
        'methods'             => 'GET',
        'callback'            => 'foxo_rest_get_decision_trees',
        'permission_callback' => '__return_true',
    ] );

    register_rest_route( $ns, '/decision-trees/(?P<id>\d+)', [
        'methods'             => 'GET',
        'callback'            => 'foxo_rest_get_decision_tree',
        'permission_callback' => '__return_true',
        'args'                => [ 'id' => [ 'validate_callback' => fn( $v ) => is_numeric( $v ) ] ],
    ] );

    register_rest_route( $ns, '/decision-trees/(?P<id>\d+)/start', [
        'methods'             => 'POST',
        'callback'            => 'foxo_rest_start_decision_tree',
        'permission_callback' => '__return_true',
        'args'                => [ 'id' => [ 'validate_callback' => fn( $v ) => is_numeric( $v ) ] ],
    ] );

    register_rest_route( $ns, '/decision-trees/(?P<id>\d+)/step', [
        'methods'             => 'POST',
        'callback'            => 'foxo_rest_step_decision_tree',
        'permission_callback' => '__return_true',
        'args'                => [ 'id' => [ 'validate_callback' => fn( $v ) => is_numeric( $v ) ] ],
    ] );

    register_rest_route( $ns, '/decision-trees/(?P<id>\d+)/visit', [
        'methods'             => 'POST',
        'callback'            => 'foxo_rest_visit_decision_tree',
        'permission_callback' => 'is_user_logged_in',
        'args'                => [ 'id' => [ 'validate_callback' => fn( $v ) => is_numeric( $v ) ] ],
    ] );
}

function foxo_rest_get_decision_trees(): WP_REST_Response {
    $posts = get_posts( [
        'post_type'      => 'foxo_decision_tree',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'orderby'        => 'menu_order',
        'order'          => 'ASC',
    ] );

    $data = array_values( array_filter( array_map( function ( $p ) {
        if ( ! get_field( 'tree_active', $p->ID ) ) return null;
        return [
            'id'    => $p->ID,
            'title' => $p->post_title,
            'intro' => (string) get_field( 'tree_intro_text', $p->ID ),
            'url'   => get_permalink( $p->ID ),
        ];
    }, $posts ) ) );

    return new WP_REST_Response( $data, 200 );
}

function foxo_rest_get_decision_tree( WP_REST_Request $request ): WP_REST_Response|WP_Error {
    $post = get_post( (int) $request['id'] );
    if ( ! $post || $post->post_type !== 'foxo_decision_tree' ) {
        return new WP_Error( 'not_found', __( 'Rozhodovací strom nenalezen.', 'cba' ), [ 'status' => 404 ] );
    }

    $tree = FoxoDecisionTree::from_post( $post );
    return new WP_REST_Response( [
        'id'              => $tree->id,
        'title'           => $tree->title,
        'introText'       => $tree->introText,
        'active'          => $tree->active,
        'progressEnabled' => $tree->progressEnabled,
    ], 200 );
}

function foxo_rest_start_decision_tree( WP_REST_Request $request ): WP_REST_Response|WP_Error {
    $post = get_post( (int) $request['id'] );
    if ( ! $post || $post->post_type !== 'foxo_decision_tree' ) {
        return new WP_Error( 'not_found', __( 'Rozhodovací strom nenalezen.', 'cba' ), [ 'status' => 404 ] );
    }

    $tree = FoxoDecisionTree::from_post( $post );

    if ( ! $tree->active ) {
        return new WP_Error( 'inactive', __( 'Tento strom momentálně není dostupný.', 'cba' ), [ 'status' => 403 ] );
    }

    $start_node = FoxoDecisionTreeService::get_start_node( $tree );
    if ( ! $start_node ) {
        return new WP_Error( 'no_nodes', __( 'Strom neobsahuje žádné uzly.', 'cba' ), [ 'status' => 422 ] );
    }

    $session_uid = 'dt_' . bin2hex( random_bytes( 8 ) );

    return new WP_REST_Response( [
        'sessionUid'      => $session_uid,
        'treeId'          => $tree->id,
        'progressEnabled' => $tree->progressEnabled,
        'node'            => FoxoDecisionTreeService::format_node_for_frontend( $start_node ),
    ], 200 );
}

function foxo_rest_step_decision_tree( WP_REST_Request $request ): WP_REST_Response|WP_Error {
    $tree_id = (int) $request['id'];
    $params  = $request->get_json_params() ?: $request->get_params();

    $node_uid   = sanitize_text_field( $params['node_uid']   ?? '' );
    $answer_uid = sanitize_text_field( $params['answer_uid'] ?? '' );
    $session_uid = sanitize_text_field( $params['session_uid'] ?? '' );
    $path_order  = max( 1, (int) ( $params['path_order'] ?? 1 ) );

    if ( ! $node_uid || ! $answer_uid || ! $session_uid ) {
        return new WP_Error( 'missing_params', __( 'Chybí povinné parametry.', 'cba' ), [ 'status' => 400 ] );
    }

    $user_id = get_current_user_id();
    $result  = FoxoDecisionTreeService::process_step( $tree_id, $node_uid, $answer_uid, $session_uid, $user_id, $path_order );

    if ( isset( $result['error'] ) ) {
        return new WP_Error( 'step_error', $result['error'], [ 'status' => $result['status'] ?? 400 ] );
    }

    return new WP_REST_Response( $result, 200 );
}

function foxo_rest_visit_decision_tree( WP_REST_Request $request ): WP_REST_Response {
    FoxoDecisionTreeService::record_visit( get_current_user_id(), (int) $request['id'] );
    return new WP_REST_Response( [ 'success' => true ], 200 );
}
