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
		$pais_iata = $pais->getPais_iata();

		$this->data = compact(
			'id_pais',
			'pais',
			'pais_iata'
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
				pais,
				pais_iata
			)
			VALUES (
				"'.$this->data['id_pais'].'",
				"'.$this->data['pais'].'",
				"'.$this->data['pais_iata'].'"
			)
		';
		$resultSet = $conn->Execute($sql);
		$result = $this->buildResult($resultSet, $conn->Insert_ID());

		return $result;
	}

	public function buildSelect()
	{

		$sql = 'SELECT
			 id_pais,
			 pais,
			 pais_iata
			FROM pais
		';

		$sql .= $this->buildSelectWhere();

		return $sql;
	}


	public function buildSelectWhere()
	{
		$filter        = [];
		$primaryFilter = [];
		$operator      = $this->getOperator();
		$joinOperator  = ' AND ';

		foreach($this->data as $key => $data){
			if ($data <> ''){
				if ($key == 'id_pais') {
					$primaryFilter[] = $key . ' = "' . $data . '"';
				} else {
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
		}

		$sql             = '';

		if(!empty($primaryFilter)){
			$sql            .= ($this->getWhereAssignment()) ? ' AND ' : ' WHERE ' ;
			$sql            .= ' ('. implode( ' AND ', $primaryFilter ).')';
			$this->setWhereAssignment( true );
		}
		if(!empty($filter)){
			$sql .= ($this->getWhereAssignment()) ? ' AND ' : ' WHERE ' ;
			$sql .= '  ('. implode( $joinOperator, $filter ).')';
		}

		return $sql;
	}

}
