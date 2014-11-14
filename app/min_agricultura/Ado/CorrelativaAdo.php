<?php

require_once ('BaseAdo.php');

class CorrelativaAdo extends BaseAdo {

	protected function setTable()
	{
		$this->table = 'correlativa';
	}

	protected function setPrimaryKey()
	{
		$this->primaryKey = 'correlativa_id';
	}

	protected function setData()
	{
		$correlativa = $this->getModel();

		$correlativa_id = $correlativa->getCorrelativa_id();
		$correlativa_origen_posicion_id = $correlativa->getCorrelativa_origen_posicion_id();
		$correlativa_destino_posicion_id = $correlativa->getCorrelativa_destino_posicion_id();
		$correlativa_fvigente = $correlativa->getCorrelativa_fvigente();
		$correlativa_decreto = $correlativa->getCorrelativa_decreto();
		$correlativa_observacion = $correlativa->getCorrelativa_observacion();
		$correlativa_uinsert = $correlativa->getCorrelativa_uinsert();
		$correlativa_finsert = $correlativa->getCorrelativa_finsert();

		$this->data = compact(
			'correlativa_id',
			'correlativa_origen_posicion_id',
			'correlativa_destino_posicion_id',
			'correlativa_fvigente',
			'correlativa_decreto',
			'correlativa_observacion',
			'correlativa_uinsert',
			'correlativa_finsert'
		);
	}

	public function create($correlativa)
	{
		$conn = $this->getConnection();
		$this->setModel($correlativa);
		$this->setData();

		$sql = '
			INSERT INTO correlativa (
				correlativa_id,
				correlativa_origen_posicion_id,
				correlativa_destino_posicion_id,
				correlativa_fvigente,
				correlativa_decreto,
				correlativa_observacion,
				correlativa_uinsert,
				correlativa_finsert
			)
			VALUES (
				"'.$this->data['correlativa_id'].'",
				"'.$this->data['correlativa_origen_posicion_id'].'",
				"'.$this->data['correlativa_destino_posicion_id'].'",
				"'.$this->data['correlativa_fvigente'].'",
				"'.$this->data['correlativa_decreto'].'",
				"'.$this->data['correlativa_observacion'].'",
				"'.$this->data['correlativa_uinsert'].'",
				"'.$this->data['correlativa_finsert'].'"
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
			 correlativa_id,
			 correlativa_origen_posicion_id,
			 correlativa_destino_posicion_id,
			 correlativa_fvigente,
			 correlativa_decreto,
			 correlativa_observacion,
			 correlativa_uinsert,
			 correlativa_finsert,
			 origen.posicion,
			 destino.posicion
			FROM correlativa
			LEFT JOIN posicion AS origen ON correlativa_origen_posicion_id = origen.posicion_id
			LEFT JOIN posicion AS destino ON correlativa_destino_posicion_id = destino.posicion_id
		';
		if(!empty($filter)){
			$sql .= ' WHERE ('. implode( $joinOperator, $filter ).')';
		}

		return $sql;
	}

}
