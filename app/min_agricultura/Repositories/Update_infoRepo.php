<?php

require PATH_MODELS.'Entities/Update_info.php';
require PATH_MODELS.'Ado/Update_infoAdo.php';
require_once ('BaseRepo.php');

class Update_infoRepo extends BaseRepo {

	public function getModel()
	{
		return new Update_info;
	}
	
	public function getModelAdo()
	{
		return new Update_infoAdo;
	}

	public function getPrimaryKey()
	{
		return 'update_info_id';
	}

	public function validateModify($params)
	{
		extract($params);
		$result = $this->findPrimaryKey($update_info_id);

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
			$result = $this->findPrimaryKey($update_info_id);

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
			empty($update_info_id) ||
			empty($update_info_product) ||
			empty($update_info_trade) ||
			empty($update_info_from) ||
			empty($update_info_to)
		) {
			$result = [
				'success' => false,
				'error'   => 'Incomplete data for this request.'
			];
			return $result;
		}
		$this->model->setUpdate_info_id($update_info_id);
		$this->model->setUpdate_info_product($update_info_product);
		$this->model->setUpdate_info_trade($update_info_trade);
		$this->model->setUpdate_info_from($update_info_from);
		$this->model->setUpdate_info_to($update_info_to);

		if ($action == 'create') {
		} elseif ($action == 'modify') {
		}
		$result = ['success' => true];
		return $result;
	}

	public function updateInfo($params)
	{
		extract($params);

		$product = ( !empty($product) ) ? $product : 'aduanas';
		$trade   = ( !empty($trade) ) ? $trade : 'impo';

		$this->model->setUpdate_info_product($product);
		$this->model->setUpdate_info_trade($trade);

		return $this->modelAdo->exactSearch($this->model);

	}

}
