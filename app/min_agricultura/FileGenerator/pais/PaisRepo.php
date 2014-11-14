<?php

require PATH_APP.'min_agricultura/Entities/Pais.php';
require PATH_APP.'min_agricultura/Ado/PaisAdo.php';
require_once ('BaseRepo.php');

class PaisRepo extends BaseRepo {

	public function getModel()
	{
		return new Pais;
	}
	
	public function getModelAdo()
	{
		return new PaisAdo;
	}

}	

