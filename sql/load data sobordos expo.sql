LOAD DATA LOCAL INFILE 'C:/Users/fsiatama.RTQHDOMAIN/Downloads/data minagricultura/sobordoexp.csv'
INTO TABLE sobordoexp
CHARACTER SET utf8
FIELDS TERMINATED BY ';'
OPTIONALLY ENCLOSED BY '"'
LINES TERMINATED BY '\r\n' STARTING BY ''
(@id, anio,periodo, fecha, id_paisdestino, id_capitulo, id_partida,id_subpartida, peso_neto)
SET id = NULL


