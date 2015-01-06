<?php

require_once ('BaseAdo.php');

class Contingente_detAdo extends BaseAdo {

	protected function setTable()
	{
		$this->table = 'contingente_det';
	}

	protected function setPrimaryKey()
	{
		$this->primaryKey = 'contingente_det_contingente_acuerdo_det_acuerdo_id';
	}

	protected function setData()
	{
		$contingente_det = $this->getModel();

		$contingente_det_id = $contingente_det->getContingente_det_id();
		$contingente_det_anio_ini = $contingente_det->getContingente_det_anio_ini();
		$contingente_det_anio_fin = $contingente_det->getContingente_det_anio_fin();
		$contingente_det_peso_neto = $contingente_det->getContingente_det_peso_neto();
		$contingente_det_contingente_id = $contingente_det->getContingente_det_contingente_id();
		$contingente_det_contingente_acuerdo_det_id = $contingente_det->getContingente_det_contingente_acuerdo_det_id();
		$contingente_det_contingente_acuerdo_det_acuerdo_id = $contingente_det->getContingente_det_contingente_acuerdo_det_acuerdo_id();

		$this->data = compact(
			'contingente_det_id',
			'contingente_det_anio_ini',
			'contingente_det_anio_fin',
			'contingente_det_peso_neto',
			'contingente_det_contingente_id',
			'contingente_det_contingente_acuerdo_det_id',
			'contingente_det_contingente_acuerdo_det_acuerdo_id'
		);
	}

	public function create($contingente_det)
	{
		$conn = $this->getConnection();
		$this->setModel($contingente_det);
		$this->setData();

		$sql = '
			INSERT INTO contingente_det (
				contingente_det_id,
				contingente_det_anio_ini,
				contingente_det_anio_fin,
				contingente_det_peso_neto,
				contingente_det_contingente_id,
				contingente_det_contingente_acuerdo_det_id,
				contingente_det_contingente_acuerdo_det_acuerdo_id
			)
			VALUES (
				"'.$this->data['contingente_det_id'].'",
				"'.$this->data['contingente_det_anio_ini'].'",
				"'.$this->data['contingente_det_anio_fin'].'",
				"'.$this->data['contingente_det_peso_neto'].'",
				"'.$this->data['contingente_det_contingente_id'].'",
				"'.$this->data['contingente_det_contingente_acuerdo_det_id'].'",
				"'.$this->data['contingente_det_contingente_acuerdo_det_acuerdo_id'].'"
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
			 contingente_det_id,
			 contingente_det_anio_ini,
			 contingente_det_anio_fin,
			 contingente_det_peso_neto,
			 contingente_det_contingente_id,
			 contingente_det_contingente_acuerdo_det_id,
			 contingente_det_contingente_acuerdo_det_acuerdo_id
			FROM contingente_det
		';
		if(!empty($filter)){
			$sql .= ' WHERE ('. implode( $joinOperator, $filter ).')';
		}

		return $sql;
	}

}
