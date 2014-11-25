<?php

require_once ('BaseAdo.php');

class IndicadorAdo extends BaseAdo {

	protected function setTable()
	{
		$this->table = 'indicador';
	}

	protected function setPrimaryKey()
	{
		$this->primaryKey = 'indicador_id';
	}

	protected function setData()
	{
		$indicador = $this->getModel();

		$indicador_id = $indicador->getIndicador_id();
		$indicador_nombre = $indicador->getIndicador_nombre();
		$indicador_tipo_indicador_id = $indicador->getIndicador_tipo_indicador_id();
		$indicador_campos = $indicador->getIndicador_campos();
		$indicador_filtros = $indicador->getIndicador_filtros();
		$indicador_leaf = $indicador->getIndicador_leaf();
		$indicador_parent = $indicador->getIndicador_parent();
		$indicador_uinsert = $indicador->getIndicador_uinsert();
		$indicador_finsert = $indicador->getIndicador_finsert();
		$indicador_fupdate = $indicador->getIndicador_fupdate();

		$this->data = compact(
			'indicador_id',
			'indicador_nombre',
			'indicador_tipo_indicador_id',
			'indicador_campos',
			'indicador_filtros',
			'indicador_leaf',
			'indicador_parent',
			'indicador_uinsert',
			'indicador_finsert',
			'indicador_fupdate'
		);
	}

	public function create($indicador)
	{
		$conn = $this->getConnection();
		$this->setModel($indicador);
		$this->setData();

		$sql = '
			INSERT INTO indicador (
				indicador_id,
				indicador_nombre,
				indicador_tipo_indicador_id,
				indicador_campos,
				indicador_filtros,
				indicador_leaf,
				indicador_parent,
				indicador_uinsert,
				indicador_finsert,
				indicador_fupdate
			)
			VALUES (
				"'.$this->data['indicador_id'].'",
				"'.$this->data['indicador_nombre'].'",
				"'.$this->data['indicador_tipo_indicador_id'].'",
				"'.$this->data['indicador_campos'].'",
				"'.$this->data['indicador_filtros'].'",
				"'.$this->data['indicador_leaf'].'",
				"'.$this->data['indicador_parent'].'",
				"'.$this->data['indicador_uinsert'].'",
				"'.$this->data['indicador_finsert'].'",
				"'.$this->data['indicador_fupdate'].'"
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
			 indicador_id,
			 indicador_nombre,
			 indicador_tipo_indicador_id,
			 indicador_campos,
			 indicador_filtros,
			 indicador_leaf,
			 indicador_parent,
			 indicador_uinsert,
			 indicador_finsert,
			 indicador_fupdate
			FROM indicador
		';
		if(!empty($filter)){
			$sql .= ' WHERE ('. implode( $joinOperator, $filter ).')';
		}

		return $sql;
	}

}
