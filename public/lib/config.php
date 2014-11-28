<?php
session_start();

ini_set('display_errors', true);
error_reporting(E_ALL);
//ini_set('session.cookie_domain', '.' );
//date_default_timezone_set('America/Bogota');

//header("Content-Type: text/html; charset=iso-8859-1");

define("URL_RAIZ", "https://".$_SERVER['HTTP_HOST']."/");
define("URL_INGRESO", URL_RAIZ."main");

define("URL_EXT", URL_RAIZ."js/ext-3.4.0/");
define("EXT_TEMA", "xtheme-gray-extend.css");

define("PATH_RAIZ", "C:/wamp/www/CCI/public/");
define("PATH_APP", "C:/wamp/www/CCI/app/");
define("PATH_MODELS", PATH_APP."/min_agricultura/");
define("PATH_REPORTES", PATH_RAIZ."/rep/");

define("DEFAULT_LANGUAGE", "es");

define("MAXREGEXCEL", 65000);

//DEFINE LOS TIPOS DE GRAFICA
define("COLUMNAS", "MSColumn3D.swf");     //GRAFICA DE BARRAS
define("BARRAS", "MSBar3D.swf");     //GRAFICA DE BARRAS
define("PIE", "Pie3D.swf");        //GRAFICA DE PIE
define("LINEAL", "MSLine.swf");      //GRAFICA LINEAL
define("AREA", "StackedArea2D.swf");      //GRAFICA LINEAL
