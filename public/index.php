<?php

require '../vendor/autoload.php';

/*
 * El frontend controller se encarga de
 * configurar nuestra aplicacion
 */


$dotenv = new Dotenv\Dotenv("../");
$dotenv->load();

require 'lib/config.php';
require 'lib/Helpers.php';

//Library
require 'lib/Request.php';
require 'lib/Inflector.php';
require 'lib/Response.php';
require 'lib/View.php';
require 'lib/Lang.php';
require 'lib/Excel.php';
//require 'lib/FileDownload.php';


//Llamar al controlador indicado

if (empty($_GET['url']))
{
    $url = "";
}
else
{
    $url = $_GET['url'];
}

$request = new Request($url);
$request->execute();