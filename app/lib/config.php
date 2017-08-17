<?php

$connection = [
	'default' => 'min_agricultura',
	'min_agricultura' => (object)[
		'driver'   => 'mysqli',
		'host'     => getenv('DB_HOST'),
		'database' => 'min_agricultura',
		'username' => getenv('DB_USERNAME'),
		'password' => getenv('DB_PASSWORD')
	]
];

return (object) $connection;