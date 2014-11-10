<?php

require PATH_APP.'min_agricultura/Ado/SessionAdo.php';
require_once ('BaseRepo.php');

class UserRepo extends BaseRepo {

	public function getModel()
	{
		return new Session;
	}
	
	public function getModelAdo()
	{
		return new SessionAdo;
	}

}	

