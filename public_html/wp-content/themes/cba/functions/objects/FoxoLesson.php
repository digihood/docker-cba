<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class FoxoLesson {
    public int    $id          = 0;
    public string $title       = '';
    public string $slug        = '';
    public string $url         = '';
    public string $excerpt     = '';
    public string $content     = '';
    public string $type        = 'text'; // text | video | combined
    public string $videoUrl    = '';
    public array  $materials   = [];
    public string $durationText = '';
    public bool   $active      = true;

    public static function from_post( WP_Post $post ): self {
        $l               = new self();
        $l->id           = $post->ID;
        $l->title        = $post->post_title;
        $l->slug         = $post->post_name;
        $l->url          = get_permalink( $post->ID );
        $l->excerpt      = get_the_excerpt( $post );
        $l->content      = apply_filters( 'the_content', $post->post_content );
        $l->type         = (string) ( get_field( 'foxo_lesson_type', $post->ID ) ?: 'text' );
        $l->videoUrl     = (string) get_field( 'foxo_lesson_video_url', $post->ID );
        $l->durationText = (string) get_field( 'foxo_lesson_duration', $post->ID );
        $l->active       = (bool)   get_field( 'foxo_lesson_active', $post->ID );

        $raw_materials = get_field( 'foxo_lesson_materials', $post->ID );
        $l->materials = is_array( $raw_materials ) ? array_map( function( $m ) {
            return [
                'title' => (string) ( $m['material_title'] ?? '' ),
                'url'   => is_array( $m['material_file'] ?? null )
                    ? (string) ( $m['material_file']['url'] ?? '' )
                    : (string) ( $m['material_file'] ?? '' ),
            ];
        }, $raw_materials ) : [];

        return $l;
    }
}

class FoxoUserProfile {
    public int    $userId    = 0;
    public string $title     = '';
    public string $firstName = '';
    public string $lastName  = '';
    public string $street    = '';
    public string $city      = '';
    public string $zip       = '';
    public string $country   = '';
    public string $companyId = '';
    public string $vatId     = '';
}
