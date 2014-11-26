<?php

require_once ('BaseAdo.php');

class PaisAdo extends BaseAdo {

	protected function setTable()
	{
		$this->table = 'pais';
	}

	protected function setPrimaryKey()
	{
		$this->primaryKey = 'id_pais';
	}

	protected function setData()
	{
		$pais = $this->getModel();

		$id_pais = $pais->getId_pais();
		$pais = $pais->getPais();

		$this->data = compact(
			'id_pais',
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
				id_pais,
				pais
			)
			VALUES (
				"'.$this->data['id_pais'].'",
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
			 id_pais,
			 pais
			FROM pais
		';
		if(!empty($filter)){
			$sql .= ' WHERE ('. implode( $joinOperator, $filter ).')';
		}

		return $sql;
	}

}
