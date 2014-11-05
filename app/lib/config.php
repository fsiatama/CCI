<?php
/**
* @author Fabian Siatama
* posee variables y constantes de configuracion para el backend
*/



//Configuración para enviar los reportes especiales
define("MAIL_FROM", "sales@sicex.com");
define("MAIL_FROMNAME", "Pablo Castano");
define("MAIL_REPLY", MAIL_FROM);
define("MAIL_HOST", "172.16.1.7");
define("MAIL_MAILER", "smtp");

//Configuración al servidor central
$config['driver']   = 'mysqli';
$config['server']   = "192.168.15.3";
$config['db_name']  = "sicex_r";
$config['login']    = "root";
$config['password'] = "";

$coneccion['sicex_r'] = $config;



?>