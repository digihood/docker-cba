<?php

/**
 * Tlačítka (odkazy) – výstup přes Tailwind utility třídy
 *
 * @author Digihood
 */

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

if( ! class_exists( 'd1g1BuilderButton' ) )
{
    class d1g1BuilderButton
    {
        // Base Tailwind classes shared across all button variants
        const BASE = 'inline-flex items-center justify-center w-fit text-sm font-bold leading-none py-3.5 px-7 border-2 border-transparent rounded-full no-underline uppercase tracking-[0.07em] font-display transition-colors cursor-pointer whitespace-nowrap hover:no-underline focus:no-underline focus:outline-none';

        // Variant-specific Tailwind classes
        const PRIMARY   = 'bg-primary border-primary text-white hover:bg-primary-dark hover:border-primary-dark hover:text-white focus:bg-primary-dark focus:border-primary-dark focus:text-white';
        const SECONDARY = 'bg-secondary border-secondary text-white hover:bg-[#8A7459] hover:border-[#8A7459] hover:text-white focus:bg-[#8A7459] focus:border-[#8A7459] focus:text-white';
        const OUTLINE   = 'bg-transparent border-primary text-primary hover:bg-primary hover:border-primary hover:text-white focus:bg-primary focus:border-primary focus:text-white';
        const WHITE     = 'bg-white border-white text-primary hover:bg-gray-light hover:border-gray-light hover:text-primary-dark focus:bg-gray-light focus:border-gray-light focus:text-primary-dark';
        const SUCCESS   = 'bg-[#0d9488] border-[#0d9488] text-white hover:bg-[#0a7a70] hover:border-[#0a7a70] hover:text-white focus:bg-[#0a7a70] focus:border-[#0a7a70] focus:text-white';

        public function __construct() {}

        /**
         * Returns full Tailwind class string for a given variant.
         *
         * @param string $variant  'primary'|'secondary'|'outline'|'white'|'success'
         * @param string $extra    Additional Tailwind classes to append
         * @return string
         */
        public static function get_btn_class( $variant = 'primary', $extra = '' ) {
            $classes = self::BASE . ' ' . self::get_variant_class( $variant );
            if ( $extra ) $classes .= ' ' . $extra;
            return trim( $classes );
        }

        /**
         * Returns an <a> link with variant Tailwind button classes.
         *
         * @param string       $title     Link text
         * @param string       $link      Href URL
         * @param string       $variant   Button variant
         * @param string       $extra     Extra Tailwind classes
         * @param string       $id        HTML id attribute
         * @param array|string $attribute Extra HTML attributes
         * @return string HTML
         */
        public static function get_variant_link( $title, $link, $variant = 'primary', $extra = '', $id = '', $attribute = [] ) {
            $classes = self::get_btn_class( $variant, $extra );
            return self::get_link( $title, $link, $classes, $id, $attribute );
        }

        /**
         * Returns a raw <a> link with a custom class string.
         *
         * @param string       $title     Link text
         * @param string       $link      Href URL
         * @param string       $class     Full class string
         * @param string       $id        HTML id attribute
         * @param array|string $attribute Extra HTML attributes
         * @return string HTML
         */
        public static function get_link( $title, $link, $class = '', $id = '', $attribute = [] ) {
            $html = '';
            if ( $title && $link ) {
                $attr = self::get_attribute( $attribute );
                $html = '<a href="' . $link . '" class="' . $class . '"'
                    . ( $id ? ' id="' . $id . '"' : '' )
                    . ( $attr ? ' ' . $attr : '' )
                    . '>' . $title . '</a>';
            }
            return $html;
        }

        private static function get_variant_class( $variant ) {
            switch ( $variant ) {
                case 'secondary': return self::SECONDARY;
                case 'outline':   return self::OUTLINE;
                case 'white':     return self::WHITE;
                case 'success':   return self::SUCCESS;
                default:          return self::PRIMARY;
            }
        }

        private static function get_attribute( $attributes ) {
            if ( is_array( $attributes ) ) {
                $return = '';
                foreach ( $attributes as $key => $value ) {
                    if ( $key ) $return .= $key . '="' . $value . '" ';
                }
                return trim( $return );
            }
            return (string) $attributes;
        }
    }
}
