<?php

require_once ('BaseAdo.php');

class PaisAdo extends BaseAdo {

	protected function setTable()
	{
		$this->table = 'pais';
	}

	protected function setPrimaryKey()
	{
		$this->primaryKey = 'pais_id';
	}

	protected function setData()
	{
		$pais = $this->getModel();

		$pais_id = $pais->getPais_id();
		$pais = $pais->getPais();

		$this->data = compact(
			'pais_id',
			'pais'
		);
	}

	public function create($pais)
	{
		$conn = $this->getConnection();
		$this->setModel($pais);
		$this->setData();

		$sql = '
			INSERT INTO pais (
				pais_id,
				pais
			)
			VALUES (
				"'.$this->data['pais_id'].'",
				"'.$this->data['pais'].'"
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
			 pais_id,
			 pais
			FROM pais
		';
		if(!empty($filter)){
			$sql .= ' WHERE ('. implode( $joinOperator, $filter ).')';
		}

		return $sql;
	}

}
