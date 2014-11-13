<?php

require_once ('BaseAdo.php');

class MenuAdo extends BaseAdo {

	protected function setTable()
	{
		$this->table = 'menu';
	}

	protected function setPrimaryKey()
	{
		$this->primaryKey = 'menu_id';
	}

	protected function setData()
	{
		$menu = $this->getModel();

		$menu_id = $menu->getMenu_id();
		$menu_name = $menu->getMenu_name();
		$menu_category_menu_id = $menu->getMenu_category_menu_id();
		$menu_url = $menu->getMenu_url();
		$menu_order = $menu->getMenu_order();
		$menu_hidden = $menu->getMenu_hidden();

		$this->data = compact(
			'menu_id',
			'menu_name',
			'menu_category_menu_id',
			'menu_url',
			'menu_order',
			'menu_hidden'
		);
	}

	public function create($menu)
	{
		$conn = $this->getConnection();
		$this->setModel($menu);
		$this->setData();

		$sql = '
			INSERT INTO menu (
				menu_id,
				menu_name,
				menu_category_menu_id,
				menu_url,
				menu_order,
				menu_hidden
			)
			VALUES (
				"'.$this->data['menu_id'].'",
				"'.$this->data['menu_name'].'",
				"'.$this->data['menu_category_menu_id'].'",
				"'.$this->data['menu_url'].'",
				"'.$this->data['menu_order'].'",
				"'.$this->data['menu_hidden'].'"
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
			 menu_id,
			 menu_name,
			 menu_category_menu_id,
			 menu_url,
			 menu_order,
			 menu_hidden
			FROM menu
		';
		if(!empty($filter)){
			$sql .= ' WHERE ('. implode( $joinOperator, $filter ).')';
		}

		$sql .= ' ORDER BY menu_order';

		return $sql;
	}

}
