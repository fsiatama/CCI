<?php

require PATH_MODELS.'Entities/Desgravacion_det.php';
require PATH_MODELS.'Ado/Desgravacion_detAdo.php';
require_once ('BaseRepo.php');

class Desgravacion_detRepo extends BaseRepo {

	public function getModel()
	{
		return new Desgravacion_det;
	}
	
	public function getModelAdo()
	{
		return new Desgravacion_detAdo;
	}

	public function getPrimaryKey()
	{
		return 'desgravacion_det_desgravacion_acuerdo_det_acuerdo_id';
	}

	public function validateModify($params)
	{
		extract($params);
		$result = $this->findPrimaryKey($desgravacion_det_desgravacion_acuerdo_det_acuerdo_id);

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
			$result = $this->findPrimaryKey($desgravacion_det_desgravacion_acuerdo_det_acuerdo_id);

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
			empty($desgravacion_det_id) ||
			empty($desgravacion_det_anio_ini) ||
			empty($desgravacion_det_anio_fin) ||
			empty($desgravacion_det_tasa_intra) ||
			empty($desgravacion_det_tasa_extra) ||
			empty($desgravacion_det_tipo_operacion) ||
			empty($desgravacion_det_desgravacion_id) ||
			empty($desgravacion_det_desgravacion_acuerdo_det_id) ||
			empty($desgravacion_det_desgravacion_acuerdo_det_acuerdo_id)
		) {
			$result = [
				'success' => false,
				'error'   => 'Incomplete data for this request.'
			];
			return $result;
		}
		$this->model->setDesgravacion_det_id($desgravacion_det_id);
		$this->model->setDesgravacion_det_anio_ini($desgravacion_det_anio_ini);
		$this->model->setDesgravacion_det_anio_fin($desgravacion_det_anio_fin);
		$this->model->setDesgravacion_det_tasa_intra($desgravacion_det_tasa_intra);
		$this->model->setDesgravacion_det_tasa_extra($desgravacion_det_tasa_extra);
		$this->model->setDesgravacion_det_tipo_operacion($desgravacion_det_tipo_operacion);
		$this->model->setDesgravacion_det_desgravacion_id($desgravacion_det_desgravacion_id);
		$this->model->setDesgravacion_det_desgravacion_acuerdo_det_id($desgravacion_det_desgravacion_acuerdo_det_id);
		$this->model->setDesgravacion_det_desgravacion_acuerdo_det_acuerdo_id($desgravacion_det_desgravacion_acuerdo_det_acuerdo_id);

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
			$this->model->setDesgravacion_det_id(implode('", "', $query));
			$this->model->setDesgravacion_det_anio_ini(implode('", "', $query));
			$this->model->setDesgravacion_det_anio_fin(implode('", "', $query));
			$this->model->setDesgravacion_det_tasa_intra(implode('", "', $query));
			$this->model->setDesgravacion_det_tasa_extra(implode('", "', $query));
			$this->model->setDesgravacion_det_tipo_operacion(implode('", "', $query));
			$this->model->setDesgravacion_det_desgravacion_id(implode('", "', $query));
			$this->model->setDesgravacion_det_desgravacion_acuerdo_det_id(implode('", "', $query));
			$this->model->setDesgravacion_det_desgravacion_acuerdo_det_acuerdo_id(implode('", "', $query));

			return $this->modelAdo->inSearch($this->model);
		}
		else {
			$this->model->setDesgravacion_det_id($query);
			$this->model->setDesgravacion_det_anio_ini($query);
			$this->model->setDesgravacion_det_anio_fin($query);
			$this->model->setDesgravacion_det_tasa_intra($query);
			$this->model->setDesgravacion_det_tasa_extra($query);
			$this->model->setDesgravacion_det_tipo_operacion($query);
			$this->model->setDesgravacion_det_desgravacion_id($query);
			$this->model->setDesgravacion_det_desgravacion_acuerdo_det_id($query);
			$this->model->setDesgravacion_det_desgravacion_acuerdo_det_acuerdo_id($query);

			return $this->modelAdo->paginate($this->model, 'LIKE', $limit, $page);
		}

	}

}
