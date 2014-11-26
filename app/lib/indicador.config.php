<?php
return [
	'filters' => [
		'1' => [
			[
				'field'      => 'id_pais',
				'required'   => true,
				'multivalue' => true,
			],[
				'field'      => 'id_posicion',
				'required'   => true,
				'multivalue' => true,
			]
		],
		'2' => [
			[
				'field'      => 'id_pais',
				'required'   => true,
				'multivalue' => true,
			],[
				'field'      => 'id_posicion',
				'required'   => true,
				'multivalue' => true,
			]
		],
		'3' => [
			[
				'field'      => 'id_pais',
				'required'   => true,
				'multivalue' => true,
			],[
				'field'      => 'id_posicion',
				'required'   => true,
				'multivalue' => true,
			]
		]
	],
	'executeConfig' => [
		'1' => ['repoClassName' => 'DeclaracionesRepo', 'methodName' => 'BalanzaRelativa'],
		'2' => ['repoClassName' => 'DeclaracionesRepo', 'methodName' => 'Balanza'],
		'3' => ['repoClassName' => 'DeclaracionesRepo', 'methodName' => 'BalanzaVariacion'],
	],
];