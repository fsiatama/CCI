<?php

require_once ('BaseAdo.php');

class PibAdo extends BaseAdo {

	protected function setTable()
	{
		$this->table = 'pib';
	}

	protected function setPrimaryKey()
	{
		$this->primaryKey = 'pib_id';
	}

	protected function setData()
	{
		$pib = $this->getModel();

		$pib_id = $pib->getPib_id();
		$pib_anio = $pib->getPib_anio();
		$pib_periodo = $pib->getPib_periodo();
		$pib_agricultura = $pib->getPib_agricultura();
		$pib_nacional = $pib->getPib_nacional();
		$pib_finsert = $pib->getPib_finsert();
		$pib_uinsert = $pib->getPib_uinsert();
		$pib_fupdate = $pib->getPib_fupdate();
		$pib_uupdate = $pib->getPib_uupdate();

		$this->data = compact(
			'pib_id',
			'pib_anio',
			'pib_periodo',
			'pib_agricultura',
			'pib_nacional',
			'pib_finsert',
			'pib_uinsert',
			'pib_fupdate',
			'pib_uupdate'
		);
	}

	public function create($pib)
	{
		$conn = $this->getConnection();
		$this->setModel($pib);
		$this->setData();

		$sql = '
			INSERT INTO pib (
				pib_id,
				pib_anio,
				pib_periodo,
				pib_agricultura,
				pib_nacional,
				pib_finsert,
				pib_uinsert,
				pib_fupdate,
				pib_uupdate
			)
			VALUES (
				"'.$this->data['pib_id'].'",
				"'.$this->data['pib_anio'].'",
				"'.$this->data['pib_periodo'].'",
				"'.$this->data['pib_agricultura'].'",
				"'.$this->data['pib_nacional'].'",
				"'.$this->data['pib_finsert'].'",
				"'.$this->data['pib_uinsert'].'",
				"'.$this->data['pib_fupdate'].'",
				"'.$this->data['pib_uupdate'].'"
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
			 pib_id,
			 pib_anio,
			 pib_periodo,
			 pib_agricultura,
			 pib_nacional,
			 pib_finsert,
			 pib_uinsert,
			 pib_fupdate,
			 pib_uupdate
			FROM pib
		';
		if(!empty($filter)){
			$sql .= ' WHERE ('. implode( $joinOperator, $filter ).')';
		}

		return $sql;
	}

}
