<?php

require PATH_APP.'min_agricultura/Entities/Correlativa.php';
require PATH_APP.'min_agricultura/Ado/CorrelativaAdo.php';
require_once ('BaseRepo.php');

class CorrelativaRepo extends BaseRepo {

	public function getModel()
	{
		return new Correlativa;
	}
	
	public function getModelAdo()
	{
		return new CorrelativaAdo;
	}

}	

