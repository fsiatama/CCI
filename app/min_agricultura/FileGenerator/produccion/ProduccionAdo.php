<?php

require_once ('BaseAdo.php');

class ProduccionAdo extends BaseAdo {

	protected function setTable()
	{
		$this->table = 'produccion';
	}

	protected function setPrimaryKey()
	{
		$this->primaryKey = 'produccion_id';
	}

	protected function setData()
	{
		$produccion = $this->getModel();

		$produccion_id = $produccion->getProduccion_id();
		$produccion_sector_id = $produccion->getProduccion_sector_id();
		$produccion_anio = $produccion->getProduccion_anio();
		$produccion_peso_neto = $produccion->getProduccion_peso_neto();
		$produccion_finsert = $produccion->getProduccion_finsert();
		$produccion_uinsert = $produccion->getProduccion_uinsert();
		$produccion_fupdate = $produccion->getProduccion_fupdate();
		$produccion_uupdate = $produccion->getProduccion_uupdate();

		$this->data = compact(
			'produccion_id',
			'produccion_sector_id',
			'produccion_anio',
			'produccion_peso_neto',
			'produccion_finsert',
			'produccion_uinsert',
			'produccion_fupdate',
			'produccion_uupdate'
		);
	}

	public function create($produccion)
	{
		$conn = $this->getConnection();
		$this->setModel($produccion);
		$this->setData();

		$sql = '
			INSERT INTO produccion (
				produccion_id,
				produccion_sector_id,
				produccion_anio,
				produccion_peso_neto,
				produccion_finsert,
				produccion_uinsert,
				produccion_fupdate,
				produccion_uupdate
			)
			VALUES (
				"'.$this->data['produccion_id'].'",
				"'.$this->data['produccion_sector_id'].'",
				"'.$this->data['produccion_anio'].'",
				"'.$this->data['produccion_peso_neto'].'",
				"'.$this->data['produccion_finsert'].'",
				"'.$this->data['produccion_uinsert'].'",
				"'.$this->data['produccion_fupdate'].'",
				"'.$this->data['produccion_uupdate'].'"
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
			 produccion_id,
			 produccion_sector_id,
			 produccion_anio,
			 produccion_peso_neto,
			 produccion_finsert,
			 produccion_uinsert,
			 produccion_fupdate,
			 produccion_uupdate
			FROM produccion
		';
		if(!empty($filter)){
			$sql .= ' WHERE ('. implode( $joinOperator, $filter ).')';
		}

		return $sql;
	}

}
