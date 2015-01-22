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
