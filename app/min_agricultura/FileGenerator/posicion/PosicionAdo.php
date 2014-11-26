<?php

require_once ('BaseAdo.php');

class PosicionAdo extends BaseAdo {

	protected function setTable()
	{
		$this->table = 'posicion';
	}

	protected function setPrimaryKey()
	{
		$this->primaryKey = 'id_posicion';
	}

	protected function setData()
	{
		$posicion = $this->getModel();

		$id_posicion = $posicion->getId_posicion();
		$posicion = $posicion->getPosicion();

		$this->data = compact(
			'id_posicion',
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
				id_posicion,
				posicion
			)
			VALUES (
				"'.$this->data['id_posicion'].'",
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
			 id_posicion,
			 posicion
			FROM posicion
		';
		if(!empty($filter)){
			$sql .= ' WHERE ('. implode( $joinOperator, $filter ).')';
		}

		return $sql;
	}

}
