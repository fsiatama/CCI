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
		$filter        = [];
		$primaryFilter = [];
		$operator      = $this->getOperator();
		$joinOperator  = ' AND ';
		foreach($this->data as $key => $data){
			if ($data <> ''){
				if ($key == 'desgravacion_acuerdo_det_id' || $key == 'desgravacion_acuerdo_det_acuerdo_id') {
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
			 desgravacion_id,
			 desgravacion_id_pais,
			 desgravacion_mdesgravacion,
			 desgravacion_desc,
			 desgravacion_acuerdo_det_id,
			 desgravacion_acuerdo_det_acuerdo_id,
			 acuerdo_mercado_id,
			 mercado_nombre,
			 acuerdo_id_pais,
			 pais,
			 acuerdo_det_desgravacion_igual_pais
			FROM desgravacion
			LEFT JOIN acuerdo ON desgravacion_acuerdo_det_acuerdo_id = acuerdo_id
			LEFT JOIN acuerdo_det ON desgravacion_acuerdo_det_id = acuerdo_det_id
			LEFT JOIN mercado ON desgravacion_id_pais = mercado_id
			LEFT JOIN pais ON desgravacion_id_pais = id_pais
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

		//echo "<pre>$sql</pre>";
		
		return $sql;
	}

}
