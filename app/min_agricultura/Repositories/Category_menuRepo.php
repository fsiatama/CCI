<?php

require PATH_APP.'min_agricultura/Entities/Category_menu.php';
require PATH_APP.'min_agricultura/Ado/Category_menuAdo.php';
require_once ('BaseRepo.php');

class Category_menuRepo extends BaseRepo {

	public function getModel()
	{
		return new Category_menu;
	}
	
	public function getModelAdo()
	{
		return new Category_menuAdo;
	}

	public function listAll()
	{
		$result = $this->modelAdo->exactSearch($this->model);
		return $result;
	}

}	

