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