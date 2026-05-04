<?php 
namespace pluginbrandslug\framework\PluginAdmin\Menu;

use pluginbrandslug\framework\pluginAdmin\d1g1PluginsHomePage;

if ( ! defined( 'ABSPATH' ) ) {

  exit;

}

if( ! class_exists( 'd1g1MainMenu' ) )
{
    class d1g1MainMenu
	{
		//hook functions
		public function __construct()
		{
            add_action( 'admin_menu',  [$this, 'main_menu_callback'] );
            
        }

        /**
         * Přidává stránku nastavení digihood pluginů do adminu
         *
         * @param none
         * 
         * @author digihood
         * @return true/false
         */ 
        public function main_menu_callback() {

            if (!isset( $GLOBALS['admin_page_hooks']['d1g1-plugins']) || empty( $GLOBALS['admin_page_hooks']['d1g1-plugins'] ) ) {
                add_menu_page(
                    __( 'Digi Plugins', TM_PLUGSEC ),
                    __( 'Digi Plugins', TM_PLUGSEC ),
                    'manage_options',
                    'd1g1-plugins',
                    array( $this, 'my_admin_page_contents' ),
                    'dashicons-admin-generic',
                    65
                );
        
            }
            
            
        }

        /**
         * Zobrazení hlavní admin stránky v adminu wordpressu
         *
         * @param none
         * 
         * @author digihood
         * @return true/false
         */ 
        function my_admin_page_contents() {

            $homePageClass = new d1g1PluginsHomePage;
            $homePageClass->display_plugin_home();
        }

    }
	new d1g1MainMenu;
}
