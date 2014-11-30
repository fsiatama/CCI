<?php
return [
	'filters' => [
		'1' => [
			[
				'field'      => 'id_pais',
				'field_expo' => 'id_paisdestino',
				'field_impo' => 'id_paisprocedencia',
				'required'   => true,
				'multivalue' => true,
			],[
				'field'      => 'id_posicion',
				'field_expo' => 'id_posicion',
				'field_impo' => 'id_posicion',
				'required'   => true,
				'multivalue' => true,
			]
		],
		'2' => [
			[
				'field'      => 'id_pais',
				'field_expo' => 'id_paisdestino',
				'field_impo' => 'id_paisprocedencia',
				'required'   => true,
				'multivalue' => true,
			],[
				'field'      => 'id_posicion',
				'field_expo' => 'id_posicion',
				'field_impo' => 'id_posicion',
				'required'   => true,
				'multivalue' => true,
			]
		],
		'3' => [
			[
				'field'      => 'id_pais',
				'field_expo' => 'id_paisdestino',
				'field_impo' => 'id_paisprocedencia',
				'required'   => true,
				'multivalue' => true,
			],[
				'field'      => 'id_posicion',
				'field_expo' => 'id_posicion',
				'field_impo' => 'id_posicion',
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
	'yearsAvailable' => ['2010', '2011', '2012', '2013', '2014'],
	'groupBy' => [
		'year' => ''
	]
];