<?php

require PATH_APP.'min_agricultura/Entities/Mercado.php';
require PATH_APP.'min_agricultura/Ado/MercadoAdo.php';
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
			$this->model->setMercado_uinsert($mercado_uinsert);
			$this->model->setMercado_finsert($mercado_finsert);
			$this->model->setMercado_uupdate($mercado_uupdate);
			$this->model->setMercado_fupdate($mercado_fupdate);
		

		if ($action == 'create') {
		}
		elseif ($action == 'modify') {
		}
		$result = ['success' => true];
		return $result;
	}

}	

