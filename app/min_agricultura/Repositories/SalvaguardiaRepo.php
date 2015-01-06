<?php

require PATH_APP.'min_agricultura/Entities/Salvaguardia.php';
require PATH_APP.'min_agricultura/Ado/SalvaguardiaAdo.php';
require_once ('BaseRepo.php');

class SalvaguardiaRepo extends BaseRepo {

	public function getModel()
	{
		return new Salvaguardia;
	}
	
	public function getModelAdo()
	{
		return new SalvaguardiaAdo;
	}

	public function getPrimaryKey()
	{
		return 'salvaguardia_contingente_acuerdo_det_acuerdo_id';
	}

	public function validateModify($params)
	{
		extract($params);
		$result = $this->findPrimaryKey($salvaguardia_contingente_acuerdo_det_acuerdo_id);

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
			$result = $this->findPrimaryKey($salvaguardia_contingente_acuerdo_det_acuerdo_id);

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
			empty($salvaguardia_id) ||
			empty($salvaguardia_msalvaguardia) ||
			empty($salvaguardia_contingente_id) ||
			empty($salvaguardia_contingente_acuerdo_det_id) ||
			empty($salvaguardia_contingente_acuerdo_det_acuerdo_id)
		) {
			$result = [
				'success' => false,
				'error'   => 'Incomplete data for this request.'
			];
			return $result;
		}
		$this->model->setSalvaguardia_id($salvaguardia_id);
		$this->model->setSalvaguardia_msalvaguardia($salvaguardia_msalvaguardia);
		$this->model->setSalvaguardia_contingente_id($salvaguardia_contingente_id);
		$this->model->setSalvaguardia_contingente_acuerdo_det_id($salvaguardia_contingente_acuerdo_det_id);
		$this->model->setSalvaguardia_contingente_acuerdo_det_acuerdo_id($salvaguardia_contingente_acuerdo_det_acuerdo_id);

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
			$this->model->setSalvaguardia_id(implode('", "', $query));
			$this->model->setSalvaguardia_msalvaguardia(implode('", "', $query));
			$this->model->setSalvaguardia_contingente_id(implode('", "', $query));
			$this->model->setSalvaguardia_contingente_acuerdo_det_id(implode('", "', $query));
			$this->model->setSalvaguardia_contingente_acuerdo_det_acuerdo_id(implode('", "', $query));

			return $this->modelAdo->inSearch($this->model);
		}
		else {
			$this->model->setSalvaguardia_id($query);
			$this->model->setSalvaguardia_msalvaguardia($query);
			$this->model->setSalvaguardia_contingente_id($query);
			$this->model->setSalvaguardia_contingente_acuerdo_det_id($query);
			$this->model->setSalvaguardia_contingente_acuerdo_det_acuerdo_id($query);

			return $this->modelAdo->paginate($this->model, 'LIKE', $limit, $page);
		}

	}

}
