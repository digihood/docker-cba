<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'CbaUser' ) ) {

    class CbaUser {

        private $wp_user;

        public function __construct( $user_id = null ) {
            $id = $user_id !== null ? (int) $user_id : get_current_user_id();
            $this->wp_user = $id ? get_userdata( $id ) : false;
        }

        public static function current(): self {
            return new self();
        }

        public function is_valid(): bool {
            return $this->wp_user instanceof WP_User;
        }

        public function is_logged_in(): bool {
            return is_user_logged_in() && $this->is_valid();
        }

        public function get_id(): int {
            return $this->is_valid() ? (int) $this->wp_user->ID : 0;
        }

        public function get_email(): string {
            return $this->is_valid() ? $this->wp_user->user_email : '';
        }

        public function get_first_name(): string {
            return $this->is_valid() ? (string) get_user_meta( $this->get_id(), 'first_name', true ) : '';
        }

        public function get_last_name(): string {
            return $this->is_valid() ? (string) get_user_meta( $this->get_id(), 'last_name', true ) : '';
        }

        public function get_display_name(): string {
            return $this->is_valid() ? $this->wp_user->display_name : '';
        }

        public function get_roles(): array {
            return $this->is_valid() ? (array) $this->wp_user->roles : [];
        }

        /**
         * Save profile fields. Accepts: first_name, last_name, email, password.
         * Returns ['success' => bool, 'message' => string].
         */
        public function save( array $data ): array {
            if ( ! $this->is_valid() ) {
                return [ 'success' => false, 'message' => __( 'Uživatel nenalezen.', 'cba' ) ];
            }

            $user_id = $this->get_id();
            $update  = [ 'ID' => $user_id ];

            if ( isset( $data['first_name'] ) ) {
                update_user_meta( $user_id, 'first_name', sanitize_text_field( $data['first_name'] ) );
            }

            if ( isset( $data['last_name'] ) ) {
                update_user_meta( $user_id, 'last_name', sanitize_text_field( $data['last_name'] ) );
            }

            if ( ! empty( $data['email'] ) ) {
                $email = sanitize_email( $data['email'] );
                if ( ! is_email( $email ) ) {
                    return [ 'success' => false, 'message' => __( 'Zadejte platnou e-mailovou adresu.', 'cba' ) ];
                }
                if ( $email !== $this->wp_user->user_email ) {
                    if ( email_exists( $email ) ) {
                        return [ 'success' => false, 'message' => __( 'Tento e-mail je již používán jiným účtem.', 'cba' ) ];
                    }
                    $update['user_email'] = $email;
                }
            }

            if ( ! empty( $data['password'] ) ) {
                if ( strlen( $data['password'] ) < 8 ) {
                    return [ 'success' => false, 'message' => __( 'Heslo musí mít alespoň 8 znaků.', 'cba' ) ];
                }
                $update['user_pass'] = $data['password'];
            }

            $result = wp_update_user( $update );

            if ( is_wp_error( $result ) ) {
                return [ 'success' => false, 'message' => $result->get_error_message() ];
            }

            $this->wp_user = get_userdata( $user_id );

            return [ 'success' => true, 'message' => __( 'Profil byl úspěšně uložen.', 'cba' ) ];
        }

        /**
         * Get raw saved data for a specific user meta key (JSON-encoded).
         */
        public function get_saved_calculator_data( string $meta_key ): ?array {
            if ( ! $this->is_valid() ) {
                return null;
            }
            $raw = get_user_meta( $this->get_id(), $meta_key, true );
            if ( ! $raw ) {
                return null;
            }
            $decoded = json_decode( $raw, true );
            return is_array( $decoded ) ? $decoded : null;
        }
    }
}
