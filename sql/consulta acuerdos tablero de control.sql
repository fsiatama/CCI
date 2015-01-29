SELECT 
acuerdo_id,
acuerdo_det_id,
acuerdo_det_productos,
acuerdo_det_productos_desc,
acuerdo_det_nperiodos,
acuerdo_det_contingente_acumulado_pais,
contingente_id_pais,
contingente_mcontingente,
contingente_msalvaguardia,
mercado_nombre,
IF(acuerdo_det_contingente_acumulado_pais = '0', pais, mercado_nombre) AS pais,
IF(acuerdo_det_contingente_acumulado_pais = '0', id_pais, mercado_paises) AS id_pais,
IF(contingente_det_peso_neto IS NULL, 0, contingente_det_peso_neto) AS contingente_det_peso_neto,
IF(contingente_msalvaguardia = '0', 0, ( ( 1 + (contingente_salvaguardia_sobretasa / 100 ) ) *  contingente_det_peso_neto)) AS salvaguardia_peso_neto,
contingente_salvaguardia_sobretasa,
alerta_contingente_verde,
alerta_contingente_amarilla,
alerta_contingente_roja,
alerta_salvaguardia_verde,
alerta_salvaguardia_amarilla,
alerta_salvaguardia_roja
FROM acuerdo_det 
LEFT JOIN acuerdo ON acuerdo_det_acuerdo_id = acuerdo_id
LEFT JOIN mercado ON acuerdo_mercado_id = mercado_id
LEFT JOIN contingente ON acuerdo_det_id = contingente_acuerdo_det_id
LEFT JOIN alerta ON contingente_id = alerta_contingente_id
LEFT JOIN pais ON contingente_id_pais = id_pais
LEFT JOIN (
	SELECT contingente_det_id, contingente_det_contingente_id, contingente_det_peso_neto 
	FROM contingente_det 
	WHERE  2014 >= contingente_det_anio_ini AND 2014 <= contingente_det_anio_fin
) AS contingente_det ON contingente_id = contingente_det_contingente_id
WHERE acuerdo_det_acuerdo_id = 6