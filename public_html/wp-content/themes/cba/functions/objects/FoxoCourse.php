<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class FoxoCourseLessonItem {
    public int    $lessonId       = 0;
    public int    $courseId       = 0;
    public int    $position       = 0;
    public string $title          = '';
    public string $url            = '';
    public bool   $required       = true;
    public bool   $active         = true;
    public bool   $completed      = false;
    public bool   $current        = false;
    public int    $previousLessonId = 0;
    public int    $nextLessonId     = 0;
}

class FoxoCourse {
    public int    $id           = 0;
    public string $title        = '';
    public string $slug         = '';
    public string $url          = '';
    public string $intro        = '';
    public string $description  = '';
    public string $image        = '';
    public bool   $active       = true;
    public string $level        = '';
    public string $durationText = '';
    public string $accessMode   = 'public'; // public | login_required | locked
    public int    $finalQuizId  = 0;
    public int    $requiredScore = 0;
    /** @var FoxoCourseLessonItem[] */
    public array  $lessons      = [];
    public int    $progress     = 0; // 0-100

    public static function from_post( WP_Post $post, int $user_id = 0 ): self {
        $c               = new self();
        $c->id           = $post->ID;
        $c->title        = $post->post_title;
        $c->slug         = $post->post_name;
        $c->url          = get_permalink( $post->ID );
        $c->intro        = (string) get_field( 'foxo_course_intro', $post->ID );
        $c->description  = apply_filters( 'the_content', $post->post_content );
        $c->active       = (bool)   get_field( 'foxo_course_active', $post->ID );
        $c->level        = (string) get_field( 'foxo_course_level', $post->ID );
        $c->durationText = (string) get_field( 'foxo_course_duration', $post->ID );
        $c->accessMode   = (string) ( get_field( 'foxo_course_access_mode', $post->ID ) ?: 'public' );
        $c->requiredScore = (int)   get_field( 'foxo_course_required_score', $post->ID );

        $thumb = get_post_thumbnail_id( $post->ID );
        $c->image = $thumb ? wp_get_attachment_image_url( $thumb, 'large' ) : '';

        $final_quiz = get_field( 'foxo_course_final_quiz', $post->ID );
        $c->finalQuizId = is_a( $final_quiz, 'WP_Post' ) ? $final_quiz->ID : 0;

        $c->lessons = FoxoCourseService::get_lessons( $post->ID, $user_id );

        if ( $user_id ) {
            $c->progress = FoxoCourseService::get_progress( $post->ID, $user_id );
        }

        return $c;
    }
}
