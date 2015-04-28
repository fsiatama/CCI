<?php

require_once ('BaseAdo.php');

class SobordoimpAdo extends BaseAdo {

	protected function setTable()
	{
		$this->table = 'sobordoimp';
	}

	protected function setPrimaryKey()
	{
		$this->primaryKey = 'id';
	}

	protected function setData()
	{
		$sobordoimp = $this->getModel();

		$id = $sobordoimp->getId();
		$anio = $sobordoimp->getAnio();
		$periodo = $sobordoimp->getPeriodo();
		$fecha = $sobordoimp->getFecha();
		$id_paisprocedencia = $sobordoimp->getId_paisprocedencia();
		$id_capitulo = $sobordoimp->getId_capitulo();
		$id_partida = $sobordoimp->getId_partida();
		$id_subpartida = $sobordoimp->getId_subpartida();
		$peso_neto = $sobordoimp->getPeso_neto();

		$this->data = compact(
			'id',
			'anio',
			'periodo',
			'fecha',
			'id_paisprocedencia',
			'id_capitulo',
			'id_partida',
			'id_subpartida',
			'peso_neto'
		);
	}

	public function create($sobordoimp)
	{
		$conn = $this->getConnection();
		$this->setModel($sobordoimp);
		$this->setData();

		$sql = '
			INSERT INTO sobordoimp (
				id,
				anio,
				periodo,
				fecha,
				id_paisprocedencia,
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
				"'.$this->data['id_paisprocedencia'].'",
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
			 id_paisprocedencia,
			 id_capitulo,
			 id_partida,
			 id_subpartida,
			 peso_neto
			FROM sobordoimp
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
