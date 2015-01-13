<?php
return [
	'table_name' => 'Contingente Arancelario',
	'contingente_mcontingente' => [
        '0' => 'No',
        '1' => 'Si'
    ],
    'contingente_msalvaguardia' => [
        '0' => 'No',
        '1' => 'Si'
    ],
    'alerts' => [
		'contingente_mcontingente'   => '¡Advertencia!  Modificar este parámetro eliminará los cupos asignados, si existen.',
    ],
    'columns_title' => [
		'contingente_id_pais'                => 'País',
		'contingente_mcontingente'           => 'Maneja Contingente?',
		'contingente_desc'                   => 'Descripción',
		'contingente_msalvaguardia'          => 'Maneja Salvaguardia?',
		'contingente_salvaguardia_sobretasa' => 'Porcentaje para aplicar Salvaguardia',
    ]
];