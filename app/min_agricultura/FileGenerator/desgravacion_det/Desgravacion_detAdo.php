<?php

require_once ('BaseAdo.php');

class Desgravacion_detAdo extends BaseAdo {

	protected function setTable()
	{
		$this->table = 'desgravacion_det';
	}

	protected function setPrimaryKey()
	{
		$this->primaryKey = 'desgravacion_det_desgravacion_acuerdo_det_acuerdo_id';
	}

	protected function setData()
	{
		$desgravacion_det = $this->getModel();

		$desgravacion_det_id = $desgravacion_det->getDesgravacion_det_id();
		$desgravacion_det_anio_ini = $desgravacion_det->getDesgravacion_det_anio_ini();
		$desgravacion_det_anio_fin = $desgravacion_det->getDesgravacion_det_anio_fin();
		$desgravacion_det_tasa = $desgravacion_det->getDesgravacion_det_tasa();
		$desgravacion_det_desgravacion_id = $desgravacion_det->getDesgravacion_det_desgravacion_id();
		$desgravacion_det_desgravacion_acuerdo_det_id = $desgravacion_det->getDesgravacion_det_desgravacion_acuerdo_det_id();
		$desgravacion_det_desgravacion_acuerdo_det_acuerdo_id = $desgravacion_det->getDesgravacion_det_desgravacion_acuerdo_det_acuerdo_id();

		$this->data = compact(
			'desgravacion_det_id',
			'desgravacion_det_anio_ini',
			'desgravacion_det_anio_fin',
			'desgravacion_det_tasa',
			'desgravacion_det_desgravacion_id',
			'desgravacion_det_desgravacion_acuerdo_det_id',
			'desgravacion_det_desgravacion_acuerdo_det_acuerdo_id'
		);
	}

	public function create($desgravacion_det)
	{
		$conn = $this->getConnection();
		$this->setModel($desgravacion_det);
		$this->setData();

		$sql = '
			INSERT INTO desgravacion_det (
				desgravacion_det_id,
				desgravacion_det_anio_ini,
				desgravacion_det_anio_fin,
				desgravacion_det_tasa,
				desgravacion_det_desgravacion_id,
				desgravacion_det_desgravacion_acuerdo_det_id,
				desgravacion_det_desgravacion_acuerdo_det_acuerdo_id
			)
			VALUES (
				"'.$this->data['desgravacion_det_id'].'",
				"'.$this->data['desgravacion_det_anio_ini'].'",
				"'.$this->data['desgravacion_det_anio_fin'].'",
				"'.$this->data['desgravacion_det_tasa'].'",
				"'.$this->data['desgravacion_det_desgravacion_id'].'",
				"'.$this->data['desgravacion_det_desgravacion_acuerdo_det_id'].'",
				"'.$this->data['desgravacion_det_desgravacion_acuerdo_det_acuerdo_id'].'"
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
			 desgravacion_det_id,
			 desgravacion_det_anio_ini,
			 desgravacion_det_anio_fin,
			 desgravacion_det_tasa,
			 desgravacion_det_desgravacion_id,
			 desgravacion_det_desgravacion_acuerdo_det_id,
			 desgravacion_det_desgravacion_acuerdo_det_acuerdo_id
			FROM desgravacion_det
		';
		if(!empty($filter)){
			$sql .= ' WHERE ('. implode( $joinOperator, $filter ).')';
		}

		return $sql;
	}

}
