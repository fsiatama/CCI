<?php
include("indicator.php");
class IndicatorAdo extends BaseAdo {

	private $data;

	public function getData ($indicator)
	{
		$indicator_id = $indicator->getIndicator_id();
		$indicator_name = $indicator->getIndicator_name();
		$this->data = compact(
				'indicator_id',
				'indicator_name'
		);
	}

	public function lista($indicator)
	{
		$conn = $this->conn;
		$filtro = array();
		foreach($indicator as $key => $data){
			if ($data <> ''){
				$filtro[] = $key . " = '" . $data ."'";
			}
		}
		$sql  = 'SELECT * FROM indicator';
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
	public function lista_filtro($query, $queryValuesIndicator, $limit)
	{
		$conn = $this->conn;
		$filtro = array();
		if($queryValuesIndicator && is_array($query)){
			$filtro[] = "indicator_id IN('".implode("','",$query)."')";
		}
		else{
			if(is_array($query)){
				$tmp_query = array_pop($query);
				$filtro[] = "indicator_id IN('".implode("','",$query)."')";
				$query = $tmp_query;
			}
			else{
				$filtro[] = "(
					   indicator_id LIKE '%" . $query ."%'
					OR indicator_name LIKE '%" . $query ."%'
				)";
			}
		}
		$sql  = 'SELECT indicator_id,indicator_name FROM indicator';
		if(!empty($filtro)){
			$sql .= ' WHERE '. implode(' AND ', $filtro);
		}
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
	public function insertar($indicator)
	{
		$conn = $this->conn;
		$sql = "
			INSERT INTO indicator (
				indicator_id,
				indicator_name
			)
			VALUES (
				'".$indicator_id."',
				'".$indicator_name."'
			)
		";
		$rs   = $conn->Execute($sql);
		$return = array();
		if(!$rs){
			$return["success"] = false;
			$return["error"]  = $conn->ErrorMsg();
			return $return;
		}
		$return["success"] = true;
		$return["insert_id"] = $conn->Insert_ID();
		$rs->Close();
		return $return;
	}
	public function actualizar($indicator)
	{
		$conn = $this->conn;
		$sqlUpd = array();
		foreach($indicator as $key => $value){
			if($value != ""){
				$sqlUpd[] = $key. ' = "' . $value . '"';
			}
		}
		$indicator_id = $indicator->getIndicator_id();
		$sql = "
			UPDATE indicator SET
				".implode(", ",$sqlUpd)."
			WHERE indicator_id = '".$indicator_id."'
		";
		$rs   = $conn->Execute($sql);
		if(!$rs){
			return $conn->ErrorMsg();
		}
		$rs->Close();
		return true;
	}
	public function borrar($indicator)
	{
		$conn = $this->conn;
		$indicator_id = $indicator->getIndicator_id();
		$sql  = "DELETE FROM indicator WHERE indicator_id = '".$indicator_id."'";
		$rs   = $conn->Execute($sql);
		if(!$rs){
			return $conn->ErrorMsg();
		}
		$rs->Close();
		return true;
	}
}
?>
