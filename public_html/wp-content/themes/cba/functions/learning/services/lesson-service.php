<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class FoxoLessonService {

    public static function complete( int $lesson_id, int $course_id, int $user_id ): array {
        if ( ! $user_id ) {
            return [ 'success' => false, 'message' => __( 'Nejste přihlášen.', 'cba' ) ];
        }

        $now = current_time( 'mysql' );

        // Mark lesson as completed
        FoxoLearningDB::upsert_lesson_data( $user_id, $course_id, $lesson_id, 'completed', 'completed', 'true' );
        FoxoLearningDB::upsert_lesson_data( $user_id, $course_id, $lesson_id, 'completed', 'completed_at', $now );

        // Update last lesson in course data
        if ( $course_id ) {
            FoxoLearningDB::upsert_course_data( $user_id, $course_id, 'last_lesson', 'last_lesson_id', (string) $lesson_id );
        }

        // Update last visited in usermeta
        self::update_last_visited_usermeta( $user_id, $lesson_id, $course_id, $now );

        // Recalculate course progress
        $progress = $course_id ? FoxoCourseService::recalculate_progress( $course_id, $user_id ) : 0;

        // Find next lesson
        $next_lesson_url = null;
        $final_quiz_url  = null;

        if ( $course_id ) {
            $lessons = FoxoCourseService::get_lessons( $course_id, $user_id );
            foreach ( $lessons as $item ) {
                if ( $item->lessonId === $lesson_id ) {
                    if ( $item->nextLessonId ) {
                        $next_lesson_url = add_query_arg( 'course', $course_id, get_permalink( $item->nextLessonId ) );
                    } else {
                        // Last lesson — check for final quiz
                        $final_quiz_id = (int) get_field( 'foxo_course_final_quiz', $course_id );
                        if ( $final_quiz_id ) {
                            $final_quiz_url = get_permalink( $final_quiz_id );
                        }
                    }
                    break;
                }
            }
        }

        return [
            'success'        => true,
            'progress'       => $progress,
            'next_lesson_url' => $next_lesson_url,
            'final_quiz_url'  => $final_quiz_url,
            'course_url'      => $course_id ? get_permalink( $course_id ) : null,
        ];
    }

    private static function update_last_visited_usermeta( int $user_id, int $lesson_id, int $course_id, string $now ): void {
        $post = get_post( $lesson_id );
        if ( ! $post ) return;

        $learning_data = FoxoUserLearningService::get_user_learning_data( $user_id );
        $learning_data['lastVisited']['lesson'] = [
            'id'        => $lesson_id,
            'courseId'  => $course_id,
            'title'     => $post->post_title,
            'url'       => add_query_arg( 'course', $course_id, get_permalink( $lesson_id ) ),
            'visitedAt' => $now,
        ];
        FoxoUserLearningService::save_user_learning_data( $user_id, $learning_data );
    }

    public static function record_visit( int $user_id, int $lesson_id, int $course_id ): void {
        if ( ! $user_id ) return;
        $post = get_post( $lesson_id );
        if ( ! $post ) return;

        $now = current_time( 'mysql' );

        FoxoLearningDB::upsert_lesson_data( $user_id, $course_id, $lesson_id, 'visited', 'visited_at', $now );

        if ( $course_id ) {
            FoxoLearningDB::upsert_course_data( $user_id, $course_id, 'last_lesson', 'last_lesson_id', (string) $lesson_id );
        }

        $learning_data = FoxoUserLearningService::get_user_learning_data( $user_id );
        $learning_data['lastVisited']['lesson'] = [
            'id'        => $lesson_id,
            'courseId'  => $course_id,
            'title'     => $post->post_title,
            'url'       => add_query_arg( 'course', $course_id, get_permalink( $lesson_id ) ),
            'visitedAt' => $now,
        ];
        FoxoUserLearningService::save_user_learning_data( $user_id, $learning_data );
    }
}
