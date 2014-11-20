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

	public function getPrimaryKey()
	{
		return 'posicion_id';
	}

	public function listAll($params)
	{
		extract($params);
		$posicion    = $this->model;
		$posicionAdo = $this->modelAdo;

		$start = ( isset($start) ) ? $start : 0;
		$limit = ( isset($limit) ) ? $limit : 30;
		$page  = ( $start==0 ) ? 1 : ( $start/$limit )+1;

		if ($valuesqry) {
			$query = explode('|',$query);
			$posicion->setPosicion_id(implode('", "', $query));
			return $posicionAdo->inSearch($posicion);
		}
		else {
			$posicion->setPosicion_id($query);
			$posicion->setPosicion($query);
			return $posicionAdo->paginate($posicion, 'LIKE', $limit, $page);
		}

	}

}	

