<?php
=======
/**
* @author Fabian Siatama
* posee variables y constantes de configuracion para el backend
*/


/*
|--------------------------------------------------------------------------
| Database Connections
|--------------------------------------------------------------------------
|
| Estas son cada una de las conecciones a las diferentes bases de datos que
| que maneje la aplicacion
|
*/
$connections = array(
	
	'default' => 'min_agricultura',

	'min_agricultura' => array(
		'driver'   => 'mysql',
		'host'     => '192.168.15.3',
		'database' => 'min_agricultura',
		'username' => 'root',
		'password' => ''
	)
	
);

