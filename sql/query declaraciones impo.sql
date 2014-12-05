SELECT id,
DATE_FORMAT(decl.fecha, '%Y') AS anio,
decl.periodo,
decl.id_empresa,
decl.id_paisorigen,
decl.id_paiscompra,
decl.id_paisprocedencia,
decl.id_capitulo,
decl.id_partida,
decl.id_subpartida,
decl.id_posicion,
decl.id_ciiu,
SUM(decl.valorcif) AS "valorcif", 
SUM(decl.valorfob) AS "valorfob", 
SUM(decl.peso_neto) AS "peso_neto" 
FROM acumulado_impo AS decl
GROUP BY 
DATE_FORMAT(decl.fecha, '%Y'),
decl.periodo,
decl.id_empresa,decl.id_paisorigen,decl.id_paiscompra,decl.id_paisprocedencia,
decl.id_posicion,decl.id_ciiu