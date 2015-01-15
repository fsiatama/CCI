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

	public function updateByAgreementDet($params)
	{
		extract($params);
		$arrData = json_decode(stripslashes($data), true);
		if (empty($arrData)) {
			return ['success' => true];
		}
		//extrae los campos de la llave del primer registro
		$row            = current($arrData);
		$contingente_id = (empty($row['contingente_det_contingente_id'])) ? '' : $row['contingente_det_contingente_id'] ;
		$acuerdo_det_id = (empty($row['contingente_det_contingente_acuerdo_det_id'])) ? '' : $row['contingente_det_contingente_acuerdo_det_id'] ;
		$acuerdo_id     = (empty($row['contingente_det_contingente_acuerdo_det_acuerdo_id'])) ? '' : $row['contingente_det_contingente_acuerdo_det_acuerdo_id'] ;

		if (
			empty($contingente_id) ||
			empty($acuerdo_det_id) ||
			empty($acuerdo_id)
		) {
			$result = [
				'success' => false,
				'error'   => 'Incomplete data for this request.'
			];
			return $result;
		}

		//busca todos los registros en contingente_det por la llave de contingente
		$this->model->setContingente_det_contingente_id($contingente_id);
		$this->model->setContingente_det_contingente_acuerdo_det_id($acuerdo_det_id);
		$this->model->setContingente_det_contingente_acuerdo_det_acuerdo_id($acuerdo_id);

		$result = $this->modelAdo->exactSearch($this->model);
		if (!$result['success']) {
			return $result;
		}

		foreach ($result['data'] as $key => $row) {

			$rowModified = Helpers::findKeyInArrayMulti(
				$arrData,
				'contingente_det_id',
				$row['contingente_det_id']
			);
			if ($rowModified !== false) {
				$this->model = $this->getModel();
				$params = [
					'contingente_det_id'                                 => $row['contingente_det_id'],
					'contingente_det_anio_ini'                           => $row['contingente_det_anio_ini'],
					'contingente_det_anio_fin'                           => $row['contingente_det_anio_fin'],
					'contingente_det_peso_neto'                          => $rowModified['contingente_det_peso_neto'],
					'contingente_det_contingente_id'                     => $row['contingente_det_contingente_id'],
					'contingente_det_contingente_acuerdo_det_id'         => $row['contingente_det_contingente_acuerdo_det_id'],
					'contingente_det_contingente_acuerdo_det_acuerdo_id' => $row['contingente_det_contingente_acuerdo_det_acuerdo_id'],
				];
				$result = $this->modify($params);
				if (!$result['success']) {
					return $result;
				}

			}
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

		$yearFirst = (int)date('Y', $acuerdo_fvigente);
		$yearLast  = $yearFirst + (int)$rowAcuerdo_det['acuerdo_det_nperiodos'] - 1;
		$rangeYear = range($yearFirst, $yearLast);
		$endYear   = end($rangeYear);
		reset($rangeYear);

		foreach ($rangeYear as $year) {

			$this->model = $this->getModel();

			$yearLast = ($year == $endYear) ? _UNDEFINEDYEAR : $year ;

			$params = [
				'contingente_det_id'                                 => '',
				'contingente_det_anio_ini'                           => $year,
				'contingente_det_anio_fin'                           => $yearLast,
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
			$result = $this->delete($row);
			if (!$result['success']) {
				return $result;
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

	public function grid($params)
	{
		extract($params);
		/**/
		$start = ( isset($start) ) ? $start : 0;
		$limit = ( isset($limit) ) ? $limit : 30;
		$page  = ( $start==0 ) ? 1 : ( $start / $limit ) + 1;

		if (empty($contingente_det_contingente_id) || empty($contingente_det_contingente_acuerdo_det_id) || empty($contingente_det_contingente_acuerdo_det_acuerdo_id)) {
			$result = [
				'success' => false,
				'error'   => 'Incomplete data for this request.'
			];
			return $result;
		}
		$this->model->setContingente_det_contingente_id($contingente_det_contingente_id);
		$this->model->setContingente_det_contingente_acuerdo_det_id($contingente_det_contingente_acuerdo_det_id);
		$this->model->setContingente_det_contingente_acuerdo_det_acuerdo_id($contingente_det_contingente_acuerdo_det_acuerdo_id);

		if (!empty($query)) {
			if (!empty($fullTextFields)) {
				
				$fullTextFields = json_decode(stripslashes($fullTextFields));
				
				foreach ($fullTextFields as $value) {
					$methodName = $this->getColumnMethodName('set', $value);
					
					if (method_exists($this->model, $methodName)) {
						call_user_func_array([$this->model, $methodName], compact('query'));
					}
				}
			} else {
				$this->model->setContingente_det_id($query);
				$this->model->setContingente_det_anio_ini($query);
				$this->model->setContingente_det_anio_fin($query);
				$this->model->setContingente_det_peso_neto($query);
				$this->model->setContingente_det_contingente_id($query);
				$this->model->setContingente_det_contingente_acuerdo_det_id($query);
				$this->model->setContingente_det_contingente_acuerdo_det_acuerdo_id($query);
			}
			
		}

		$this->modelAdo->setColumns([
			'contingente_det_id',
			'contingente_det_anio_ini',
			'contingente_det_anio_fin',
			'contingente_det_anio_fin_title',
			'contingente_det_peso_neto',
			'contingente_det_contingente_id',
			'contingente_det_contingente_acuerdo_det_id',
			'contingente_det_contingente_acuerdo_det_acuerdo_id'
		]);

		$result = $this->modelAdo->paginate($this->model, 'LIKE', $limit, $page);

		return $result;
	}

}
