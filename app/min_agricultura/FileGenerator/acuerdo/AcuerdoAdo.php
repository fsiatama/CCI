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
		$acuerdo_ffirma = $acuerdo->getAcuerdo_ffirma();
		$acuerdo_ley = $acuerdo->getAcuerdo_ley();
		$acuerdo_decreto = $acuerdo->getAcuerdo_decreto();
		$acuerdo_url = $acuerdo->getAcuerdo_url();
		$acuerdo_tipo_acuerdo = $acuerdo->getAcuerdo_tipo_acuerdo();
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
			'acuerdo_ffirma',
			'acuerdo_ley',
			'acuerdo_decreto',
			'acuerdo_url',
			'acuerdo_tipo_acuerdo',
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
				acuerdo_ffirma,
				acuerdo_ley,
				acuerdo_decreto,
				acuerdo_url,
				acuerdo_tipo_acuerdo,
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
				"'.$this->data['acuerdo_ffirma'].'",
				"'.$this->data['acuerdo_ley'].'",
				"'.$this->data['acuerdo_decreto'].'",
				"'.$this->data['acuerdo_url'].'",
				"'.$this->data['acuerdo_tipo_acuerdo'].'",
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

		$sql = 'SELECT
			 acuerdo_id,
			 acuerdo_nombre,
			 acuerdo_descripcion,
			 acuerdo_intercambio,
			 acuerdo_fvigente,
			 acuerdo_ffirma,
			 acuerdo_ley,
			 acuerdo_decreto,
			 acuerdo_url,
			 acuerdo_tipo_acuerdo,
			 acuerdo_uinsert,
			 acuerdo_finsert,
			 acuerdo_uupdate,
			 acuerdo_fupdate,
			 acuerdo_mercado_id,
			 acuerdo_id_pais
			FROM acuerdo
		';

		$sql .= $this->buildSelectWhere();

		return $sql;
	}


	public function buildSelectWhere()
	{
		$filter        = [];
		$primaryFilter = [];
		$operator      = $this->getOperator();
		$joinOperator  = ' AND ';

		foreach($this->data as $key => $data){
			if ($data <> ''){
				if ($key == 'acuerdo_id') {
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

		$sql             = '';

		if(!empty($primaryFilter)){
			$sql            .= ($this->getWhereAssignment()) ? ' AND ' : ' WHERE ' ;
			$sql            .= ' ('. implode( ' AND ', $primaryFilter ).')';
			$this->setWhereAssignment( true );
		}
		if(!empty($filter)){
			$sql .= ($this->getWhereAssignment()) ? ' AND ' : ' WHERE ' ;
			$sql .= '  ('. implode( $joinOperator, $filter ).')';
		}

		return $sql;
	}

}
