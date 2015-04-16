<?php
return [
	'filters' => [
		'1' => [
			[
				'field'              => 'id_pais',
				'field_expo'         => 'id_paisdestino',
				'field_impo'         => 'id_paisprocedencia',
				'required'   		 => false,
				'requiredComplement' => true,
				'complement'         => ['mercado_id'],
			],[
				'field'         => 'mercado_id',
				'field_expo'    => 'mercado_id',
				'field_impo'    => 'mercado_id',
				'required'      => false,
				'itComplements' => true, //son complemento del filtro id_pais
			],[
				'field'      => 'id_posicion',
				'field_expo' => 'id_posicion',
				'field_impo' => 'id_posicion',
				'required'   => false,
				'multivalue' => true,
			],[
				'field'      => 'sector_id',
				'field_expo' => 'sector_id',
				'field_impo' => 'sector_id',
				'required'   => false,
				'multivalue' => false,
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
				'field'              => 'id_pais',
				'field_expo'         => 'id_paisdestino',
				'field_impo'         => 'id_paisprocedencia',
				'required'   		 => false,
				'requiredComplement' => true,
				'complement'         => ['mercado_id'],
			],[
				'field'         => 'mercado_id',
				'field_expo'    => 'mercado_id',
				'field_impo'    => 'mercado_id',
				'required'      => false,
				'itComplements' => true, //son complemento del filtro id_pais
			],[
				'field'      => 'id_posicion',
				'field_expo' => 'id_posicion',
				'field_impo' => 'id_posicion',
				'required'   => false,
				'multivalue' => true,
			],[
				'field'      => 'sector_id',
				'field_expo' => 'sector_id',
				'field_impo' => 'sector_id',
				'required'   => false,
				'multivalue' => false,
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
				'field'              => 'id_pais',
				'field_expo'         => 'id_paisdestino',
				'field_impo'         => 'id_paisprocedencia',
				'required'   		 => false,
				'requiredComplement' => true,
				'complement'         => ['mercado_id'],
			],[
				'field'         => 'mercado_id',
				'field_expo'    => 'mercado_id',
				'field_impo'    => 'mercado_id',
				'required'      => false,
				'itComplements' => true, //son complemento del filtro id_pais
			],[
				'field'              => 'id_posicion',
				'field_expo'         => 'id_posicion',
				'field_impo'         => 'id_posicion',
				'required'           => false,
				'multivalue'         => true,
				'requiredComplement' => true,
				'complement'         => ['sector_id'],
			],[
				'field'         => 'sector_id',
				'field_expo'    => 'sector_id',
				'field_impo'    => 'sector_id',
				'required'      => false,
				'multivalue'    => false,
				'itComplements' => true, //son complemento del filtro id_posicion
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
				'field'              => 'id_pais',
				'field_expo'         => 'id_paisdestino',
				'field_impo'         => 'id_paisprocedencia',
				'required'   		 => false,
				'requiredComplement' => true,
				'complement'         => ['mercado_id'],
			],[
				'field'         => 'mercado_id',
				'field_expo'    => 'mercado_id',
				'field_impo'    => 'mercado_id',
				'required'      => false,
				'itComplements' => true, //son complemento del filtro id_pais
			],[
				'field'      => 'id_posicion',
				'field_expo' => 'id_posicion',
				'field_impo' => 'id_posicion',
				'required'   => false,
				'multivalue' => true,
			],[
				'field'      => 'sector_id',
				'field_expo' => 'sector_id',
				'field_impo' => 'sector_id',
				'required'   => false,
				'multivalue' => false,
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
		'5' => [
			[
				'field'              => 'id_pais',
				'field_expo'         => 'id_paisdestino',
				'field_impo'         => 'id_paisprocedencia',
				'required'   		 => false,
				'requiredComplement' => true,
				'complement'         => ['mercado_id'],
			],[
				'field'         => 'mercado_id',
				'field_expo'    => 'mercado_id',
				'field_impo'    => 'mercado_id',
				'required'      => false,
				'itComplements' => true, //son complemento del filtro id_pais
			],[
				'field'      => 'id_posicion',
				'field_expo' => 'id_posicion',
				'field_impo' => 'id_posicion',
				'required'   => false,
				'multivalue' => true,
			],[
				'field'      => 'sector_id',
				'field_expo' => 'sector_id',
				'field_impo' => 'sector_id',
				'required'   => false,
				'multivalue' => false,
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
		'6' => [
			[
				'field'      => 'intercambio',
				'field_expo' => 'intercambio',
				'field_impo' => 'intercambio',
				'required'   => true,
				'multivalue' => false,
			],[
				'field'              => 'id_pais',
				'field_expo'         => 'id_paisdestino',
				'field_impo'         => 'id_paisprocedencia',
				'required'   		 => false,
				'requiredComplement' => true,
				'complement'         => ['mercado_id'],
			],[
				'field'         => 'mercado_id',
				'field_expo'    => 'mercado_id',
				'field_impo'    => 'mercado_id',
				'required'      => false,
				'itComplements' => true, //son complemento del filtro id_pais
			],[
				'field'      => 'id_posicion',
				'field_expo' => 'id_posicion',
				'field_impo' => 'id_posicion',
				'required'   => false,
				'multivalue' => true,
			],[
				'field'      => 'sector_id',
				'field_expo' => 'sector_id',
				'field_impo' => 'sector_id',
				'required'   => false,
				'multivalue' => false,
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
			],[
				'field'      => 'id_posicion',
				'field_expo' => 'id_posicion',
				'field_impo' => 'id_posicion',
				'required'   => false,
				'multivalue' => true,
			],[
				'field'      => 'sector_id',
				'field_expo' => 'sector_id',
				'field_impo' => 'sector_id',
				'required'   => false,
				'multivalue' => false,
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
				'field'              => 'id_pais',
				'field_expo'         => 'id_paisdestino',
				'field_impo'         => 'id_paisprocedencia',
				'required'   		 => false,
				'requiredComplement' => true,
				'complement'         => ['mercado_id'],
			],[
				'field'         => 'mercado_id',
				'field_expo'    => 'mercado_id',
				'field_impo'    => 'mercado_id',
				'required'      => false,
				'itComplements' => true, //son complemento del filtro id_pais
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
				'field'      => 'mercado_id',
				'field_expo' => 'mercado_id',
				'field_impo' => 'mercado_id',
				'required'   => false,
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
		'10' => [
			[
				'field'      => 'id_pais',
				'field_expo' => 'id_paisdestino',
				'field_impo' => 'id_paisprocedencia',
				'required'   => false,
				'multivalue' => true,
			],[
				'field'      => 'mercado_id',
				'field_expo' => 'mercado_id',
				'field_impo' => 'mercado_id',
				'required'   => false,
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
		'11' => [
			[
				'field'              => 'id_pais',
				'field_expo'         => 'id_paisdestino',
				'field_impo'         => 'id_paisprocedencia',
				'required'   		 => false,
				'requiredComplement' => true,
				'complement'         => ['mercado_id'],
			],[
				'field'         => 'mercado_id',
				'field_expo'    => 'mercado_id',
				'field_impo'    => 'mercado_id',
				'required'      => false,
				'itComplements' => true, //son complemento del filtro id_pais
			],[
				'field'              => 'id_posicion',
				'field_expo'         => 'id_posicion',
				'field_impo'         => 'id_posicion',
				'required'           => false,
				'multivalue'         => true,
				'requiredComplement' => true,
				'complement'         => ['sector_id'],
			],[
				'field'         => 'sector_id',
				'field_expo'    => 'sector_id',
				'field_impo'    => 'sector_id',
				'required'      => false,
				'multivalue'    => false,
				'itComplements' => true, //son complemento del filtro id_posicion
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
		'12' => [
			[
				'field'              => 'id_pais',
				'field_expo'         => 'id_paisdestino',
				'field_impo'         => 'id_paisprocedencia',
				'required'   		 => false,
				'requiredComplement' => true,
				'complement'         => ['mercado_id'],
			],[
				'field'         => 'mercado_id',
				'field_expo'    => 'mercado_id',
				'field_impo'    => 'mercado_id',
				'required'      => false,
				'itComplements' => true, //son complemento del filtro id_pais
			],[
				'field'              => 'id_posicion',
				'field_expo'         => 'id_posicion',
				'field_impo'         => 'id_posicion',
				'required'           => false,
				'multivalue'         => true,
				'requiredComplement' => true,
				'complement'         => ['sector_id'],
			],[
				'field'         => 'sector_id',
				'field_expo'    => 'sector_id',
				'field_impo'    => 'sector_id',
				'required'      => false,
				'multivalue'    => false,
				'itComplements' => true, //son complemento del filtro id_posicion
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
		'13' => [
			[
				'field'              => 'id_posicion',
				'field_expo'         => 'id_posicion',
				'field_impo'         => 'id_posicion',
				'required'           => false,
				'multivalue'         => true,
				'requiredComplement' => true,
				'complement'         => ['sector_id'],
			],[
				'field'         => 'sector_id',
				'field_expo'    => 'sector_id',
				'field_impo'    => 'sector_id',
				'required'      => false,
				'multivalue'    => false,
				'itComplements' => true, //son complemento del filtro id_posicion
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
		/*'14' => [
			[
				'field'              => 'id_pais',
				'field_expo'         => 'id_paisdestino',
				'field_impo'         => 'id_paisprocedencia',
				'required'   		 => false,
				'requiredComplement' => true,
				'complement'         => ['mercado_id'],
			],[
				'field'         => 'mercado_id',
				'field_expo'    => 'mercado_id',
				'field_impo'    => 'mercado_id',
				'required'      => false,
				'itComplements' => true, //son complemento del filtro id_pais
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
				'yearRange'  => ['anio_fin'],
			],[
				'field'         => 'anio_fin',
				'field_expo'    => 'anio',
				'field_impo'    => 'anio',
				'required'      => true,
				'itComplements' => true, //son complemento del filtro anio_ini
			]
		],*/

		'14' => [
			[
				'field'      => 'id_subpartida',
				'field_expo' => 'id_subpartida',
				'field_impo' => 'id_subpartida',
				'required'   => true,
				'multivalue' => true,
			],[
				'field'      => 'id_pais_destino',
				'field_expo' => 'id_pais_destino',
				'field_impo' => 'id_pais_destino',
				'required'   => true,
				'multivalue' => false,
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

		'15' => [
			[
				'field'      => 'id_pais',
				'field_expo' => 'id_paisdestino',
				'field_impo' => 'id_paisprocedencia',
				'required'   => false,
				'multivalue' => true,
			],[
				'field'      => 'mercado_id',
				'field_expo' => 'mercado_id',
				'field_impo' => 'mercado_id',
				'required'   => false,
			],[
				'field'      => 'id_posicion',
				'field_expo' => 'id_posicion',
				'field_impo' => 'id_posicion',
				'required'   => false,
				'multivalue' => true,
			],[
				'field'      => 'sector_id',
				'field_expo' => 'sector_id',
				'field_impo' => 'sector_id',
				'required'   => false,
				'multivalue' => false,
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
		'16' => [
			[
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
		'17' => [
			[
				'field'              => 'id_posicion',
				'field_expo'         => 'id_posicion',
				'field_impo'         => 'id_posicion',
				'required'           => false,
				'multivalue'         => true,
				'requiredComplement' => true,
				'complement'         => ['sector_id'],
			],[
				'field'         => 'sector_id',
				'field_expo'    => 'sector_id',
				'field_impo'    => 'sector_id',
				'required'      => false,
				'multivalue'    => false,
				'itComplements' => true, //son complemento del filtro id_posicion
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
		'18' => [
			[
				'field'      => 'sector_id',
				'field_expo' => 'sector_id',
				'field_impo' => 'sector_id',
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
		'19' => [
			[
				'field'      => 'sector_id',
				'field_expo' => 'sector_id',
				'field_impo' => 'sector_id',
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
		'20' => [
			[
				'field'      => 'id_pais',
				'field_expo' => 'id_paisdestino',
				'field_impo' => 'id_paisprocedencia',
				'required'   => false,
				'multivalue' => true,
			],[
				'field'      => 'mercado_id',
				'field_expo' => 'mercado_id',
				'field_impo' => 'mercado_id',
				'required'   => false,
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
		'21' => [
			[
				'field'      => 'sector_id',
				'field_expo' => 'sector_id',
				'field_impo' => 'sector_id',
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
		'22' => [
			[
				'field'      => 'id_subpartida',
				'field_expo' => 'id_subpartida',
				'field_impo' => 'id_subpartida',
				'required'   => true,
				'multivalue' => true,
			],[
				'field'      => 'id_pais_origen',
				'field_expo' => 'id_pais_origen',
				'field_impo' => 'id_pais_origen',
				'required'   => true,
				'multivalue' => false,
			/*],[
				'field'      => 'id_pais_destino',
				'field_expo' => 'id_pais_destino',
				'field_impo' => 'id_pais_destino',
				'required'   => false,
				'multivalue' => false,*/
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
		'23' => [
			[
				'field'      => 'id_subpartida',
				'field_expo' => 'id_subpartida',
				'field_impo' => 'id_subpartida',
				'required'   => true,
				'multivalue' => true,
			],[
				'field'      => 'id_pais_origen',
				'field_expo' => 'id_pais_origen',
				'field_impo' => 'id_pais_origen',
				'required'   => true,
				'multivalue' => false,
			]
		],
		'contingente' => [
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
				'multivalue' => true,
			],[
				'field'      => 'id_posicion',
				'field_expo' => 'id_posicion',
				'field_impo' => 'id_posicion',
				'required'   => false,
				'multivalue' => true,
			]
		],
		'acuerdo_det' => [
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
				'multivalue' => true,
			],[
				'field'      => 'id_posicion',
				'field_expo' => 'id_posicion',
				'field_impo' => 'id_posicion',
				'required'   => false,
				'multivalue' => true,
			]
		],
		'cuadrantes' => [
			[
				'field'      => 'id_subpartida',
				'field_expo' => 'id_subpartida',
				'field_impo' => 'id_subpartida',
				'required'   => false,
				'multivalue' => true,
			],[
				'field'      => 'id_pais_destino',
				'field_expo' => 'id_pais_destino',
				'field_impo' => 'id_pais_destino',
				'required'   => false,
				'multivalue' => false,
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
		'colombiaAlMundo' => [
			[
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
		'principalesDestinos' => [
			[
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
		'principalesOrigenes' => [
			[
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
	],
	'executeConfig' => [
		'1'  => ['repoClassName' => 'DeclaracionesRepo', 'methodName' => 'BalanzaRelativa'],
		'2'  => ['repoClassName' => 'DeclaracionesRepo', 'methodName' => 'Balanza'],
		'3'  => ['repoClassName' => 'DeclaracionesRepo', 'methodName' => 'BalanzaVariacion'],
		'4'  => ['repoClassName' => 'DeclaracionesRepo', 'methodName' => 'OfertaExportable'],
		'5'  => ['repoClassName' => 'DeclaracionesRepo', 'methodName' => 'NumeroProductos'],
		'6'  => ['repoClassName' => 'DeclaracionesRepo', 'methodName' => 'TasaCrecimientoProductosNuevos'],
		'7'  => ['repoClassName' => 'DeclaracionesRepo', 'methodName' => 'NumeroPaisesDestino'],
		'8'  => ['repoClassName' => 'DeclaracionesRepo', 'methodName' => 'IHH'],
		'9'  => ['repoClassName' => 'DeclaracionesRepo', 'methodName' => 'ParticipacionExpoSectorAgricola'],
		'10' => ['repoClassName' => 'DeclaracionesRepo', 'methodName' => 'ParticipacionExpoNoTradicional'],
		'11' => ['repoClassName' => 'DeclaracionesRepo', 'methodName' => 'ParticipacionExpoPorProducto'],
		'12' => ['repoClassName' => 'DeclaracionesRepo', 'methodName' => 'CrecimientoExportadores'],
		'13' => ['repoClassName' => 'DeclaracionesRepo', 'methodName' => 'PromedioPonderadoArancel'],
		'14' => ['repoClassName' => 'DeclaracionesRepo', 'methodName' => 'ComtradeRelacionCrecimientoExpoColombiaImpoPais'],
		'15' => ['repoClassName' => 'DeclaracionesRepo', 'methodName' => 'RelacionCrecimientoExpoAgroExpoTot'],
		'16' => ['repoClassName' => 'DeclaracionesRepo', 'methodName' => 'ParticipacionExpoSectorAgricolaPib'],
		'17' => ['repoClassName' => 'DeclaracionesRepo', 'methodName' => 'ParticipacionExpoSectorAgricolaPibAgricola'],
		'18' => ['repoClassName' => 'DeclaracionesRepo', 'methodName' => 'CoeficientePenetracionImpo'],
		'19' => ['repoClassName' => 'DeclaracionesRepo', 'methodName' => 'CoeficienteAperturaExpo'],
		'20' => ['repoClassName' => 'DeclaracionesRepo', 'methodName' => 'RelacionCrecimientoExpoAgroNoTradicionalExpoAgro'],
		'21' => ['repoClassName' => 'DeclaracionesRepo', 'methodName' => 'ConsumoAparente'],
		'22' => ['repoClassName' => 'DeclaracionesRepo', 'methodName' => 'ComtradePenetracionMercado'],
		'23' => ['repoClassName' => 'DeclaracionesRepo', 'methodName' => 'ComtradePuestoColombiaProveedor'],
		'contingente' => ['repoClassName' => 'DeclaracionesRepo', 'methodName' => 'AcumuladoContingente'],
		'acuerdo_det' => ['repoClassName' => 'DeclaracionesRepo', 'methodName' => 'AcumuladoPosicionPais'],
		'cuadrantes'  => ['repoClassName' => 'DeclaracionesRepo', 'methodName' => 'ComtradeCuadrantes'],
		'colombiaAlMundo'  => ['repoClassName' => 'DeclaracionesRepo', 'methodName' => 'ColombiaAlMundo'],
		'principalesDestinos'  => ['repoClassName' => 'DeclaracionesRepo', 'methodName' => 'PrincipalesDestinos'],
		'principalesOrigenes'  => ['repoClassName' => 'DeclaracionesRepo', 'methodName' => 'PrincipalesOrigenes'],
	],
	'yearsAvailable' => ['2005','2006','2007','2008','2009','2010', '2011', '2012', '2013', '2014'],
	'periods' => [
		[12, Lang::get('indicador.reports.annual')],
		[6,  Lang::get('indicador.reports.semester')],
		[3,  Lang::get('indicador.reports.quarter')],
		[1,  Lang::get('indicador.reports.montly')]
	],
	'trade' => [
		['impo', Lang::get('indicador.reports.impo')],
		['expo', Lang::get('indicador.reports.expo')]
	],
	'scopes' => [
		['1', Lang::get('indicador.reports.national')],
		['2', Lang::get('indicador.reports.regional')],
		['3', Lang::get('indicador.reports.departmental')],
	],
	'scales' => [
		['1', Lang::get('indicador.reports.scaleUnit')],
		['2', Lang::get('indicador.reports.scaleThousands')],
		['3', Lang::get('indicador.reports.scaleMillions')],
	],
	'activator' => [
		['precio', Lang::get('tipo_indicador.tipo_indicador_activador.precio')],
		['volumen', Lang::get('tipo_indicador.tipo_indicador_activador.volumen')],
	],
	'charts' => [
		[COLUMNAS, Lang::get('indicador.charts.columns')],
		[LINEAL, Lang::get('indicador.charts.lines')],
		[AREA, Lang::get('indicador.charts.area')],
		[PIE, Lang::get('indicador.charts.pie')],
	],
	'sectorIdAgriculture'  => 2,
	'sectorIdTraditional'  => 3,
	'sectorIdMiningSector' => 1,
	'ConcentrationExportableSupply' => 80,
	'urlApiComtrade' => 'http://comtrade.un.org/api/get?',
	'colombiaIdComtrade' => 170,
];
