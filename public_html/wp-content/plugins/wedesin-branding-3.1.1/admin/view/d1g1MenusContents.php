<?php

namespace pluginbrandslug\admin\view;
use pluginbrandslug\framework\pluginSystem\d1g1license;
if (!defined('ABSPATH')) {

    exit;
}
/**
 * d1g1MenusContents
 *  obsah navigace a jednotlivých tabů
 * 
 * @author Digihood
 */
if (!class_exists('d1g1MenusContents')) {
    class d1g1MenusContents {


        function __construct() {
            add_action( 'd1g1_navigations-'.D1G1_BRANDING, [$this,'add_navigations']);
            add_filter( 'd1g1_navigation_content-'.D1G1_BRANDING, [$this,'add_navigation_contents'] );
          

        }
        /**
         * zobrazní navigační panely
         * 
         * @param $ŧab = get jmeno tabu
         * 
         * @author Digihood
         */
        public static function add_navigations($tab) {
           
        
            echo '<a href="?page='.D1G1_BRANDING.'" class="nav-tab '.($tab === null ? 'nav-tab-active' : '' ).'">'. d1g1_get_svg(D1G1_BRANDPATH . "assets/icons/home-line.svg").' Default Tab</a>';
            if(d1g1license::validate_lisense_key()){
                echo '<a href="?page='.D1G1_BRANDING.'&tab=tools" class="nav-tab '.($tab === 'tools' ? 'nav-tab-active' : '' ).'">'. d1g1_get_svg(D1G1_BRANDPATH . "assets/icons/settings-line.svg").'tools</a>';
            }
            echo '<a href="?page='.D1G1_BRANDING.'&tab=update_report" class="nav-tab '.($tab === 'update_report' ? 'nav-tab-active' : '' ).'">'. d1g1_get_svg(D1G1_BRANDPATH . "assets/icons/link-line.svg").'Odeslání reportu</a>';
            //echo '<a href="?page='.D1G1_BRANDING.'&tab=send_digi_mail" class="nav-tab '.($tab === 'send_digi_mail' ? 'nav-tab-active' : '' ).'">'. d1g1_get_svg(D1G1_BRANDPATH . "assets/icons/link-line.svg").'Odeslaní emailu</a>';
            echo '<a href="?page='.D1G1_BRANDING.'&tab=emails" class="nav-tab '.($tab === 'emails' ? 'nav-tab-active' : '' ).'">'. d1g1_get_svg(D1G1_BRANDPATH . "assets/icons/link-line.svg").'Emaily</a>';
            //echo '<a href="?page='.D1G1_BRANDING.'&tab=tools" class="nav-tab '.($tab === 'tools' ? 'nav-tab-active' : '' ).'">'. d1g1_get_svg(D1G1_BRANDPATH . "assets/icons/link-line.svg").'tools</a>';

        }
        /**
         * zobrazní navigačním panelum obsah 
         * 
         * @param $contents = array [jmeno tabu => jmeno fieldu]
         * 
         * @author Digihood
         * @return array
         */
        public function add_navigation_contents($contents){
            $contents['default'] = [ __CLASS__,'the_updates_list'];
            /*if(d1g1license::validate_lisense_key()){
                $contents['tools'] = 'test_form_mini_for_test';
            }*/
            $contents['emails'] = 'brand_emails_settings';
            $contents['send_digi_mail'] = 'send_digi_mail';
            $contents['update_report'] = 'update_report';
            $contents['tools'] = 'plugins_tools';
            return $contents;

        }

        public static function the_updates_list() {
            $updates_list = get_option('_d1g1_list', []);
            $tab = (isset($_GET['tab']) &&  $_GET['tab'] ? $_GET['tab'] : null);
            if($tab !== null)return;
            ?>
            <h2>Seznam dostupných zapsaných aktualizací</h2>
            <table width=100%>
                <thead style="font-weight:700">
                    <tr>
                        <td>Datum</td>
                        <td>Autor</td>
                        <td>Seznam aktualizací</td>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $rev_updates_list = array_reverse($updates_list, true);
                    foreach ($rev_updates_list as $date => $record) {
                        if (isset($record['user']) && $record['update']) {
                            $user = get_userdata($record['user']);
                          //  $zone = new \DateTimeZone('UTC +2');
                           
                            echo '<tr style="border-top: 1px solid black">';
                            echo '<td>'.digi_date('d. m. Y H:i', $date) .'</td>';
                            echo '<td>'.$user->user_login. '('.$user->ID.')</td>';
                            echo '<td>'.$record['update'].'</td>';
                            echo '</tr>';
                            
                        }

                    }
                    ?>
                </tbody>
            </table>
            <?php
            
           // preprint($updates_list);
        }

    }
    new d1g1MenusContents;
}
   