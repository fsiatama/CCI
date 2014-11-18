<?php

require_once ('BaseAdo.php');

class ProfileAdo extends BaseAdo {

	protected function setTable()
	{
		$this->table = 'profile';
	}

	protected function setPrimaryKey()
	{
		$this->primaryKey = 'profile_id';
	}

	protected function setData()
	{
		$profile = $this->getModel();

		$profile_id = $profile->getProfile_id();
		$profile_name = $profile->getProfile_name();

		$this->data = compact(
			'profile_id',
			'profile_name'
		);
	}

	public function create($profile)
	{
		$conn = $this->getConnection();
		$this->setModel($profile);
		$this->setData();

		$sql = '
			INSERT INTO profile (
				profile_id,
				profile_name
			)
			VALUES (
				"'.$this->data['profile_id'].'",
				"'.$this->data['profile_name'].'"
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
			 profile_id,
			 profile_name
			FROM profile
		';
		if(!empty($filter)){
			$sql .= ' WHERE ('. implode( $joinOperator, $filter ).')';
		}

		return $sql;
	}

}
