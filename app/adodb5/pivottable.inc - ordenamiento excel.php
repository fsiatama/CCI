<?php
/** 
 * @version V4.93 10 Oct 2006 (c) 2000-2012 John Lim (jlim#natsoft.com). All rights reserved.
 * Released under both BSD license and Lesser GPL library license. 
 * Whenever there is any discrepancy between the two licenses, 
 * the BSD license will take precedence. 
 *
 * Set tabs to 4 for best viewing.
 * 
*/

/*
 * Concept from daniel.lucazeau@ajornet.com. 
 *
 * @param db		Adodb database connection
 * @param tables	List of tables to join
 * @rowfields		List of fields to display on each row
 * @colfield		Pivot field to slice and display in columns, if we want to calculate
 *						ranges, we pass in an array (see example2)
 * @where			Where clause. Optional.
 * @aggfield		This is the field to sum. Optional. 
 *						Since 2.3.1, if you can use your own aggregate function 
 *						instead of SUM, eg. $aggfield = 'fieldname'; $aggfn = 'AVG';
 * @sumlabel		Prefix to display in sum columns. Optional.
 * @aggfn			Aggregate function to use (could be AVG, SUM, COUNT)
 * @showcount		Show count of records
 *
 * @returns			Sql generated
 */
 
function PivotTableSQL(&$db,$tables,$rowfields,$colfield, $where=false, $aggfield = false,$sumlabel='Sum ',$aggfn ='SUM', $showcount = true, $primary_key = false){
	global $_ano;
	if ($aggfield) $hidecnt = true;
	else $hidecnt = false;
	
	$iif = strpos($db->databaseType,'access') !== false; 
		// note - vfp 6 still doesn' work even with IIF enabled || $db->databaseType == 'vfp';
	
	//$hidecnt = false;
	
 	if ($where) $where = "\nWHERE $where";
	if (!is_array($colfield)){
		if($colfield == "decl.ano"){
			$colarr = $_ano;
		}
		else{
			$colarr = $db->GetCol("select distinct $colfield from $tables $where order by 1");
		}
	}
	if (!$aggfield) $hidecnt = false;
	if(is_array($rowfields)){
		$group_arr = $rowfields;
	}
	else{
		$group_arr = explode(",",$rowfields);
	}
	
	$i = 0;
	$groupby = array();
	/*elimina el campo id del agrupamiento*/
	foreach($group_arr as $key => $campo){
			if($campo != "id" && $campo != $primary_key){
				if(strpos($campo, "filtroid_") === false){
					$i += 1;
				}
				$groupby[] = $campo;
			}
	}
	//si hay mas de dos campos en filas ajusta el ordenamiento de la tabla dinamica
	if($aggfield && $i > 1){
		$arr_tmp = $aggfield;
		$ultimo_campo_totales = array_pop($arr_tmp);
		$funcion = "SUM";
		if($ultimo_campo_totales["tipo"] != 'n'){
			$funcion = 'COUNT';
		}
		$campo_ordenar = $funcion . "(".$ultimo_campo_totales["campo"]. ")";
		$orderby = array();
		$arr_campos_referencia = array();
		//descrta el ultimo campo
		array_pop($group_arr);
		foreach($group_arr as $key => $campo){
			if($campo != "id" && $campo != $primary_key){
				if(strpos($campo, "filtroid_") === false){
					//print "select distinct $campo, $campo_ordenar from $tables $where group by 1\n\n\n";
					$recordSet = &$db->Execute("select distinct $campo, $campo_ordenar from $tables $where group by 1");
					if(!$recordSet){
						return $db->ErrorMsg();
					}
					else{
						$cmp = preg_replace('/ AS (\w+)/i', '', $campo);
						$campo_referencia = "(CASE ".$cmp." ";
						while (!$recordSet->EOF){
							$campo_referencia .= "WHEN \"".$recordSet->fields[0]."\" THEN ". $recordSet->fields[1]." ";
							$recordSet->MoveNext();
						}
						$campo_referencia .= "END) AS campo_referencia".$key;
						$orderby[] = "campo_referencia".$key." DESC";
						$arr_campos_referencia[] = $campo_referencia;
						$recordSet->Close();
					}
				}
			}
		}
	}
	
	if(is_array($rowfields)){
		$rowfields = implode(",",$rowfields);
	}
	$sel  = "$rowfields, ";
	if($arr_campos_referencia){
		$campo_referencia = implode(",",$arr_campos_referencia);
		$sel .= $campo_referencia.", ";
	}
	
	$rowfields = implode(",",$groupby);
	$rowfields = preg_replace('/ AS (\w+)/i', '', $rowfields);
	
	
	if (is_array($colfield)) {
		
		foreach ($colfield as $k => $v) {
			$k = trim($k);
			if (!$hidecnt) {
				$sel .= $iif ? 
					"\n\t$aggfn(IIF($v,1,0)) AS \"$k\", "
					:
					"\n\t$aggfn(CASE WHEN $v THEN 1 ELSE 0 END) AS \"$k\", ";
			}
			if ($aggfield) {
				$sel .= $iif ?
					"\n\t$aggfn(IIF($v,$aggfield,0)) AS \"$sumlabel$k\", "
					:
					"\n\t$aggfn(CASE WHEN $v THEN $aggfield ELSE 0 END) AS \"$sumlabel$k\", ";
			}
		} 
	} else {
		foreach ($colarr as $v) {
			if (!is_numeric($v)) $vq = $db->qstr($v);
			else $vq = $v;
			$v = trim($v);
			if (strlen($v) == 0	) $v = 'null';
			$i = 0;
			
			if (!$hidecnt) {
				$sel .= $iif ?
					"\n\t$aggfn(IIF($colfield=$vq,1,0)) AS \"$v\", "
					:
					"\n\t$aggfn(CASE WHEN $colfield=$vq THEN 1 ELSE 0 END) AS \"$v\", ";
			}
			if ($aggfield) {
			
				foreach($aggfield as $campo){
					if ($hidecnt) $label = $v . " " .$campo["nombre"];
					else $label = "{$v}_".$campo["nombre"];
					if($campo["tipo"] != 'n'){
						$campo["campo"] = "1";
					}
					$sel .= $iif ?
						"\n\t$aggfn(IIF($colfield=$vq,".$campo["campo"].",0)) AS \"$label\", "
						:
						"\n\t$aggfn(CASE WHEN $colfield=$vq THEN ".$campo["campo"]." ELSE 0 END) AS \"$label\", ";
				}
			}
		}
	}
	if($aggfield && $aggfield != '1'){
		foreach($aggfield as $campo){
			if($campo["tipo"] != 'n'){
				$aggfn = 'COUNT';
			}
			$agg = $aggfn . "(".$campo["campo"]. ")";
			$sel .= "\n\t" . $agg ." as \"".$sumlabel. $campo["alias"] . "\", ";
		}
	}
	
	if ($showcount)
		$sel .= "\n\tSUM(1) as Total";
	else
		$sel = substr($sel,0,strlen($sel)-2);
	
	
	// Strip aliases
	$rowfields = implode(",",$groupby);
	$rowfields = preg_replace('/ AS (\w+)/i', '', $rowfields);
	
	$sql = "SELECT $sel \nFROM $tables $where \nGROUP BY $rowfields \n ";
	if($orderby){
		$orderfields = implode(",",$orderby);
		$sql .= "ORDER BY $orderfields";
	}
	$return = array("series"=>$colarr, "sql"=>$sql, "campos"=>$sel);
	return $return;
}

/* EXAMPLES USING MS NORTHWIND DATABASE */
if (0) {

# example1
#
# Query the main "product" table
# Set the rows to CompanyName and QuantityPerUnit
# and the columns to the Categories
# and define the joins to link to lookup tables 
# "categories" and "suppliers"
#

 $sql = PivotTableSQL(
 	$gDB,  											# adodb connection
 	'products p ,categories c ,suppliers s',  		# tables
	'CompanyName,QuantityPerUnit',					# row fields
	'CategoryName',									# column fields 
	'p.CategoryID = c.CategoryID and s.SupplierID= p.SupplierID' # joins/where
);
 print "<pre>$sql";
 $rs = $gDB->Execute($sql);
 rs2html($rs);
 
/*
Generated SQL:

SELECT CompanyName,QuantityPerUnit, 
	SUM(CASE WHEN CategoryName='Beverages' THEN 1 ELSE 0 END) AS "Beverages", 
	SUM(CASE WHEN CategoryName='Condiments' THEN 1 ELSE 0 END) AS "Condiments", 
	SUM(CASE WHEN CategoryName='Confections' THEN 1 ELSE 0 END) AS "Confections", 
	SUM(CASE WHEN CategoryName='Dairy Products' THEN 1 ELSE 0 END) AS "Dairy Products", 
	SUM(CASE WHEN CategoryName='Grains/Cereals' THEN 1 ELSE 0 END) AS "Grains/Cereals", 
	SUM(CASE WHEN CategoryName='Meat/Poultry' THEN 1 ELSE 0 END) AS "Meat/Poultry", 
	SUM(CASE WHEN CategoryName='Produce' THEN 1 ELSE 0 END) AS "Produce", 
	SUM(CASE WHEN CategoryName='Seafood' THEN 1 ELSE 0 END) AS "Seafood", 
	SUM(1) as Total 
FROM products p ,categories c ,suppliers s  WHERE p.CategoryID = c.CategoryID and s.SupplierID= p.SupplierID 
GROUP BY CompanyName,QuantityPerUnit
*/
//=====================================================================

# example2
#
# Query the main "product" table
# Set the rows to CompanyName and QuantityPerUnit
# and the columns to the UnitsInStock for diiferent ranges
# and define the joins to link to lookup tables 
# "categories" and "suppliers"
#
 $sql = PivotTableSQL(
 	$gDB,										# adodb connection
 	'products p ,categories c ,suppliers s',	# tables
	'CompanyName,QuantityPerUnit',				# row fields
												# column ranges
array(										
' 0 ' => 'UnitsInStock <= 0',
"1 to 5" => '0 < UnitsInStock and UnitsInStock <= 5',
"6 to 10" => '5 < UnitsInStock and UnitsInStock <= 10',
"11 to 15"  => '10 < UnitsInStock and UnitsInStock <= 15',
"16+" =>'15 < UnitsInStock'
),
	' p.CategoryID = c.CategoryID and s.SupplierID= p.SupplierID', # joins/where
	'UnitsInStock', 							# sum this field
	'Sum'										# sum label prefix
);
 print "<pre>$sql";
 $rs = $gDB->Execute($sql);
 rs2html($rs);
 /*
 Generated SQL:
 
SELECT CompanyName,QuantityPerUnit, 
	SUM(CASE WHEN UnitsInStock <= 0 THEN UnitsInStock ELSE 0 END) AS "Sum  0 ", 
	SUM(CASE WHEN 0 < UnitsInStock and UnitsInStock <= 5 THEN UnitsInStock ELSE 0 END) AS "Sum 1 to 5", 
	SUM(CASE WHEN 5 < UnitsInStock and UnitsInStock <= 10 THEN UnitsInStock ELSE 0 END) AS "Sum 6 to 10", 
	SUM(CASE WHEN 10 < UnitsInStock and UnitsInStock <= 15 THEN UnitsInStock ELSE 0 END) AS "Sum 11 to 15", 
	SUM(CASE WHEN 15 < UnitsInStock THEN UnitsInStock ELSE 0 END) AS "Sum 16+",
	SUM(UnitsInStock) AS "Sum UnitsInStock", 
	SUM(1) as Total 
FROM products p ,categories c ,suppliers s  WHERE  p.CategoryID = c.CategoryID and s.SupplierID= p.SupplierID 
GROUP BY CompanyName,QuantityPerUnit
 */
}
?>