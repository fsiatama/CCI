<?php

require_once ('BaseAdo.php');

class MercadoAdo extends BaseAdo {

	protected function setTable()
	{
		$this->table = 'mercado';
	}

	protected function setPrimaryKey()
	{
		$this->primaryKey = 'mercado_id';
	}

	protected function setData()
	{
		$mercado = $this->getModel();

		$mercado_id = $mercado->getMercado_id();
		$mercado_nombre = $mercado->getMercado_nombre();
		$mercado_paises = $mercado->getMercado_paises();
		$mercado_bandera = $mercado->getMercado_bandera();
		$mercado_uinsert = $mercado->getMercado_uinsert();
		$mercado_finsert = $mercado->getMercado_finsert();
		$mercado_uupdate = $mercado->getMercado_uupdate();
		$mercado_fupdate = $mercado->getMercado_fupdate();

		$this->data = compact(
			'mercado_id',
			'mercado_nombre',
			'mercado_paises',
			'mercado_bandera',
			'mercado_uinsert',
			'mercado_finsert',
			'mercado_uupdate',
			'mercado_fupdate'
		);
	}

	public function create($mercado)
	{
		$conn = $this->getConnection();
		$this->setModel($mercado);
		$this->setData();

		$sql = '
			INSERT INTO mercado (
				mercado_id,
				mercado_nombre,
				mercado_paises,
				mercado_bandera,
				mercado_uinsert,
				mercado_finsert,
				mercado_uupdate,
				mercado_fupdate
			)
			VALUES (
				"'.$this->data['mercado_id'].'",
				"'.$this->data['mercado_nombre'].'",
				"'.$this->data['mercado_paises'].'",
				"'.$this->data['mercado_bandera'].'",
				"'.$this->data['mercado_uinsert'].'",
				"'.$this->data['mercado_finsert'].'",
				"'.$this->data['mercado_uupdate'].'",
				"'.$this->data['mercado_fupdate'].'"
			)
		';
		$resultSet = $conn->Execute($sql);
		$result = $this->buildResult($resultSet, $conn->Insert_ID());

		return $result;
	}

	public function buildSelect()
	{

		$sql = 'SELECT
			 mercado_id,
			 mercado_nombre,
			 mercado_paises,
			 mercado_bandera,
			 mercado_uinsert,
			 mercado_finsert,
			 mercado_uupdate,
			 mercado_fupdate
			FROM mercado
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
				if ($key == 'mercado_id') {
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
