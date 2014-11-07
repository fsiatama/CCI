<?php

require 'BaseAdo.php';

class UserAdo extends BaseAdo {

	private $data;

	public function setData($user)
	{
		$user_id        = $user->getUser_id();
		$user_full_name = $user->getUser_full_name();
		$user_email     = $user->getUser_email();
		$user_password  = $user->getUser_password();
		$user_uinsert   = $user->getUser_uinsert();
		$user_finsert   = $user->getUser_finsert();
		$user_fupdate   = $user->getUser_fupdate();

		$this->data = compact(
			'user_id',
			'user_full_name',
			'user_email',
			'user_password',
			'user_uinsert',
			'user_finsert',
			'user_fupdate'
		);
	}

	public function search($user)
	{
		$conn = $this->connection;
		$this->setData($user);

		$filter = array();
		foreach($this->data as $key => $data){
			if ($data <> ''){
				$filter[] = $key . " = '" . $data ."'";
			}
		}

		$sql  = 'SELECT * FROM user';
		if(!empty($filter)){
			$sql .= ' WHERE '. implode(' AND ', $filter);
		}

		$rs = $conn->Execute($sql);
		$result = array();
		if(!$rs){
			return $conn->ErrorMsg();
		}
		$total = $rs->RecordCount();
		while(!$rs->EOF){
			$result["data"][] = $rs->fields;
			$rs->MoveNext();
		}
		$result["total"] = $total;
		$rs->Close();
		return $result;
	}
	public function lista_filtro($query, $queryValuesIndicator, $limit)
	{
		$conn = $this->connection;
		$filtro = array();
		if($queryValuesIndicator && is_array($query)){
			$filtro[] = "user_id IN('".implode("','",$query)."')";
		}
		else{
			if(is_array($query)){
				$tmp_query = array_pop($query);
				$filtro[] = "user_id IN('".implode("','",$query)."')";
				$query = $tmp_query;
			}
			else{
				$filtro[] = "(
					   user_id LIKE '%" . $query ."%'
					OR user_full_name LIKE '%" . $query ."%'
					OR user_email LIKE '%" . $query ."%'
					OR user_password LIKE '%" . $query ."%'
					OR user_uinsert LIKE '%" . $query ."%'
					OR user_finsert LIKE '%" . $query ."%'
					OR user_fupdate LIKE '%" . $query ."%'
				)";
			}
		}
		$sql  = 'SELECT user_id,user_full_name,user_email,user_password,user_uinsert,user_finsert,user_fupdate FROM user';
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
	public function create($user)
	{
		$conn = $this->connection;
		
		$this->setData($user);

		$sql = "
			INSERT INTO user (
				user_id,
				user_full_name,
				user_email,
				user_password,
				user_uinsert,
				user_finsert,
				user_fupdate
			)
			VALUES (
				'".$this->data['user_id']."',
				'".$this->data['user_full_name']."',
				'".$this->data['user_email']."',
				'".$this->data['user_password']."',
				'".$this->data['user_uinsert']."',
				'".$this->data['user_finsert']."',
				'".$this->data['user_fupdate']."'
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
	public function update($user)
	{
		$conn = $this->connection;
		$sqlUpd = array();
		foreach($user as $key => $value){
			if($value != ""){
				$sqlUpd[] = $key. ' = "' . $value . '"';
			}
		}
		$user_id = $user->getUser_id();
		$sql = "
			UPDATE user SET
				".implode(", ",$sqlUpd)."
			WHERE user_id = '".$user_id."'
		";
		$rs   = $conn->Execute($sql);
		if(!$rs){
			return $conn->ErrorMsg();
		}
		$rs->Close();
		return true;
	}
	public function delete($user)
	{
		$conn = $this->connection;
		$user_id = $user->getUser_id();
		$sql  = "DELETE FROM user WHERE user_id = '".$user_id."'";
		$rs   = $conn->Execute($sql);
		if(!$rs){
			return $conn->ErrorMsg();
		}
		$rs->Close();
		return true;
	}
}
