<?php

require_once ('BaseAdo.php');

class CorrelativaAdo extends BaseAdo {

	protected function setTable()
	{
		$this->table = 'correlativa';
	}

	protected function setPrimaryKey()
	{
		$this->primaryKey = 'correlativa_id';
	}

	protected function setData()
	{
		$correlativa = $this->getModel();

		$correlativa_id = $correlativa->getCorrelativa_id();
		$correlativa_fvigente = $correlativa->getCorrelativa_fvigente();
		$correlativa_decreto = $correlativa->getCorrelativa_decreto();
		$correlativa_observacion = $correlativa->getCorrelativa_observacion();
		$correlativa_origen = $correlativa->getCorrelativa_origen();
		$correlativa_destino = $correlativa->getCorrelativa_destino();
		$correlativa_uinsert = $correlativa->getCorrelativa_uinsert();
		$correlativa_finsert = $correlativa->getCorrelativa_finsert();
		$correlativa_uupdate = $correlativa->getCorrelativa_uupdate();
		$correlativa_fupdate = $correlativa->getCorrelativa_fupdate();

		$this->data = compact(
			'correlativa_id',
			'correlativa_fvigente',
			'correlativa_decreto',
			'correlativa_observacion',
			'correlativa_origen',
			'correlativa_destino',
			'correlativa_uinsert',
			'correlativa_finsert',
			'correlativa_uupdate',
			'correlativa_fupdate'
		);
	}

	public function create($correlativa)
	{
		$conn = $this->getConnection();
		$this->setModel($correlativa);
		$this->setData();

		$sql = '
			INSERT INTO correlativa (
				correlativa_id,
				correlativa_fvigente,
				correlativa_decreto,
				correlativa_observacion,
				correlativa_origen,
				correlativa_destino,
				correlativa_uinsert,
				correlativa_finsert,
				correlativa_uupdate,
				correlativa_fupdate
			)
			VALUES (
				"'.$this->data['correlativa_id'].'",
				"'.$this->data['correlativa_fvigente'].'",
				"'.$this->data['correlativa_decreto'].'",
				"'.$this->data['correlativa_observacion'].'",
				"'.$this->data['correlativa_origen'].'",
				"'.$this->data['correlativa_destino'].'",
				"'.$this->data['correlativa_uinsert'].'",
				"'.$this->data['correlativa_finsert'].'",
				"'.$this->data['correlativa_uupdate'].'",
				"'.$this->data['correlativa_fupdate'].'"
			)
		';
		$resultSet = $conn->Execute($sql);
		$result = $this->buildResult($resultSet, $conn->Insert_ID());

		return $result;
	}

	public function buildSelect()
	{
		$filter = array();
		$operator = $this->getOperator();
		$joinOperator = ' AND ';
		foreach($this->data as $key => $data){
			if ($data <> ''){
				if ($operator == '=') {
					$filter[] = $key . ' ' . $operator . ' "' . $data . '"';
				}
				elseif ($operator == 'IN') {
					$filter[] = $key . ' ' . $operator . '("' . $data . '")';
				}
				else {
					$filter[] = $key . ' ' . $operator . ' "%' . $data . '%"';
					$joinOperator = ' OR ';
				}
			}
		}

		$sql = 'SELECT
			 correlativa_id,
			 correlativa_fvigente,
			 correlativa_decreto,
			 correlativa_observacion,
			 correlativa_origen,
			 correlativa_destino,
			 correlativa_uinsert,
			 correlativa_finsert,
			 correlativa_uupdate,
			 correlativa_fupdate
			FROM correlativa
		';
		if(!empty($filter)){
			$sql .= ' WHERE ('. implode( $joinOperator, $filter ).')';
		}

		return $sql;
	}

}
