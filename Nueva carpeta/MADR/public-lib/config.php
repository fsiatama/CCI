<?php
session_start();

//ini_set('display_errors', true);
//error_reporting(E_ALL);
//ini_set('session.cookie_domain', '.' );
date_default_timezone_set('America/Bogota');

//header("Content-Type: text/html; charset=iso-8859-1");

define("URL_RAIZ", "http://".$_SERVER['HTTP_HOST']."/");
define("URL_INGRESO", URL_RAIZ."main");

define("URL_EXT", URL_RAIZ."js/ext-3.4.0/");
define("EXT_TEMA", "xtheme-gray-extend.css");

define("PATH_RAIZ", "E:/wamp/www/MadrApp/public/");
define("PATH_APP", "E:/wamp/www/MadrApp/app/");
define("PATH_MODELS", PATH_APP."min_agricultura/");
define("PATH_REPORTS", PATH_APP."rep/");

define("DEFAULT_LANGUAGE", "es");

define("MAXREGEXCEL", 65000);

//DEFINE LOS TIPOS DE GRAFICA
define("COLUMNAS", "mscolumn3d");     //GRAFICA DE BARRAS
define("BARRAS", "msbar3d");     //GRAFICA DE BARRAS
define("PIE", "pie3d");        //GRAFICA DE PIE
define("LINEAL", "msline");      //GRAFICA LINEAL
define("AREA", "msarea");      //GRAFICA LINEAL


define("_UNDEFINEDYEAR", 65535); //anio indefinido ( maximo numero soportado por SMALLINT(4) )
define("_ENDLESSTONS", 9999999999999999999999);

define('_ADMIN_USERS', 'johan.valencia,linda.medina,liliana.rubio');
