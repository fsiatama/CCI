<?php

require PATH_APP.'min_agricultura/Entities/Salvaguardia_det.php';
require PATH_APP.'min_agricultura/Ado/Salvaguardia_detAdo.php';
require_once ('BaseRepo.php');

class Salvaguardia_detRepo extends BaseRepo {

	public function getModel()
	{
		return new Salvaguardia_det;
	}
	
	public function getModelAdo()
	{
		return new Salvaguardia_detAdo;
	}

	public function getPrimaryKey()
	{
		return 'salvaguardia_det_salvaguardia_contingente_acuerdo_det_acuerdo_id';
	}

	public function validateModify($params)
	{
		extract($params);
		$result = $this->findPrimaryKey($salvaguardia_det_salvaguardia_contingente_acuerdo_det_acuerdo_id);

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
			$result = $this->findPrimaryKey($salvaguardia_det_salvaguardia_contingente_acuerdo_det_acuerdo_id);

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
			empty($salvaguardia_det_id) ||
			empty($salvaguardia_det_anio_ini) ||
			empty($salvaguardia_det_anio_fin) ||
			empty($salvaguardia_det_peso_neto) ||
			empty($salvaguardia_det_salvaguardia_id) ||
			empty($salvaguardia_det_salvaguardia_contingente_id) ||
			empty($salvaguardia_det_salvaguardia_contingente_acuerdo_det_id) ||
			empty($salvaguardia_det_salvaguardia_contingente_acuerdo_det_acuerdo_id)
		) {
			$result = [
				'success' => false,
				'error'   => 'Incomplete data for this request.'
			];
			return $result;
		}
		$this->model->setSalvaguardia_det_id($salvaguardia_det_id);
		$this->model->setSalvaguardia_det_anio_ini($salvaguardia_det_anio_ini);
		$this->model->setSalvaguardia_det_anio_fin($salvaguardia_det_anio_fin);
		$this->model->setSalvaguardia_det_peso_neto($salvaguardia_det_peso_neto);
		$this->model->setSalvaguardia_det_salvaguardia_id($salvaguardia_det_salvaguardia_id);
		$this->model->setSalvaguardia_det_salvaguardia_contingente_id($salvaguardia_det_salvaguardia_contingente_id);
		$this->model->setSalvaguardia_det_salvaguardia_contingente_acuerdo_det_id($salvaguardia_det_salvaguardia_contingente_acuerdo_det_id);
		$this->model->setSalvaguardia_det_salvaguardia_contingente_acuerdo_det_acuerdo_id($salvaguardia_det_salvaguardia_contingente_acuerdo_det_acuerdo_id);

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
			$this->model->setSalvaguardia_det_id(implode('", "', $query));
			$this->model->setSalvaguardia_det_anio_ini(implode('", "', $query));
			$this->model->setSalvaguardia_det_anio_fin(implode('", "', $query));
			$this->model->setSalvaguardia_det_peso_neto(implode('", "', $query));
			$this->model->setSalvaguardia_det_salvaguardia_id(implode('", "', $query));
			$this->model->setSalvaguardia_det_salvaguardia_contingente_id(implode('", "', $query));
			$this->model->setSalvaguardia_det_salvaguardia_contingente_acuerdo_det_id(implode('", "', $query));
			$this->model->setSalvaguardia_det_salvaguardia_contingente_acuerdo_det_acuerdo_id(implode('", "', $query));

			return $this->modelAdo->inSearch($this->model);
		}
		else {
			$this->model->setSalvaguardia_det_id($query);
			$this->model->setSalvaguardia_det_anio_ini($query);
			$this->model->setSalvaguardia_det_anio_fin($query);
			$this->model->setSalvaguardia_det_peso_neto($query);
			$this->model->setSalvaguardia_det_salvaguardia_id($query);
			$this->model->setSalvaguardia_det_salvaguardia_contingente_id($query);
			$this->model->setSalvaguardia_det_salvaguardia_contingente_acuerdo_det_id($query);
			$this->model->setSalvaguardia_det_salvaguardia_contingente_acuerdo_det_acuerdo_id($query);

			return $this->modelAdo->paginate($this->model, 'LIKE', $limit, $page);
		}

	}

}
