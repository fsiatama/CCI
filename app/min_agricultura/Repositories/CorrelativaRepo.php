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

	public function create($params)
	{
		extract($params);
		$correlativa    = $this->model;
		$correlativaAdo = $this->modelAdo;

		if (
			empty($correlativa_fvigente) || 
			empty($correlativa_decreto) || 
			empty($correlativa_observacion) ||
			empty($correlativa_origen) ||
			empty($correlativa_destino)
		) {
			$result = array(
				'success' => false,
				'error'   => 'Incomplete data for this request.'
			);
			return $result;
		}

		$correlativa->setCorrelativa_fvigente($correlativa_fvigente);
		$correlativa->setCorrelativa_decreto($correlativa_decreto);
		$correlativa->setCorrelativa_observacion($correlativa_observacion);
		$correlativa->setCorrelativa_origen(implode(',', $correlativa_origen));
		$correlativa->setCorrelativa_destino(implode(',', $correlativa_destino));
		$correlativa->setCorrelativa_uinsert($_SESSION['user_id']);
		$correlativa->setCorrelativa_finsert(Helpers::getDateTimeNow());

		$result = $correlativaAdo->create($correlativa);
		if ($result['success']) {
			return ['success' => true];
		}
	}

}	

