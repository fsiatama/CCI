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

	public function getPrimaryKey()
	{
		return 'correlativa_id';
	}

	public function validateModify($params)
	{
		extract($params);
		$result = $this->findPrimaryKey($correlativa_id);

		if (!$result['success']) {
			$result = [
				'success'  => false,
				'closeTab' => true,
				'tab'      => 'tab-'.$module,
				'error'    => $result['error']
			];
		}
		return $result;
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
			'correlativa_id',
			'correlativa_fvigente',
			'correlativa_decreto',
			'correlativa_observacion',
			'correlativa_origen',
			'correlativa_destino',
		]);

		$result = $correlativaAdo->paginate($correlativa, 'LIKE', $limit, $page);

		return $result;
	}

	public function setData($params, $action)
	{
		extract($params);

		if ($action == 'modify') {
			$result = $this->findPrimaryKey($correlativa_id);

			if (!$result['success']) {
				$result = [
					'success'  => false,
					'closeTab' => true,
					'tab'      => 'tab-'.$module,
					'error'    => $result['error']
				];
				return $result;
			}
		}

		if (
			empty($correlativa_fvigente) || 
			empty($correlativa_decreto) || 
			empty($correlativa_observacion) ||
			empty($correlativa_origen) || 
			!is_array($correlativa_origen) ||
			empty($correlativa_destino) || 
			!is_array($correlativa_destino)
		) {
			$result = array(
				'success' => false,
				'error'   => 'Incomplete data for this request.'
			);
			return $result;
		}

		$this->model->setCorrelativa_fvigente($correlativa_fvigente);
		$this->model->setCorrelativa_decreto($correlativa_decreto);
		$this->model->setCorrelativa_observacion($correlativa_observacion);
		$this->model->setCorrelativa_origen(implode(',', $correlativa_origen));
		$this->model->setCorrelativa_destino(implode(',', $correlativa_destino));
		if ($action == 'create') {
			$this->model->setCorrelativa_uinsert($_SESSION['user_id']);
			$this->model->setCorrelativa_finsert(Helpers::getDateTimeNow());
		}
		elseif ($action == 'modify') {
			$this->model->setCorrelativa_uupdate($_SESSION['user_id']);
			$this->model->setCorrelativa_fupdate(Helpers::getDateTimeNow());
		}
		$result = array('success' => true);
		return $result;
	}

	public function create($params)
	{
		$result = $this->setData($params, 'create');
		if (!$result['success']) {
			return $result;
		}

		$result = $this->modelAdo->create($this->model);
		if ($result['success']) {
			return ['success' => true];
		}
		return $result;
	}

	public function modify($params)
	{
		$result = $this->setData($params, 'modify');
		if (!$result['success']) {
			return $result;
		}

		$result = $this->modelAdo->update($this->model);
		if ($result['success']) {
			return ['success' => true];
		}
		return $result;
	}

	public function delete($params)
	{
		extract($params);
		$result = $this->findPrimaryKey($correlativa_id);

		if ($result['success']) {
			$result = $this->modelAdo->delete($this->model);
		}

		return $result;
	}

}	

