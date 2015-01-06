<?php

require PATH_APP.'min_agricultura/Entities/Contingente.php';
require PATH_APP.'min_agricultura/Ado/ContingenteAdo.php';
require_once ('BaseRepo.php');

class ContingenteRepo extends BaseRepo {

	public function getModel()
	{
		return new Contingente;
	}
	
	public function getModelAdo()
	{
		return new ContingenteAdo;
	}

	public function getPrimaryKey()
	{
		return 'contingente_acuerdo_det_acuerdo_id';
	}

	public function validateModify($params)
	{
		extract($params);
		$result = $this->findPrimaryKey($contingente_acuerdo_det_acuerdo_id);

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
			$result = $this->findPrimaryKey($contingente_acuerdo_det_acuerdo_id);

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
			empty($contingente_id) ||
			empty($contingente_id_pais) ||
			empty($contingente_acumulado_pais) ||
			empty($contingente_mcontingente) ||
			empty($contingente_desc) ||
			empty($contingente_acuerdo_det_id) ||
			empty($contingente_acuerdo_det_acuerdo_id)
		) {
			$result = [
				'success' => false,
				'error'   => 'Incomplete data for this request.'
			];
			return $result;
		}
		$this->model->setContingente_id($contingente_id);
		$this->model->setContingente_id_pais($contingente_id_pais);
		$this->model->setContingente_acumulado_pais($contingente_acumulado_pais);
		$this->model->setContingente_mcontingente($contingente_mcontingente);
		$this->model->setContingente_desc($contingente_desc);
		$this->model->setContingente_acuerdo_det_id($contingente_acuerdo_det_id);
		$this->model->setContingente_acuerdo_det_acuerdo_id($contingente_acuerdo_det_acuerdo_id);

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
			$this->model->setContingente_id(implode('", "', $query));
			$this->model->setContingente_id_pais(implode('", "', $query));
			$this->model->setContingente_acumulado_pais(implode('", "', $query));
			$this->model->setContingente_mcontingente(implode('", "', $query));
			$this->model->setContingente_desc(implode('", "', $query));
			$this->model->setContingente_acuerdo_det_id(implode('", "', $query));
			$this->model->setContingente_acuerdo_det_acuerdo_id(implode('", "', $query));

			return $this->modelAdo->inSearch($this->model);
		}
		else {
			$this->model->setContingente_id($query);
			$this->model->setContingente_id_pais($query);
			$this->model->setContingente_acumulado_pais($query);
			$this->model->setContingente_mcontingente($query);
			$this->model->setContingente_desc($query);
			$this->model->setContingente_acuerdo_det_id($query);
			$this->model->setContingente_acuerdo_det_acuerdo_id($query);

			return $this->modelAdo->paginate($this->model, 'LIKE', $limit, $page);
		}

	}

}
