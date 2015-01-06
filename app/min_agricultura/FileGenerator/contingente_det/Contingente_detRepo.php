<?php

require PATH_APP.'min_agricultura/Entities/Contingente_det.php';
require PATH_APP.'min_agricultura/Ado/Contingente_detAdo.php';
require_once ('BaseRepo.php');

class Contingente_detRepo extends BaseRepo {

	public function getModel()
	{
		return new Contingente_det;
	}
	
	public function getModelAdo()
	{
		return new Contingente_detAdo;
	}

	public function getPrimaryKey()
	{
		return 'contingente_det_contingente_acuerdo_det_acuerdo_id';
	}

	public function validateModify($params)
	{
		extract($params);
		$result = $this->findPrimaryKey($contingente_det_contingente_acuerdo_det_acuerdo_id);

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
			$result = $this->findPrimaryKey($contingente_det_contingente_acuerdo_det_acuerdo_id);

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
			empty($contingente_det_id) ||
			empty($contingente_det_anio_ini) ||
			empty($contingente_det_anio_fin) ||
			empty($contingente_det_peso_neto) ||
			empty($contingente_det_contingente_id) ||
			empty($contingente_det_contingente_acuerdo_det_id) ||
			empty($contingente_det_contingente_acuerdo_det_acuerdo_id)
		) {
			$result = [
				'success' => false,
				'error'   => 'Incomplete data for this request.'
			];
			return $result;
		}
		$this->model->setContingente_det_id($contingente_det_id);
		$this->model->setContingente_det_anio_ini($contingente_det_anio_ini);
		$this->model->setContingente_det_anio_fin($contingente_det_anio_fin);
		$this->model->setContingente_det_peso_neto($contingente_det_peso_neto);
		$this->model->setContingente_det_contingente_id($contingente_det_contingente_id);
		$this->model->setContingente_det_contingente_acuerdo_det_id($contingente_det_contingente_acuerdo_det_id);
		$this->model->setContingente_det_contingente_acuerdo_det_acuerdo_id($contingente_det_contingente_acuerdo_det_acuerdo_id);

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
			$this->model->setContingente_det_id(implode('", "', $query));
			$this->model->setContingente_det_anio_ini(implode('", "', $query));
			$this->model->setContingente_det_anio_fin(implode('", "', $query));
			$this->model->setContingente_det_peso_neto(implode('", "', $query));
			$this->model->setContingente_det_contingente_id(implode('", "', $query));
			$this->model->setContingente_det_contingente_acuerdo_det_id(implode('", "', $query));
			$this->model->setContingente_det_contingente_acuerdo_det_acuerdo_id(implode('", "', $query));

			return $this->modelAdo->inSearch($this->model);
		}
		else {
			$this->model->setContingente_det_id($query);
			$this->model->setContingente_det_anio_ini($query);
			$this->model->setContingente_det_anio_fin($query);
			$this->model->setContingente_det_peso_neto($query);
			$this->model->setContingente_det_contingente_id($query);
			$this->model->setContingente_det_contingente_acuerdo_det_id($query);
			$this->model->setContingente_det_contingente_acuerdo_det_acuerdo_id($query);

			return $this->modelAdo->paginate($this->model, 'LIKE', $limit, $page);
		}

	}

}
