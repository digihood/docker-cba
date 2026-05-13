<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class FoxoCourseService {

    /**
     * Returns ordered list of FoxoCourseLessonItem for a course.
     */
    public static function get_lessons( int $course_id, int $user_id = 0, int $current_lesson_id = 0 ): array {
        $raw_lessons = get_field( 'foxo_course_lessons', $course_id );
        if ( ! is_array( $raw_lessons ) ) return [];

        $completed_ids = $user_id
            ? array_map( 'intval', FoxoLearningDB::get_completed_lessons_for_course( $user_id, $course_id ) )
            : [];

        $items = [];
        foreach ( $raw_lessons as $i => $row ) {
            $lesson_post = is_a( $row['lesson_id'] ?? null, 'WP_Post' )
                ? $row['lesson_id']
                : ( is_int( $row['lesson_id'] ?? null ) ? get_post( $row['lesson_id'] ) : null );

            if ( ! $lesson_post || $lesson_post->post_status !== 'publish' ) continue;

            $item               = new FoxoCourseLessonItem();
            $item->lessonId     = $lesson_post->ID;
            $item->courseId     = $course_id;
            $item->position     = $i + 1;
            $item->title        = ! empty( $row['lesson_custom_title'] )
                ? $row['lesson_custom_title']
                : $lesson_post->post_title;
            $item->url          = add_query_arg( 'course', $course_id, get_permalink( $lesson_post->ID ) );
            $item->required     = (bool) ( $row['lesson_required'] ?? true );
            $item->active       = (bool) get_field( 'foxo_lesson_active', $lesson_post->ID );
            $item->completed    = in_array( $lesson_post->ID, $completed_ids, true );
            $item->current      = $current_lesson_id && $lesson_post->ID === $current_lesson_id;

            $items[] = $item;
        }

        // Set prev/next references
        $count = count( $items );
        for ( $i = 0; $i < $count; $i++ ) {
            $items[ $i ]->previousLessonId = $i > 0          ? $items[ $i - 1 ]->lessonId : 0;
            $items[ $i ]->nextLessonId     = $i < $count - 1 ? $items[ $i + 1 ]->lessonId : 0;
        }

        return $items;
    }

    public static function get_progress( int $course_id, int $user_id ): int {
        $rows = FoxoLearningDB::get_course_data( $user_id, $course_id, 'progress', 'progress_percentage' );
        if ( $rows ) return (int) $rows[0]['data_value'];

        return self::recalculate_progress( $course_id, $user_id );
    }

    public static function recalculate_progress( int $course_id, int $user_id ): int {
        $lessons     = self::get_lessons( $course_id );
        $required    = array_filter( $lessons, fn( $l ) => $l->required );
        $total       = count( $required );

        if ( $total === 0 ) return 0;

        $completed_ids = array_map( 'intval', FoxoLearningDB::get_completed_lessons_for_course( $user_id, $course_id ) );
        $done = count( array_filter( $required, fn( $l ) => in_array( $l->lessonId, $completed_ids, true ) ) );

        $pct = (int) round( ( $done / $total ) * 100 );

        FoxoLearningDB::upsert_course_data( $user_id, $course_id, 'progress', 'progress_percentage', (string) $pct );

        if ( $pct >= 100 ) {
            FoxoLearningDB::upsert_course_data( $user_id, $course_id, 'completed', 'completed', 'true' );
        }

        return $pct;
    }

    public static function record_visit( int $user_id, int $course_id ): void {
        if ( ! $user_id ) return;
        $post = get_post( $course_id );
        if ( ! $post ) return;

        $now = current_time( 'mysql' );
        FoxoLearningDB::upsert_course_data( $user_id, $course_id, 'last_visited', 'last_visited_at', $now );

        $learning_data = FoxoUserLearningService::get_user_learning_data( $user_id );
        $learning_data['lastVisited']['course'] = [
            'id'        => $course_id,
            'title'     => $post->post_title,
            'url'       => get_permalink( $course_id ),
            'visitedAt' => $now,
        ];
        FoxoUserLearningService::save_user_learning_data( $user_id, $learning_data );
    }

    public static function get_all_active( int $user_id = 0 ): array {
        $posts = get_posts( [
            'post_type'      => 'foxo_course',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'orderby'        => 'menu_order',
            'order'          => 'ASC',
            'meta_query'     => [ [ 'key' => 'foxo_course_active', 'value' => '1', 'compare' => '=' ] ],
        ] );

        $result = [];
        foreach ( $posts as $p ) {
            $c = FoxoCourse::from_post( $p, $user_id );
            if ( $c->accessMode === 'locked' ) continue;
            $result[] = $c;
        }
        return $result;
    }
}
