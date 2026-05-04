<?php
if(!class_exists('Monolog\Logger')){
   include_once( D1G1_BRANDPATH . 'vendor/autoload.php');
}
//framework
include_once( D1G1_BRANDPATH . 'framework/includes.php');  

//admin
include_once( D1G1_BRANDPATH . 'admin/fields/d1g1ThisPluginField.php'); 
include_once( D1G1_BRANDPATH . 'admin/view/d1g1MenusContents.php'); 

// components
include_once( D1G1_BRANDPATH . 'components/email-content.php'); 
include_once( D1G1_BRANDPATH . 'components/d1g1-function.php'); 
include_once( D1G1_BRANDPATH . 'components/admin-update.php'); 
include_once( D1G1_BRANDPATH . 'components/styles-scripts.php');
include_once( D1G1_BRANDPATH . 'components/custom-functions.php');

include_once( D1G1_BRANDPATH . 'components/maintenance/digi-wp-die.php');

