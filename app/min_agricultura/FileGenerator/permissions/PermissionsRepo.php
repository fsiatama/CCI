<?php

require PATH_APP.'min_agricultura/Entities/Permissions.php';
require PATH_APP.'min_agricultura/Ado/PermissionsAdo.php';
require_once ('BaseRepo.php');

class PermissionsRepo extends BaseRepo {

	public function getModel()
	{
		return new Permissions;
	}
	
	public function getModelAdo()
	{
		return new PermissionsAdo;
	}

}	

