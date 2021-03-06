<?php

require_once ('BaseAdo.php');

class Desgravacion_detAdo extends BaseAdo {

	protected function setTable()
	{
		$this->table = 'desgravacion_det';
	}

	protected function setPrimaryKey()
	{
		$this->primaryKey = 'desgravacion_det_id';
	}

	protected function setData()
	{
		$desgravacion_det = $this->getModel();

		$desgravacion_det_id = $desgravacion_det->getDesgravacion_det_id();
		$desgravacion_det_anio_ini = $desgravacion_det->getDesgravacion_det_anio_ini();
		$desgravacion_det_anio_fin = $desgravacion_det->getDesgravacion_det_anio_fin();
		$desgravacion_det_tasa_intra = $desgravacion_det->getDesgravacion_det_tasa_intra();
		$desgravacion_det_tasa_extra = $desgravacion_det->getDesgravacion_det_tasa_extra();
		$desgravacion_det_tipo_operacion = $desgravacion_det->getDesgravacion_det_tipo_operacion();
		$desgravacion_det_desgravacion_id = $desgravacion_det->getDesgravacion_det_desgravacion_id();
		$desgravacion_det_desgravacion_acuerdo_det_id = $desgravacion_det->getDesgravacion_det_desgravacion_acuerdo_det_id();
		$desgravacion_det_desgravacion_acuerdo_det_acuerdo_id = $desgravacion_det->getDesgravacion_det_desgravacion_acuerdo_det_acuerdo_id();

		$this->data = compact(
			'desgravacion_det_id',
			'desgravacion_det_anio_ini',
			'desgravacion_det_anio_fin',
			'desgravacion_det_tasa_intra',
			'desgravacion_det_tasa_extra',
			'desgravacion_det_tipo_operacion',
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
				desgravacion_det_tasa_intra,
				desgravacion_det_tasa_extra,
				desgravacion_det_tipo_operacion,
				desgravacion_det_desgravacion_id,
				desgravacion_det_desgravacion_acuerdo_det_id,
				desgravacion_det_desgravacion_acuerdo_det_acuerdo_id
			)
			VALUES (
				"'.$this->data['desgravacion_det_id'].'",
				"'.$this->data['desgravacion_det_anio_ini'].'",
				"'.$this->data['desgravacion_det_anio_fin'].'",
				"'.$this->data['desgravacion_det_tasa_intra'].'",
				"'.$this->data['desgravacion_det_tasa_extra'].'",
				"'.$this->data['desgravacion_det_tipo_operacion'].'",
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
		$filter        = [];
		$primaryFilter = [];
		$operator      = $this->getOperator();
		$joinOperator  = ' AND ';

		foreach($this->data as $key => $data){
			if ($data <> ''){
				if ($key == 'desgravacion_det_desgravacion_id' || $key == 'desgravacion_det_desgravacion_acuerdo_det_id' || $key == 'desgravacion_det_desgravacion_acuerdo_det_acuerdo_id') {
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

		$sql = 'SELECT
			 desgravacion_det_id,
			 desgravacion_det_anio_ini,
			 desgravacion_det_anio_fin,
			 desgravacion_det_tasa_intra,
			 desgravacion_det_tasa_extra,
			 desgravacion_det_tipo_operacion,
			 desgravacion_det_desgravacion_id,
			 desgravacion_det_desgravacion_acuerdo_det_id,
			 desgravacion_det_desgravacion_acuerdo_det_acuerdo_id,
			 desgravacion_mdesgravacion
			FROM desgravacion_det
			LEFT JOIN desgravacion ON desgravacion_det_desgravacion_id = desgravacion_id
		';

		$whereAssignment = false;

		if(!empty($primaryFilter)){
			$sql            .= ' WHERE ('. implode( ' AND ', $primaryFilter ).')';
			$whereAssignment = true;
		}
		if(!empty($filter)){
			$sql .= ($whereAssignment) ? ' AND ' : ' WHERE ' ;
			$sql .= '  ('. implode( $joinOperator, $filter ).')';
		}

		return $sql;
	}

}
