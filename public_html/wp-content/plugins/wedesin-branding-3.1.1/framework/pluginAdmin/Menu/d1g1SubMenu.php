<?php 
namespace pluginbrandslug\framework\pluginAdmin\Menu;
use pluginbrandslug\framework\pluginAdmin\Menu\d1d1SubMenuContent;
if ( ! defined( 'ABSPATH' ) ) {

  exit;

}

if( ! class_exists( 'd1g1SubMenu' ) )

{
    class d1g1SubMenu

	{
		//hook functions

		public function __construct()
		{
            add_action( 'admin_menu',  [$this, 'submenus_callback'] );
        }

        /**
         * Přidává stránku nastavení digihood pluginů do adminu
         *
         * @param none
         * 
         * @author digihood
         * @return true/false
         */ 
        public function submenus_callback() {

            /*https://developer.wordpress.org/reference/functions/add_submenu_page/
            1) slub nadřazené položky menu
            2) titulek stránky
            3) menu stránky
            4) pravomoce
            5) menu slug
            6) callback na přidání obsahu
            */

            $subcontent = new d1d1SubMenuContent;

            $submenus = [
                [
                    'd1g1-plugins',                              
                    __( TM_PLUGNAMEBRAND, TM_PLUGSEC ),                    
                    __(TM_PLUGNAMEBRAND, TM_PLUGSEC),               
                    'manage_options',                           
                    D1G1_BRANDING,                              
                    [$subcontent, 'my_admin_page_contents']
                           
                ]
            ];

            if ( !empty( $submenus ) ) {
                foreach ( $submenus as $submenu ) {
                    add_submenu_page(
                        $submenu[0],
                        $submenu[1],
                        $submenu[2],
                        $submenu[3],
                        $submenu[4],
                        $submenu[5]
                    );
                }
            }             
            
        }

    }

	new d1g1SubMenu;

}

