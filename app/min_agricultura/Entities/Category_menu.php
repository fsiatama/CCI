<?php
class Category_menu {

	private $category_menu_id;
	private $category_menu_name;
	private $category_menu_order;

	public function setCategory_menu_id($category_menu_id){
		$this->category_menu_id = $category_menu_id;
	}

	public function getCategory_menu_id(){
		return $this->category_menu_id;
	}

	public function setCategory_menu_name($category_menu_name){
		$this->category_menu_name = $category_menu_name;
	}

	public function getCategory_menu_name(){
		return $this->category_menu_name;
	}

	public function setCategory_menu_order($category_menu_order){
		$this->category_menu_order = $category_menu_order;
	}

	public function getCategory_menu_order(){
		return $this->category_menu_order;
	}

}