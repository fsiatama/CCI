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
		return 'id_posicion';
	}

	public function setData($params, $action)
	{

	}

	public function listAll($params)
	{
		extract($params);
		$posicion    = $this->getModel();
		$posicionAdo = $this->getModelAdo();

		$start = ( isset($start) ) ? $start : 0;
		$limit = ( isset($limit) ) ? $limit : 30;
		$page  = ( $start==0 ) ? 1 : ( $start/$limit )+1;

		if (!empty($valuesqry) && $valuesqry) {
			$query = explode('|',$query);
			$posicion->setId_posicion(implode('", "', $query));
			$posicion->setId_partida(implode('", "', $query));
			$posicion->setId_subpartida(implode('", "', $query));
			$posicion->setId_capitulo(implode('", "', $query));

			return $posicionAdo->inSearch($posicion);
		}
		else {
			$posicion->setId_posicion($query);
			$posicion->setPosicion($query);
			if (!empty($selected)) {
				$posicionAdo->setSelectedValues($selected);
			}
			return $posicionAdo->paginate($posicion, 'LIKE', $limit, $page);
		}

	}

	public function listInAgreement($params)
	{
		extract($params);

		$trade     = (empty($trade)) ? 'impo' : $trade ;
		$countries = ( empty($countries) || ! is_array($countries) ) ? [] : $countries ;
		//productos solo deberia venir uno
		$country   = array_shift($countries);

		if ( empty($query) &&  empty($country) ) {
			return [
				'success' => true,
				'data'    => [],
				'total'   => 0,
			];
		}


		$this->model->setId_posicion($query);
		$this->model->setPosicion($query);
		
		return $this->modelAdo->listInAgreement( $this->model, $trade, $country );


	}

}	

