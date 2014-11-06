<?php
ini_set("display_errors", 0);
//ini_set('session.cookie_domain', '.' );
//date_default_timezone_set('America/Bogota');

//header("Content-Type: text/html; charset=iso-8859-1");

define("URL_RAIZ", "http://".$_SERVER['HTTP_HOST']."/");
define("URL_INGRESO", URL_RAIZ."main");

define("URL_EXT", URL_RAIZ."js/ext-3.4.0/");
define("EXT_TEMA", "xtheme-gray-extend.css");

define("PATH_RAIZ", "C:/wamp/www/CCI/public/");
define("PATH_APP", "C:/wamp/www/CCI/app/");
define("PATH_REPORTES", PATH_RAIZ."/rep/");

define("IDIOMA", "colombia.dic.php");


foreach($_POST as $var => $val)
{
    $$var = $val;
}

foreach($_GET as $var => $val)
{
    $$var = $val;
}
