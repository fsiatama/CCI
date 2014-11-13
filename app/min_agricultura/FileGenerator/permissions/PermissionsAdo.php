<?php

require_once ('BaseAdo.php');

class PermissionsAdo extends BaseAdo {

	protected function setTable()
	{
		$this->table = 'permissions';
	}

	protected function setPrimaryKey()
	{
		$this->primaryKey = 'permissions_id';
	}

	protected function setData()
	{
		$permissions = $this->getModel();

		$permissions_id = $permissions->getPermissions_id();
		$permissions_profile_id = $permissions->getPermissions_profile_id();
		$permissions_menu_id = $permissions->getPermissions_menu_id();
		$permissions_list = $permissions->getPermissions_list();
		$permissions_modify = $permissions->getPermissions_modify();
		$permissions_create = $permissions->getPermissions_create();
		$permissions_delete = $permissions->getPermissions_delete();
		$permissions_export = $permissions->getPermissions_export();

		$this->data = compact(
			'permissions_id',
			'permissions_profile_id',
			'permissions_menu_id',
			'permissions_list',
			'permissions_modify',
			'permissions_create',
			'permissions_delete',
			'permissions_export'
		);
	}

	public function create($permissions)
	{
		$conn = $this->getConnection();
		$this->setModel($permissions);
		$this->setData();

		$sql = '
			INSERT INTO permissions (
				permissions_id,
				permissions_profile_id,
				permissions_menu_id,
				permissions_list,
				permissions_modify,
				permissions_create,
				permissions_delete,
				permissions_export
			)
			VALUES (
				"'.$this->data['permissions_id'].'",
				"'.$this->data['permissions_profile_id'].'",
				"'.$this->data['permissions_menu_id'].'",
				"'.$this->data['permissions_list'].'",
				"'.$this->data['permissions_modify'].'",
				"'.$this->data['permissions_create'].'",
				"'.$this->data['permissions_delete'].'",
				"'.$this->data['permissions_export'].'"
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
			 permissions_id,
			 permissions_profile_id,
			 permissions_menu_id,
			 permissions_list,
			 permissions_modify,
			 permissions_create,
			 permissions_delete,
			 permissions_export
			FROM permissions
		';
		if(!empty($filter)){
			$sql .= ' WHERE ('. implode( $joinOperator, $filter ).')';
		}

		return $sql;
	}

}
