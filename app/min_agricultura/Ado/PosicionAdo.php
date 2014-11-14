<?php

require_once ('BaseAdo.php');

class PosicionAdo extends BaseAdo {

	protected function setTable()
	{
		$this->table = 'posicion';
	}

	protected function setPrimaryKey()
	{
		$this->primaryKey = 'posicion_id';
	}

	protected function setData()
	{
		$posicion = $this->getModel();

		$posicion_id = $posicion->getPosicion_id();
		$posicion = $posicion->getPosicion();

		$this->data = compact(
			'posicion_id',
			'posicion'
		);
	}

	public function create($posicion)
	{
		$conn = $this->getConnection();
		$this->setModel($posicion);
		$this->setData();

		$sql = '
			INSERT INTO posicion (
				posicion_id,
				posicion
			)
			VALUES (
				"'.$this->data['posicion_id'].'",
				"'.$this->data['posicion'].'"
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
			 posicion_id,
			 posicion
			FROM posicion
		';
		if(!empty($filter)){
			$sql .= ' WHERE ('. implode( $joinOperator, $filter ).')';
		}

		return $sql;
	}

}
