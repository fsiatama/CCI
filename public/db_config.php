<?php
ini_set('display_errors', true);
$connection = [
	'default' => 'agrocomercio',
	'min_agricultura' => (object)[
		'driver'   => 'mysqli',
		'host'     => '172.20.52.11',
		'database' => 'agrocomercio',
		'username' => 'AgroComercio',
		'password' => '@$un70$1N'
	]
];

$x = (object) $connection;

var_dump(str_rot13(base64_encode(serialize($x))));
exit();