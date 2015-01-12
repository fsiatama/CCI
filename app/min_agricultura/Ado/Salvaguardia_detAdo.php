<?php

require_once ('BaseAdo.php');

class Salvaguardia_detAdo extends BaseAdo {

	protected function setTable()
	{
		$this->table = 'salvaguardia_det';
	}

	protected function setPrimaryKey()
	{
		$this->primaryKey = 'salvaguardia_det_id';
	}

	protected function setData()
	{
		$salvaguardia_det = $this->getModel();

		$salvaguardia_det_id = $salvaguardia_det->getSalvaguardia_det_id();
		$salvaguardia_det_anio_ini = $salvaguardia_det->getSalvaguardia_det_anio_ini();
		$salvaguardia_det_anio_fin = $salvaguardia_det->getSalvaguardia_det_anio_fin();
		$salvaguardia_det_peso_neto = $salvaguardia_det->getSalvaguardia_det_peso_neto();
		$salvaguardia_det_salvaguardia_id = $salvaguardia_det->getSalvaguardia_det_salvaguardia_id();
		$salvaguardia_det_salvaguardia_contingente_id = $salvaguardia_det->getSalvaguardia_det_salvaguardia_contingente_id();
		$salvaguardia_det_salvaguardia_contingente_acuerdo_det_id = $salvaguardia_det->getSalvaguardia_det_salvaguardia_contingente_acuerdo_det_id();
		$salvaguardia_det_salvaguardia_contingente_acuerdo_det_acuerdo_id = $salvaguardia_det->getSalvaguardia_det_salvaguardia_contingente_acuerdo_det_acuerdo_id();

		$this->data = compact(
			'salvaguardia_det_id',
			'salvaguardia_det_anio_ini',
			'salvaguardia_det_anio_fin',
			'salvaguardia_det_peso_neto',
			'salvaguardia_det_salvaguardia_id',
			'salvaguardia_det_salvaguardia_contingente_id',
			'salvaguardia_det_salvaguardia_contingente_acuerdo_det_id',
			'salvaguardia_det_salvaguardia_contingente_acuerdo_det_acuerdo_id'
		);
	}

	public function create($salvaguardia_det)
	{
		$conn = $this->getConnection();
		$this->setModel($salvaguardia_det);
		$this->setData();

		$sql = '
			INSERT INTO salvaguardia_det (
				salvaguardia_det_id,
				salvaguardia_det_anio_ini,
				salvaguardia_det_anio_fin,
				salvaguardia_det_peso_neto,
				salvaguardia_det_salvaguardia_id,
				salvaguardia_det_salvaguardia_contingente_id,
				salvaguardia_det_salvaguardia_contingente_acuerdo_det_id,
				salvaguardia_det_salvaguardia_contingente_acuerdo_det_acuerdo_id
			)
			VALUES (
				"'.$this->data['salvaguardia_det_id'].'",
				"'.$this->data['salvaguardia_det_anio_ini'].'",
				"'.$this->data['salvaguardia_det_anio_fin'].'",
				"'.$this->data['salvaguardia_det_peso_neto'].'",
				"'.$this->data['salvaguardia_det_salvaguardia_id'].'",
				"'.$this->data['salvaguardia_det_salvaguardia_contingente_id'].'",
				"'.$this->data['salvaguardia_det_salvaguardia_contingente_acuerdo_det_id'].'",
				"'.$this->data['salvaguardia_det_salvaguardia_contingente_acuerdo_det_acuerdo_id'].'"
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
			 salvaguardia_det_id,
			 salvaguardia_det_anio_ini,
			 salvaguardia_det_anio_fin,
			 salvaguardia_det_peso_neto,
			 salvaguardia_det_salvaguardia_id,
			 salvaguardia_det_salvaguardia_contingente_id,
			 salvaguardia_det_salvaguardia_contingente_acuerdo_det_id,
			 salvaguardia_det_salvaguardia_contingente_acuerdo_det_acuerdo_id
			FROM salvaguardia_det
		';
		if(!empty($filter)){
			$sql .= ' WHERE ('. implode( $joinOperator, $filter ).')';
		}

		return $sql;
	}

}
