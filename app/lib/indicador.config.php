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
			],[
				'field'      => 'anio_ini',
				'field_expo' => 'anio',
				'field_impo' => 'anio',
				'required'   => true,
				'yearRange'  => ['anio_fin'],
			],[
				'field'         => 'anio_fin',
				'field_expo'    => 'anio',
				'field_impo'    => 'anio',
				'required'      => true,
				'itComplements' => true, //son complemento del filtro anio_ini
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
			],[
				'field'      => 'anio_ini',
				'field_expo' => 'anio',
				'field_impo' => 'anio',
				'required'   => true,
				'yearRange'  => ['anio_fin'],
			],[
				'field'         => 'anio_fin',
				'field_expo'    => 'anio',
				'field_impo'    => 'anio',
				'required'      => true,
				'itComplements' => true, //son complemento del filtro anio_ini
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
				'dateRange'  => ['desde_ini', 'hasta_ini'],
			],[
				'field'      => 'anio_fin',
				'field_expo' => 'anio',
				'field_impo' => 'anio',
				'required'   => true,
				'dateRange'  => ['desde_fin', 'hasta_fin'],
			],[
				'field'      => 'desde_ini',
				'field_expo' => 'periodo',
				'field_impo' => 'periodo',
				'required'   => true,
				'itComplements' => true, //son complemento del filtro anio
			],[
				'field'      => 'hasta_ini',
				'field_expo' => 'periodo',
				'field_impo' => 'periodo',
				'required'   => true,
				'itComplements' => true, //son complemento del filtro anio
			],[
				'field'      => 'desde_fin',
				'field_expo' => 'periodo',
				'field_impo' => 'periodo',
				'required'   => true,
				'itComplements' => true, //son complemento del filtro anio
			],[
				'field'      => 'hasta_fin',
				'field_expo' => 'periodo',
				'field_impo' => 'periodo',
				'required'   => true,
				'itComplements' => true, //son complemento del filtro anio
			]
		],
		'4' => [
			[
				'field'      => 'id_pais',
				'field_expo' => 'id_paisdestino',
				'field_impo' => 'id_paisprocedencia',
				'required'   => true,
				'multivalue' => true,
			],[
				'field'      => 'anio_ini',
				'field_expo' => 'anio',
				'field_impo' => 'anio',
				'required'   => true,
				'dateRange'  => ['desde_ini', 'hasta_ini'],
			],[
				'field'      => 'desde_ini',
				'field_expo' => 'periodo',
				'field_impo' => 'periodo',
				'required'   => true,
				'itComplements' => true, //son complemento del filtro anio
			],[
				'field'      => 'hasta_ini',
				'field_expo' => 'periodo',
				'field_impo' => 'periodo',
				'required'   => true,
				'itComplements' => true, //son complemento del filtro anio
			]
		],
		'5' => [ //pendiente
			[
			]
		],
		'6' => [ //pendiente
			[
				'field'      => 'id_pais',
				'field_expo' => 'id_paisdestino',
				'field_impo' => 'id_paisprocedencia',
				'required'   => true,
				'multivalue' => true,
			],[
				'field'      => 'anio_ini',
				'field_expo' => 'anio',
				'field_impo' => 'anio',
				'required'   => true,
				'dateRange'  => ['desde_ini', 'hasta_ini'],
			],[
				'field'      => 'anio_fin',
				'field_expo' => 'anio',
				'field_impo' => 'anio',
				'required'   => true,
				'dateRange'  => ['desde_fin', 'hasta_fin'],
			],[
				'field'      => 'desde_ini',
				'field_expo' => 'periodo',
				'field_impo' => 'periodo',
				'required'   => true,
				'itComplements' => true, //son complemento del filtro anio
			],[
				'field'      => 'hasta_ini',
				'field_expo' => 'periodo',
				'field_impo' => 'periodo',
				'required'   => true,
				'itComplements' => true, //son complemento del filtro anio
			],[
				'field'      => 'desde_fin',
				'field_expo' => 'periodo',
				'field_impo' => 'periodo',
				'required'   => true,
				'itComplements' => true, //son complemento del filtro anio
			],[
				'field'      => 'hasta_fin',
				'field_expo' => 'periodo',
				'field_impo' => 'periodo',
				'required'   => true,
				'itComplements' => true, //son complemento del filtro anio
			]
		],
		'7' => [
			[
				'field'      => 'anio_ini',
				'field_expo' => 'anio',
				'field_impo' => 'anio',
				'required'   => true,
				'dateRange'  => ['desde_ini', 'hasta_ini'],
			],[
				'field'      => 'desde_ini',
				'field_expo' => 'periodo',
				'field_impo' => 'periodo',
				'required'   => true,
				'itComplements' => true, //son complemento del filtro anio
			],[
				'field'      => 'hasta_ini',
				'field_expo' => 'periodo',
				'field_impo' => 'periodo',
				'required'   => true,
				'itComplements' => true, //son complemento del filtro anio
			]
		],
		'8' => [
			[
				'field'      => 'intercambio',
				'field_expo' => 'intercambio',
				'field_impo' => 'intercambio',
				'required'   => true,
				'multivalue' => false,
			],[
				'field'      => 'id_pais',
				'field_expo' => 'id_paisdestino',
				'field_impo' => 'id_paisprocedencia',
				'required'   => true,
				'multivalue' => true,
			],[
				'field'      => 'anio_ini',
				'field_expo' => 'anio',
				'field_impo' => 'anio',
				'required'   => true,
				'yearRange'  => ['anio_fin'],
			],[
				'field'         => 'anio_fin',
				'field_expo'    => 'anio',
				'field_impo'    => 'anio',
				'required'      => true,
				'itComplements' => true, //son complemento del filtro anio_ini
			]
		],
		'9' => [
			[
				'field'      => 'id_pais',
				'field_expo' => 'id_paisdestino',
				'field_impo' => 'id_paisprocedencia',
				'required'   => false,
				'multivalue' => true,
			],[
				'field'      => 'anio_ini',
				'field_expo' => 'anio',
				'field_impo' => 'anio',
				'required'   => true,
				'dateRange'  => ['desde_ini', 'hasta_ini'],
			],[
				'field'      => 'desde_ini',
				'field_expo' => 'periodo',
				'field_impo' => 'periodo',
				'required'   => true,
				'itComplements' => true, //son complemento del filtro anio
			],[
				'field'      => 'hasta_ini',
				'field_expo' => 'periodo',
				'field_impo' => 'periodo',
				'required'   => true,
				'itComplements' => true, //son complemento del filtro anio
			]
		],
		'10' => [
			[
				'field'      => 'id_pais',
				'field_expo' => 'id_paisdestino',
				'field_impo' => 'id_paisprocedencia',
				'required'   => false,
				'multivalue' => true,
			],[
				'field'      => 'anio_ini',
				'field_expo' => 'anio',
				'field_impo' => 'anio',
				'required'   => true,
				'dateRange'  => ['desde_ini', 'hasta_ini'],
			],[
				'field'      => 'desde_ini',
				'field_expo' => 'periodo',
				'field_impo' => 'periodo',
				'required'   => true,
				'itComplements' => true, //son complemento del filtro anio
			],[
				'field'      => 'hasta_ini',
				'field_expo' => 'periodo',
				'field_impo' => 'periodo',
				'required'   => true,
				'itComplements' => true, //son complemento del filtro anio
			]
		],
		'11' => [
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
				'dateRange'  => ['desde_ini', 'hasta_ini'],
			],[
				'field'      => 'desde_ini',
				'field_expo' => 'periodo',
				'field_impo' => 'periodo',
				'required'   => true,
				'itComplements' => true, //son complemento del filtro anio
			],[
				'field'      => 'hasta_ini',
				'field_expo' => 'periodo',
				'field_impo' => 'periodo',
				'required'   => true,
				'itComplements' => true, //son complemento del filtro anio
			]
		],
		'12' => [
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
				'required'   => false,
				'multivalue' => true,
			],[
				'field'      => 'anio_ini',
				'field_expo' => 'anio',
				'field_impo' => 'anio',
				'required'   => true,
				'dateRange'  => ['desde_ini', 'hasta_ini'],
			],[
				'field'      => 'anio_fin',
				'field_expo' => 'anio',
				'field_impo' => 'anio',
				'required'   => true,
				'dateRange'  => ['desde_fin', 'hasta_fin'],
			],[
				'field'      => 'desde_ini',
				'field_expo' => 'periodo',
				'field_impo' => 'periodo',
				'required'   => true,
				'itComplements' => true, //son complemento del filtro anio
			],[
				'field'      => 'hasta_ini',
				'field_expo' => 'periodo',
				'field_impo' => 'periodo',
				'required'   => true,
				'itComplements' => true, //son complemento del filtro anio
			],[
				'field'      => 'desde_fin',
				'field_expo' => 'periodo',
				'field_impo' => 'periodo',
				'required'   => true,
				'itComplements' => true, //son complemento del filtro anio
			],[
				'field'      => 'hasta_fin',
				'field_expo' => 'periodo',
				'field_impo' => 'periodo',
				'required'   => true,
				'itComplements' => true, //son complemento del filtro anio
			]
		],
	],
	'executeConfig' => [
		'1'  => ['repoClassName' => 'DeclaracionesRepo', 'methodName' => 'BalanzaRelativa'],
		'2'  => ['repoClassName' => 'DeclaracionesRepo', 'methodName' => 'Balanza'],
		'3'  => ['repoClassName' => 'DeclaracionesRepo', 'methodName' => 'BalanzaVariacion'],
		'4'  => ['repoClassName' => 'DeclaracionesRepo', 'methodName' => 'OfertaExportable'],
		'5'  => ['repoClassName' => 'DeclaracionesRepo', 'methodName' => 'OfertaExportable'],
		'6'  => ['repoClassName' => 'DeclaracionesRepo', 'methodName' => 'TasaCrecimientoProductosNuevos'],
		'7'  => ['repoClassName' => 'DeclaracionesRepo', 'methodName' => 'NumeroPaisesDestino'],
		'8'  => ['repoClassName' => 'DeclaracionesRepo', 'methodName' => 'IHH'],
		'9'  => ['repoClassName' => 'DeclaracionesRepo', 'methodName' => 'ParticipacionExpoSectorAgricola'],
		'10' => ['repoClassName' => 'DeclaracionesRepo', 'methodName' => 'ParticipacionExpoNoTradicional'],
		'11' => ['repoClassName' => 'DeclaracionesRepo', 'methodName' => 'ParticipacionExpoPorProducto'],
		'12' => ['repoClassName' => 'DeclaracionesRepo', 'methodName' => 'CrecimientoExportadores'],
	],
	'yearsAvailable' => ['2010', '2011', '2012', '2013', '2014'],
	'periods' => [
		[12, Lang::get('indicador.reports.annual')],
		[6,  Lang::get('indicador.reports.semester')],
		[3,  Lang::get('indicador.reports.quarter')],
		[1,  Lang::get('indicador.reports.montly')]
	],
	'trade' => [
		['impo', Lang::get('indicador.reports.imports')],
		['expo', Lang::get('indicador.reports.exports')]
	],
	'productsAgriculture' => ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24', '5201', '5202', '5203'],
	'productsTraditional' => ['0901', '2101', '0603', '0803'],
	'energeticMiningSector' => ['25', '26', '27', '71'],
	'ConcentrationExportableSupply' => 80,
];