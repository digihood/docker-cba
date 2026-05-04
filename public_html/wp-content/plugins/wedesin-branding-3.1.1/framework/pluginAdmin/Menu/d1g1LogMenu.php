<?php 
namespace pluginbrandslug\framework\pluginAdmin\Menu;
use pluginbrandslug\framework\monolog\d1g1MonologFunction;

if ( ! defined( 'ABSPATH' ) ) {

  exit;

}

if (!class_exists('d1g1LogMenu')){
    class d1g1LogMenu{
        public $LogFile;
        private $log;

        //konstruktor
        public function __construct()
        {
            //spuštní
        
            add_action('d1g1_plugin_log_tab_content', [$this, 'add_tab'] );
            add_action('d1g1_plugin_log_header_content', [$this, 'add_header'] );
           // add_action( 'init',  [$this, 'del_error_count'] );
            $this->log = new d1g1MonologFunction;

        }


        /**
         * Zapíše zprávu do logu TEST
         *
         * @param none
         * 
         * @author digihood
         * @return true/false
         */ 
        public function test__log() {
            if ( isset($_GET['test_log']) && $_GET['test_log'] == 2  ) {
            $this->add_log(     
                'ahoj',
                'testuji si tu uložení',
                'blabla'
            );
    
            die('tu');
            }
        }


        /**
         * Přidá záložku logu pro tento plugin
         *
         * @param none
         * 
         * @author digihood
         * @return true/false
         */ 
        public function add_header( ) { 
            $plugin_data = get_plugin_data( D1G1_BRANDPATH . 'setup.php' );
            $plugin_name = $plugin_data['Name'];
            
            $default_tab = null;
            $tab = isset($_GET['tab']) ? $_GET['tab'] : $default_tab;
            ?>
            <a href="?page=d1g1-logs&tab=<?= D1G1_BRANDING ?>" class="nav-tab <?= ($tab=== D1G1_BRANDING ? 'nav-tab-active' : '')?>">
                <?php echo $plugin_name; ?>
            </a>
        <?php }

        /**
         * Přidá záložku logu pro tento plugin
         *
         * @param none
         * 
         * @author digihood
         * @return true/false
         */ 
        public function add_tab( ) {
            $tab = isset($_GET['tab']) ? $_GET['tab'] : '';
            $file = $this->LogFile;
            if ($tab) $file = $tab;
            $this->the_log_excerpt_admin($file);
        }

        /**
         * Výpis logu do admina 
         *
         * @param none
         * 
         * @author digihood
         * @return true/false
         */ 
        public function the_log_excerpt_admin() { 
      
                if ($_GET['tab'] == D1G1_BRANDING ) { 
            
                    include_once( D1G1_BRANDPATH. 'framework/Monolog/views/view-log.php' );
                }
        }

        public function active_or_deactive_error_count($activate = ''){
            $directory =  $this->log->d1g1_get_log_folder();
            $file = $directory . '/' . D1G1_BRANDING . '.json';
            $create_file = false;
            if(!file_exists($file)){
                try {
                    $create_file = file_put_contents($file, "");
                } catch (\Throwable $th) {
                    //throw $th;
                }
                
            }
            if(!$create_file)return;
            $json = file_get_contents($file);
            $jsonsplit = new d1g1MonologFunction;
            $array = $jsonsplit->json_split_objects($json);
            $arraycount = count($array);
            for($i = 0; $i <= $arraycount; $i++){
                $key = array_keys($array);
                if(isset($key[$i]) && $key[$i]) {
                    $jsondata = json_decode($array[$i]);
                    
                    foreach($jsondata as $key => $value) {
                        if($key == 'level_name' && $value == 'ERROR'){
                            if (!isset($count[$value])) $count[$value] = 0;
                            $count[$value]++;
                            $actual_errors = get_option('D1G1_all_error_counts');
                            $error = ($actual_errors['error'] ? $actual_errors['error'] : '0');
                          
                            if($activate == 1){
                                $new_errors = $error + $count['ERROR'];
                                $info = 'activace';  
                            }else{
                               
                                $new_errors = $error - $count['ERROR'];
                                $info = 'deactivace';  
                            }
                            $plugin_data = [
                                'error' => $new_errors,
                            ];
                            $new_array = array_replace($actual_errors,$plugin_data);  
                          
                        }
                       
                    }  
                }
            }
          
            $jsonsplit->d1g1_add_log(__METHOD__,'INFO',$info, $new_array);
            update_option('D1G1_all_error_counts', $new_array);         
        }
    }
    new d1g1LogMenu;
     
}
