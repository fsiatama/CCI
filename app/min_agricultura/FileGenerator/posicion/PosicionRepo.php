<?php

require PATH_APP.'min_agricultura/Entities/Posicion.php';
require PATH_APP.'min_agricultura/Ado/PosicionAdo.php';
require_once ('BaseRepo.php');

class PosicionRepo extends BaseRepo {

	public function getModel()
	{
		return new Posicion;
	}
	
	public function getModelAdo()
	{
		return new PosicionAdo;
	}

}	

