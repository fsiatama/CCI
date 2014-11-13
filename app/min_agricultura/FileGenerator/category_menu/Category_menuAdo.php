<?php

require 'BaseAdo.php';

class Category_menuAdo extends BaseAdo {

	protected function setTable()
	{
		$this->table = 'category_menu';
	}

	protected function setPrimaryKey()
	{
		$this->primaryKey = 'category_menu_id';
	}

	protected function setData()
	{
		$category_menu = $this->getModel();

		$category_menu_id = $category_menu->getCategory_menu_id();
		$category_menu_name = $category_menu->getCategory_menu_name();
		$category_menu_order = $category_menu->getCategory_menu_order();

		$this->data = compact(
			'category_menu_id',
			'category_menu_name',
			'category_menu_order'
		);
	}

	public function create($category_menu)
	{
		$conn = $this->getConnection();
		$this->setModel($category_menu);
		$this->setData();

		$sql = '
			INSERT INTO category_menu (
				category_menu_id,
				category_menu_name,
				category_menu_order
			)
			VALUES (
				"'.$this->data['category_menu_id'].'",
				"'.$this->data['category_menu_name'].'",
				"'.$this->data['category_menu_order'].'"
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
			 category_menu_id,
			 category_menu_name,
			 category_menu_order
			FROM category_menu
		';
		if(!empty($filter)){
			$sql .= ' WHERE ('. implode( $joinOperator, $filter ).')';
		}

		return $sql;
	}

}
