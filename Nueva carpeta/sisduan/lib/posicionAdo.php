<?php
include("posicion.php");
class PosicionAdo extends Conexion{
	var $conn;
	function PosicionAdo($_bd){
		parent::Conexion($_bd);
	}
	function lista($posicion){
		$conn = $this->conn;
		$filtro = array();
		foreach($posicion as $key => $data){
			if ($data <> ''){
				$filtro[] = $key . " = '" . $data ."'";
			}
		}
		$sql  = 'SELECT * FROM posicion';
		if(!empty($filtro)){
			$sql .= ' WHERE '. implode(' AND ', $filtro);
		}
		$rs   = $conn->Execute($sql);
		$result = array();
		if(!$rs){
			return $conn->ErrorMsg();
		}
		$total = $rs->RecordCount();
		while(!$rs->EOF){
			$result["datos"][] = $rs->fields;
			$rs->MoveNext();
		}
		$result["total"] = $total;
		$rs->Close();
		return $result;
	}
	function listaArr($arr){ //recibe un array para buscar todos los valores que coincidan pueden ser capitulos, partidas, subpartidas o posiciones
		$conn = $this->conn;
		$filtro = array();
		foreach($arr as $key => $data){
			if ($data <> ''){
				$filtro[] = " id_posicion LIKE '" . $data ."%'";
			}
		}
		$sql  = 'SELECT * FROM posicion';
		if(!empty($filtro)){
			$sql .= ' WHERE '. implode(' OR ', $filtro);
		}
		//print $sql;
		//$conn->debug = true;
		$rs   = $conn->Execute($sql);
		$result = array();
		if(!$rs){
			return $conn->ErrorMsg();
		}
		while(!$rs->EOF){
			$result[] = $rs->fields;
			$rs->MoveNext();
		}
		$rs->Close();
		return $result;
	}
	function lista_filtro($query, $queryValuesIndicator, $limit, $seleccionados = false){
		$conn = $this->conn;
		global $ADODB_COUNTRECS;
		$filtro = array();
		if($queryValuesIndicator && is_array($query)){
			$arr = array();
			foreach($query as $key => $value){
				$arr[] = "id_posicion LIKE '" . $value ."%'";
			}
			$filtro[] = "(".implode(" OR ",$arr).")";
		}
		else{
			if(is_array($query)){
				$tmp_query = array_pop($query);
				$arr = array();
				foreach($query as $key => $value){
					$arr[] = "id_posicion LIKE '" . $value ."%'";
				}
				$filtro[] = "(".implode(" OR ",$arr).")";
				$query = $tmp_query;
			}
			if(is_numeric($query)){
				$filtro[] = "id_posicion LIKE '" . $query ."%'";
			}
			else{
				$filtro[] = "(
					   aran.descripcion_ing LIKE '%" . $query ."%'
					OR id_posicion LIKE '%" . $query ."%'
					OR posicion LIKE '%" . $query ."%'
				)";
			}
			if($seleccionados && count($seleccionados) > 0){
				$filtro[] = "(NOT id_posicion IN (".implode(",",$seleccionados)."))";
				$filtro[] = "(NOT SUBSTR(id_posicion,1,2) IN (".implode(",",$seleccionados)."))";
				$filtro[] = "(NOT SUBSTR(id_posicion,1,4) IN (".implode(",",$seleccionados)."))";
				$filtro[] = "(NOT SUBSTR(id_posicion,1,6) IN (".implode(",",$seleccionados)."))";
			}
		}
		
		$sql = "
			SELECT GROUP_CONCAT(DISTINCT SUBSTR(id_posicion,1,2) SEPARATOR \"','\") AS capitulos,
			GROUP_CONCAT(DISTINCT SUBSTR(id_posicion,1,4) SEPARATOR \"','\") AS partidas,
			GROUP_CONCAT(DISTINCT SUBSTR(id_posicion,1,6) SEPARATOR \"','\") AS subpartidas 
			FROM posicion LEFT JOIN arancel_aduana.arancel_ingles AS aran ON SUBSTRING(id_posicion,1,6) = aran.cod_armonizado
		";		
		$filtro_sql = "";
		if(!empty($filtro)){
			$filtro_sql = ' WHERE '. implode(' AND ', $filtro);
			$sql .= $filtro_sql;			
		}
		//print $sql;
		$colarr = $conn->getRow($sql);
		if($colarr["partidas"] == "" || $colarr["capitulos"] == "" || $colarr["subpartidas"] == ""){
			return false;
		}
		$sql = "
			SELECT * FROM (
				SELECT * FROM (	
					SELECT id_posicion, posicion, aran.descripcion_ing AS posicion_ing
					FROM posicion 
					LEFT JOIN arancel_aduana.arancel_ingles AS aran ON SUBSTRING(id_posicion,1,6) = aran.cod_armonizado
					".$filtro_sql."
				  ) AS subpartidas UNION SELECT * FROM (
					SELECT CONCAT(\"\",arancel.cod_capitulo) AS id_posicion, descripcion, descripcion_ing
					FROM arancel_aduana.arancel, arancel_aduana.arancel_ingles 
					WHERE arancel_ingles.cod_capitulo = arancel.cod_capitulo
					AND arancel_ingles.cod_partida    IS NULL
					AND arancel_ingles.cod_subpartida IS NULL
					AND arancel_ingles.cod_posicion   IS NULL							
					AND arancel.cod_capitulo IN ('".$colarr["capitulos"]."')
					AND arancel.cod_partida	   IS NULL
					AND arancel.cod_subpartida IS NULL
					AND arancel.cod_posicion  IS NULL 
				  ) AS capitulos UNION SELECT * FROM (
					SELECT CONCAT(arancel.cod_capitulo,arancel.cod_partida)  AS id_posicion, descripcion, descripcion_ing
					FROM arancel_aduana.arancel, arancel_aduana.arancel_ingles 
					WHERE arancel_ingles.cod_capitulo = arancel.cod_capitulo
					AND arancel_ingles.cod_partida    = arancel.cod_partida
					AND arancel_ingles.cod_subpartida IS NULL
					AND arancel_ingles.cod_posicion   IS NULL							
					AND CONCAT(arancel.cod_capitulo,arancel.cod_partida) IN ('".$colarr["partidas"]."')
					AND arancel.cod_subpartida IS NULL
					AND arancel.cod_posicion  IS NULL
				  ) AS partidas UNION SELECT * FROM (
					SELECT CONCAT(arancel.cod_capitulo,arancel.cod_partida,arancel.cod_subpartida)  AS id_posicion, descripcion, descripcion_ing
					FROM arancel_aduana.arancel, arancel_aduana.arancel_ingles 
					WHERE arancel_ingles.cod_capitulo = arancel.cod_capitulo
					AND arancel_ingles.cod_partida    = arancel.cod_partida
					AND arancel_ingles.cod_subpartida = arancel.cod_subpartida
					AND arancel_ingles.cod_posicion   IS NULL							
					AND CONCAT(arancel.cod_capitulo,arancel.cod_partida,arancel.cod_subpartida) IN ('".$colarr["subpartidas"]."')
					AND arancel.cod_posicion  IS NULL
				  ) AS subpartidas
			) AS qry
			ORDER BY id_posicion  
		";
		$sql = str_replace("''","'",$sql);
		//print $sql;
		$result = array();
		if($queryValuesIndicator && is_array($query)){
			$rs = $conn->Execute($sql);
			$result["total"] = $rs->RecordCount();
		}
		elseif($limit != ""){
			$arr_limit = explode(",",$limit);
			$savec = $ADODB_COUNTRECS;
			if($conn->pageExecuteCountRows) $ADODB_COUNTRECS = true;
			$rs = $conn->PageExecute($sql,$arr_limit[1], $arr_limit[0]);
			$ADODB_COUNTRECS = $savec;
			$result["total"] = $rs->_maxRecordCount;
		}
		if(!$rs){
			return $conn->ErrorMsg();
		}
		while(!$rs->EOF){
			$result["datos"][] = $rs->fields;
			$rs->MoveNext();
		}
		$rs->Close();
		return $result;
	}
	function insertar($posicion){
		$conn = $this->conn;
		$id_posicion = $posicion->getId_posicion();
		$posicion = $posicion->getPosicion();
		$sql = "
			INSERT INTO posicion (
				id_posicion,
				posicion
			)
			VALUES (
				'".$id_posicion."',
				'".$posicion."'
			)
		";
		$rs   = $conn->Execute($sql);
		if(!$rs){
			return $conn->ErrorMsg();
		}
		$rs->Close();
		return true;
	}
	function actualizar($posicion){
		$conn = $this->conn;
		$id_posicion = $posicion->getId_posicion();
		$posicion = $posicion->getPosicion();
		$sql = "
			UPDATE posicion SET
				id_posicion = '".$id_posicion."',
				posicion = '".$posicion."'
			WHERE id_posicion = '".$id_posicion."'
		";
		$rs   = $conn->Execute($sql);
		if(!$rs){
			return $conn->ErrorMsg();
		}
		$rs->Close();
		return true;
	}
	function borrar($posicion){
		$conn = $this->conn;
		$id_posicion = $posicion->getId_posicion();
		$sql  = "DELETE FROM posicion WHERE id_posicion = '".$id_posicion."'";
		$rs   = $conn->Execute($sql);
		if(!$rs){
			return $conn->ErrorMsg();
		}
		$rs->Close();
		return true;
	}
}
?>
