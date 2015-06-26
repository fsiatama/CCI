<?php

require_once ('BaseAdo.php');

class Acuerdo_infoAdo extends BaseAdo {

	protected function setTable()
	{
		$this->table = 'acuerdo_info';
	}

	protected function setPrimaryKey()
	{
		$this->primaryKey = 'acuerdo_id';
	}

	protected function setData()
	{
		$acuerdo_info = $this->getModel();

		$acuerdo_id = $acuerdo_info->getAcuerdo_id();
		$acuerdo_nombre = $acuerdo_info->getAcuerdo_nombre();
		$acuerdo_descripcion = $acuerdo_info->getAcuerdo_descripcion();
		$acuerdo_fvigente = $acuerdo_info->getAcuerdo_fvigente();
		$acuerdo_ffirma = $acuerdo_info->getAcuerdo_ffirma();
		$acuerdo_ley = $acuerdo_info->getAcuerdo_ley();
		$acuerdo_decreto = $acuerdo_info->getAcuerdo_decreto();
		$acuerdo_url = $acuerdo_info->getAcuerdo_url();
		$acuerdo_estado = $acuerdo_info->getAcuerdo_estado();
		$acuerdo_uinsert = $acuerdo_info->getAcuerdo_uinsert();
		$acuerdo_finsert = $acuerdo_info->getAcuerdo_finsert();
		$acuerdo_uupdate = $acuerdo_info->getAcuerdo_uupdate();
		$acuerdo_fupdate = $acuerdo_info->getAcuerdo_fupdate();
		$acuerdo_mercado_id = $acuerdo_info->getAcuerdo_mercado_id();
		$acuerdo_id_pais = $acuerdo_info->getAcuerdo_id_pais();

		$this->data = compact(
			'acuerdo_id',
			'acuerdo_nombre',
			'acuerdo_descripcion',
			'acuerdo_fvigente',
			'acuerdo_ffirma',
			'acuerdo_ley',
			'acuerdo_decreto',
			'acuerdo_url',
			'acuerdo_estado',
			'acuerdo_uinsert',
			'acuerdo_finsert',
			'acuerdo_uupdate',
			'acuerdo_fupdate',
			'acuerdo_mercado_id',
			'acuerdo_id_pais'
		);
	}

	public function create($acuerdo_info)
	{
		$conn = $this->getConnection();
		$this->setModel($acuerdo_info);
		$this->setData();

		$sql = '
			INSERT INTO acuerdo_info (
				acuerdo_id,
				acuerdo_nombre,
				acuerdo_descripcion,
				acuerdo_fvigente,
				acuerdo_ffirma,
				acuerdo_ley,
				acuerdo_decreto,
				acuerdo_url,
				acuerdo_estado,
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
				"'.$this->data['acuerdo_fvigente'].'",
				"'.$this->data['acuerdo_ffirma'].'",
				"'.$this->data['acuerdo_ley'].'",
				"'.$this->data['acuerdo_decreto'].'",
				"'.$this->data['acuerdo_url'].'",
				"'.$this->data['acuerdo_estado'].'",
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
			 acuerdo_fvigente,
			 acuerdo_ffirma,
			 acuerdo_ley,
			 acuerdo_decreto,
			 acuerdo_url,
			 acuerdo_estado,
			 acuerdo_uinsert,
			 acuerdo_finsert,
			 acuerdo_uupdate,
			 acuerdo_fupdate,
			 acuerdo_mercado_id,
			 acuerdo_id_pais
			FROM acuerdo_info
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
