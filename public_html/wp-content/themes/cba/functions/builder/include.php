<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/*
Soubor volá ostatní soubory pro builder
*/
// zobrazení obrázků
include_once __DIR__ . '/d1g1BuilderImage.php';
// zobrazení buttonů (linků)
include_once __DIR__ . '/d1g1BuilderButton.php';
// zobrazení sekcí a grid containerů
include_once __DIR__ . '/d1g1BuilderGrid.php';
// zobrazení zpráv
include_once __DIR__ . '/d1g1BuilderMessage.php';
// zobrazení zpráv
include_once __DIR__ . '/d1g1BuilderTabs.php';
// demo typografie
include_once __DIR__ . '/d1g1TypographyDemo.php';

// hlavní třída builderu
include_once __DIR__ . '/d1g1B.php';
