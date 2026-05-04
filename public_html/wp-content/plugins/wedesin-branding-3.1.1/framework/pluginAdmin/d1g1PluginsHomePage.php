<?php

namespace pluginbrandslug\framework\pluginAdmin;

use pluginbrandslug\framework\monolog\d1g1MonologFunction;
use pluginbrandslug\framework\PluginAdmin\d1g1PluginsAPI;

if ( ! defined( 'ABSPATH' ) ) {

  exit;

}

if( ! class_exists( 'd1g1PluginsHomePage' ) )
{
    class d1g1PluginsHomePage
	{
     
        private $log;
		//hook functions
		public function __construct()
		{
          
          
            $this->log = new d1g1MonologFunction;
        }
        /**
         * Plugins menu
         * // nefunguje zobrazeni odkazu na nastaveni 
         * @param none
         * 
         * @author digihood
         * @return true/false
         */ 
        public function display_plugin_home() {
            // d1g1 pluginy na webu
            $this->display_plugins_menu();
            // další produkty k zakoupení
          //  $this->display_related_product();

        }

        /**
         * Plugins menu
         * // nefunguje zobrazeni odkazu na nastaveni 
         * @param none
         * 
         * @author digihood
         * @return true/false
         */ 
        function display_plugins_menu() { 
            ?>
            <div class="wrap">
                <h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
                <section class="plugins-section">
                    <div class="plugins-list">
                        <?php $show = $this->display_plugins_installed(); ?>
                    </div>
                </section>
                
                <section class="plugins-section">
                <div><h2><?php echo __('Další naše pluginy', D1G1_BRANDING); ?></h2>   </div>
                    <div class="plugins-list">
                        <?php $show = $this->display_plugins_api(); ?>
                    </div>
                </section>
            </div>
            <?php
        } 
        
         /**
         * seradí array podle abcedy (activni/neactivní)
         *
         * @param $a prvni array
         * @param $b druhy array
         * @author digihood
         * @return strnatcmp
         */ 
        
        function compare_activated($a, $b){
          return strnatcmp($b['activated'], $a['activated']);
        }
       
    
        /**
         * zobrazi pluginy v boxech 
         *
         * @param $data -- array s daty nainstalovanych pluginu
         * // nefunguje zobrazeni odkazu na nastaveni 
         * @author digihood
         * @return echo
         */ 
        public function display_plugins_installed(){
            
            $class = new d1g1PluginsAPI;
            $data = $class->d1g1_get_plugins();
            //preprint($data);
            $url = get_site_url();
            if ($data && isset($data)) {
                usort($data, [$this,'compare_activated']);
                foreach($data as $value){
                    
                    echo '<div class="digi-plugin-card">';
                        if(empty($value['activated']) || $value['activated'] === false ){
                            echo '<form method="post">';
                            echo '<button type="submit" class="button button-primary d1g1-plugins-button" name="plugin_name" value="'.$value['TextDomain'].'">' .__('Aktivovat',D1G1_BRANDING).'</button></p>';
                            echo '</form>';
                        }
                        echo '<p class="h2"><b>' .__(''.$value['name'].'', D1G1_BRANDING).'</b></h2>';
                        if($value['activated'] === true && $value['TextDomain'] == D1G1_BRANDING ){
                           // echo '<p class="h4"><a class="button button-primary" href="'.$url.'/wp-admin/admin.php?page='.$value['TextDomain'].'">' .__('Nastavení', D1G1_BRANDING).'</a></h4>';   
                        }
                        echo '<div class="d1g1-widget">';
                            echo '<p><b>'.__('Autor', TM_PLUGSEC).'</b>: <a href="'.$value['Author_URI'].'">'.$value['author'].'</a></p>';
                            echo '<p><b>'.__('Popis pluginu', TM_PLUGSEC).'</b>: '.$value['description'].'</p>';
                            echo '<p><b>'.__('Verze', TM_PLUGSEC).'</b>: '.$value['version'].'</p>';
                            // přidat kontrolu aktuality a licence - později
                        echo '</div>';
                    echo '</div>';
                
                }  
                
                if(array_key_exists('plugin_name', $_POST)) {
                    $result = activate_plugin( ''.$_POST['plugin_name'].'/setup.php', '/wp-admin/admin.php?page=d1g1-plugins' );
                    $this->log->d1g1_add_log(__METHOD__,'INFO','plugin aktivovan:' . $_POST['plugin_name'] , $result);     
                   
                }
            }
        }
   
       
      
        /**
         * zobrazi relevantní pluginy v boxech 
         *
         * @param $data -- array s daty nainstalovanych pluginu
         * 
         * @author digihood
         * @return echo
         */ 

         public function display_plugins_api(){
            $pluginClass = new d1g1PluginsAPI;
            $data = $pluginClass->d1g1_comparison_plugins();
          
            
            if ($data && isset($data)) {
                
                foreach($data as $value){
                  
                    echo '<div class="digi-plugin-card">';
                    if(isset($value['thumbnail']) && $value['thumbnail']){
                        echo '<img src="'.$value['thumbnail'].'"class="plugin-image" alt="' .__(''.$value['title'].'', D1G1_BRANDING).'" ">';
                    }
                        echo (isset($value['title']) && $value['title'] ? '<p class="h2 text-bold">' .__(''.$value['title'].'', D1G1_BRANDING).'</p>' : '');
                       
                            if (isset($value['small_content']) && $value['small_content']) {
                                echo '<p>'.$value['small_content'].'</p>';
                            }

                            echo '<a class="button button-primary d1g1-plugins-button" href="">' .__('Více o pluginu', D1G1_BRANDING).'</a>';
                    
                        echo '</div>';
                
                }  
            }
         }

    }
}
