<?php
if ( ! defined( 'ABSPATH' ) ) exit;

// Database
require_once __DIR__ . '/db/learning-db.php';

// Data objects
require_once get_template_directory() . '/functions/objects/FoxoQuiz.php';
require_once get_template_directory() . '/functions/objects/FoxoCourse.php';
require_once get_template_directory() . '/functions/objects/FoxoLesson.php';
require_once get_template_directory() . '/functions/objects/FoxoDecisionTree.php';

// Services (order matters: UserLearning before others)
require_once __DIR__ . '/services/user-learning-service.php';
require_once __DIR__ . '/services/quiz-service.php';
require_once __DIR__ . '/services/course-service.php';
require_once __DIR__ . '/services/lesson-service.php';
require_once __DIR__ . '/services/decision-tree-service.php';

// CPT registration
require_once __DIR__ . '/cpt/cpt-learning.php';

// ACF field groups
require_once __DIR__ . '/acf/acf-quiz.php';
require_once __DIR__ . '/acf/acf-course.php';
require_once __DIR__ . '/acf/acf-lesson.php';
require_once __DIR__ . '/acf/acf-decision-tree.php';

// REST API
require_once __DIR__ . '/rest/rest-quizzes.php';
require_once __DIR__ . '/rest/rest-courses.php';
require_once __DIR__ . '/rest/rest-lessons.php';
require_once __DIR__ . '/rest/rest-account.php';
require_once __DIR__ . '/rest/rest-decision-trees.php';

// Frontend assets
require_once __DIR__ . '/assets/learning-assets.php';

// Install / update DB tables on init (version check prevents repeated runs)
add_action( 'init', [ 'FoxoLearningDB', 'maybe_install' ], 1 );

// Initialize foxo_user_learning_data for newly registered users
add_action( 'user_register', function ( int $user_id ): void {
    FoxoUserLearningService::ensure_initialized( $user_id );
}, 10 );

// Record lesson/course visit from frontend (hook on template load)
add_action( 'template_redirect', function (): void {
    if ( ! is_user_logged_in() ) return;

    $user_id = get_current_user_id();

    if ( is_singular( 'foxo_lesson' ) ) {
        $lesson_id = get_the_ID();
        $course_id = (int) ( $_GET['course'] ?? 0 );
        FoxoLessonService::record_visit( $user_id, $lesson_id, $course_id );
        if ( $course_id ) {
            FoxoCourseService::record_visit( $user_id, $course_id );
        }
    } elseif ( is_singular( 'foxo_course' ) ) {
        FoxoCourseService::record_visit( $user_id, get_the_ID() );
    } elseif ( is_singular( 'foxo_quiz' ) ) {
        FoxoQuizService::record_visit( $user_id, get_the_ID() );
    } elseif ( is_singular( 'foxo_decision_tree' ) ) {
        FoxoDecisionTreeService::record_visit( $user_id, get_the_ID() );
    }
} );

// Redirect login-only courses for non-logged users
add_action( 'template_redirect', function (): void {
    if ( is_singular( 'foxo_course' ) && ! is_user_logged_in() ) {
        $access = get_field( 'foxo_course_access_mode', get_the_ID() );
        if ( $access === 'login_required' || $access === 'locked' ) {
            wp_redirect( linksd1g1::login_registration() );
            exit;
        }
    }

    if ( is_singular( 'foxo_lesson' ) && ! is_user_logged_in() ) {
        $course_id = (int) ( $_GET['course'] ?? 0 );
        if ( $course_id ) {
            $access = get_field( 'foxo_course_access_mode', $course_id );
            if ( $access === 'login_required' || $access === 'locked' ) {
                wp_redirect( linksd1g1::login_registration() );
                exit;
            }
        }
    }
} );
