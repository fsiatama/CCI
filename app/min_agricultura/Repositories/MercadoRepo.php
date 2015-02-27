<?php

require PATH_MODELS.'Entities/Mercado.php';
require PATH_MODELS.'Ado/MercadoAdo.php';
require_once ('BaseRepo.php');

class MercadoRepo extends BaseRepo {

	public function getModel()
	{
		return new Mercado;
	}
	
	public function getModelAdo()
	{
		return new MercadoAdo;
	}

	public function getPrimaryKey()
	{
		return 'mercado_id';
	}

	public function validateModify($params)
	{
		extract($params);
		$result = $this->findPrimaryKey($mercado_id);

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

				$fullTextFields = json_decode(stripslashes($fullTextFields));

				foreach ($fullTextFields as $value) {
					$methodName = $this->getColumnMethodName('set', $value);

					if (method_exists($this->model, $methodName)) {
						call_user_func_array([$this->model, $methodName], compact('query'));
					}
				}
			} else {
				$this->model->setMercado_id($query);
				$this->model->setMercado_nombre($query);
				$this->model->setMercado_paises($query);
			}

		}
		$this->modelAdo->setColumns([
			'mercado_id',
			'mercado_nombre',
			'mercado_paises'
		]);

		$result = $this->modelAdo->paginate($this->model, 'LIKE', $limit, $page);

		return $result;
	}

	public function setData($params, $action)
	{
		extract($params);

		if ($action == 'modify') {
			$result = $this->findPrimaryKey($mercado_id);

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
			empty($mercado_nombre) ||
			empty($mercado_paises) ||
			!is_array($mercado_paises)
		) {
			$result = [
				'success' => false,
				'error'   => 'Incomplete data for this request.'
			];
			return $result;
		}

		$mercado_bandera = ( empty($mercado_bandera) ) ? '' : $mercado_bandera ;
		
		//$this->model->setMercado_id($mercado_id);
		$this->model->setMercado_nombre($mercado_nombre);
		$this->model->setMercado_paises(implode(',', $mercado_paises));
		$this->model->setMercado_bandera($mercado_bandera);

		if ($action == 'create') {
			$this->model->setMercado_uinsert($_SESSION['user_id']);
			$this->model->setMercado_finsert(Helpers::getDateTimeNow());
		} elseif ($action == 'modify') {
			$this->model->setMercado_uupdate($_SESSION['user_id']);
			$this->model->setMercado_fupdate(Helpers::getDateTimeNow());
		}
		$result = ['success' => true];
		return $result;
	}

}	

