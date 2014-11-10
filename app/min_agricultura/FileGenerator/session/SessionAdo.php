<?php

require 'BaseAdo.php';

class SessionAdo extends BaseAdo {

	protected function setTable()
	{
		$this->table = 'session';
	}

	protected function setPrimaryKey()
	{
		$this->primaryKey = 'session_user_id';
	}

	protected function setData()
	{
		$session = $this->getModel();

		$session_user_id = $session->getSession_user_id();
		$session_php_id = $session->getSession_php_id();
		$session_date = $session->getSession_date();
		$session_active = $session->getSession_active();

		$this->data = compact(
			'session_user_id',
			'session_php_id',
			'session_date',
			'session_active'
		);
	}

	public function create($session)
	{
		$conn = $this->getConnection();
		$this->setModel($session);
		$this->setData();

		$sql = '
			INSERT INTO user (
				session_user_id,
				session_php_id,
				session_date,
				session_active
			)
			VALUES (
				"'.$this->data['session_user_id'].'",
				"'.$this->data['session_php_id'].'",
				"'.$this->data['session_date'].'",
				"'.$this->data['session_active'].'"
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
			 session_user_id,
			 session_php_id,
			 session_date,
			 session_active
			FROM user
		';
		if(!empty($filter)){
			$sql .= ' WHERE ('. implode( $joinOperator, $filter ).')';
		}

		return $sql;
	}

}
