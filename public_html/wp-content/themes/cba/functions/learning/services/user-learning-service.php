<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class FoxoUserLearningService {

    const META_KEY = 'foxo_user_learning_data';

    private static function default_structure(): array {
        return [
            'profile'     => [
                'title'     => '',
                'firstName' => '',
                'lastName'  => '',
                'street'    => '',
                'city'      => '',
                'zip'       => '',
                'country'   => '',
                'companyId' => '',
                'vatId'     => '',
            ],
            'lastVisited' => [
                'course'       => [ 'id' => null, 'title' => '', 'url' => '', 'visitedAt' => '' ],
                'lesson'       => [ 'id' => null, 'courseId' => null, 'title' => '', 'url' => '', 'visitedAt' => '' ],
                'quiz'         => [ 'id' => null, 'title' => '', 'url' => '', 'visitedAt' => '' ],
                'decisionTree' => [ 'id' => null, 'title' => '', 'url' => '', 'visitedAt' => '', 'lastResultNodeUid' => '' ],
            ],
            'quizStats' => [],
        ];
    }

    public static function get_user_learning_data( int $user_id ): array {
        $raw = get_user_meta( $user_id, self::META_KEY, true );
        if ( ! $raw ) return self::default_structure();

        $decoded = is_string( $raw ) ? json_decode( $raw, true ) : $raw;
        if ( ! is_array( $decoded ) ) return self::default_structure();

        return array_replace_recursive( self::default_structure(), $decoded );
    }

    public static function save_user_learning_data( int $user_id, array $data ): void {
        update_user_meta( $user_id, self::META_KEY, wp_json_encode( $data ) );
    }

    public static function ensure_initialized( int $user_id ): void {
        $existing = get_user_meta( $user_id, self::META_KEY, true );
        if ( ! $existing ) {
            $wp_user = get_userdata( $user_id );
            $data    = self::default_structure();
            if ( $wp_user ) {
                $data['profile']['firstName'] = (string) get_user_meta( $user_id, 'first_name', true );
                $data['profile']['lastName']  = (string) get_user_meta( $user_id, 'last_name', true );
            }
            self::save_user_learning_data( $user_id, $data );
        }
    }

    public static function get_profile( int $user_id ): FoxoUserProfile {
        $data    = self::get_user_learning_data( $user_id );
        $raw     = $data['profile'] ?? [];
        $profile = new FoxoUserProfile();
        $profile->userId    = $user_id;
        $profile->title     = (string) ( $raw['title']     ?? '' );
        $profile->firstName = (string) ( $raw['firstName'] ?? '' );
        $profile->lastName  = (string) ( $raw['lastName']  ?? '' );
        $profile->street    = (string) ( $raw['street']    ?? '' );
        $profile->city      = (string) ( $raw['city']      ?? '' );
        $profile->zip       = (string) ( $raw['zip']       ?? '' );
        $profile->country   = (string) ( $raw['country']   ?? '' );
        $profile->companyId = (string) ( $raw['companyId'] ?? '' );
        $profile->vatId     = (string) ( $raw['vatId']     ?? '' );
        return $profile;
    }

    public static function save_profile( int $user_id, array $input ): array {
        $allowed = [ 'title', 'firstName', 'lastName', 'street', 'city', 'zip', 'country', 'companyId', 'vatId' ];
        $data    = self::get_user_learning_data( $user_id );

        foreach ( $allowed as $key ) {
            if ( isset( $input[ $key ] ) ) {
                $data['profile'][ $key ] = sanitize_text_field( $input[ $key ] );
            }
        }

        // Sync first/last name with WP core
        if ( isset( $input['firstName'] ) ) {
            update_user_meta( $user_id, 'first_name', sanitize_text_field( $input['firstName'] ) );
        }
        if ( isset( $input['lastName'] ) ) {
            update_user_meta( $user_id, 'last_name', sanitize_text_field( $input['lastName'] ) );
            $display = trim(
                sanitize_text_field( $input['firstName'] ?? get_user_meta( $user_id, 'first_name', true ) )
                . ' ' .
                sanitize_text_field( $input['lastName'] )
            );
            wp_update_user( [ 'ID' => $user_id, 'display_name' => $display ] );
        }

        self::save_user_learning_data( $user_id, $data );
        return [ 'success' => true, 'message' => __( 'Profil byl uložen.', 'cba' ) ];
    }

    public static function get_quiz_stats( int $user_id ): array {
        $data = self::get_user_learning_data( $user_id );
        return $data['quizStats'] ?? [];
    }
}
