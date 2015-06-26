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
		$filter = [];
		$operator = $this->getOperator();
		$joinOperator = ' AND ';
		foreach( $this->data as $key => $data) {
			if ($data <> '') {
				if ($operator == '=') {
					$filter[] = $key . ' ' . $operator . ' "' . $data . '"';
				}
				elseif ($operator == 'IN') {
					//si el operador es in, utilizo acuerdo_mercado_id para buscar un id de pais
					if ( $key == 'acuerdo_mercado_id' ) {

						$regexp = str_replace(',', '|', $data);
						$filter[] = '( mercado_paises REGEXP "' . $regexp . '" OR FIND_IN_SET(acuerdo_id_pais, "' . $data . '") )';

					} else {

						$filter[] = $key . ' ' . $operator . '("' . $data . '")';
					}
				}
				else {
					//si el operador es like, utilizo acuerdo_mercado_id para buscar por nombre de pais
					if ( $key == 'acuerdo_mercado_id' ) {

						$filter[] = 'paises_nombre ' . $operator . ' "%' . $data . '%"';
						$filter[] = 'pais ' . $operator . ' "%' . $data . '%"';

					} else {

						$filter[] = $key . ' ' . $operator . ' "%' . $data . '%"';

					}
					$joinOperator = ' OR ';
				}
			}
		}


		$sql = 'SELECT
			 acuerdo_id,
			 acuerdo_nombre,
			 acuerdo_descripcion,
			 IF (acuerdo_fvigente = "0000-00-00","", acuerdo_fvigente) AS acuerdo_fvigente,
			 IF (acuerdo_ffirma = "0000-00-00","", acuerdo_ffirma) AS acuerdo_ffirma,
			 acuerdo_ley,
			 acuerdo_decreto,
			 acuerdo_url,
			 acuerdo_estado,
			 acuerdo_uinsert,
			 acuerdo_finsert,
			 acuerdo_uupdate,
			 acuerdo_fupdate,
			 acuerdo_mercado_id,
			 acuerdo_id_pais,
			 mercado_nombre,
			 mercado_bandera,
			 pais,
			 paises_iata,
			 pais_iata
			FROM acuerdo_info 
			LEFT JOIN (
				SELECT mercado_id, mercado_nombre, mercado_paises, mercado_bandera, GROUP_CONCAT(DISTINCT pais) AS paises_nombre , GROUP_CONCAT(DISTINCT pais_iata) AS paises_iata
				FROM mercado
				LEFT JOIN pais ON  FIND_IN_SET(id_pais, mercado_paises)
				GROUP BY mercado_id
			) AS mercado ON acuerdo_mercado_id = mercado_id
			LEFT JOIN pais ON acuerdo_id_pais = id_pais
		';
		if(!empty($filter)){
			$sql .= ' WHERE ('. implode( $joinOperator, $filter ).')';
		}

		//echo '<pre>'.$sql.'</pre>';

		return $sql;
	}

}
