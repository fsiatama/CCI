LOAD DATA LOCAL INFILE 'C:/Users/fsiatama.RTQHDOMAIN/Downloads/data minagricultura/completa/declaraexp_2005_2014.csv'
INTO TABLE declaraexp
CHARACTER SET utf8
FIELDS TERMINATED BY '|'
/*OPTIONALLY ENCLOSED BY '"'*/
LINES TERMINATED BY '\r\n' STARTING BY ''
(@id,anio,periodo,fecha,id_empresa,id_paisdestino,id_deptorigen,id_capitulo,id_partida,id_subpartida,id_posicion,id_ciiu,valorfob,valorcif,valor_pesos,peso_neto)
SET id = NULL;

