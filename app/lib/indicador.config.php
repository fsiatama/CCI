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
			],[
				'field'      => 'anio_ini',
				'field_expo' => 'anio',
				'field_impo' => 'anio',
				'required'   => true,
				'multivalue' => false,
			],[
				'field'      => 'anio_fin',
				'field_expo' => 'anio',
				'field_impo' => 'anio',
				'required'   => true,
				'multivalue' => false,
			],[
				'field'      => 'desde_ini',
				'field_expo' => 'periodo',
				'field_impo' => 'periodo',
				'required'   => true,
				'multivalue' => false,
			],[
				'field'      => 'hasta_ini',
				'field_expo' => 'periodo',
				'field_impo' => 'periodo',
				'required'   => true,
				'multivalue' => false,
			],[
				'field'      => 'desde_fin',
				'field_expo' => 'periodo',
				'field_impo' => 'periodo',
				'required'   => true,
				'multivalue' => false,
			],[
				'field'      => 'hasta_fin',
				'field_expo' => 'periodo',
				'field_impo' => 'periodo',
				'required'   => true,
				'multivalue' => false,
			]
		]
	],
	'executeConfig' => [
		'1' => ['repoClassName' => 'DeclaracionesRepo', 'methodName' => 'BalanzaRelativa'],
		'2' => ['repoClassName' => 'DeclaracionesRepo', 'methodName' => 'Balanza'],
		'3' => ['repoClassName' => 'DeclaracionesRepo', 'methodName' => 'BalanzaVariacion'],
	],
	'yearsAvailable' => ['2010', '2011', '2012', '2013', '2014'],
	'periods' => [
		[12, Lang::get('indicador.reports.annual')],
		[6,  Lang::get('indicador.reports.semester')],
		[3,  Lang::get('indicador.reports.quarter')],
		[1,  Lang::get('indicador.reports.montly')]
	],
	'months' => []
];