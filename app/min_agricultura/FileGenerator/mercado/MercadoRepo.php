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
			empty($mercado_id) ||
			empty($mercado_nombre) ||
			empty($mercado_paises) ||
			empty($mercado_bandera) ||
			empty($mercado_uinsert) ||
			empty($mercado_finsert) ||
			empty($mercado_uupdate) ||
			empty($mercado_fupdate)
		) {
			$result = [
				'success' => false,
				'error'   => 'Incomplete data for this request.'
			];
			return $result;
		}
		$this->model->setMercado_id($mercado_id);
		$this->model->setMercado_nombre($mercado_nombre);
		$this->model->setMercado_paises($mercado_paises);
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

	public function listAll($params)
	{
		extract($params);
		$start = ( isset($start) ) ? $start : 0;
		$limit = ( isset($limit) ) ? $limit : MAXREGEXCEL;
		$page  = ( $start==0 ) ? 1 : ( $start/$limit )+1;

		if (!empty($valuesqry) && $valuesqry) {
			$query = explode('|',$query);
			$this->model->setMercado_id(implode('", "', $query));
			$this->model->setMercado_nombre(implode('", "', $query));
			$this->model->setMercado_paises(implode('", "', $query));
			$this->model->setMercado_bandera(implode('", "', $query));
			$this->model->setMercado_uinsert(implode('", "', $query));
			$this->model->setMercado_finsert(implode('", "', $query));
			$this->model->setMercado_uupdate(implode('", "', $query));
			$this->model->setMercado_fupdate(implode('", "', $query));

			return $this->modelAdo->inSearch($this->model);
		}
		else {
			$this->model->setMercado_id($query);
			$this->model->setMercado_nombre($query);
			$this->model->setMercado_paises($query);
			$this->model->setMercado_bandera($query);
			$this->model->setMercado_uinsert($query);
			$this->model->setMercado_finsert($query);
			$this->model->setMercado_uupdate($query);
			$this->model->setMercado_fupdate($query);

			return $this->modelAdo->paginate($this->model, 'LIKE', $limit, $page);
		}

	}

}
