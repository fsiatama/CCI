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
		$id_empresa = $declaraimp->getId_empresa();
		$id_paisorigen = $declaraimp->getId_paisorigen();
		$id_paiscompra = $declaraimp->getId_paiscompra();
		$id_paisprocedencia = $declaraimp->getId_paisprocedencia();
		$id_capitulo = $declaraimp->getId_capitulo();
		$id_partida = $declaraimp->getId_partida();
		$id_subpartida = $declaraimp->getId_subpartida();
		$id_posicion = $declaraimp->getId_posicion();
		$id_ciiu = $declaraimp->getId_ciiu();
		$valorcif = $declaraimp->getValorcif();
		$valorfob = $declaraimp->getValorfob();
		$peso_neto = $declaraimp->getPeso_neto();

		$this->data = compact(
			'id',
			'anio',
			'periodo',
			'id_empresa',
			'id_paisorigen',
			'id_paiscompra',
			'id_paisprocedencia',
			'id_capitulo',
			'id_partida',
			'id_subpartida',
			'id_posicion',
			'id_ciiu',
			'valorcif',
			'valorfob',
			'peso_neto'
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
				id_empresa,
				id_paisorigen,
				id_paiscompra,
				id_paisprocedencia,
				id_capitulo,
				id_partida,
				id_subpartida,
				id_posicion,
				id_ciiu,
				valorcif,
				valorfob,
				peso_neto
			)
			VALUES (
				"'.$this->data['id'].'",
				"'.$this->data['anio'].'",
				"'.$this->data['periodo'].'",
				"'.$this->data['id_empresa'].'",
				"'.$this->data['id_paisorigen'].'",
				"'.$this->data['id_paiscompra'].'",
				"'.$this->data['id_paisprocedencia'].'",
				"'.$this->data['id_capitulo'].'",
				"'.$this->data['id_partida'].'",
				"'.$this->data['id_subpartida'].'",
				"'.$this->data['id_posicion'].'",
				"'.$this->data['id_ciiu'].'",
				"'.$this->data['valorcif'].'",
				"'.$this->data['valorfob'].'",
				"'.$this->data['peso_neto'].'"
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
			 id,
			 anio,
			 periodo,
			 id_empresa,
			 id_paisorigen,
			 id_paiscompra,
			 id_paisprocedencia,
			 id_capitulo,
			 id_partida,
			 id_subpartida,
			 id_posicion,
			 id_ciiu,
			 valorcif,
			 valorfob,
			 peso_neto
			FROM declaraimp
		';
		if(!empty($filter)){
			$sql .= ' WHERE ('. implode( $joinOperator, $filter ).')';
		}

		return $sql;
	}

}
