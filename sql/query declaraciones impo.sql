SELECT id,
DATE_FORMAT(decl.fecha, '%Y') AS anio,
decl.periodo,
decl.id_empresa,
empresa.empresa AS empresa,
decl.id_paisorigen,
paisorigen.paises AS paisorigen,
decl.id_paiscompra,
paiscompra.paises AS paiscompra,
decl.id_paisprocedencia,
paisprocedencia.paises AS paisprocedencia,
decl.id_posicion,
posicion.posicion, 
decl.id_ciiu,
ciiu.ciiu,
SUM(decl.valorcif) AS "valorcif", 
SUM(decl.valorfob) AS "valorfob", 
SUM(decl.peso_neto) AS "peso_neto" 
FROM acumulado_impo AS decl,
empresa,
posicion,
paises AS paisprocedencia,
paises AS paisorigen,
paises AS paiscompra,
ciiu 
WHERE (decl.id_empresa = empresa.id_empresa) 
AND (decl.id_posicion = posicion.id_posicion) 
AND (decl.id_paisprocedencia = paisprocedencia.id_paises) 
AND (decl.id_paisorigen = paisorigen.id_paises) 
AND (decl.id_paiscompra = paiscompra.id_paises) 
AND (decl.id_ciiu = ciiu.id_ciiu) 
AND ( decl.id_capitulo IN('01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24') OR  decl.id_partida IN('5201','5202','5203')) 
GROUP BY 
DATE_FORMAT(decl.fecha, '%Y'),
decl.periodo,
decl.id_empresa,decl.id_paisorigen,decl.id_paiscompra,decl.id_paisprocedencia,
decl.id_posicion,decl.id_ciiu