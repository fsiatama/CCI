<?php

require 'BaseAdo.php';

class UserAdo extends BaseAdo {

	protected function setTable()
	{
		$this->table = 'user';
	}

	protected function setPrimaryKey()
	{
		$this->primaryKey = 'user_id';
	}

	protected function setData()
	{
		$user = $this->getModel();

		$user_id        = $user->getUser_id();
		$user_full_name = $user->getUser_full_name();
		$user_email     = $user->getUser_email();
		$user_password  = $user->getUser_password();
		$user_active    = $user->getUser_active();
		$user_session   = $user->getUser_session();
		$user_uinsert   = $user->getUser_uinsert();
		$user_finsert   = $user->getUser_finsert();
		$user_fupdate   = $user->getUser_fupdate();

		$this->data = compact(
			'user_id',
			'user_full_name',
			'user_email',
			'user_password',
			'user_active',
			'user_session',
			'user_uinsert',
			'user_finsert',
			'user_fupdate'
		);
	}

	public function create($user)
	{
		$conn = $this->getConnection();
		$this->setModel($user);
		$this->setData();

		$sql = "
			INSERT INTO user (
				user_id,
				user_full_name,
				user_email,
				user_password,
				user_uinsert,
				user_finsert,
				user_fupdate
			)
			VALUES (
				'".$this->data['user_id']."',
				'".$this->data['user_full_name']."',
				'".$this->data['user_email']."',
				'".$this->data['user_password']."',
				'".$this->data['user_uinsert']."',
				'".$this->data['user_finsert']."',
				'".$this->data['user_fupdate']."'
			)
		";
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
					$filter[] = $key . ' ' . $operator . ' \'' . $data . '\'';
				}
				elseif ($operator == 'IN') {
					$filter[] = $key . ' ' . $operator . '(\'' . $data . '\')';
				}
				else {
					$filter[] = $key . ' ' . $operator . ' \'%' . $data . '%\'';
					$joinOperator = ' OR ';
				}
			}
		}

		$sql = 'SELECT
			 user_id,
			 user_full_name,
			 user_email,
			 user_password,
			 user_uinsert,
			 user_finsert,
			 user_fupdate 
			FROM user
		';
		if(!empty($filter)){
			$sql .= ' WHERE ('. implode( $joinOperator, $filter ).')';
		}

		return $sql;
	}

}
