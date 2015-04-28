SELECT 
id, 
ano,
periodo,
fecha,
id_pais_puerto_ext,
id_capitulo,
id_partida,
id_subpartida,
SUM(kilos)
FROM `acumulado_expo`
WHERE fecha BETWEEN '2010-01-01' AND '2014-12-31'
GROUP BY ano, periodo, fecha, id_pais_puerto_ext, id_subpartida