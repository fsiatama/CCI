<?php

require_once ('BaseAdo.php');

class Comtrade_countryAdo extends BaseAdo {

	protected function setTable()
	{
		$this->table = 'comtrade_country';
	}

	protected function setPrimaryKey()
	{
		$this->primaryKey = 'id_country';
	}

	protected function setData()
	{
		$comtrade_country = $this->getModel();

		$id_country = $comtrade_country->getId_country();
		$country = $comtrade_country->getCountry();

		$this->data = compact(
			'id_country',
			'country'
		);
	}

	public function create($comtrade_country)
	{
		$conn = $this->getConnection();
		$this->setModel($comtrade_country);
		$this->setData();

		$sql = '
			INSERT INTO comtrade_country (
				id_country,
				country
			)
			VALUES (
				"'.$this->data['id_country'].'",
				"'.$this->data['country'].'"
			)
		';
		$resultSet = $conn->Execute($sql);
		$result = $this->buildResult($resultSet, $conn->Insert_ID());

		return $result;
	}

	public function buildSelect()
	{
		$filter = [];
		$operator = $this->getOperator();
		$joinOperator = ' AND ';
		foreach($this->data as $key => $data){
			if ($data <> ''){
				if ($operator == '=') {
					$filter[] = $key . ' ' . $operator . ' "' . $data . '"';
				}
				elseif ($operator == 'IN') {
					$filter[] = $key . ' ' . $operator . '("' . $data . '")';
					$joinOperator = ' OR ';
				}
				else {
					$filter[] = $key . ' ' . $operator . ' "%' . $data . '%"';
					$joinOperator = ' OR ';
				}
			}
		}

		$sql = 'SELECT
			 id_country,
			 country
			FROM comtrade_country
		';
		if(!empty($filter)){
			$sql .= ' WHERE ('. implode( $joinOperator, $filter ).')';
		}

		//var_dump($sql);

		return $sql;
	}

}
