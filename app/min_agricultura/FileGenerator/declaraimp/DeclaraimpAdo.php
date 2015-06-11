<?php

require_once ('BaseAdo.php');

class DeclaraimpAdo extends BaseAdo {

	protected function setTable()
	{
		$this->table = 'declaraimp';
	}

	protected function setPrimaryKey()
	{
		$this->primaryKey = 'id';
	}

	protected function setData()
	{
		$declaraimp = $this->getModel();

		$id = $declaraimp->getId();
		$anio = $declaraimp->getAnio();
		$periodo = $declaraimp->getPeriodo();
		$fecha = $declaraimp->getFecha();
		$id_empresa = $declaraimp->getId_empresa();
		$id_paisorigen = $declaraimp->getId_paisorigen();
		$id_paiscompra = $declaraimp->getId_paiscompra();
		$id_paisprocedencia = $declaraimp->getId_paisprocedencia();
		$id_deptorigen = $declaraimp->getId_deptorigen();
		$id_capitulo = $declaraimp->getId_capitulo();
		$id_partida = $declaraimp->getId_partida();
		$id_subpartida = $declaraimp->getId_subpartida();
		$id_posicion = $declaraimp->getId_posicion();
		$id_ciiu = $declaraimp->getId_ciiu();
		$valorcif = $declaraimp->getValorcif();
		$valorfob = $declaraimp->getValorfob();
		$peso_neto = $declaraimp->getPeso_neto();
		$arancel_pagado = $declaraimp->getArancel_pagado();
		$valorarancel = $declaraimp->getValorarancel();
		$porcentaje_arancel = $declaraimp->getPorcentaje_arancel();
		$cantidad = $declaraimp->getCantidad();
		$unidad = $declaraimp->getUnidad();

		$this->data = compact(
			'id',
			'anio',
			'periodo',
			'fecha',
			'id_empresa',
			'id_paisorigen',
			'id_paiscompra',
			'id_paisprocedencia',
			'id_deptorigen',
			'id_capitulo',
			'id_partida',
			'id_subpartida',
			'id_posicion',
			'id_ciiu',
			'valorcif',
			'valorfob',
			'peso_neto',
			'arancel_pagado',
			'valorarancel',
			'porcentaje_arancel',
			'cantidad',
			'unidad'
		);
	}

	public function create($declaraimp)
	{
		$conn = $this->getConnection();
		$this->setModel($declaraimp);
		$this->setData();

		$sql = '
			INSERT INTO declaraimp (
				id,
				anio,
				periodo,
				fecha,
				id_empresa,
				id_paisorigen,
				id_paiscompra,
				id_paisprocedencia,
				id_deptorigen,
				id_capitulo,
				id_partida,
				id_subpartida,
				id_posicion,
				id_ciiu,
				valorcif,
				valorfob,
				peso_neto,
				arancel_pagado,
				valorarancel,
				porcentaje_arancel,
				cantidad,
				unidad
			)
			VALUES (
				"'.$this->data['id'].'",
				"'.$this->data['anio'].'",
				"'.$this->data['periodo'].'",
				"'.$this->data['fecha'].'",
				"'.$this->data['id_empresa'].'",
				"'.$this->data['id_paisorigen'].'",
				"'.$this->data['id_paiscompra'].'",
				"'.$this->data['id_paisprocedencia'].'",
				"'.$this->data['id_deptorigen'].'",
				"'.$this->data['id_capitulo'].'",
				"'.$this->data['id_partida'].'",
				"'.$this->data['id_subpartida'].'",
				"'.$this->data['id_posicion'].'",
				"'.$this->data['id_ciiu'].'",
				"'.$this->data['valorcif'].'",
				"'.$this->data['valorfob'].'",
				"'.$this->data['peso_neto'].'",
				"'.$this->data['arancel_pagado'].'",
				"'.$this->data['valorarancel'].'",
				"'.$this->data['porcentaje_arancel'].'",
				"'.$this->data['cantidad'].'",
				"'.$this->data['unidad'].'"
			)
		';
		$resultSet = $conn->Execute($sql);
		$result = $this->buildResult($resultSet, $conn->Insert_ID());

		return $result;
	}

	public function buildSelect()
	{

		$sql = 'SELECT
			 id,
			 anio,
			 periodo,
			 fecha,
			 id_empresa,
			 id_paisorigen,
			 id_paiscompra,
			 id_paisprocedencia,
			 id_deptorigen,
			 id_capitulo,
			 id_partida,
			 id_subpartida,
			 id_posicion,
			 id_ciiu,
			 valorcif,
			 valorfob,
			 peso_neto,
			 arancel_pagado,
			 valorarancel,
			 porcentaje_arancel,
			 cantidad,
			 unidad
			FROM declaraimp
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
				if ($key == 'id') {
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
