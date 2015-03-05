<?php

require PATH_MODELS.'Entities/Desgravacion_det.php';
require PATH_MODELS.'Ado/Desgravacion_detAdo.php';
require_once PATH_MODELS.'Repositories/Acuerdo_detRepo.php';
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
		return 'desgravacion_det_id';
	}

	public function validateModify($params)
	{
		extract($params);
		$result = $this->findPrimaryKey($desgravacion_det_id);

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
		$row             = current($arrData);
		$desgravacion_id = (empty($row['desgravacion_det_desgravacion_id'])) ? '' : $row['desgravacion_det_desgravacion_id'] ;
		$acuerdo_det_id  = (empty($row['desgravacion_det_desgravacion_acuerdo_det_id'])) ? '' : $row['desgravacion_det_desgravacion_acuerdo_det_id'] ;
		$acuerdo_id      = (empty($row['desgravacion_det_desgravacion_acuerdo_det_acuerdo_id'])) ? '' : $row['desgravacion_det_desgravacion_acuerdo_det_acuerdo_id'] ;

		if (
			empty($desgravacion_id) ||
			empty($acuerdo_det_id) ||
			empty($acuerdo_id)
		) {
			$result = [
				'success' => false,
				'error'   => 'Incomplete data for this request. desgravacion_detRepo updateByAgreementDet'
			];
			return $result;
		}

		//busca todos los registros en contingente_det por la llave de desgravacion
		$this->model->setDesgravacion_det_desgravacion_id($desgravacion_id);
		$this->model->setDesgravacion_det_desgravacion_acuerdo_det_id($acuerdo_det_id);
		$this->model->setDesgravacion_det_desgravacion_acuerdo_det_acuerdo_id($acuerdo_id);

		$result = $this->modelAdo->exactSearch($this->model);
		if (!$result['success']) {
			return $result;
		}

		foreach ($result['data'] as $key => $row) {

			$rowModified = Helpers::findKeyInArrayMulti(
				$arrData,
				'desgravacion_det_id',
				$row['desgravacion_det_id']
			);
			if ($rowModified !== false) {
				$this->model = $this->getModel();
				$params = [
					'desgravacion_det_id'                                  => $row['desgravacion_det_id'],
					'desgravacion_det_anio_ini'                            => $row['desgravacion_det_anio_ini'],
					'desgravacion_det_anio_fin'                            => $row['desgravacion_det_anio_fin'],
					'desgravacion_det_tasa'                                => $rowModified['desgravacion_det_tasa'],
					'desgravacion_det_desgravacion_id'                     => $row['desgravacion_det_desgravacion_id'],
					'desgravacion_det_desgravacion_acuerdo_det_id'         => $row['desgravacion_det_desgravacion_acuerdo_det_id'],
					'desgravacion_det_desgravacion_acuerdo_det_acuerdo_id' => $row['desgravacion_det_desgravacion_acuerdo_det_acuerdo_id'],
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

		if (
			empty($desgravacion_acuerdo_det_id)
		) {
			$result = [
				'success' => false,
				'error'   => 'Incomplete data for this request. desgravacion_detRepo  createByAgreementDet'
			];
			return $result;
		}


		$acuerdo_detRepo = new Acuerdo_detRepo;
		$acuerdo_det_id  = $desgravacion_acuerdo_det_id;
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
				'desgravacion_det_id'                                  => '',
				'desgravacion_det_anio_ini'                            => $year,
				'desgravacion_det_anio_fin'                            => $yearLast,
				'desgravacion_det_tasa'                                => 0,
				'desgravacion_det_desgravacion_id'                     => $desgravacion_id,
				'desgravacion_det_desgravacion_acuerdo_det_id'         => $desgravacion_acuerdo_det_id,
				'desgravacion_det_desgravacion_acuerdo_det_acuerdo_id' => $desgravacion_acuerdo_det_acuerdo_id,
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

		if (
			empty($desgravacion_id) ||
			empty($desgravacion_acuerdo_det_id) ||
			empty($desgravacion_acuerdo_det_acuerdo_id)
		) {
			$result = [
				'success' => false,
				'error'   => 'Incomplete data for this request. desgravacion_detRepo  deleteByParent'
			];
			return $result;
		}

		$this->model = $this->getModel();
		//busca todos los registros en desgravacion_det por la llave de desgravacion
		$this->model->setDesgravacion_det_desgravacion_id($desgravacion_id);
		$this->model->setDesgravacion_det_desgravacion_acuerdo_det_id($desgravacion_acuerdo_det_id);
		$this->model->setDesgravacion_det_desgravacion_acuerdo_det_acuerdo_id($desgravacion_acuerdo_det_acuerdo_id);

		$result = $this->modelAdo->exactSearch($this->model);
		if (!$result['success']) {
			return $result;
		}

		//realiza el borrado de cada desgravacion_det
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

		$desgravacion_det_id = (empty($desgravacion_det_id)) ? '' : $desgravacion_det_id ;

		if ($action == 'modify') {
			$result = $this->findPrimaryKey($desgravacion_det_id);

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

		$desgravacion_det_tipo_operacion = (empty($desgravacion_det_tipo_operacion)) ? 'igual' : $desgravacion_det_tipo_operacion ;

		if (
			empty($desgravacion_det_anio_ini) ||
			empty($desgravacion_det_anio_fin) ||
			//empty($desgravacion_det_tipo_operacion) ||
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
		$this->model->setDesgravacion_det_tasa($desgravacion_det_tasa);
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
			$this->model->setDesgravacion_det_tasa(implode('", "', $query));
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
			$this->model->setDesgravacion_det_tasa($query);
			$this->model->setDesgravacion_det_tipo_operacion($query);
			$this->model->setDesgravacion_det_desgravacion_id($query);
			$this->model->setDesgravacion_det_desgravacion_acuerdo_det_id($query);
			$this->model->setDesgravacion_det_desgravacion_acuerdo_det_acuerdo_id($query);

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

		if (empty($desgravacion_det_desgravacion_id) || empty($desgravacion_det_desgravacion_acuerdo_det_id) || empty($desgravacion_det_desgravacion_acuerdo_det_acuerdo_id)) {
			$result = [
				'success' => false,
				'error'   => 'Incomplete data for this request. desgravacion_detRepo grid'
			];
			return $result;
		}
		$this->model->setDesgravacion_det_desgravacion_id($desgravacion_det_desgravacion_id);
		$this->model->setDesgravacion_det_desgravacion_acuerdo_det_id($desgravacion_det_desgravacion_acuerdo_det_id);
		$this->model->setDesgravacion_det_desgravacion_acuerdo_det_acuerdo_id($desgravacion_det_desgravacion_acuerdo_det_acuerdo_id);

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
				$this->model->setDesgravacion_det_id($query);
				$this->model->setDesgravacion_det_anio_ini($query);
				$this->model->setDesgravacion_det_anio_fin($query);
				$this->model->setDesgravacion_det_tasa($query);
			}
			
		}

		$this->modelAdo->setColumns([
			'desgravacion_det_id',
			'desgravacion_det_anio_ini',
			'desgravacion_det_anio_fin',
			'desgravacion_det_anio_fin_title',
			'desgravacion_det_tasa',
			'desgravacion_det_tipo_operacion',
			'desgravacion_det_desgravacion_id',
			'desgravacion_det_desgravacion_acuerdo_det_id',
			'desgravacion_det_desgravacion_acuerdo_det_acuerdo_id'
		]);

		$result = $this->modelAdo->paginate($this->model, 'LIKE', $limit, $page);

		return $result;
	}

}
