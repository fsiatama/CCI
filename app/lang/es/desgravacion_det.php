<?php
return [
	'table_name'     => 'Desgravación arancelaria detalle',
	'undefined_year' => 'Indefinido',
	'columns_title'  => [
		'desgravacion_det_anio_ini'       => 'Año Desde',      
		'desgravacion_det_anio_fin'       => 'Año Hasta',      
		'desgravacion_det_tasa'           => 'porcentaje (%)',
		'desgravacion_det_tipo_operacion' => 'Operador',
    ],
    'desgravacion_det_tipo_operacion' => [
		'igual'                => 'Igual a',
		'reduccion_porcentual' => 'Disminución anual en %',
	]
];