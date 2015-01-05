<?php

require_once ('BaseAdo.php');

class Tipo_indicadorAdo extends BaseAdo {

	protected function setTable()
	{
		$this->table = 'tipo_indicador';
	}

	protected function setPrimaryKey()
	{
		$this->primaryKey = 'tipo_indicador_id';
	}

	protected function setData()
	{
		$tipo_indicador = $this->getModel();

		$tipo_indicador_id = $tipo_indicador->getTipo_indicador_id();
		$tipo_indicador_nombre = $tipo_indicador->getTipo_indicador_nombre();
		$tipo_indicador_abrev = $tipo_indicador->getTipo_indicador_abrev();
		$tipo_indicador_activador = $tipo_indicador->getTipo_indicador_activador();
		$tipo_indicador_calculo = $tipo_indicador->getTipo_indicador_calculo();
		$tipo_indicador_definicion = $tipo_indicador->getTipo_indicador_definicion();
		$tipo_indicador_html = $tipo_indicador->getTipo_indicador_html();

		$this->data = compact(
			'tipo_indicador_id',
			'tipo_indicador_nombre',
			'tipo_indicador_abrev',
			'tipo_indicador_activador',
			'tipo_indicador_calculo',
			'tipo_indicador_definicion',
			'tipo_indicador_html'
		);
	}

	public function create($tipo_indicador)
	{
		$conn = $this->getConnection();
		$this->setModel($tipo_indicador);
		$this->setData();

		$sql = '
			INSERT INTO tipo_indicador (
				tipo_indicador_id,
				tipo_indicador_nombre,
				tipo_indicador_abrev,
				tipo_indicador_activador,
				tipo_indicador_calculo,
				tipo_indicador_definicion,
				tipo_indicador_html
			)
			VALUES (
				"'.$this->data['tipo_indicador_id'].'",
				"'.$this->data['tipo_indicador_nombre'].'",
				"'.$this->data['tipo_indicador_abrev'].'",
				"'.$this->data['tipo_indicador_activador'].'",
				"'.$this->data['tipo_indicador_calculo'].'",
				"'.$this->data['tipo_indicador_definicion'].'",
				"'.$this->data['tipo_indicador_html'].'"
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
			 tipo_indicador_id,
			 tipo_indicador_nombre,
			 tipo_indicador_abrev,
			 tipo_indicador_activador,
			 tipo_indicador_calculo,
			 tipo_indicador_definicion,
			 tipo_indicador_html
			FROM tipo_indicador
		';
		if(!empty($filter)){
			$sql .= ' WHERE ('. implode( $joinOperator, $filter ).')';
		}

		return $sql;
	}

}
