SELECT GROUP_CONCAT(DISTINCT SUBSTR(id_posicion,1,2) ) AS capitulos,
GROUP_CONCAT(DISTINCT SUBSTR(id_posicion,1,4) ) AS partidas,
GROUP_CONCAT(DISTINCT SUBSTR(id_posicion,1,6) ) AS subpartidas 
FROM posicion 
LEFT JOIN arancel ON SUBSTRING(id_posicion,1,6) = CONCAT(cod_capitulo, cod_partida, cod_subpartida)
WHERE id_posicion LIKE '09%'
			


EXPLAIN
SELECT
 id_posicion,
 posicion,
 capitulo.cod_capitulo AS id_capitulo,
 capitulo.descripcion AS capitulo,
 CONCAT(partida.cod_capitulo, partida.cod_partida) AS id_partida,
 partida.descripcion AS partida,
 CONCAT(subpartida.cod_capitulo, subpartida.cod_partida, subpartida.cod_subpartida) AS id_subpartida,
 subpartida.descripcion AS subpartida
FROM posicion
LEFT JOIN arancel AS capitulo ON (
	SUBSTR(id_posicion,1,2) = capitulo.cod_capitulo
    AND capitulo.cod_partida IS NULL
    AND capitulo.cod_subpartida IS NULL
    AND capitulo.cod_posicion IS NULL
)
LEFT JOIN arancel AS partida ON (
	SUBSTR(id_posicion,1,2) = partida.cod_capitulo
    AND SUBSTR(id_posicion,3,2) = partida.cod_partida
    AND partida.cod_subpartida IS NULL
    AND partida.cod_posicion IS NULL
)
LEFT JOIN arancel AS subpartida ON (
	SUBSTR(id_posicion,1,2) = subpartida.cod_capitulo 
    AND SUBSTR(id_posicion,3,2) = subpartida.cod_partida 
    AND SUBSTR(id_posicion,5,2) = subpartida.cod_subpartida 
    AND subpartida.cod_posicion IS NULL
)