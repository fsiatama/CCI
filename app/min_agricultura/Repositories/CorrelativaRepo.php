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

	public function grid($params)
	{
		extract($params);
		$correlativa    = $this->model;
		$correlativaAdo = $this->modelAdo;
		
		/**/
		$start = ( isset($start) ) ? $start : 0;
		$limit = ( isset($limit) ) ? $limit : 30;
		$page  = ( $start==0 ) ? 1 : ( $start/$limit )+1;

		if (!empty($query)) {
			if (!empty($fullTextFields)) {
				
				$fullTextFields = json_decode($fullTextFields);
				
				foreach ($fullTextFields as $value) {
					$methodName = $this->getColumnMethodName('set', $value);
					
					if (method_exists($correlativa, $methodName)) {
						call_user_func_array([$correlativa, $methodName], compact('query'));
					}
				}
			} else {
				$correlativa->setCorrelativa_fvigente($query);
				$correlativa->setCorrelativa_decreto($query);
				$correlativa->setCorrelativa_observacion($query);
				$correlativa->setCorrelativa_origen($query);
				$correlativa->setCorrelativa_destino($query);
			}
			
		}

		$correlativaAdo->setColumns([
			'correlativa_fvigente',
			'correlativa_decreto',
			'correlativa_observacion',
			'correlativa_origen',
			'correlativa_destino',
		]);

		$result = $correlativaAdo->paginate($correlativa, 'LIKE', $limit, $page);

		return $result;
	}

}	

