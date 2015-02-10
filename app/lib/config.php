<?php
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
$connections = [
	
	'default' => 'min_agricultura',

	'min_agricultura' => [
		'driver'   => 'mysqli',
		'host'     => '127.0.0.1',
		'database' => 'min_agricultura',
		'username' => 'root',
		'password' => ''
	]
	
];

//prueba



