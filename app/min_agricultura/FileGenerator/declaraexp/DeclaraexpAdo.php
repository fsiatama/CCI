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
		$fecha = $declaraexp->getFecha();
		$id_empresa = $declaraexp->getId_empresa();
		$id_paisdestino = $declaraexp->getId_paisdestino();
		$id_deptorigen = $declaraexp->getId_deptorigen();
		$id_capitulo = $declaraexp->getId_capitulo();
		$id_partida = $declaraexp->getId_partida();
		$id_subpartida = $declaraexp->getId_subpartida();
		$id_posicion = $declaraexp->getId_posicion();
		$id_ciiu = $declaraexp->getId_ciiu();
		$valorfob = $declaraexp->getValorfob();
		$valorcif = $declaraexp->getValorcif();
		$valor_pesos = $declaraexp->getValor_pesos();
		$peso_neto = $declaraexp->getPeso_neto();
		$cantidad = $declaraexp->getCantidad();
		$unidad = $declaraexp->getUnidad();

		$this->data = compact(
			'id',
			'anio',
			'periodo',
			'fecha',
			'id_empresa',
			'id_paisdestino',
			'id_deptorigen',
			'id_capitulo',
			'id_partida',
			'id_subpartida',
			'id_posicion',
			'id_ciiu',
			'valorfob',
			'valorcif',
			'valor_pesos',
			'peso_neto',
			'cantidad',
			'unidad'
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
				fecha,
				id_empresa,
				id_paisdestino,
				id_deptorigen,
				id_capitulo,
				id_partida,
				id_subpartida,
				id_posicion,
				id_ciiu,
				valorfob,
				valorcif,
				valor_pesos,
				peso_neto,
				cantidad,
				unidad
			)
			VALUES (
				"'.$this->data['id'].'",
				"'.$this->data['anio'].'",
				"'.$this->data['periodo'].'",
				"'.$this->data['fecha'].'",
				"'.$this->data['id_empresa'].'",
				"'.$this->data['id_paisdestino'].'",
				"'.$this->data['id_deptorigen'].'",
				"'.$this->data['id_capitulo'].'",
				"'.$this->data['id_partida'].'",
				"'.$this->data['id_subpartida'].'",
				"'.$this->data['id_posicion'].'",
				"'.$this->data['id_ciiu'].'",
				"'.$this->data['valorfob'].'",
				"'.$this->data['valorcif'].'",
				"'.$this->data['valor_pesos'].'",
				"'.$this->data['peso_neto'].'",
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
			 id_paisdestino,
			 id_deptorigen,
			 id_capitulo,
			 id_partida,
			 id_subpartida,
			 id_posicion,
			 id_ciiu,
			 valorfob,
			 valorcif,
			 valor_pesos,
			 peso_neto,
			 cantidad,
			 unidad
			FROM declaraexp
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
