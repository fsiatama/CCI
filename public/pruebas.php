<?php

/*
 * El frontend controller se encarga de
 * configurar nuestra aplicacion
 */
require 'lib/config.php';
require 'lib/Helpers.php';

//Library
require '../vendor/autoload.php';
$arrEmail = [
	['email' => 'fsiatama@sicex.com']
];

$result = Helpers::sendEmail($arrEmail, 'prueba', 'prueba');

d($result);

// use KINT directly (which has been loaded automatically via Composer)
//Kint::dump($GLOBALS, $_SERVER);