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

	public function listAll($params)
	{
		extract($params);
		$start = ( isset($start) ) ? $start : 0;
		$limit = ( isset($limit) ) ? $limit : MAXREGEXCEL;
		$page  = ( $start==0 ) ? 1 : ( $start/$limit )+1;

		if (!empty($valuesqry) && $valuesqry) {
			$query = explode('|',$query);
			$this->model->setUpdate_info_id(implode('", "', $query));
			$this->model->setUpdate_info_product(implode('", "', $query));
			$this->model->setUpdate_info_trade(implode('", "', $query));
			$this->model->setUpdate_info_from(implode('", "', $query));
			$this->model->setUpdate_info_to(implode('", "', $query));

			return $this->modelAdo->inSearch($this->model);
		}
		else {
			$this->model->setUpdate_info_id($query);
			$this->model->setUpdate_info_product($query);
			$this->model->setUpdate_info_trade($query);
			$this->model->setUpdate_info_from($query);
			$this->model->setUpdate_info_to($query);

			return $this->modelAdo->paginate($this->model, 'LIKE', $limit, $page);
		}

	}

}
