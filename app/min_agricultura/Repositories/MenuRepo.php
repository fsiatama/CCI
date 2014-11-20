<?php

require PATH_APP.'min_agricultura/Entities/Menu.php';
require PATH_APP.'min_agricultura/Ado/MenuAdo.php';

require_once ('BaseRepo.php');

class MenuRepo extends BaseRepo {

	public function getModel()
	{
		return new Menu;
	}
	
	public function getModelAdo()
	{
		return new MenuAdo;
	}

	public function getPrimaryKey()
	{
		return 'menu_id';
	}
}	

