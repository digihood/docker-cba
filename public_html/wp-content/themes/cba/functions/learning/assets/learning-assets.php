<?php
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'wp_enqueue_scripts', 'foxo_enqueue_learning_assets' );

function foxo_enqueue_learning_assets(): void {
    $theme_dir = get_stylesheet_directory();
    $theme_uri = get_stylesheet_directory_uri();
    $user_id   = get_current_user_id();

    // Global learning CSS – loaded on all learning post types and account page
    $is_learning = is_singular( [ 'foxo_quiz', 'foxo_course', 'foxo_lesson', 'foxo_decision_tree' ] )
        || is_post_type_archive( [ 'foxo_quiz', 'foxo_course', 'foxo_decision_tree' ] )
        || is_page_template( 'page-templates/template-muj-ucet.php' );

    if ( ! $is_learning ) return;

    // Quiz JS
    if ( is_singular( 'foxo_quiz' ) ) {
        $js_path = $theme_dir . '/assets/scripts/specific-scripts/quiz.js';
        wp_enqueue_script(
            'foxo-quiz',
            $theme_uri . '/assets/scripts/specific-scripts/quiz.js',
            [ 'jquery' ],
            file_exists( $js_path ) ? filemtime( $js_path ) : null,
            true
        );
        wp_localize_script( 'foxo-quiz', 'FoxoQuizData', [
            'restUrl'  => esc_url_raw( rest_url( 'foxo/v1/quizzes/' ) ),
            'nonce'    => wp_create_nonce( 'wp_rest' ),
            'userId'   => $user_id,
            'i18n'     => [
                'submit'      => __( 'Vyhodnotit', 'cba' ),
                'submitting'  => __( 'Vyhodnocuji…', 'cba' ),
                'unanswered'  => __( 'Odpovězte prosím na všechny otázky.', 'cba' ),
                'error'       => __( 'Chyba při vyhodnocení. Zkuste to znovu.', 'cba' ),
                'passed'      => __( 'Splněno!', 'cba' ),
                'failed'      => __( 'Nesplněno.', 'cba' ),
                'correct'     => __( 'Správně', 'cba' ),
                'incorrect'   => __( 'Špatně', 'cba' ),
                'outOf'       => __( 'z', 'cba' ),
                'points'      => __( 'bodů', 'cba' ),
            ],
        ] );
    }

    // Lesson JS
    if ( is_singular( 'foxo_lesson' ) ) {
        $js_path = $theme_dir . '/assets/scripts/specific-scripts/lesson.js';
        wp_enqueue_script(
            'foxo-lesson',
            $theme_uri . '/assets/scripts/specific-scripts/lesson.js',
            [ 'jquery' ],
            file_exists( $js_path ) ? filemtime( $js_path ) : null,
            true
        );
        $course_id = (int) ( $_GET['course'] ?? 0 );
        wp_localize_script( 'foxo-lesson', 'FoxoLessonData', [
            'restUrl'  => esc_url_raw( rest_url( 'foxo/v1/' ) ),
            'nonce'    => wp_create_nonce( 'wp_rest' ),
            'lessonId' => get_the_ID(),
            'courseId' => $course_id,
            'userId'   => $user_id,
            'i18n'     => [
                'completing'  => __( 'Ukládám…', 'cba' ),
                'completed'   => __( 'Lekce dokončena!', 'cba' ),
                'error'       => __( 'Chyba. Zkuste to znovu.', 'cba' ),
                'nextLesson'  => __( 'Pokračovat na další lekci', 'cba' ),
                'finalQuiz'   => __( 'Spustit závěrečný kvíz', 'cba' ),
                'backToCourse' => __( 'Zpět na kurz', 'cba' ),
            ],
        ] );
    }

    // Decision tree JS
    if ( is_singular( 'foxo_decision_tree' ) ) {
        $js_path = $theme_dir . '/assets/scripts/specific-scripts/decision-tree.js';
        wp_enqueue_script(
            'foxo-decision-tree',
            $theme_uri . '/assets/scripts/specific-scripts/decision-tree.js',
            [ 'jquery' ],
            file_exists( $js_path ) ? filemtime( $js_path ) : null,
            true
        );
        $post_id = get_the_ID();
        wp_localize_script( 'foxo-decision-tree', 'FoxoDecisionTreeData', [
            'restUrl'         => esc_url_raw( rest_url( 'foxo/v1/decision-trees/' ) ),
            'nonce'           => wp_create_nonce( 'wp_rest' ),
            'userId'          => $user_id,
            'progressEnabled' => (bool) get_field( 'tree_progress_enabled', $post_id ),
            'i18n'            => [
                'loading'  => __( 'Načítám…', 'cba' ),
                'error'    => __( 'Chyba. Zkuste to znovu.', 'cba' ),
                'nodeError' => __( 'Uzel nenalezen.', 'cba' ),
                'related'  => __( 'Doporučujeme', 'cba' ),
                'restart'  => __( 'Začít znovu', 'cba' ),
                'result'   => __( 'Váš výsledek', 'cba' ),
                'step'     => __( 'Krok %d', 'cba' ),
            ],
        ] );
    }

    // Account learning JS
    if ( is_page_template( 'page-templates/template-muj-ucet.php' ) ) {
        $js_path = $theme_dir . '/assets/scripts/specific-scripts/learning-account.js';
        wp_enqueue_script(
            'foxo-learning-account',
            $theme_uri . '/assets/scripts/specific-scripts/learning-account.js',
            [ 'jquery' ],
            file_exists( $js_path ) ? filemtime( $js_path ) : null,
            true
        );
        wp_localize_script( 'foxo-learning-account', 'FoxoAccountData', [
            'restUrl' => esc_url_raw( rest_url( 'foxo/v1/' ) ),
            'nonce'   => wp_create_nonce( 'wp_rest' ),
            'i18n'    => [
                'saving' => __( 'Ukládám…', 'cba' ),
                'saved'  => __( 'Uloženo!', 'cba' ),
                'error'  => __( 'Chyba při ukládání.', 'cba' ),
            ],
        ] );
    }
}
