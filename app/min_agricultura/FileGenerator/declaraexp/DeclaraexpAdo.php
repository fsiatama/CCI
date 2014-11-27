<?php

require_once ('BaseAdo.php');

class DeclaraexpAdo extends BaseAdo {

	protected function setTable()
	{
		$this->table = 'declaraexp';
	}

	protected function setPrimaryKey()
	{
		$this->primaryKey = 'id';
	}

	protected function setData()
	{
		$declaraexp = $this->getModel();

		$id = $declaraexp->getId();
		$anio = $declaraexp->getAnio();
		$periodo = $declaraexp->getPeriodo();
		$id_empresa = $declaraexp->getId_empresa();
		$id_paisdestino = $declaraexp->getId_paisdestino();
		$id_capitulo = $declaraexp->getId_capitulo();
		$id_partida = $declaraexp->getId_partida();
		$id_subpartida = $declaraexp->getId_subpartida();
		$id_posicion = $declaraexp->getId_posicion();
		$id_ciiu = $declaraexp->getId_ciiu();
		$valorfob = $declaraexp->getValorfob();
		$valorcif = $declaraexp->getValorcif();
		$peso_neto = $declaraexp->getPeso_neto();

		$this->data = compact(
			'id',
			'anio',
			'periodo',
			'id_empresa',
			'id_paisdestino',
			'id_capitulo',
			'id_partida',
			'id_subpartida',
			'id_posicion',
			'id_ciiu',
			'valorfob',
			'valorcif',
			'peso_neto'
		);
	}

	public function create($declaraexp)
	{
		$conn = $this->getConnection();
		$this->setModel($declaraexp);
		$this->setData();

		$sql = '
			INSERT INTO declaraexp (
				id,
				anio,
				periodo,
				id_empresa,
				id_paisdestino,
				id_capitulo,
				id_partida,
				id_subpartida,
				id_posicion,
				id_ciiu,
				valorfob,
				valorcif,
				peso_neto
			)
			VALUES (
				"'.$this->data['id'].'",
				"'.$this->data['anio'].'",
				"'.$this->data['periodo'].'",
				"'.$this->data['id_empresa'].'",
				"'.$this->data['id_paisdestino'].'",
				"'.$this->data['id_capitulo'].'",
				"'.$this->data['id_partida'].'",
				"'.$this->data['id_subpartida'].'",
				"'.$this->data['id_posicion'].'",
				"'.$this->data['id_ciiu'].'",
				"'.$this->data['valorfob'].'",
				"'.$this->data['valorcif'].'",
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
			 id_paisdestino,
			 id_capitulo,
			 id_partida,
			 id_subpartida,
			 id_posicion,
			 id_ciiu,
			 valorfob,
			 valorcif,
			 peso_neto
			FROM declaraexp
		';
		if(!empty($filter)){
			$sql .= ' WHERE ('. implode( $joinOperator, $filter ).')';
		}

		return $sql;
	}

}
