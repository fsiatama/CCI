<?php
return [
	'alerts' => [
		'change_nperiodos'                  => '¡Advertencia!  Modificar este parámetro eliminará toda la información de contingentes, si existen.',
		'change_contingente_acumulado_pais' => '¡Advertencia!  Modificar este parámetro eliminará toda la información de contingentes, si existen.',
		'change_desgravacion_igual_pais'    => '¡Advertencia!  Modificar este parámetro eliminará toda la información de desgravación de este producto, si existe.',
    ],
	'table_name' => 'Productos del acuerdo comercial',
    'columns_title' => [
		'acuerdo_det_arancel_base'               => 'Arancel Base (Valor en Porcentaje)',
		'acuerdo_det_productos'                  => 'Códigos Arancelarios',
		'acuerdo_det_productos_desc'             => 'Descripción Línea Arancelaria',
		'acuerdo_det_administracion'             => 'Mecanismo De Administración',
		'acuerdo_det_administrador'              => 'Administrador',
		'acuerdo_det_nperiodos'                  => 'Vigencia (Valor en Años)',
		'acuerdo_det_contingente_acumulado_pais' => 'Contingente es acumulado para todo el mercado?',
		'acuerdo_det_desgravacion_igual_pais'    => 'La desgravación es igual para todo el mercado?',
    ]
];