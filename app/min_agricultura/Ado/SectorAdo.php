<?php

require_once ('BaseAdo.php');

class SectorAdo extends BaseAdo {

	protected function setTable()
	{
		$this->table = 'sector';
	}

	protected function setPrimaryKey()
	{
		$this->primaryKey = 'sector_id';
	}

	protected function setData()
	{
		$sector = $this->getModel();

		$sector_id = $sector->getSector_id();
		$sector_nombre = $sector->getSector_nombre();
		$sector_productos = $sector->getSector_productos();
		$sector_uinsert = $sector->getSector_uinsert();
		$sector_finsert = $sector->getSector_finsert();
		$sector_uupdate = $sector->getSector_uupdate();
		$sector_fupdate = $sector->getSector_fupdate();

		$this->data = compact(
			'sector_id',
			'sector_nombre',
			'sector_productos',
			'sector_uinsert',
			'sector_finsert',
			'sector_uupdate',
			'sector_fupdate'
		);
	}

	public function create($sector)
	{
		$conn = $this->getConnection();
		$this->setModel($sector);
		$this->setData();

		$sql = '
			INSERT INTO sector (
				sector_id,
				sector_nombre,
				sector_productos,
				sector_uinsert,
				sector_finsert,
				sector_uupdate,
				sector_fupdate
			)
			VALUES (
				"'.$this->data['sector_id'].'",
				"'.$this->data['sector_nombre'].'",
				"'.$this->data['sector_productos'].'",
				"'.$this->data['sector_uinsert'].'",
				"'.$this->data['sector_finsert'].'",
				"'.$this->data['sector_uupdate'].'",
				"'.$this->data['sector_fupdate'].'"
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
			 sector_id,
			 sector_nombre,
			 sector_productos,
			 sector_uinsert,
			 sector_finsert,
			 sector_uupdate,
			 sector_fupdate
			FROM sector
		';
		if(!empty($filter)){
			$sql .= ' WHERE ('. implode( $joinOperator, $filter ).')';
		}

		return $sql;
	}

}
