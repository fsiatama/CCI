SELECT id,decl.anio AS ano, SUM(decl.valorfob) AS "valor_impo" 
FROM declaraimp AS decl  
WHERE anio BETWEEN 2013 AND 2014
AND periodo BETWEEN 6 AND 12
GROUP BY periodo ORDER BY 3 DESC




SELECT id,decl.anio AS ano, SUM(decl.valorfob) AS "valor_expo" 
FROM declaraexp AS decl  
GROUP BY decl.anio ORDER BY 3 DESC


SELECT  (CASE 
	   WHEN 0 < periodo AND periodo <= 3 THEN 1
	   WHEN 3 < periodo AND periodo <= 6 THEN 2
	   WHEN 6 < periodo AND periodo <= 9 THEN 3
	   WHEN 9 < periodo AND periodo <= 12 THEN 4
	 END
	) AS periodo,
	SUM(valorfob) AS "valorfob" 
FROM declaraimp 
  WHERE (id_paisprocedencia IN("589"))  AND  (id_capitulo IN("10")) 
GROUP BY (CASE 
	   WHEN 0 < periodo AND periodo <= 3 THEN 1
	   WHEN 3 < periodo AND periodo <= 6 THEN 2
	   WHEN 6 < periodo AND periodo <= 9 THEN 3
	   WHEN 9 < periodo AND periodo <= 12 THEN 4
	 END
	)
	
	
	
	

