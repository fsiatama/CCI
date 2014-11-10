<?php

require PATH_APP.'min_agricultura/Ado/UserAdo.php';
require_once ('BaseRepo.php');

class UserRepo extends BaseRepo {

	public function getModel()
	{
		return new User;
	}
	
	public function getModelAdo()
	{
		return new UserAdo;
	}

}	

