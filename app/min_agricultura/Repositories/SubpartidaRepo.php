<?php

require PATH_MODELS.'Entities/Subpartida.php';
require PATH_MODELS.'Ado/SubpartidaAdo.php';
require PATH_MODELS.'Repositories/SectorRepo.php';
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

	public function listAgriculture($params)
	{
		extract($params);

		$linesConfig = Helpers::getRequire(PATH_APP.'lib/indicador.config.php');

		$sector_id  = Helpers::arrayGet( $linesConfig, 'sectorIdAgriculture' );
		$sectorRepo = new SectorRepo;
		$result     = $sectorRepo->findPrimaryKey($sector_id);

		if (!$result['success']) {
			return $result;
		}

		$row      = array_shift( $result['data'] );
		$products = $row['sector_productos'];

		$subpartida->setId_subpartida($query);
		$subpartida->setSubpartida($query);

		//metodo incompleto, no debe ser inplementado

		
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

