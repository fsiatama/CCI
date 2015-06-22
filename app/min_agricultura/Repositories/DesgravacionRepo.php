<?php

require PATH_MODELS.'Entities/Desgravacion.php';
require PATH_MODELS.'Ado/DesgravacionAdo.php';
require_once PATH_MODELS.'Repositories/Desgravacion_detRepo.php';
require_once ('BaseRepo.php');

class DesgravacionRepo extends BaseRepo {

	private $desgravacion_detRepo;

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
		return 'desgravacion_id';
	}

	public function validateModify($params)
	{
		extract($params);
		$result = $this->findPrimaryKey($desgravacion_id);

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

	public function createByAgreement($params)
	{
		extract($params);

		//var_dump($params);

		if (
			!isset($acuerdo_det_desgravacion_igual_pais) ||
			empty($acuerdo_det_acuerdo_id) ||
			empty($acuerdo_det_id)
		) {
			$result = [
				'success' => false,
				'error'   => 'Incomplete data for this request. desgravacionRepo  createByAgreement'
			];
			return $result;
		}

		$acuerdoRepo = new AcuerdoRepo;
		$acuerdo_id  = $acuerdo_det_acuerdo_id;
		//verifica que exista el acuerdo y trae los datos incluido un array con los datos de los paises del acuerdo
		$result = $acuerdoRepo->listId(compact('acuerdo_id'));
		if (!$result['success']) {
			return $result;
		}
		$rowAcuerdo   = array_shift($result['data']);
		$country_data = $result['country_data'];

		$countryAccumulated = ($acuerdo_det_desgravacion_igual_pais == '1') ? true : false ;

		if ($countryAccumulated) {
			$params = [
				'desgravacion_id_pais'                => $rowAcuerdo['acuerdo_mercado_id'],
				'desgravacion_mdesgravacion'          => '0',
				'desgravacion_desc'                   => '',
				'desgravacion_acuerdo_det_id'         => $acuerdo_det_id,
				'desgravacion_acuerdo_det_acuerdo_id' => $acuerdo_det_acuerdo_id,
			];
			$result = $this->create($params);
			if (!$result['success']) {
				return $result;
			}
			
		} else {
			foreach ($country_data as $key => $row) {
				$this->model = $this->getModel();
				$params = [
					'desgravacion_id_pais'                => $row['id_pais'],
					'desgravacion_mdesgravacion'          => '0',
					'desgravacion_desc'                   => '',
					'desgravacion_acuerdo_det_id'         => $acuerdo_det_id,
					'desgravacion_acuerdo_det_acuerdo_id' => $acuerdo_det_acuerdo_id,
				];
				$result = $this->create($params);
				if (!$result['success']) {
					return $result;
				}
			}
		}
		return $result;
	}

	public function deleteByParent($params)
	{
		extract($params);
		if (
			empty($acuerdo_det_acuerdo_id) ||
			empty($acuerdo_det_id)
		) {
			$result = [
				'success' => false,
				'error'   => 'Incomplete data for this request. desgravacionRepo  deleteByParent'
			];
			return $result;
		}
		$this->model = $this->getModel();
		//busca todos los registros de desgravacion por acuerdo_det_id
		$this->model->setDesgravacion_acuerdo_det_id($acuerdo_det_id);
		$this->model->setDesgravacion_acuerdo_det_acuerdo_id($acuerdo_det_acuerdo_id);
		$result = $this->modelAdo->exactSearch($this->model);
		if (!$result['success']) {
			return $result;
		}

		$this->desgravacion_detRepo = new Desgravacion_detRepo;

		$arrData = $result['data'];

		//realiza el borrado de cada desgravacion y sus hijos en desgravacion_det
		foreach ($arrData as $key => $row) {
			
			//borrado de desgravacion_det
			$result = $this->deleteDeductions(
				$row['desgravacion_id'],
				$row['desgravacion_acuerdo_det_id'],
				$row['desgravacion_acuerdo_det_acuerdo_id']
			);
			if (!$result['success']) {
				return $result;
			}

			$this->model = $this->getModel();
			$result = $this->delete($row);
			if (!$result['success']) {
				return $result;
			}
		}

		return $result;
	}

	private function deleteDeductions($desgravacion_id, $desgravacion_acuerdo_det_id, $desgravacion_acuerdo_det_acuerdo_id)
	{
		$result = $this->desgravacion_detRepo->deleteByParent(
			compact(
				'desgravacion_id',
				'desgravacion_acuerdo_det_id',
				'desgravacion_acuerdo_det_acuerdo_id'
			)
		);
		return $result;
	}

	private function createDeductions($desgravacion_id, $desgravacion_acuerdo_det_id, $desgravacion_acuerdo_det_acuerdo_id)
	{
		$result = $this->desgravacion_detRepo->createByAgreementDet(
			compact(
				'desgravacion_id',
				'desgravacion_acuerdo_det_id',
				'desgravacion_acuerdo_det_acuerdo_id'
			)
		);
		return $result;
	}

	public function setData($params, $action)
	{
		extract($params);

		if (
			empty($desgravacion_id_pais) ||
			!isset($desgravacion_mdesgravacion) ||
			empty($desgravacion_acuerdo_det_id) ||
			empty($desgravacion_acuerdo_det_acuerdo_id)
		) {
			$result = [
				'success' => false,
				'error'   => 'Incomplete data for this request. desgravacionRepo setData'
			];
			return $result;
		}

		$desgravacion_id = (empty($desgravacion_id)) ? '' : $desgravacion_id ;

		if ($action == 'modify') {
			$result = $this->findPrimaryKey($desgravacion_id);

			if (!$result['success']) {
				$result = [
					'success'  => false,
					'error'    => $result['error']
				];
				return $result;
			}
			$row = array_shift($result['data']);
			$this->desgravacion_detRepo = new Desgravacion_detRepo;

			if ($desgravacion_mdesgravacion != $row['desgravacion_mdesgravacion']) {
				$result = $this->deleteDeductions(
					$desgravacion_id,
					$desgravacion_acuerdo_det_id,
					$desgravacion_acuerdo_det_acuerdo_id
				);
				if (!$result['success']) {
					return $result;
				}

				if ($desgravacion_mdesgravacion === '1') {
					$result = $this->createDeductions(
						$desgravacion_id,
						$desgravacion_acuerdo_det_id,
						$desgravacion_acuerdo_det_acuerdo_id
					);
					if (!$result['success']) {
						return $result;
					}
				}
			}
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

	public function grid($params)
	{
		extract($params);
		/**/
		$start = ( isset($start) ) ? $start : 0;
		$limit = ( isset($limit) ) ? $limit : 30;
		$page  = ( $start==0 ) ? 1 : ( $start / $limit ) + 1;

		if (empty($desgravacion_acuerdo_det_id) || empty($desgravacion_acuerdo_det_acuerdo_id)) {
			$result = [
				'success' => false,
				'error'   => 'Incomplete data for this request. desgravacionRepo grid'
			];
			return $result;
		}
		$this->model->setDesgravacion_acuerdo_det_id($desgravacion_acuerdo_det_id);
		$this->model->setDesgravacion_acuerdo_det_acuerdo_id($desgravacion_acuerdo_det_acuerdo_id);

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
				$this->model->setDesgravacion_id($query);
				$this->model->setDesgravacion_id_pais($query);
				$this->model->setDesgravacion_mdesgravacion($query);
				$this->model->setDesgravacion_desc($query);
				$this->model->setDesgravacion_acuerdo_det_id($query);
				$this->model->setDesgravacion_acuerdo_det_acuerdo_id($query);
			}
			
		}

		$this->modelAdo->setColumns([
			'desgravacion_id',
			'desgravacion_id_pais',
			'desgravacion_mdesgravacion',
			'desgravacion_mdesgravacion_title',
			'desgravacion_desc',
			'desgravacion_acuerdo_det_id',
			'desgravacion_acuerdo_det_acuerdo_id',
			'acuerdo_mercado_id',
			'acuerdo_id_pais',
			'pais',
			'mercado_nombre',
			'acuerdo_det_desgravacion_igual_pais',
		]);
		$result = $this->modelAdo->paginate($this->model, 'LIKE', $limit, $page);

		if (!$result['success']) {
			return $result;
		}

		$arrData = [];

		foreach ($result['data'] as $key => $row) {
			$pais = ($row['acuerdo_det_desgravacion_igual_pais'] == '0') ? $row['pais'] : $row['mercado_nombre'] ;
			$arrData[] = [
				'desgravacion_id'                     => $row['desgravacion_id'],
				'desgravacion_id_pais'                => $row['desgravacion_id_pais'],
				'pais'                                => $pais,
				'desgravacion_mdesgravacion'          => $row['desgravacion_mdesgravacion'],
				'desgravacion_mdesgravacion_title'    => $row['desgravacion_mdesgravacion_title'],
				'desgravacion_desc'                   => $row['desgravacion_desc'],
				'desgravacion_acuerdo_det_id'         => $row['desgravacion_acuerdo_det_id'],
				'desgravacion_acuerdo_det_acuerdo_id' => $row['desgravacion_acuerdo_det_acuerdo_id'],
			];
		}

		$result['data'] = $arrData;

		return $result;
	}

	public function listDetail($params)
	{
		extract($params);

		if (
			empty($acuerdo_id) ||
			empty($acuerdo_det_id)
		) {
			$result = [
				'success' => false,
				'error'   => 'Incomplete data for this request. desgravacionRepo  execute'
			];
			return $result;
		}

		$this->model->setDesgravacion_acuerdo_det_id($acuerdo_det_id);
		$this->model->setDesgravacion_acuerdo_det_acuerdo_id($acuerdo_id);
		if ( !empty($country) ) {
			$this->model->setDesgravacion_id_pais($country);
		}

		$result = $this->modelAdo->exactSearch($this->model);
		if (!$result['success']) {
			return $result;
		}
		//la consulta solo deberia arrojar un registro
		$rowDesgravacion                     = array_shift($result['data']);
		$desgravacion_id                     = $rowDesgravacion['desgravacion_id'];
		$desgravacion_acuerdo_det_id         = $rowDesgravacion['desgravacion_acuerdo_det_id'];
		$desgravacion_acuerdo_det_acuerdo_id = $rowDesgravacion['desgravacion_acuerdo_det_acuerdo_id'];

		$this->desgravacion_detRepo = new Desgravacion_detRepo;
		$result = $this->desgravacion_detRepo->listId( compact('desgravacion_id', 'desgravacion_acuerdo_det_id', 'desgravacion_acuerdo_det_acuerdo_id') );
		if (!$result['success']) {
			return $result;
		}

		$result = [
			'success' => true,
			'rowDesgravacion' => $rowDesgravacion,
			'arrDesgravacion_det' => $result['data'],
		];

		return $result;
	}

}
