LOAD DATA LOCAL INFILE 'C:/wamp/www/CCI/sql/old/Imp2009.csv'
INTO TABLE declaraimp
FIELDS TERMINATED BY '|'
OPTIONALLY ENCLOSED BY '"'
LINES TERMINATED BY '\r\n' STARTING BY ''
(@id, anio, periodo, id_empresa, id_paisorigen, id_paiscompra, id_paisprocedencia, id_deptorigen, id_capitulo, id_partida, id_subpartida, id_posicion, id_ciiu, valorcif, valorfob, peso_neto, arancel_pagado, valorarancel)
SET id = NULL
