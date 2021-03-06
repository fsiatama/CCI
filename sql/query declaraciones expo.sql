SELECT id,
DATE_FORMAT(decl.fecha, '%Y') AS anio,
decl.periodo,
decl.id_empresa,
decl.id_paisdestino,
decl.id_deptorigen,
decl.id_capitulo,
decl.id_partida,
decl.id_subpartida,
decl.id_posicion AS id_posicion,
decl.id_ciiu AS id_ciiu,
SUM(decl.valorfob) AS "valorfob", 
SUM(decl.valorcif) AS "valorcif", 
SUM(decl.valor_pesos) AS "valor_pesos", 
SUM(decl.peso_neto) AS "peso_neto" 
FROM acumulado_expo AS decl
WHERE DATE_FORMAT(decl.fecha, '%Y') IN (2010,2011)
GROUP BY 
DATE_FORMAT(decl.fecha, '%Y'),
decl.periodo,
decl.id_empresa,
decl.id_paisdestino,
decl.id_deptorigen,
decl.id_posicion,
decl.id_ciiu