LOAD DATA LOCAL INFILE 'Imp2005.csv'
INTO TABLE declaraimp
FIELDS TERMINATED BY '|'
OPTIONALLY ENCLOSED BY '"'
LINES TERMINATED BY '\r\n' STARTING BY ''
(@id, anio, periodo, @fecha, id_empresa, id_paisorigen, id_paiscompra, id_paisprocedencia, id_deptorigen, id_capitulo, id_partida, id_subpartida, id_posicion, id_ciiu, valorcif, valorfob, peso_neto, arancel_pagado, valorarancel)
SET id = NULL, fecha = DATE(CONCAT(anio, '-', periodo,'-01'));

LOAD DATA LOCAL INFILE 'Imp2006.csv'
INTO TABLE declaraimp
FIELDS TERMINATED BY '|'
OPTIONALLY ENCLOSED BY '"'
LINES TERMINATED BY '\r\n' STARTING BY ''
(@id, anio, periodo, @fecha, id_empresa, id_paisorigen, id_paiscompra, id_paisprocedencia, id_deptorigen, id_capitulo, id_partida, id_subpartida, id_posicion, id_ciiu, valorcif, valorfob, peso_neto, arancel_pagado, valorarancel)
SET id = NULL, fecha = DATE(CONCAT(anio, '-', periodo,'-01'));

LOAD DATA LOCAL INFILE 'Imp2007.csv'
INTO TABLE declaraimp
FIELDS TERMINATED BY '|'
OPTIONALLY ENCLOSED BY '"'
LINES TERMINATED BY '\r\n' STARTING BY ''
(@id, anio, periodo, @fecha, id_empresa, id_paisorigen, id_paiscompra, id_paisprocedencia, id_deptorigen, id_capitulo, id_partida, id_subpartida, id_posicion, id_ciiu, valorcif, valorfob, peso_neto, arancel_pagado, valorarancel)
SET id = NULL, fecha = DATE(CONCAT(anio, '-', periodo,'-01'));

LOAD DATA LOCAL INFILE 'Imp2008.csv'
INTO TABLE declaraimp
FIELDS TERMINATED BY '|'
OPTIONALLY ENCLOSED BY '"'
LINES TERMINATED BY '\r\n' STARTING BY ''
(@id, anio, periodo, @fecha, id_empresa, id_paisorigen, id_paiscompra, id_paisprocedencia, id_deptorigen, id_capitulo, id_partida, id_subpartida, id_posicion, id_ciiu, valorcif, valorfob, peso_neto, arancel_pagado, valorarancel)
SET id = NULL, fecha = DATE(CONCAT(anio, '-', periodo,'-01'));

LOAD DATA LOCAL INFILE 'Imp2009.csv'
INTO TABLE declaraimp
FIELDS TERMINATED BY '|'
OPTIONALLY ENCLOSED BY '"'
LINES TERMINATED BY '\r\n' STARTING BY ''
(@id, anio, periodo, @fecha, id_empresa, id_paisorigen, id_paiscompra, id_paisprocedencia, id_deptorigen, id_capitulo, id_partida, id_subpartida, id_posicion, id_ciiu, valorcif, valorfob, peso_neto, arancel_pagado, valorarancel)
SET id = NULL, fecha = DATE(CONCAT(anio, '-', periodo,'-01'));

LOAD DATA LOCAL INFILE 'declaraimp.csv'
INTO TABLE declaraimp
FIELDS TERMINATED BY ';'
OPTIONALLY ENCLOSED BY '"'
LINES TERMINATED BY '\r\n' STARTING BY ''
(@id, anio, periodo, @fecha, id_empresa, id_paisorigen, id_paiscompra, id_paisprocedencia, id_deptorigen, id_capitulo, id_partida, id_subpartida, id_posicion, id_ciiu, valorcif, valorfob, peso_neto, arancel_pagado, valorarancel)
SET id = NULL, fecha = DATE(CONCAT(anio, '-', periodo,'-01'));
