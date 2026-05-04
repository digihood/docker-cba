<?php

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

// Big thanks to Brett Mason (https://github.com/brettsmason) for the awesome walker
class Topbar_Menu_Walker extends Walker_Nav_Menu {
    function start_lvl(&$output, $depth = 0, $args = Array() ) {
        $indent = str_repeat("\t", $depth);
        $output .= "\n$indent<ul class=\"menu\">\n";
    }
}


class Off_Canvas_Menu_Walker extends Walker_Nav_Menu {
    function start_lvl(&$output, $depth = 0, $args = Array() ) {
        $indent = str_repeat("\t", $depth);
        $output .= "\n$indent<ul class=\"vertical menu\">\n";
    }
}

/**
 * Vyhotovení navigačního menu na webu
 * 
 * @author Digihood
 */ 
//původní jméno classy navigationMenusd1g1

if( ! class_exists( 'd1g1yNavigationMenus' ) )
{
	class d1g1yNavigationMenus
	{

		public function __construct()
		{

            //akce vytvoření menu v záhlaví
            add_action( 'd1g1_menu_top', [$this,'top_menu_nav'] );
            add_action( 'd1g1_menu_mobile', [$this, 'mobile_menu_nav'] );
            add_action( 'd1g1_menu_footer', [$this, 'footer_menu_nav'] );

            // Add Foundation active class to menu
            add_filter( 'nav_menu_css_class', [$this, 'required_active_nav_class'], 10, 2 );
        }

        // Add Foundation active class to menu
        public function required_active_nav_class( $classes, $item ) {
            if ( $item->current == 1 || $item->current_item_ancestor == true ) {
                $classes[] = 'active';
            }
            return $classes;
        }

        /**
         * Vytvoření horního menu webu
         * 
         * @author Digihood
         * @return true/false
         */ 
        public function top_menu_nav() {

            $args = [
                'container' => false,                           // Remove nav container
				'menu_class' => 'medium-horizontal menu',       // Adding custom nav class
				'items_wrap' => '<ul id="%1$s" class="%2$s" data-responsive-menu="accordion medium-dropdown">%3$s</ul>',
				'theme_location' => 'primary',        			// Where it's located in the theme
				'depth' => 5,                                   // Limit the depth of the nav
				'fallback_cb' => false,                         // Fallback function (see below)
				'walker' => new Topbar_Menu_Walker()
            ];

            wp_nav_menu( $args );           

        }

        /**
         * Vytvoření dolní menu webu
         * 
         * @author Digihood
         * @return true/false
         */ 
        public function mobile_menu_nav( ) {

            $args = [
                'theme_location' => 'mobile',        
                'items_wrap' => '<ul id="mobile-menu" class="linear-animation">%3$s</ul>'
            ];

			wp_nav_menu( $args );

        }

        /**
         * Vytvoření footer menu webu
         * 
         * @author Digihood
         * @return true/false
         */ 
        public function footer_menu_nav() {

            $args = [
                'container' => false,                           // Remove nav container
				'menu_class' => 'medium-horizontal menu',       // Adding custom nav class
				'items_wrap' => '<ul id="%1$s" class="%2$s" data-responsive-menu="accordion medium-dropdown">%3$s</ul>',
				'theme_location' => 'footer',        			// Where it's located in the theme
				'depth' => 5,                                   // Limit the depth of the nav
				'fallback_cb' => false,                         // Fallback function (see below)
				'walker' => new Topbar_Menu_Walker()
            ];

            wp_nav_menu( $args );           

        }

    }

    new d1g1yNavigationMenus;
}