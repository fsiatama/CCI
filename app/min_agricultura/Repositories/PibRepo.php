<?php

require PATH_APP.'min_agricultura/Entities/Pib.php';
require PATH_APP.'min_agricultura/Ado/PibAdo.php';
require_once ('BaseRepo.php');

class PibRepo extends BaseRepo {

	public function getModel()
	{
		return new Pib;
	}
	
	public function getModelAdo()
	{
		return new PibAdo;
	}

	public function getPrimaryKey()
	{
		return 'pib_id';
	}

	public function validateModify($params)
	{
		extract($params);
		$result = $this->findPrimaryKey($pib_id);

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

		$start = ( isset($start) ) ? $start : 0;
		$limit = ( isset($limit) ) ? $limit : 30;
		$page  = ( $start==0 ) ? 1 : ( $start/$limit )+1;

		if (!empty($query)) {
			if (!empty($fullTextFields)) {
				
				$fullTextFields = json_decode($fullTextFields);
				
				foreach ($fullTextFields as $value) {
					$methodName = $this->getColumnMethodName('set', $value);
					
					if (method_exists($this->model, $methodName)) {
						call_user_func_array([$this->model, $methodName], compact('query'));
					}
				}
			} else {
				$this->model->setPib_id($query);
				$this->model->setPib_anio($query);
				$this->model->setPib_periodo($query);
				$this->model->setPib_valor($query);
			}
			
		}
		$this->modelAdo->setColumns([
			'pib_id',
			'pib_anio',
			'pib_periodo',
			'pib_periodo_title',
			'pib_valor',
		]);

		$result = $this->modelAdo->paginate($this->model, 'LIKE', $limit, $page);

		return $result;
	}

	public function setData($params, $action)
	{
		extract($params);

		if ($action == 'modify') {
			$result = $this->findPrimaryKey($pib_id);

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
			empty($pib_anio) ||
			empty($pib_periodo) ||
			empty($pib_valor)
		) {
			$result = array(
				'success' => false,
				'error'   => 'Incomplete data for this request.'
			);
			return $result;
		}

		$this->model->setPib_id($pib_id);
		$this->model->setPib_anio($pib_anio);
		$this->model->setPib_periodo($pib_periodo);
		$this->model->setPib_valor($pib_valor);

		if ($action == 'create') {
			$this->model->setPib_finsert(Helpers::getDateTimeNow());
			$this->model->setPib_uinsert($_SESSION['user_id']);
		}
		elseif ($action == 'modify') {
			$this->model->setPib_fupdate(Helpers::getDateTimeNow());
			$this->model->setPib_uupdate($_SESSION['user_id']);
		}
		$result = array('success' => true);
		return $result;
	}

}	

