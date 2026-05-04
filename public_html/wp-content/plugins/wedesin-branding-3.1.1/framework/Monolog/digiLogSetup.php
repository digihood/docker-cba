<?php 

if( ! class_exists( 'digiLogSetup') )
{

	class digiLogSetup
	{

		public function __construct()
		{
          
            add_action( 'admin_menu',  [$this, 'admin_page_d1g1_log'],90);
            add_action( 'admin_init', [$this,'save_errors_count']);
        }

		/**
         * Přidává stránku s log výpisem do adminu
         *
         * @param none
         * 
         * @author digihood
         * @return true/false
         */ 
        public function admin_page_d1g1_log() {
            $notification_count = $this->show_bubbles();
            $show = ($notification_count == 0 ? '' : '<span class="awaiting-mod">' . $notification_count . '</span>');
            add_submenu_page(
                'd1g1-plugins',                              
                    __( 'digi Log', TM_PLUGSEC ),                    
                    __('digi Log', TM_PLUGSEC) . $show ,               
                    'manage_options',                           
                    'd1g1-logs',                              
                    array( $this, 'my_admin_page_contents' )
                  
            );
        }

        public function show_bubbles(){
            
            $newbubles = 0;
            $overview = new d1g1OverviewLog;
            $countarray = $overview->count_all_logs_output();
            $error_count_actual = $countarray['error'];
            $actual_old_counts_log = get_option('D1G1_all_error_counts');
            if(isset($actual_old_counts_log) && $actual_old_counts_log){
                $error_counts_old = $actual_old_counts_log['error'];
                if(isset($error_count_actual) && $error_count_actual && isset($error_counts_old) && $error_counts_old){
                    if($error_count_actual > $error_counts_old ){
                        $newbubles = abs($error_counts_old - $error_count_actual);
                
                    }
                }
             // preprint($actual_old_counts_log);
                return $newbubles;
            }
           
               
            
        }

        public function save_errors_count(){
            if(isset($_GET['LOGview']) && $_GET['LOGview'] == 1){
                $overview = new d1g1OverviewLog;
                $counts = $overview->count_all_logs_output();
                update_option('D1G1_all_error_counts', $counts);
                update_option('D1G1_error_counts', $overview->get_plugin_json());
                wp_redirect(get_home_url() . '/wp-admin/admin.php?page=d1g1-logs');
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
            ?>
            <div class="wrap">
                <h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
                <nav class="nav-tab-wrapper">
                    <?php 
                    $default_tab = null;
                    $tab = isset($_GET['tab']) ? $_GET['tab'] : $default_tab;
                    ?>
                    <a href="?page=d1g1-logs" class="nav-tab <?= ($tab===null ? 'nav-tab-active' : '')?>">
                        <?= __('Přehled logů', TM_PLUGSEC) ?>
                    </a>
                    <?php 
                        do_action('d1g1_plugin_log_header_content');
                    ?>
                </nav>
                <div class="tab-content">
                    <?php if($tab===null ){ ?>
                        <div class="wrap">
                            <div class="col-12">
                                <div class="box-info">
                                   
                                    <div class="box-body">
                                      <?php
                          
                           $test = new d1g1OverviewLog;
                           $te = $test->overview_plugins_log();
                           echo '<div class="box-button"><a href="admin.php?page=d1g1-logs&LOGview=1" class="button button-primary d1g1-plugins-button">'.__('Označit jako přečtené', D1G1_BRANDING) .'</a></div>' ;
                                    
                                    
                                      ?>
                                    </div>
                                </div>                 
                            </div>
                        </div>
                        <div style="clear:both;"></div>  
                    <?php } else { 
                        do_action('d1g1_plugin_log_tab_content');
                    }
                    ?>
                </div>

            </div>
            <?php
        }

 

    }

 new digiLogSetup;

}
//přidáme další soubory

