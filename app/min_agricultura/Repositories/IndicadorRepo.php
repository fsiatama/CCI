<?php

require PATH_APP.'min_agricultura/Entities/Indicador.php';
require PATH_APP.'min_agricultura/Ado/IndicadorAdo.php';
require_once ('BaseRepo.php');

class IndicadorRepo extends BaseRepo {

	public function getModel()
	{
		return new Indicador;
	}
	
	public function getModelAdo()
	{
		return new IndicadorAdo;
	}

	public function getPrimaryKey()
	{
		return 'indicador_id';
	}

	public function validateModify($params)
	{
		extract($params);
		$result = $this->findPrimaryKey($indicador_id);

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

	public function listUserId($params)
	{
		extract($params);
		if (empty($tipo_indicador_id)) {
			$result = array(
				'success' => false,
				'error'   => 'Incomplete data for this request.'
			);
			return $result;
		}
		$this->model->setIndicador_uinsert($_SESSION['user_id']);
		$this->model->setIndicador_tipo_indicador_id($tipo_indicador_id);

		$this->modelAdo->setColumns([
			'indicador_id',
			'indicador_nombre',
			'indicador_leaf'
		]);

		$result = $this->modelAdo->exactSearch($this->model);
		return $result;

	}

	public function setData($params, $action)
	{
		extract($params);

		if ($action == 'modify') {
			$result = $this->findPrimaryKey($indicador_id);

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

		$indicador_filtros = $this->getFiltersValue($params);

		if (
			empty($indicador_nombre) ||
			empty($indicador_tipo_indicador_id) ||
			empty($indicador_filtros)
		) {
			$result = array(
				'success' => false,
				'error'   => 'Incomplete data for this request.'
			);
			return $result;
		}

		$this->model->setIndicador_nombre($indicador_nombre);
		$this->model->setIndicador_tipo_indicador_id($indicador_tipo_indicador_id);
		$this->model->setIndicador_filtros($indicador_filtros);
		$this->model->setIndicador_leaf('1');
		
		if ($action == 'create') {
			$this->model->setIndicador_uinsert($_SESSION['user_id']);
			$this->model->setIndicador_finsert(Helpers::getDateTimeNow());
		}
		elseif ($action == 'modify') {
			$this->model->setIndicador_fupdate(Helpers::getDateTimeNow());
		}
		$result = array('success' => true);
		return $result;
	}

	public function getFiltersValue($params)
	{
		$lines = Helpers::getRequire(PATH_APP.'lib/indicador.config.php');

		$arrFiltersName = Helpers::arrayGet($lines, 'filters.'.$params['indicador_tipo_indicador_id']);

		$arrFiltersValue = [];

		foreach ($arrFiltersName as $key) {
			if (array_key_exists($key, $params)) {
				
				if (is_array($params[$key])) {
					$arrFiltersValue[] = $key . ':' .implode(',', $params[$key]);
				} else {
					$arrFiltersValue[] = $key . ':' .$params[$key];
				}
				
			} else {
				//retorna una cadena vacia ya que los filtros no estan completos
				return '';
			}
		}
		return implode('||', $arrFiltersValue);
	}

}	

