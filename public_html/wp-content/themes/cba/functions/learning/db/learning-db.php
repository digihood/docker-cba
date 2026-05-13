<?php
if ( ! defined( 'ABSPATH' ) ) exit;

define( 'FOXO_LEARNING_DB_VERSION', '1.1.0' );

class FoxoLearningDB {

    const VERSION_KEY = 'foxo_learning_db_version';

    public static function maybe_install(): void {
        if ( get_option( self::VERSION_KEY ) !== FOXO_LEARNING_DB_VERSION ) {
            self::install();
        }
    }

    public static function install(): void {
        global $wpdb;
        $charset = $wpdb->get_charset_collate();
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';

        dbDelta( "CREATE TABLE {$wpdb->prefix}foxo_quiz_answers (
            id          BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            user_id     BIGINT(20) UNSIGNED NOT NULL DEFAULT 0,
            quiz_id     BIGINT(20) UNSIGNED NOT NULL,
            question_uid VARCHAR(64) NOT NULL,
            answer_uid  VARCHAR(64) NOT NULL DEFAULT '',
            answer_value LONGTEXT NOT NULL DEFAULT '',
            attempt_uid VARCHAR(64) NOT NULL DEFAULT '',
            is_correct  TINYINT(1) NOT NULL DEFAULT 0,
            created_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY user_quiz (user_id, quiz_id),
            KEY attempt (attempt_uid)
        ) $charset;" );

        dbDelta( "CREATE TABLE {$wpdb->prefix}foxo_course_user_data (
            id          BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            user_id     BIGINT(20) UNSIGNED NOT NULL,
            course_id   BIGINT(20) UNSIGNED NOT NULL,
            data_uid    VARCHAR(64) NOT NULL,
            data_key    VARCHAR(64) NOT NULL,
            data_value  LONGTEXT NOT NULL DEFAULT '',
            created_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            UNIQUE KEY user_course_uid_key (user_id, course_id, data_uid, data_key),
            KEY user_course (user_id, course_id)
        ) $charset;" );

        dbDelta( "CREATE TABLE {$wpdb->prefix}foxo_decision_tree_user_data (
            id                BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            user_id           BIGINT(20) UNSIGNED NOT NULL DEFAULT 0,
            tree_id           BIGINT(20) UNSIGNED NOT NULL,
            node_unique_id    VARCHAR(64) NOT NULL DEFAULT '',
            answer_unique_id  VARCHAR(64) NOT NULL DEFAULT '',
            target_node_uid   VARCHAR(64) NOT NULL DEFAULT '',
            session_uid       VARCHAR(64) NOT NULL DEFAULT '',
            path_order        INT UNSIGNED NOT NULL DEFAULT 0,
            is_final          TINYINT(1) NOT NULL DEFAULT 0,
            result_node_uid   VARCHAR(64) NOT NULL DEFAULT '',
            created_at        DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY user_tree (user_id, tree_id),
            KEY session (session_uid)
        ) $charset;" );

        dbDelta( "CREATE TABLE {$wpdb->prefix}foxo_lesson_user_data (
            id          BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            user_id     BIGINT(20) UNSIGNED NOT NULL,
            course_id   BIGINT(20) UNSIGNED NOT NULL DEFAULT 0,
            lesson_id   BIGINT(20) UNSIGNED NOT NULL,
            data_uid    VARCHAR(64) NOT NULL,
            data_key    VARCHAR(64) NOT NULL,
            data_value  LONGTEXT NOT NULL DEFAULT '',
            created_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            UNIQUE KEY user_lesson_uid_key (user_id, course_id, lesson_id, data_uid, data_key),
            KEY user_lesson (user_id, lesson_id)
        ) $charset;" );

        update_option( self::VERSION_KEY, FOXO_LEARNING_DB_VERSION );
    }

    // ---- Quiz answers -------------------------------------------------------

    public static function save_quiz_answer( array $data ): int|false {
        global $wpdb;
        $wpdb->insert(
            $wpdb->prefix . 'foxo_quiz_answers',
            [
                'user_id'      => absint( $data['user_id'] ?? 0 ),
                'quiz_id'      => absint( $data['quiz_id'] ),
                'question_uid' => sanitize_text_field( $data['question_uid'] ),
                'answer_uid'   => sanitize_text_field( $data['answer_uid'] ?? '' ),
                'answer_value' => wp_json_encode( $data['answer_value'] ?? '' ),
                'attempt_uid'  => sanitize_text_field( $data['attempt_uid'] ?? '' ),
                'is_correct'   => $data['is_correct'] ? 1 : 0,
            ],
            [ '%d', '%d', '%s', '%s', '%s', '%s', '%d' ]
        );
        return $wpdb->insert_id ?: false;
    }

    public static function get_quiz_answers_by_attempt( string $attempt_uid ): array {
        global $wpdb;
        return $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM {$wpdb->prefix}foxo_quiz_answers WHERE attempt_uid = %s ORDER BY id ASC",
                $attempt_uid
            ),
            ARRAY_A
        ) ?: [];
    }

    public static function get_user_quiz_attempts( int $user_id, int $quiz_id ): array {
        global $wpdb;
        return $wpdb->get_results(
            $wpdb->prepare(
                "SELECT DISTINCT attempt_uid, MAX(created_at) AS attempt_date,
                    SUM(is_correct) AS correct,
                    COUNT(*) AS total
                 FROM {$wpdb->prefix}foxo_quiz_answers
                 WHERE user_id = %d AND quiz_id = %d
                 GROUP BY attempt_uid
                 ORDER BY attempt_date DESC",
                $user_id,
                $quiz_id
            ),
            ARRAY_A
        ) ?: [];
    }

    // ---- Course user data ---------------------------------------------------

    public static function upsert_course_data( int $user_id, int $course_id, string $data_uid, string $data_key, string $data_value ): void {
        global $wpdb;
        $wpdb->query(
            $wpdb->prepare(
                "INSERT INTO {$wpdb->prefix}foxo_course_user_data
                    (user_id, course_id, data_uid, data_key, data_value, created_at, updated_at)
                 VALUES (%d, %d, %s, %s, %s, NOW(), NOW())
                 ON DUPLICATE KEY UPDATE data_value = VALUES(data_value), updated_at = NOW()",
                $user_id, $course_id, $data_uid, $data_key, $data_value
            )
        );
    }

    public static function get_course_data( int $user_id, int $course_id, string $data_uid = '', string $data_key = '' ): array {
        global $wpdb;
        $sql  = "SELECT * FROM {$wpdb->prefix}foxo_course_user_data WHERE user_id = %d AND course_id = %d";
        $args = [ $user_id, $course_id ];
        if ( $data_uid ) { $sql .= ' AND data_uid = %s'; $args[] = $data_uid; }
        if ( $data_key ) { $sql .= ' AND data_key = %s'; $args[] = $data_key; }
        return $wpdb->get_results( $wpdb->prepare( $sql, ...$args ), ARRAY_A ) ?: [];
    }

    // ---- Lesson user data ---------------------------------------------------

    public static function upsert_lesson_data( int $user_id, int $course_id, int $lesson_id, string $data_uid, string $data_key, string $data_value ): void {
        global $wpdb;
        $wpdb->query(
            $wpdb->prepare(
                "INSERT INTO {$wpdb->prefix}foxo_lesson_user_data
                    (user_id, course_id, lesson_id, data_uid, data_key, data_value, created_at, updated_at)
                 VALUES (%d, %d, %d, %s, %s, %s, NOW(), NOW())
                 ON DUPLICATE KEY UPDATE data_value = VALUES(data_value), updated_at = NOW()",
                $user_id, $course_id, $lesson_id, $data_uid, $data_key, $data_value
            )
        );
    }

    public static function get_lesson_data( int $user_id, int $lesson_id, int $course_id = 0 ): array {
        global $wpdb;
        if ( $course_id ) {
            return $wpdb->get_results(
                $wpdb->prepare(
                    "SELECT * FROM {$wpdb->prefix}foxo_lesson_user_data WHERE user_id = %d AND lesson_id = %d AND course_id = %d",
                    $user_id, $lesson_id, $course_id
                ),
                ARRAY_A
            ) ?: [];
        }
        return $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM {$wpdb->prefix}foxo_lesson_user_data WHERE user_id = %d AND lesson_id = %d",
                $user_id, $lesson_id
            ),
            ARRAY_A
        ) ?: [];
    }

    // ---- Decision tree user data --------------------------------------------

    public static function save_tree_step( array $data ): int|false {
        global $wpdb;
        $wpdb->insert(
            $wpdb->prefix . 'foxo_decision_tree_user_data',
            [
                'user_id'          => absint( $data['user_id'] ?? 0 ),
                'tree_id'          => absint( $data['tree_id'] ),
                'node_unique_id'   => sanitize_text_field( $data['node_unique_id'] ?? '' ),
                'answer_unique_id' => sanitize_text_field( $data['answer_unique_id'] ?? '' ),
                'target_node_uid'  => sanitize_text_field( $data['target_node_uid'] ?? '' ),
                'session_uid'      => sanitize_text_field( $data['session_uid'] ?? '' ),
                'path_order'       => absint( $data['path_order'] ?? 0 ),
                'is_final'         => $data['is_final'] ? 1 : 0,
                'result_node_uid'  => sanitize_text_field( $data['result_node_uid'] ?? '' ),
            ],
            [ '%d', '%d', '%s', '%s', '%s', '%s', '%d', '%d', '%s' ]
        );
        return $wpdb->insert_id ?: false;
    }

    public static function get_tree_session( string $session_uid ): array {
        global $wpdb;
        return $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM {$wpdb->prefix}foxo_decision_tree_user_data
                 WHERE session_uid = %s ORDER BY path_order ASC",
                $session_uid
            ),
            ARRAY_A
        ) ?: [];
    }

    public static function get_user_tree_sessions( int $user_id, int $tree_id ): array {
        global $wpdb;
        return $wpdb->get_results(
            $wpdb->prepare(
                "SELECT DISTINCT session_uid, MIN(created_at) AS started_at,
                    MAX(created_at) AS last_step_at,
                    MAX(is_final) AS completed,
                    MAX(result_node_uid) AS result_node_uid,
                    COUNT(*) AS steps
                 FROM {$wpdb->prefix}foxo_decision_tree_user_data
                 WHERE user_id = %d AND tree_id = %d
                 GROUP BY session_uid
                 ORDER BY last_step_at DESC",
                $user_id,
                $tree_id
            ),
            ARRAY_A
        ) ?: [];
    }

    public static function get_completed_lessons_for_course( int $user_id, int $course_id ): array {
        global $wpdb;
        return $wpdb->get_col(
            $wpdb->prepare(
                "SELECT DISTINCT lesson_id FROM {$wpdb->prefix}foxo_lesson_user_data
                 WHERE user_id = %d AND course_id = %d AND data_uid = 'completed' AND data_value = 'true'",
                $user_id, $course_id
            )
        ) ?: [];
    }
}
