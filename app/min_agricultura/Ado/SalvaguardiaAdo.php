<?php

require_once ('BaseAdo.php');

class SalvaguardiaAdo extends BaseAdo {

	protected function setTable()
	{
		$this->table = 'salvaguardia';
	}

	protected function setPrimaryKey()
	{
		$this->primaryKey = 'salvaguardia_contingente_acuerdo_det_acuerdo_id';
	}

	protected function setData()
	{
		$salvaguardia = $this->getModel();

		$salvaguardia_id = $salvaguardia->getSalvaguardia_id();
		$salvaguardia_msalvaguardia = $salvaguardia->getSalvaguardia_msalvaguardia();
		$salvaguardia_contingente_id = $salvaguardia->getSalvaguardia_contingente_id();
		$salvaguardia_contingente_acuerdo_det_id = $salvaguardia->getSalvaguardia_contingente_acuerdo_det_id();
		$salvaguardia_contingente_acuerdo_det_acuerdo_id = $salvaguardia->getSalvaguardia_contingente_acuerdo_det_acuerdo_id();

		$this->data = compact(
			'salvaguardia_id',
			'salvaguardia_msalvaguardia',
			'salvaguardia_contingente_id',
			'salvaguardia_contingente_acuerdo_det_id',
			'salvaguardia_contingente_acuerdo_det_acuerdo_id'
		);
	}

	public function create($salvaguardia)
	{
		$conn = $this->getConnection();
		$this->setModel($salvaguardia);
		$this->setData();

		$sql = '
			INSERT INTO salvaguardia (
				salvaguardia_id,
				salvaguardia_msalvaguardia,
				salvaguardia_contingente_id,
				salvaguardia_contingente_acuerdo_det_id,
				salvaguardia_contingente_acuerdo_det_acuerdo_id
			)
			VALUES (
				"'.$this->data['salvaguardia_id'].'",
				"'.$this->data['salvaguardia_msalvaguardia'].'",
				"'.$this->data['salvaguardia_contingente_id'].'",
				"'.$this->data['salvaguardia_contingente_acuerdo_det_id'].'",
				"'.$this->data['salvaguardia_contingente_acuerdo_det_acuerdo_id'].'"
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
			 salvaguardia_id,
			 salvaguardia_msalvaguardia,
			 salvaguardia_contingente_id,
			 salvaguardia_contingente_acuerdo_det_id,
			 salvaguardia_contingente_acuerdo_det_acuerdo_id
			FROM salvaguardia
		';
		if(!empty($filter)){
			$sql .= ' WHERE ('. implode( $joinOperator, $filter ).')';
		}

		return $sql;
	}

}
