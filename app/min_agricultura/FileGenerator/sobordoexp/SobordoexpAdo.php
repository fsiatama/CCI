<?php

require_once ('BaseAdo.php');

class SobordoexpAdo extends BaseAdo {

	protected function setTable()
	{
		$this->table = 'sobordoexp';
	}

	protected function setPrimaryKey()
	{
		$this->primaryKey = 'id';
	}

	protected function setData()
	{
		$sobordoexp = $this->getModel();

		$id = $sobordoexp->getId();
		$anio = $sobordoexp->getAnio();
		$periodo = $sobordoexp->getPeriodo();
		$fecha = $sobordoexp->getFecha();
		$id_paisdestino = $sobordoexp->getId_paisdestino();
		$id_capitulo = $sobordoexp->getId_capitulo();
		$id_partida = $sobordoexp->getId_partida();
		$id_subpartida = $sobordoexp->getId_subpartida();
		$peso_neto = $sobordoexp->getPeso_neto();

		$this->data = compact(
			'id',
			'anio',
			'periodo',
			'fecha',
			'id_paisdestino',
			'id_capitulo',
			'id_partida',
			'id_subpartida',
			'peso_neto'
		);
	}

	public function create($sobordoexp)
	{
		$conn = $this->getConnection();
		$this->setModel($sobordoexp);
		$this->setData();

		$sql = '
			INSERT INTO sobordoexp (
				id,
				anio,
				periodo,
				fecha,
				id_paisdestino,
				id_capitulo,
				id_partida,
				id_subpartida,
				peso_neto
			)
			VALUES (
				"'.$this->data['id'].'",
				"'.$this->data['anio'].'",
				"'.$this->data['periodo'].'",
				"'.$this->data['fecha'].'",
				"'.$this->data['id_paisdestino'].'",
				"'.$this->data['id_capitulo'].'",
				"'.$this->data['id_partida'].'",
				"'.$this->data['id_subpartida'].'",
				"'.$this->data['peso_neto'].'"
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
			 id_paisdestino,
			 id_capitulo,
			 id_partida,
			 id_subpartida,
			 peso_neto
			FROM sobordoexp
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
