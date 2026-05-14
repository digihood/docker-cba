<?php

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

//definition
define( "MAP_API_KEY", "");
define( "CL", time( ) + 60*60*24*7);

//include autoload
require 'vendor/autoload.php';
include_once __DIR__ . '/functions/include.php';

// Calculator CPT & Budget Planner
require_once get_template_directory() . '/functions/cpt/calculator.php';
require_once get_template_directory() . '/functions/calculators/budget-planner/budget-planner-functions.php';
require_once get_template_directory() . '/functions/calculators/budget-planner/budget-planner-admin.php';
require_once get_template_directory() . '/functions/calculators/budget-planner/budget-planner-ajax.php';
require_once get_template_directory() . '/functions/calculators/budget-planner/budget-planner-email.php';
require_once get_template_directory() . '/functions/calculators/budget-planner/budget-planner-assets.php';

// Retirement Savings Calculator
require_once get_template_directory() . '/functions/calculators/retirement-savings/retirement-savings-functions.php';
require_once get_template_directory() . '/functions/calculators/retirement-savings/retirement-savings-admin.php';
require_once get_template_directory() . '/functions/calculators/retirement-savings/retirement-savings-ajax.php';
require_once get_template_directory() . '/functions/calculators/retirement-savings/retirement-savings-assets.php';

// Net Worth Calculator
require_once get_template_directory() . '/functions/calculators/net-worth/net-worth-functions.php';
require_once get_template_directory() . '/functions/calculators/net-worth/net-worth-admin.php';
require_once get_template_directory() . '/functions/calculators/net-worth/net-worth-ajax.php';
require_once get_template_directory() . '/functions/calculators/net-worth/net-worth-email.php';
require_once get_template_directory() . '/functions/calculators/net-worth/net-worth-assets.php';

// Domain objects
require_once get_template_directory() . '/functions/objects/CbaUser.php';
require_once get_template_directory() . '/functions/objects/CbaCalculator.php';

// SVG upload support
add_filter('upload_mimes', function ($mimes) {
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
});

// Account (Můj účet + Přihlášení)
require_once get_template_directory() . '/functions/account/account-ajax.php';
require_once get_template_directory() . '/functions/account/account-assets.php';
require_once get_template_directory() . '/functions/account/login-assets.php';
require_once get_template_directory() . '/functions/account/account-nav.php';