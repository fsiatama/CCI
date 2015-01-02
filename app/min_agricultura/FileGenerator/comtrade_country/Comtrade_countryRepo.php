<?php

require PATH_APP.'min_agricultura/Entities/Comtrade_country.php';
require PATH_APP.'min_agricultura/Ado/Comtrade_countryAdo.php';
require_once ('BaseRepo.php');

class Comtrade_countryRepo extends BaseRepo {

	public function getModel()
	{
		return new Comtrade_country;
	}
	
	public function getModelAdo()
	{
		return new Comtrade_countryAdo;
	}

	public function getPrimaryKey()
	{
		return 'id_country';
	}

	public function validateModify($params)
	{
		extract($params);
		$result = $this->findPrimaryKey($id_country);

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
			$result = $this->findPrimaryKey($id_country);

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
			empty($id_country) ||
			empty($country)
		) {
			$result = [
				'success' => false,
				'error'   => 'Incomplete data for this request.'
			];
			return $result;
		}
		$this->model->setId_country($id_country);
		$this->model->setCountry($country);

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
		$this->model->setId_country(implode('", "', $query));
		$this->model->setCountry(implode('", "', $query));

			return $this->modelAdo->inSearch($this->model);
		}
		else {
		$this->model->setId_country($query);
		$this->model->setCountry($query);

			return $this->modelAdo->paginate($this->model, 'LIKE', $limit, $page);
		}

	}

}
