<?php

require_once ('BaseAdo.php');

class DesgravacionAdo extends BaseAdo {

	protected function setTable()
	{
		$this->table = 'desgravacion';
	}

	protected function setPrimaryKey()
	{
		$this->primaryKey = 'desgravacion_id';
	}

	protected function setData()
	{
		$desgravacion = $this->getModel();

		$desgravacion_id = $desgravacion->getDesgravacion_id();
		$desgravacion_id_pais = $desgravacion->getDesgravacion_id_pais();
		$desgravacion_mdesgravacion = $desgravacion->getDesgravacion_mdesgravacion();
		$desgravacion_desc = $desgravacion->getDesgravacion_desc();
		$desgravacion_acuerdo_det_id = $desgravacion->getDesgravacion_acuerdo_det_id();
		$desgravacion_acuerdo_det_acuerdo_id = $desgravacion->getDesgravacion_acuerdo_det_acuerdo_id();

		$this->data = compact(
			'desgravacion_id',
			'desgravacion_id_pais',
			'desgravacion_mdesgravacion',
			'desgravacion_desc',
			'desgravacion_acuerdo_det_id',
			'desgravacion_acuerdo_det_acuerdo_id'
		);
	}

	public function create($desgravacion)
	{
		$conn = $this->getConnection();
		$this->setModel($desgravacion);
		$this->setData();

		$sql = '
			INSERT INTO desgravacion (
				desgravacion_id,
				desgravacion_id_pais,
				desgravacion_mdesgravacion,
				desgravacion_desc,
				desgravacion_acuerdo_det_id,
				desgravacion_acuerdo_det_acuerdo_id
			)
			VALUES (
				"'.$this->data['desgravacion_id'].'",
				"'.$this->data['desgravacion_id_pais'].'",
				"'.$this->data['desgravacion_mdesgravacion'].'",
				"'.$this->data['desgravacion_desc'].'",
				"'.$this->data['desgravacion_acuerdo_det_id'].'",
				"'.$this->data['desgravacion_acuerdo_det_acuerdo_id'].'"
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
			 desgravacion_id,
			 desgravacion_id_pais,
			 desgravacion_mdesgravacion,
			 desgravacion_desc,
			 desgravacion_acuerdo_det_id,
			 desgravacion_acuerdo_det_acuerdo_id
			FROM desgravacion
		';
		if(!empty($filter)){
			$sql .= ' WHERE ('. implode( $joinOperator, $filter ).')';
		}

		return $sql;
	}

}
