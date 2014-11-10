<?php

require PATH_APP.'lib/connection/Connection.php';

abstract class BaseAdo extends Connection {

	protected $operator;
	protected $model;
	protected $data;
	protected $table;
	protected $primaryKey;
	
	public function __construct()
	{
		parent::__construct('min_agricultura');
		$this->setTable();
		$this->setPrimaryKey();
	}

	abstract protected function setData();
	abstract protected function buildSelect();
	
	protected function getOperator()
	{
		return $this->operator;
	}

	protected function setOperator($operator)
	{
		$this->operator = $operator;
	}

	protected function getModel()
	{
		return $this->model;
	}

	protected function setModel($model)
	{
		$this->model = $model;
	}

	protected function getTable()
	{
		return $this->table;
	}

	abstract protected function setTable();

	protected function getPrimaryKey()
	{
		return $this->primaryKey;
	}

	abstract protected function setPrimaryKey();

	public function paginate($model, $operator, $numRows, $page)
	{
		$this->setModel($model);
		$this->setOperator($operator);

		$conn = $this->getConnection();
		$this->setData();

		$sql = $this->buildSelect();

		$savec = $ADODB_COUNTRECS;
		if ($conn->pageExecuteCountRows) {
			$ADODB_COUNTRECS = true;
		}
		$resultSet = $conn->PageExecute($sql, $numRows, $page);
		$ADODB_COUNTRECS = $savec;

		$result = $this->buildResult($resultSet);

		return $result;
	}

	protected function search()
	{
		$conn = $this->getConnection();
		$this->setData();

		$sql = $this->buildSelect();
		$resultSet = $conn->Execute($sql);
		$result = $this->buildResult($resultSet);

		return $result;
	}

	public function exactSearch($model)
	{
		$this->setModel($model);
		$this->setOperator('=');
		return $this->search();
	}

	public function likeSearch($model)
	{
		$this->setModel($model);
		$this->setOperator('LIKE');
		return $this->search();
	}

	public function inSearch($model)
	{
		$this->setModel($model);
		$this->setOperator('IN');
		return $this->search();
	}

	public function update($model)
	{
		$conn = $this->getConnection();
		$primaryKey = $this->getPrimaryKey();
		$table = $this->getTable();

		$this->setModel($model);
		$this->setData();

		$filter = array();
		foreach($this->data as $key => $value){
			if($value != '' && $key <> $primaryKey){
				$filter[] = $key. ' = "' . $value . '"';
			}
		}
		$id = $this->data[$primaryKey];
		$sql = '
			UPDATE '.$table.' SET
				'.implode(', ',$filter).'
			WHERE '.$primaryKey.' = "'.$id.'"
		';
		
		$resultSet = $conn->Execute($sql);
		$result = $this->buildResult($resultSet);

		return $result;
	}

	public function delete($model)
	{
		$conn = $this->getConnection();
		$primaryKey = $this->getPrimaryKey();
		$table = $this->getTable();

		$id = $this->data[$primaryKey];
		$sql = '
			DELETE FROM '.$table.' WHERE '.$primaryKey.' = "'.$id.'"
		';
		
		$resultSet = $conn->Execute($sql);
		$result = $this->buildResult($resultSet);

		return $result;
	}

	protected function buildResult(&$resultSet, $insertId = false)
	{
		$conn = $this->getConnection();
		$result = array();
		
		if(!$resultSet){
			$result["success"] = false;
			$result["error"]  = $conn->ErrorMsg();
		}
		else{
			$result["success"] = true;
			$result["total"]  = $resultSet->RecordCount();
			if ($insertId !== false) {
				$result["insertId"] = $insertId;
			}
			while(!$resultSet->EOF){
				$result["data"][] = $resultSet->fields;
				$resultSet->MoveNext();
			}
			$resultSet->Close();
		}

		return $result;
	}
}