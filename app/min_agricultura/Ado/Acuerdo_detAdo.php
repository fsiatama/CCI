<?php

require_once ('BaseAdo.php');

class Acuerdo_detAdo extends BaseAdo {

	protected function setTable()
	{
		$this->table = 'acuerdo_det';
	}

	protected function setPrimaryKey()
	{
		$this->primaryKey = 'acuerdo_det_id';
	}

	protected function setData()
	{
		$acuerdo_det = $this->getModel();

		$acuerdo_det_id = $acuerdo_det->getAcuerdo_det_id();
		$acuerdo_det_arancel_base = $acuerdo_det->getAcuerdo_det_arancel_base();
		$acuerdo_det_productos = $acuerdo_det->getAcuerdo_det_productos();
		$acuerdo_det_productos_desc = $acuerdo_det->getAcuerdo_det_productos_desc();
		$acuerdo_det_administracion = $acuerdo_det->getAcuerdo_det_administracion();
		$acuerdo_det_administrador = $acuerdo_det->getAcuerdo_det_administrador();
		$acuerdo_det_nperiodos = $acuerdo_det->getAcuerdo_det_nperiodos();
		$acuerdo_det_acuerdo_id = $acuerdo_det->getAcuerdo_det_acuerdo_id();
		$acuerdo_det_contingente_acumulado_pais = $acuerdo_det->getAcuerdo_det_contingente_acumulado_pais();
		$acuerdo_det_desgravacion_igual_pais = $acuerdo_det->getAcuerdo_det_desgravacion_igual_pais();

		$this->data = compact(
			'acuerdo_det_id',
			'acuerdo_det_arancel_base',
			'acuerdo_det_productos',
			'acuerdo_det_productos_desc',
			'acuerdo_det_administracion',
			'acuerdo_det_administrador',
			'acuerdo_det_nperiodos',
			'acuerdo_det_acuerdo_id',
			'acuerdo_det_contingente_acumulado_pais',
			'acuerdo_det_desgravacion_igual_pais'
		);
	}

	public function create($acuerdo_det)
	{
		$conn = $this->getConnection();
		$this->setModel($acuerdo_det);
		$this->setData();

		$sql = '
			INSERT INTO acuerdo_det (
				acuerdo_det_id,
				acuerdo_det_arancel_base,
				acuerdo_det_productos,
				acuerdo_det_productos_desc,
				acuerdo_det_administracion,
				acuerdo_det_administrador,
				acuerdo_det_nperiodos,
				acuerdo_det_acuerdo_id,
				acuerdo_det_contingente_acumulado_pais,
				acuerdo_det_desgravacion_igual_pais
			)
			VALUES (
				"'.$this->data['acuerdo_det_id'].'",
				"'.$this->data['acuerdo_det_arancel_base'].'",
				"'.$this->data['acuerdo_det_productos'].'",
				"'.$this->data['acuerdo_det_productos_desc'].'",
				"'.$this->data['acuerdo_det_administracion'].'",
				"'.$this->data['acuerdo_det_administrador'].'",
				"'.$this->data['acuerdo_det_nperiodos'].'",
				"'.$this->data['acuerdo_det_acuerdo_id'].'",
				"'.$this->data['acuerdo_det_contingente_acumulado_pais'].'",
				"'.$this->data['acuerdo_det_desgravacion_igual_pais'].'"
			)
		';
		$resultSet = $conn->Execute($sql);
		$result = $this->buildResult($resultSet, $conn->Insert_ID());

		return $result;
	}

	public function paginateDetailed($model, $operator, $numRows, $page, $year)
	{
		$this->setModel($model);
		$this->setOperator($operator);

		$conn = $this->getConnection();
		$this->setData();

		$sql = $this->buildDetailedSelect($year);

		$savec = ( empty($ADODB_COUNTRECS) ) ? false : $ADODB_COUNTRECS;
		if ($conn->pageExecuteCountRows) {
			$ADODB_COUNTRECS = true;
		}
		$resultSet = $conn->PageExecute($sql, $numRows, $page);
		$ADODB_COUNTRECS = $savec;

		$result = $this->buildResult($resultSet, false, true);

		return $result;
	}

	private function buildDetailedSelect($year)
	{
		$filter        = [];
		$primaryFilter = [];
		$operator      = $this->getOperator();
		$joinOperator  = ' AND ';
		foreach($this->data as $key => $data){
			if ($data <> ''){
				if ($key == 'acuerdo_det_acuerdo_id' || $key == 'acuerdo_det_id') {
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
			acuerdo_id,
			acuerdo_det_id,
			acuerdo_det_productos,
			acuerdo_det_productos_desc,
			acuerdo_det_nperiodos,
			acuerdo_det_contingente_acumulado_pais,
			contingente_id,
			contingente_id_pais,
			contingente_mcontingente,
			contingente_msalvaguardia,
			mercado_nombre,
			IF(acuerdo_det_contingente_acumulado_pais = "0", id_pais, mercado_paises) AS id_pais,
			IF(acuerdo_det_contingente_acumulado_pais = "0", pais, mercado_nombre) AS pais,
			IF(contingente_det_peso_neto IS NULL, 0, contingente_det_peso_neto) AS contingente_det_peso_neto,
			IF(contingente_msalvaguardia = "0", 0, ( ( 1 + (contingente_salvaguardia_sobretasa / 100 ) ) *  contingente_det_peso_neto)) AS salvaguardia_peso_neto,
			contingente_salvaguardia_sobretasa,
			alerta_contingente_verde,
			alerta_contingente_amarilla,
			alerta_contingente_roja,
			alerta_salvaguardia_verde,
			alerta_salvaguardia_amarilla,
			alerta_salvaguardia_roja
			FROM acuerdo_det 
			LEFT JOIN acuerdo ON acuerdo_det_acuerdo_id = acuerdo_id
			LEFT JOIN mercado ON acuerdo_mercado_id = mercado_id
			LEFT JOIN contingente ON acuerdo_det_id = contingente_acuerdo_det_id
			LEFT JOIN alerta ON contingente_id = alerta_contingente_id
			LEFT JOIN pais ON contingente_id_pais = id_pais
			LEFT JOIN (
				SELECT contingente_det_id, contingente_det_contingente_id, contingente_det_peso_neto 
				FROM contingente_det 
				WHERE  '.$year.' >= contingente_det_anio_ini AND '.$year.' <= contingente_det_anio_fin
			) AS contingente_det ON contingente_id = contingente_det_contingente_id
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

		//echo '<pre>'.$sql.'</pre>';

		return $sql;
	}

	public function buildSelect()
	{
		$filter        = [];
		$primaryFilter = [];
		$operator      = $this->getOperator();
		$joinOperator  = ' AND ';
		foreach($this->data as $key => $data){
			if ($data <> ''){
				if ($key == 'acuerdo_det_acuerdo_id' || $key == 'acuerdo_det_id') {
					$primaryFilter[] = $key . ' = "' . $data . '"';
				} elseif ($key == 'acuerdo_det_productos') {
					//construye una expesion regular con la data que llega (capitulo, partida, subpartida o posicion)

					$arrRegexp   = [];
					$arrRegexp[] = '[[:<:]]' . $data . '.*';

					if ( strlen($data) > 2 ) {
						$arrRegexp[] = '[[:<:]]' . substr($data, 0 ,2) . '$';
					}
					if ( strlen($data) > 4 ) {
						$arrRegexp[] = '[[:<:]]' . substr($data, 0 ,4) . '$';
					}
					if ( strlen($data) > 6 ) {
						$arrRegexp[] = '[[:<:]]' . substr($data, 0 ,6) . '$';
					}

					$regexp = implode('|', $arrRegexp);

					$filter[] = $key . ' REGEXP "' . $regexp . '"';

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
			 acuerdo_det_id,
			 acuerdo_det_arancel_base,
			 acuerdo_det_productos,
			 acuerdo_det_productos_desc,
			 acuerdo_det_administracion,
			 acuerdo_det_administrador,
			 acuerdo_det_nperiodos,
			 acuerdo_det_acuerdo_id,
			 acuerdo_det_contingente_acumulado_pais,
			 acuerdo_det_desgravacion_igual_pais,
			 acuerdo_nombre,
			 acuerdo_fvigente,
			 acuerdo_intercambio
			FROM acuerdo_det
			LEFT JOIN acuerdo ON acuerdo_det_acuerdo_id = acuerdo_id
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

		//echo '<pre>'.$sql.'</pre>';

		return $sql;
	}

}
