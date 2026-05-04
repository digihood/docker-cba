<?php

/**
 * Vkládání obrázků 
 *
 * 
 * @author Digihood
 */ 

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

if( ! class_exists( 'd1g1BuilderImage' ) )
{
	class d1g1BuilderImage
	{

		public function __construct()
		{

        }

        /**
        * 	Vrátí obrázek s lazyload scriptem a noscriptem
        * 
        * 	@author Digihood
        * 	@return echo
        */
        
        public static function basic_img( $thumbnail_id, $size="", $figureClass="", $id="", $data_atributes=[]) { 

            if ( !$size ) $size = 'full'; 
            $image_alt = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', TRUE);
            $url = wp_get_attachment_image_src( $thumbnail_id, $size );
    
            if ( isset($url['0'] ) ) {
            echo'<figure '. ( $figureClass ? 'class="'. $figureClass. '"' : '' ) .'>
                <img src="'.$url['0'] .'" '. ($id ? 'id="'.$id.'"' : '' ).' alt="'.$image_alt.'" '.($data_atributes ? self::add_attributes($data_atributes): '').'>
            </figure>';
            }
    
        }
        /**
         * Add svg icon
         *
         * @param $value - name svg
         * @param $classs - class svg
         * @param $id - id svg
         * @param $data_attributes - ['data'=>'value=""'] 
         * 
         * @author Digihood
         * @return echo/false
         */ 
        
        public static function the_icon($value, $class = "", $type = 'img', $data_atributes=[] ) {
            
            $svg_path = get_stylesheet_directory() . '/assets/svg/' . $value . '.svg';
            
            if (!file_exists($svg_path)) {
            return false;
            }
            
            if ($type === 'img') {
            echo '<span class="svg-icon inline-block ' . esc_attr($class) . '">
                <img data-lazyimg="' . get_theme_file_uri('/assets/svg/' . $value . '.svg') . '" alt="' . esc_attr__('Ikona', 'cba') . '" style="opacity:0">
            </span>';
            } else {
            // Načtení SVG souboru
            $svg_content = file_get_contents($svg_path);
            
            if ($svg_content === false) {
                return false;
            }
            
            // Přidání třídy do SVG elementu
            if (!empty($class)) {
                $svg_content = preg_replace('/<svg /', '<svg class="inline-block ' . esc_attr($class) . '" ', $svg_content, 1);
            }
            
            // Odstranění XML deklarace pro inline použití
            $svg_content = preg_replace('/<\?xml.*?\?>/', '', $svg_content);
            
            echo $svg_content;
            }
  
        }

        /**
         * Add svg icon
         *
         * @param $value - name svg
         * @param $classs - class svg
         * @param $id - id svg
         * @param $data_attributes - ['data'=>'value=""'] 
         * 
         * @author Digihood
         * @return echo/false
         */ 
        
        public static function get_icon($value, $class="", $id="", $data_atributes=[] ) {
            if ( file_exists( get_stylesheet_directory() . '/assets/svg/' . $value . ".svg" ) )
                return '<span class="svg-icon ' . $class. '">
                    <img src="#" '. ($id ? 'id="'.$id.'"' : '' ). ($data_atributes ? self::add_attributes($data_atributes): '') .' data-lazyimg="' . get_theme_file_uri( '/assets/svg/' . $value . '.svg' ) . '" alt="'. __('svg ikona', 'cba') .'" style="opacity:0">
                </span>';
            else 
                return false;
        }

        /**
         * Funkce, která vrátí logo včetně odkazu na web
         *
         * @param none
         * 
         * @author Digihood
         */ 
        public static function get_logo($url, $width, $height, $id="", $data_atributes=[]) {
            $return = '<a href="'.get_home_url(). '">';
                $return .= '<img src="'. $url .'" '. ($id ? 'id="'.$id.'"' : 'id="site-logo"' ).' alt="'. get_bloginfo('name').'" width="'.$width.'" height="'.$height.'" '.($data_atributes ? self::add_attributes($data_atributes): '').'>';
            $return .= '</a>';
            
            return $return;
        }

        /**
         * Přidá do obrázku data-attributes
         * @param $data_attributes - array
         * 
         * @author Digihood
         * @return string
         */ 
        public static function add_attributes($data_attributes=[]) {
            if (is_array($data_attributes) && !empty($data_attributes)) {
            $attributes = " ";
            foreach ($data_attributes as $attribute => $value) {
                if($attribute) {
                $attributes .= ' ' . $attribute;
                if ($value) {
                    $attributes .='=' . $value; 
                }
                };
            }
            return $attributes;
            }
            return null;
    
        }
    }
}