<?php
namespace pluginbrandslug;
use pluginbrandslug\framework\pluginSystem\UpdatePlugin;

use pluginbrandslug\framework\pluginSystem\d1g1PluginRequirements;
use pluginbrandslug\framework\pluginAdmin\Menu\d1g1LogMenu;

/*
* Plugin Name: digihood Branding
* Plugin URI: 
* Description: Nová verze wedesin branding pluginu. Po změně brandingu přichází i změna názvů pluginů. Nově již naše pluginy najdete pro názvem digihood. Děkujeme, že děláte weby s námi.
* Author: Digihood
* Author URI: https://www.digihood.cz/
* Version: 3.1.2
* Version Framework: 3.0
* Requires at least: 3.0.
* Tested up to: 5.6
* License: GPL2 or higher
*/

/*
ve složce zde soubor includes stačí odkomentovat include souboru digi-wp-die.php a v momente co nekde použiješ funkci wp_die(); tak se použije html template z souboru framework/wp-die.php
Toto bych pak pozdeji hodil i do šablony se stejným nazvem classy bez namespace.  at to máme jak v pluginu tak i v šabloně. 
*/
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}


/*******************************
/           DEFINE             *
********************************/

//tuto definici neměnit.

//definice
define( 'TM_PLUGSEC', 'd1g1_plugins');
define( 'TM_PLUGNAMEBRAND', 'Digihood Branding');
define( 'D1G1_BRANDPATH', plugin_dir_path( __FILE__ ) );
define( 'D1G1_BRANDURL', plugin_dir_url( __FILE__ ) );
define( 'D1G1_BRANDING', 'D1G1BRAND' );
define( 'D1G1_LFILEDIR_BRAND','d1g1-'. D1G1_BRANDING .'-logs');
// Nastavení automatické kontroly auktualizací 
// D1G1_GITHUBURL example : https://github.com/WeDesIn/wedesin-branding
// D1G1_GITHUBREP example : wedesin-branding
define( 'D1G1_IS_LICENSING_BRAND', FALSE);
define( 'D1G1_GITHUBURL_BRAND', 'https://github.com/digihood/wedesin-branding');
define( 'D1G1_GITHUBREP_BRAND', 'wedesin-branding');


//dočastné definice
if(!defined('GithubToken')){
    define( 'GithubToken', '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');
}
/*******************************
/     Plugin requirements      *
********************************/ 
require( D1G1_BRANDPATH . 'framework/pluginSystem/d1g1PluginRequirements.php');  

class DemandingPlugin extends d1g1PluginRequirements {
 
    const PLUGIN_NAME = "Šablona pluginů";
 
    public function __construct() {
        $this->add_activation_hooks( __FILE__ ); 
    }
 
    protected function check_plugin_requirements() {
        global $wp_version;
        $failed = array();
        if ( $wp_version < 4.0 )
            $failed[] = 'WordPress version must be at least 4.0! ';
        if ( version_compare( PHP_VERSION, '5.6.0', '<' ) )
            $failed[] = 'PHP version must be at least 5.6.0! ';
        return $failed;
    }
 
}
$DemandingPlugin = new DemandingPlugin();


/*******************************
/       INCLUDE PARTS          *
********************************/


include_once( D1G1_BRANDPATH . 'includes.php');

$license = UpdatePlugin::Update_Plugin();