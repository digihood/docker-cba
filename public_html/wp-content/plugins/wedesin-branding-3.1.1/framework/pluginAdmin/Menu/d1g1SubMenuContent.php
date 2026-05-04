<?php

namespace pluginbrandslug\framework\pluginAdmin\Menu;

use pluginbrandslug\framework\Forms\d1g1FormsBuilderFields;
use pluginbrandslug\framework\pluginAdmin\InfoBox\viewAdminBox;
use pluginbrandslug\framework\feedback\d1g1Feedback;
use pluginbrandslug\framework\Forms\d1g1FrontForm;
use pluginbrandslug\framework\d1g1Session;
if (!defined('ABSPATH')) {

    exit;
}

if (!class_exists('d1d1SubMenuContent')) {
    class d1d1SubMenuContent
    {
        //hook functions
        public function __construct()
        {
        
        }
        /**
         * Zobrazení ukázkového formuláře
         *
         * @param none
         * 
         * @author digihood
         * @return true/false
         */
        function my_admin_page_contents()
        {   
              
            //Získat aktivní tab z parametru $_GET 
            $default_tab = null;
            $builder = new d1g1FormsBuilderFields();
            $tab = isset($_GET['tab']) ? $_GET['tab'] : $default_tab; ?>
            <div class="wrap d1g1-admin">
                <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
                <div class="row">
                    <div class="column content">
                        <nav class="nav-tab-wrapper">
                        <?php 
                            do_action( 'd1g1_navigations-'.D1G1_BRANDING, $tab);
                            if(is_licensing()){
                        ?>
                            <a href="?page=<?= D1G1_BRANDING ?>&tab=license" class="nav-tab <?php if ($tab === 'license') : ?>nav-tab-active<?php endif; ?>"><?php echo d1g1_get_svg(D1G1_BRANDPATH . "assets/icons/info-standard-line.svg"); ?>Licence</a>
                            <?php } ?>
                            <a href="?page=<?= D1G1_BRANDING ?>&tab=feedback" class="nav-tab <?php if ($tab === 'feedback') : ?>nav-tab-active<?php endif; ?>"><?php echo d1g1_get_svg(D1G1_BRANDPATH . "assets/icons/info-standard-line.svg"); ?>feedback</a>
                        </nav>
                        <div class="content-box">
                            <?php 
                            $contents = [];
                            $contents = apply_filters( 'd1g1_navigation_content-'.D1G1_BRANDING, $contents );
                           
                            
                            foreach($contents as $tab_name => $content){
                              
                                if(is_array($content)){
                                    if(isset($content[0]) && $content[0] && isset($content[1]) && $content[1]){
                                        if(class_exists($content[0]) && method_exists($content[0],$content[1])){
                                            call_user_func( $content[0].'::'.$content[1]);
                                        }
                                    }
                                }else{
                                    $tab = ($tab === null ? 'default' :$tab);
                                    if($tab === $tab_name ){
                                        d1g1FrontForm::display_form($content);
                                    }
                                }  
                            }
                           
                            if($tab == 'license'){
                              
                                d1g1FrontForm::display_form("license"); 
                            } 
                            if($tab == 'feedback'){
                                d1g1Feedback::feedback_session();
                                d1g1FrontForm::display_form("feedback");
                            }
                            ?>
                        </div>
                    </div>
                    <div class="column sidebar">
                    
                    <?php 
                    $zobrazeni = new viewAdminBox;
                    $zobrazeni->d1g1_dashboard_widget_display('pluginsnews');
                    $zobrazeni->d1g1_dashboard_widget_display('plugins');
                    ?>
                      
                    
            
                    </div>
                </div>
            </div>
<?php
d1g1Session::end_session();
        }

  
    }

    new d1d1SubMenuContent;
}