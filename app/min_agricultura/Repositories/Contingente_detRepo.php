<?php

require PATH_APP.'min_agricultura/Entities/Contingente_det.php';
require PATH_APP.'min_agricultura/Ado/Contingente_detAdo.php';
require_once PATH_MODELS.'Repositories/Acuerdo_detRepo.php';
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
		return 'contingente_det_id';
	}

	public function validateModify($params)
	{
		extract($params);
		$result = $this->findPrimaryKey($contingente_det_id);

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

	public function createByAgreementDet($params)
	{
		extract($params);
		$acuerdo_detRepo = new Acuerdo_detRepo;
		$acuerdo_det_id  = $contingente_acuerdo_det_id;
		//verifica que exista el acuerdo_det y trae los datos
		$result = $acuerdo_detRepo->listId(compact('acuerdo_det_id'));
		if (!$result['success']) {
			return $result;
		}
		$rowAcuerdo_det = array_shift($result['data']);
		$acuerdo_fvigente = strtotime($rowAcuerdo_det['acuerdo_fvigente']);

		$yearIni = (int)date('Y', $acuerdo_fvigente);
		$yearFin = $yearIni + (int)$rowAcuerdo_det['acuerdo_det_nperiodos'] - 1;
		$rangeYear = range($yearIni, $yearFin);

		foreach ($rangeYear as $year) {
			$params = [
				'contingente_det_id'                                 => '',
				'contingente_det_anio_ini'                           => $year,
				'contingente_det_anio_fin'                           => $year,
				'contingente_det_peso_neto'                          => 0,
				'contingente_det_contingente_id'                     => $contingente_id,
				'contingente_det_contingente_acuerdo_det_id'         => $contingente_acuerdo_det_id,
				'contingente_det_contingente_acuerdo_det_acuerdo_id' => $contingente_acuerdo_det_acuerdo_id,
			];
			$result = $this->create($params);
			if (!$result['success']) {
				return $result;
			}
		}

		return $result;
	}

	public function deleteByParent($params)
	{
		extract($params);
		//busca todos los registros en contingente_det por la llave de contingente
		$this->model->setContingente_det_contingente_id($contingente_id);
		$this->model->setContingente_det_contingente_acuerdo_det_id($contingente_acuerdo_det_id);
		$this->model->setContingente_det_contingente_acuerdo_det_acuerdo_id($contingente_acuerdo_det_acuerdo_id);

		$result = $this->modelAdo->exactSearch($this->model);
		if (!$result['success']) {
			return $result;
		}

		//realiza el borrado de cada contingente_det
		foreach ($result['data'] as $key => $row) {
			$this->model = $this->getModel();
			$primaryKey  = $row[$this->primaryKey];

			$result = $this->findPrimaryKey($primaryKey);
			if ($result['success']) {
				$result = $this->modelAdo->delete($this->model);
			}
		}

		return $result;
	}

	public function setData($params, $action)
	{
		extract($params);

		if ($action == 'modify') {
			$result = $this->findPrimaryKey($contingente_det_id);

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
			empty($contingente_det_anio_ini) ||
			empty($contingente_det_anio_fin) ||
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
