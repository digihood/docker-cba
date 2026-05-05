<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'CbaCalculator' ) ) {

    class CbaCalculator {

        private $post;

        /** Slug → meta key for saved user data */
        private static $meta_keys = [
            'planovac-rozpoctu' => '_budget_planner_data',
            'sporeni-na-duchod' => '_retirement_savings_data',
            'ciste-jmeni'       => '_net_worth_current_data',
        ];

        /** Slug → emoji icon */
        private static $icons = [
            'planovac-rozpoctu' => '💰',
            'sporeni-na-duchod' => '🏦',
            'ciste-jmeni'       => '⚖️',
        ];

        public function __construct( $post_or_id ) {
            $this->post = is_a( $post_or_id, 'WP_Post' ) ? $post_or_id : get_post( (int) $post_or_id );
        }

        /** Returns all published calculators as CbaCalculator[] */
        public static function get_all(): array {
            $query = new WP_Query( [
                'post_type'      => 'calculator',
                'post_status'    => 'publish',
                'posts_per_page' => -1,
                'orderby'        => 'menu_order title',
                'order'          => 'ASC',
                'no_found_rows'  => true,
            ] );
            return array_map( fn( $p ) => new self( $p ), $query->posts );
        }

        public function is_valid(): bool {
            return $this->post instanceof WP_Post && $this->post->post_type === 'calculator';
        }

        public function get_id(): int {
            return $this->is_valid() ? (int) $this->post->ID : 0;
        }

        public function get_title(): string {
            return $this->is_valid() ? get_the_title( $this->post ) : '';
        }

        public function get_slug(): string {
            return $this->is_valid() ? $this->post->post_name : '';
        }

        public function get_permalink(): string {
            return $this->is_valid() ? get_permalink( $this->post ) : '';
        }

        public function get_excerpt(): string {
            return $this->is_valid() ? get_the_excerpt( $this->post ) : '';
        }

        public function get_thumbnail_url( string $size = 'medium' ): string {
            return (string) get_the_post_thumbnail_url( $this->get_id(), $size );
        }

        public function get_icon(): string {
            return self::$icons[ $this->get_slug() ] ?? '🧮';
        }

        /** Meta key used to store user's saved data for this calculator. */
        public function get_user_meta_key(): string {
            return self::$meta_keys[ $this->get_slug() ] ?? '_calculator_data_' . $this->get_slug();
        }

        /**
         * Full config (categories + items + result_messages) merged with defaults.
         * Delegates to budget_planner_get_config() for the budget planner.
         */
        public function get_config(): array {
            if ( $this->get_slug() === 'planovac-rozpoctu' && function_exists( 'budget_planner_get_config' ) ) {
                return budget_planner_get_config( $this->get_id() );
            }
            if ( $this->get_slug() === 'sporeni-na-duchod' && function_exists( 'retirement_savings_get_config' ) ) {
                return retirement_savings_get_config( $this->get_id() );
            }
            if ( $this->get_slug() === 'ciste-jmeni' && function_exists( 'net_worth_get_config' ) ) {
                return net_worth_get_config( $this->get_id() );
            }
            return [];
        }

        /**
         * Returns the CbaUser's saved data for this calculator, or null.
         */
        public function get_user_data( CbaUser $user ): ?array {
            return $user->get_saved_calculator_data( $this->get_user_meta_key() );
        }
    }
}
