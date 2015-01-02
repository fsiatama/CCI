<?php

require_once ('BaseAdo.php');

class SubpartidaAdo extends BaseAdo {

	protected function setTable()
	{
		$this->table = 'subpartida';
	}

	protected function setPrimaryKey()
	{
		$this->primaryKey = 'id_subpartida';
	}

	protected function setData()
	{
		$subpartida = $this->getModel();

		$id_subpartida = $subpartida->getId_subpartida();
		$subpartida = $subpartida->getSubpartida();
		$id_capitulo = $subpartida->getId_capitulo();
		$id_partida = $subpartida->getId_partida();

		$this->data = compact(
			'id_subpartida',
			'subpartida',
			'id_capitulo',
			'id_partida'
		);
	}

	public function create($subpartida)
	{
		$conn = $this->getConnection();
		$this->setModel($subpartida);
		$this->setData();

		$sql = '
			INSERT INTO subpartida (
				id_subpartida,
				subpartida,
				id_capitulo,
				id_partida
			)
			VALUES (
				"'.$this->data['id_subpartida'].'",
				"'.$this->data['subpartida'].'",
				"'.$this->data['id_capitulo'].'",
				"'.$this->data['id_partida'].'"
			)
		';
		$resultSet = $conn->Execute($sql);
		$result = $this->buildResult($resultSet, $conn->Insert_ID());

		return $result;
	}

	public function buildSelect()
	{
		$filter = [];
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
			 id_subpartida,
			 subpartida,
			 id_capitulo,
			 id_partida
			FROM subpartida
		';
		if(!empty($filter)){
			$sql .= ' WHERE ('. implode( $joinOperator, $filter ).')';
		}

		return $sql;
	}

}
