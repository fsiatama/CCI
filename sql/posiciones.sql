SELECT GROUP_CONCAT(DISTINCT SUBSTR(id_posicion,1,2) SEPARATOR "','") AS capitulos,
GROUP_CONCAT(DISTINCT SUBSTR(id_posicion,1,4) SEPARATOR "','") AS partidas,
GROUP_CONCAT(DISTINCT SUBSTR(id_posicion,1,6) SEPARATOR "','") AS subpartidas 
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


EXPLAIN
SELECT * FROM (
	SELECT * FROM (	
		SELECT id_posicion, posicion
		FROM posicion 
	WHERE ( NOT id_posicion IN (09,10,1001)
		AND NOT id_capitulo IN (09,10,1001)
		AND NOT id_partida IN (09,10,1001)
		AND NOT id_subpartida IN (09,10,1001)
	) AND (id_posicion LIKE "02%" OR posicion LIKE "02%")
	  ) AS posiciones 
	UNION SELECT * FROM (
		EXPLAIN
		SELECT CONCAT("",cod_capitulo) AS id_posicion, descripcion
		FROM arancel
		WHERE cod_capitulo IN ('02')
		  AND cod_partida    IS NULL
		  AND cod_subpartida IS NULL
		  AND cod_posicion   IS NULL 
	  ) AS capitulos 
	
			UNION SELECT * FROM (
				SELECT CONCAT(cod_capitulo,cod_partida)  AS id_posicion, descripcion
				FROM arancel
				WHERE CONCAT(cod_capitulo,cod_partida) IN ('0201','0202','0203','0204','0205','0206','0207','0208','0209','0210')
				AND cod_subpartida IS NULL
				AND cod_posicion  IS NULL
			  ) AS partidas 
		
				UNION SELECT * FROM (
					SELECT CONCAT(cod_capitulo,cod_partida,cod_subpartida)  AS id_posicion, descripcion
					FROM arancel
					WHERE CONCAT(cod_capitulo,cod_partida,cod_subpartida) IN ('020110','020120','020130','020210','020220','020230','020311','020312','020319','020321','020322','020329','020410','020421','020422','020423','020430','020441','020442','020443','020450','020500','020610','020621','020622','020629','020630','020641','020649','020680','020690','020711','020712','020713','020714','020724','020725','020726','020727','020732','020733','020734','020735','020736','020741','020742','020743','020744','020745','020751','020752','020753','020754','020755','020760','020810','020830','020840','020850','020860','020890','020900','020910','020990','021011','021012','021019','021020','021091','021092','021093','021099')
					AND cod_posicion  IS NULL
				  ) AS subpartidas
			
) AS qry
ORDER BY id_posicion 