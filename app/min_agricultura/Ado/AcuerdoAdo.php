<?php

require_once ('BaseAdo.php');

class AcuerdoAdo extends BaseAdo {

	protected function setTable()
	{
		$this->table = 'acuerdo';
	}

	protected function setPrimaryKey()
	{
		$this->primaryKey = 'acuerdo_id';
	}

	protected function setData()
	{
		$acuerdo = $this->getModel();

		$acuerdo_id = $acuerdo->getAcuerdo_id();
		$acuerdo_nombre = $acuerdo->getAcuerdo_nombre();
		$acuerdo_descripcion = $acuerdo->getAcuerdo_descripcion();
		$acuerdo_intercambio = $acuerdo->getAcuerdo_intercambio();
		$acuerdo_fvigente = $acuerdo->getAcuerdo_fvigente();
		$acuerdo_uinsert = $acuerdo->getAcuerdo_uinsert();
		$acuerdo_finsert = $acuerdo->getAcuerdo_finsert();
		$acuerdo_uupdate = $acuerdo->getAcuerdo_uupdate();
		$acuerdo_fupdate = $acuerdo->getAcuerdo_fupdate();
		$acuerdo_mercado_id = $acuerdo->getAcuerdo_mercado_id();
		$acuerdo_id_pais = $acuerdo->getAcuerdo_id_pais();

		$this->data = compact(
			'acuerdo_id',
			'acuerdo_nombre',
			'acuerdo_descripcion',
			'acuerdo_intercambio',
			'acuerdo_fvigente',
			'acuerdo_uinsert',
			'acuerdo_finsert',
			'acuerdo_uupdate',
			'acuerdo_fupdate',
			'acuerdo_mercado_id',
			'acuerdo_id_pais'
		);
	}

	public function create($acuerdo)
	{
		$conn = $this->getConnection();
		$this->setModel($acuerdo);
		$this->setData();

		$sql = '
			INSERT INTO acuerdo (
				acuerdo_id,
				acuerdo_nombre,
				acuerdo_descripcion,
				acuerdo_intercambio,
				acuerdo_fvigente,
				acuerdo_uinsert,
				acuerdo_finsert,
				acuerdo_uupdate,
				acuerdo_fupdate,
				acuerdo_mercado_id,
				acuerdo_id_pais
			)
			VALUES (
				"'.$this->data['acuerdo_id'].'",
				"'.$this->data['acuerdo_nombre'].'",
				"'.$this->data['acuerdo_descripcion'].'",
				"'.$this->data['acuerdo_intercambio'].'",
				"'.$this->data['acuerdo_fvigente'].'",
				"'.$this->data['acuerdo_uinsert'].'",
				"'.$this->data['acuerdo_finsert'].'",
				"'.$this->data['acuerdo_uupdate'].'",
				"'.$this->data['acuerdo_fupdate'].'",
				"'.$this->data['acuerdo_mercado_id'].'",
				"'.$this->data['acuerdo_id_pais'].'"
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
			 acuerdo_id,
			 acuerdo_nombre,
			 acuerdo_descripcion,
			 acuerdo_intercambio,
			 acuerdo_fvigente,
			 acuerdo_uinsert,
			 acuerdo_finsert,
			 acuerdo_uupdate,
			 acuerdo_fupdate,
			 acuerdo_mercado_id,
			 acuerdo_id_pais
			FROM acuerdo
		';
		if(!empty($filter)){
			$sql .= ' WHERE ('. implode( $joinOperator, $filter ).')';
		}

		return $sql;
	}

}
