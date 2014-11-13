<?php
class Menu {

	private $menu_id;
	private $menu_name;
	private $menu_category_menu_id;
	private $menu_url;
	private $menu_order;
	private $menu_hidden;

	public function setMenu_id($menu_id){
		$this->menu_id = $menu_id;
	}

	public function getMenu_id(){
		return $this->menu_id;
	}

	public function setMenu_name($menu_name){
		$this->menu_name = $menu_name;
	}

	public function getMenu_name(){
		return $this->menu_name;
	}

	public function setMenu_category_menu_id($menu_category_menu_id){
		$this->menu_category_menu_id = $menu_category_menu_id;
	}

	public function getMenu_category_menu_id(){
		return $this->menu_category_menu_id;
	}

	public function setMenu_url($menu_url){
		$this->menu_url = $menu_url;
	}

	public function getMenu_url(){
		return $this->menu_url;
	}

	public function setMenu_order($menu_order){
		$this->menu_order = $menu_order;
	}

	public function getMenu_order(){
		return $this->menu_order;
	}

	public function setMenu_hidden($menu_hidden){
		$this->menu_hidden = $menu_hidden;
	}

	public function getMenu_hidden(){
		return $this->menu_hidden;
	}

}