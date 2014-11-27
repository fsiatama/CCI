<?php
//include_once(PATH_RAIZ.'adodb5/adodb-lib.inc.php');
include_once(PATH_RAIZ.'adodb5/toexport.inc.php');
include_once(PATH_RAIZ.'adodb5/pivottable.inc.php'); 
set_time_limit(0);
ini_set('memory_limit', '2048M');
//ini_set("display_errors", true);
class DeclaracionesAdo extends Conexion{
	var $conn;
 	function DeclaracionesAdo($_bd){
		parent::Conexion($_bd);
	}
 	function lista($bd, $intercambio, $tablas, $campos, $filtros, $limit = "", $order = "", $group = "", $cache = false, $total, $join){//base de datos, nombre de la tabla, array con los campos, array con los filtro
		$conn = $this->conn;
		global $ADODB_COUNTRECS;
		
		$sql   = "SELECT ".implode(", ", $campos)  ." FROM ".$tablas;
		$sql  .= (empty($join)) ? "" : " WHERE ((".implode(") AND (", $join).")";
		$sql  .= (empty($filtros)) ? "" : " AND (".implode(") AND (", $filtros)."))";
		$sql  .= ($group != "") ? " GROUP BY ". $group : "";
		$sql  .= ($order != "") ? " ORDER BY ". $order : "";
		
		
		//$limit = ($limit != "") ? $limit : "0, ".MAXREGEXCEL;
		//$limit = ($limit != "") ? $limit : MAXREGEXCEL;
		
		//print($sql);
		//exit();
		//$conn->debug = true;		
		
		$result = array();
		
		if($limit != ""){
			$arr_limit = explode(",",$limit);
			
			$savec = $ADODB_COUNTRECS;
			if ($conn->pageExecuteCountRows) $ADODB_COUNTRECS = true;			
			
			if($cache){
				$rs = $conn->CachePageExecute(259200,$sql,$arr_limit[1], $arr_limit[0]);
			}
			else{
				$rs = $conn->PageExecute($sql,$arr_limit[1], $arr_limit[0]);
			}
			$ADODB_COUNTRECS = $savec;
			//$result["total"] = ($group != "")?$rs->_maxRecordCount:$total;//provisinalmente para inabilitar el sphinx
			$result["total"] = $rs->_maxRecordCount;
		}
		else{
			$sql .= " LIMIT ".MAXREGEXCEL;
			if($cache){
				$rs = $conn->CacheExecute(259200, $sql);
			}
			else{
				$rs = $conn->Execute($sql);
			}
			$result["total"] 	= $rs->_numOfRows;
		}
				
		
		if(!$rs){
			return $conn->ErrorMsg();
		}
		else{
			/*******este bloque arma el conjunto de columnas devueltos por la consulta**********/
			$fieldTypes = $rs->FieldTypesArray();
			reset($fieldTypes);
			$i = 0;
			$elements = array();
			while(list(,$o) = each($fieldTypes)) {
				$type = $rs->MetaType($o->type);
				//print $type." = ".$o->type."\n";
				//print_r($o);
				$v = ($o) ? $o->name : 'Field'.($i++);
				$v = strip_tags(str_replace("\n", " ", str_replace("\r\n"," ",$v)));
				$elements[] = array("type"=>$type, "col"=>$v);
				if($type != 'C' && substr($v,0,9) != "filtroid_" && $v != "id" && !in_array($v,$arr_filas_name)){
					$elements[] = array("type"=>'rate', "col"=>$v);
				}
			}
			/*******************************************************************************************/
			
			$result["columns"]  = $elements;
			while (!$rs->EOF) {
				$result["datos"][] = $rs->fields;
				$rs->MoveNext();
			}
		}
		$rs->Close();
		$conn->Close();
		
 		return $result;
	}
	function pivot($bd, $intercambio, $tablas, $filas, $columnas = false, $totales = false, $filtros, $limit = "", $order = "", $cache = false, $join){
		global $bd_sindirectorio;
		$conn = $this->conn;
		$funcion = (empty($totales))?'SUM':'SUM';
		if(empty($join)){
			$filtros_sql .= (empty($filtros)) ? "" :" (" . implode(") AND (", $filtros).")";
		}
		else{
			$filtros_sql  = "(".implode(") AND (", $join).")";
			$filtros_sql .= (empty($filtros)) ? "" :" AND (" . implode(") AND (", $filtros).")";
		}
		$resultado = PivotTableSQL( 
			$conn,        # adodb connection 
			$tablas,   	  # tables 
			$filas,       # rows (multiple fields allowed) 
			$columnas,    # column to pivot on 
			$filtros_sql, # joins/where
			$totales,
			'',
			$funcion,
			false
		);
		$sql  = $resultado["sql"];
		$arr_filas = explode(",",$filas);
		$arr_filas_name = array();
		foreach($arr_filas as $key => $data){
			$tmp = explode(" AS ", $data);
			if(count($tmp) > 1){
				$arr_filas_name[] = $tmp[1];
			}
		}
		
		//print_r($arr_filas_name);
		//ajusta el ordenamiento
		if($order == ""){
			$select_arr = explode(" AS ",$resultado["campos"]);
			if($filas && $totales){
				$order = (count($select_arr) + 1) . " DESC";
			}
			else{
				$order = (count($select_arr)) . " DESC";
			}
		}
		
		$sql .= ($order != "") ? " ORDER BY ". $order : " ";
		
		//print($sql);
		//exit();
		//$conn->debug = true;
		
		$arr_limit = explode(",",$limit);
		if($cache){
			$rs = $conn->CacheExecute(259200, $sql);
		}
		else{
			$rs = $conn->Execute($sql);
		}
		
		$result = array();
		if(!$rs){
			return $conn->ErrorMsg();
		}
		else{
			
			/*******este bloque arma el conjunto de columnas devueltos por la consulta**********/
			$fieldTypes = $rs->FieldTypesArray();
			reset($fieldTypes);
			$i = 0;
			while(list(,$o) = each($fieldTypes)) {
				$type = $rs->MetaType($o->type);
				//print $type." = ".$o->type."\n";
				//print_r($o);
				$v = ($o) ? $o->name : 'Field'.($i++);
				$v = strip_tags(str_replace("\n", " ", str_replace("\r\n"," ",$v)));
				$elements[] = array("type"=>$type, "col"=>$v);
				if($type != 'C' && substr($v,0,9) != "filtroid_" && $v != "id" && !in_array($v,$arr_filas_name)){
					$elements[] = array("type"=>'rate', "col"=>$v);
				}
			}
			/*******************************************************************************************/
			
			//print_r($elements);
			$result["total"] 	= $rs->_numOfRows;
			$result["columns"]  = $elements;
			$result["series"]   = $resultado["series"];
			while (!$rs->EOF) {
				$result["datos"][] = $rs->fields;
				$rs->MoveNext();
			}
		}
		$rs->Close();
		$conn->Close();
 		return $result;
	}
}
?>
