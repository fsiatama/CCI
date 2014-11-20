<?php

require PATH_APP.'lib/connection/Connection.php';

abstract class BaseAdo extends Connection {

	protected $operator;
	protected $model;
	protected $data;
	protected $table;
	protected $primaryKey;
	protected $columns = null;
	
	public function __construct()
	{
		parent::__construct('min_agricultura');
		$this->setTable();
		$this->setPrimaryKey();
	}

	abstract protected function setData();
	abstract protected function buildSelect();

	public function setColumns($columns){
		$this->columns = $columns;
	}

	public function getColumns(){
		return $this->columns;
	}

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

		$savec = ( empty($ADODB_COUNTRECS) ) ? false : $ADODB_COUNTRECS;
		if ($conn->pageExecuteCountRows) {
			$ADODB_COUNTRECS = true;
		}
		$resultSet = $conn->PageExecute($sql, $numRows, $page);
		$ADODB_COUNTRECS = $savec;

		$result = $this->buildResult($resultSet, false, true);

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

	protected function buildResult(&$resultSet, $insertId = false, $paginate = false)
	{
		$conn = $this->getConnection();
		$result = array();
		
		if(!$resultSet){
			$result['success'] = false;
			$result['error']  = $conn->ErrorMsg();
		}
		else{
			$result['success'] = true;
			$result['total']   = (!$paginate) ? $resultSet->RecordCount() : $resultSet->_maxRecordCount;
			$result['data']    = [];
			if ($insertId !== false) {
				$result['insertId'] = $insertId;
			}
			while(!$resultSet->EOF){
				$result['data'][] = $this->filterRow($resultSet->fields);
				$resultSet->MoveNext();
			}
			$resultSet->Close();
		}

		return $result;
	}

    /**
     * filterRow
     * 
     * @param array $row contiene un array con los valores de un registro de la entidad que hereda
     *
     *
     * @access protected
     *
     * @return array $row solo con las columnas especificadas en $this->columns.
     */
	protected function filterRow($row)
	{
		$columns = $this->getColumns();
		$model   = $this->getModel();

		if (!is_null($columns) && is_array($columns)) {
			$newRow = [];
			foreach ($columns as $column) {

				$methodName = 'get' . Inflector::underCamel($column) . 'Attribute';
				if (array_key_exists($column, $row)) {
					$newRow[$column] = $row[$column];
				}
				elseif (method_exists($model, $methodName)) {
					$segments   = explode('_', $column);
					$attribute  = array_pop($segments);
					$columnName = implode('_', $segments);

					if (!empty($row[$columnName])) {
						$response        = call_user_func_array([$model, $methodName], [$row[$columnName]]);
						$newRow[$column] = $response;
					}
				}
			}
			$row = $newRow;
		}

		return $row;
	}
}