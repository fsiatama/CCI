SELECT id,decl.anio AS ano, SUM(decl.valorfob) AS "valor_impo" 
FROM declaraimp AS decl  
GROUP BY decl.anio ORDER BY 3 DESC




SELECT id,decl.anio AS ano, SUM(decl.valorfob) AS "valor_expo" 
FROM declaraexp AS decl  
GROUP BY decl.anio ORDER BY 3 DESC