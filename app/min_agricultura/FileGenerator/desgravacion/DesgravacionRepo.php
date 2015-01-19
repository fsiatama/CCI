<?php

require PATH_MODELS.'Entities/Desgravacion.php';
require PATH_MODELS.'Ado/DesgravacionAdo.php';
require_once ('BaseRepo.php');

class DesgravacionRepo extends BaseRepo {

	public function getModel()
	{
		return new Desgravacion;
	}
	
	public function getModelAdo()
	{
		return new DesgravacionAdo;
	}

	public function getPrimaryKey()
	{
		return 'desgravacion_acuerdo_det_acuerdo_id';
	}

	public function validateModify($params)
	{
		extract($params);
		$result = $this->findPrimaryKey($desgravacion_acuerdo_det_acuerdo_id);

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
			$result = $this->findPrimaryKey($desgravacion_acuerdo_det_acuerdo_id);

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
			empty($desgravacion_id) ||
			empty($desgravacion_id_pais) ||
			empty($desgravacion_mdesgravacion) ||
			empty($desgravacion_desc) ||
			empty($desgravacion_acuerdo_det_id) ||
			empty($desgravacion_acuerdo_det_acuerdo_id)
		) {
			$result = [
				'success' => false,
				'error'   => 'Incomplete data for this request.'
			];
			return $result;
		}
		$this->model->setDesgravacion_id($desgravacion_id);
		$this->model->setDesgravacion_id_pais($desgravacion_id_pais);
		$this->model->setDesgravacion_mdesgravacion($desgravacion_mdesgravacion);
		$this->model->setDesgravacion_desc($desgravacion_desc);
		$this->model->setDesgravacion_acuerdo_det_id($desgravacion_acuerdo_det_id);
		$this->model->setDesgravacion_acuerdo_det_acuerdo_id($desgravacion_acuerdo_det_acuerdo_id);

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
			$this->model->setDesgravacion_id(implode('", "', $query));
			$this->model->setDesgravacion_id_pais(implode('", "', $query));
			$this->model->setDesgravacion_mdesgravacion(implode('", "', $query));
			$this->model->setDesgravacion_desc(implode('", "', $query));
			$this->model->setDesgravacion_acuerdo_det_id(implode('", "', $query));
			$this->model->setDesgravacion_acuerdo_det_acuerdo_id(implode('", "', $query));

			return $this->modelAdo->inSearch($this->model);
		}
		else {
			$this->model->setDesgravacion_id($query);
			$this->model->setDesgravacion_id_pais($query);
			$this->model->setDesgravacion_mdesgravacion($query);
			$this->model->setDesgravacion_desc($query);
			$this->model->setDesgravacion_acuerdo_det_id($query);
			$this->model->setDesgravacion_acuerdo_det_acuerdo_id($query);

			return $this->modelAdo->paginate($this->model, 'LIKE', $limit, $page);
		}

	}

}
