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
WHERE ( decl.id_capitulo IN('01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24') OR  decl.id_partida IN('5201','5202','5203')) 
GROUP BY 
DATE_FORMAT(decl.fecha, '%Y'),
decl.periodo,
decl.id_empresa,decl.id_paisorigen,decl.id_paiscompra,decl.id_paisprocedencia,
decl.id_posicion,decl.id_ciiu