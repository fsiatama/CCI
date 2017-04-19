<?php
ini_set('display_errors', true);
$connection = [
	'default' => 'agrocomercio',
	'min_agricultura' => (object)[
		'driver'   => 'mysqli',
		'host'     => '172.16.1.233',
		'database' => 'min_agricultura',
		'username' => 'appusr',
		'password' => 's1c3x2016**'
	]
];

$x = (object) $connection;

var_dump(str_rot13(base64_encode(serialize($x))));
exit();