<?php
if(version_compare(PHP_VERSION, '7.4', '>=')){
    include_once(__DIR__.'/digiLogSetup.php');
}
include_once(__DIR__.'/d1g1MonologFunction.php');

if(version_compare(PHP_VERSION, '7.4', '>=')){
    include_once(__DIR__.'/d1g1OverviewLog.php');
}