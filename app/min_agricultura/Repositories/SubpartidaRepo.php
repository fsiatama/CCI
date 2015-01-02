<?php

require PATH_APP.'min_agricultura/Entities/Subpartida.php';
require PATH_APP.'min_agricultura/Ado/SubpartidaAdo.php';
require_once ('BaseRepo.php');

class SubpartidaRepo extends BaseRepo {

	public function getModel()
	{
		return new Subpartida;
	}
	
	public function getModelAdo()
	{
		return new SubpartidaAdo;
	}

	public function getPrimaryKey()
	{
		return 'id_subpartida';
	}

	public function setData($params, $action)
	{
		
	}

	public function listAll($params)
	{
		extract($params);
		$subpartida    = $this->getModel();
		$subpartidaAdo = $this->getModelAdo();

		$start = ( isset($start) ) ? $start : 0;
		$limit = ( isset($limit) ) ? $limit : 30;
		$page  = ( $start==0 ) ? 1 : ( $start/$limit )+1;

		if (!empty($valuesqry) && $valuesqry) {
			$query = explode('|',$query);
			$subpartida->setId_partida(implode('", "', $query));
			$subpartida->setId_subpartida(implode('", "', $query));
			$subpartida->setId_capitulo(implode('", "', $query));

			return $subpartidaAdo->inSearch($subpartida);
		}
		else {
			$subpartida->setId_subpartida($query);
			$subpartida->setSubpartida($query);
			if (!empty($selected)) {
				$subpartidaAdo->setSelectedValues($selected);
			}
			return $subpartidaAdo->paginate($subpartida, 'LIKE', $limit, $page);
		}

	}

}	

