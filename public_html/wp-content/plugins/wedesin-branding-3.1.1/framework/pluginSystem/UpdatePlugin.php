<?php
namespace pluginbrandslug\framework\pluginSystem;
use pluginbrandslug\framework\d1g1Session;
 
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if( ! class_exists( 'UpdatePlugin' ) )
{
	class UpdatePlugin
	{   
     

        static function Update_Plugin(){
          
            $license = d1g1license::check_update();
          
            if(!empty($license) && is_licensing()){
                if(isset($license->token) && $license->token){ 
                    if(isset($_SESSION['lisense']) && $_SESSION['lisense'] == true){
                        d1g1Session::remove_session('lisense');
                    }
                    if(D1G1_GITHUBURL_BRAND && D1G1_GITHUBREP_BRAND){
                    
                        $myUpdateChecker = \Puc_v4_Factory::buildUpdateChecker(
                            D1G1_GITHUBURL_BRAND,
                            D1G1_BRANDPATH.'/setup.php',
                            D1G1_GITHUBREP_BRAND
                        );
                        $myUpdateChecker->setBranch('master');
                        $myUpdateChecker->setAuthentication($license->token); 


                    }
                    d1g1Session::remove_session(D1G1_BRANDING);
                }else {
                    if(isset($license['message']) && $license['message']){
                        d1g1Session::add_session(D1G1_BRANDING,$license);
                    }
                }
            }else{
               
                if(D1G1_GITHUBURL_BRAND && D1G1_GITHUBREP_BRAND){
                    
                    $myUpdateChecker = \Puc_v4_Factory::buildUpdateChecker(
                        D1G1_GITHUBURL_BRAND,
                        D1G1_BRANDPATH.'/setup.php',
                        D1G1_GITHUBREP_BRAND
                    );
                    $myUpdateChecker->setBranch('master');
                    $myUpdateChecker->setAuthentication(GithubToken); 


                }
            }
            add_filter( 'site_transient_update_plugins', [static::class , 'stop_plugin_updates'] );  
          
        }
      
        static function stop_plugin_updates( $value ) {
            unset( $value->response[D1G1_BRANDING.'/setup.php']->package );
            return $value;
        }
        
        

        
    }
    new UpdatePlugin;
}