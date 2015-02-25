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
			 acuerdo_intercambio,
			 acuerdo_fvigente,
			 acuerdo_uinsert,
			 acuerdo_finsert,
			 acuerdo_uupdate,
			 acuerdo_fupdate,
			 acuerdo_mercado_id,
			 acuerdo_id_pais,
			 mercado_nombre,
			 pais,
			 paises_iata,
			 pais_iata
			FROM acuerdo 
			LEFT JOIN (
				SELECT mercado_id, mercado_nombre, mercado_paises, GROUP_CONCAT(DISTINCT pais) AS paises_nombre , GROUP_CONCAT(DISTINCT pais_iata) AS paises_iata
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
