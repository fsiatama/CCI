<?php

require PATH_MODELS.'Entities/Acuerdo.php';
require PATH_MODELS.'Ado/AcuerdoAdo.php';
require PATH_MODELS.'Repositories/PaisRepo.php';
require PATH_MODELS.'Repositories/MercadoRepo.php';

require_once ('BaseRepo.php');

class AcuerdoRepo extends BaseRepo {

	public function getModel()
	{
		return new Acuerdo;
	}
	
	public function getModelAdo()
	{
		return new AcuerdoAdo;
	}

	public function getPrimaryKey()
	{
		return 'acuerdo_id';
	}

	public function validateModify($params)
	{
		extract($params);
		$result = $this->findPrimaryKey($acuerdo_id);

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
			$result = $this->findPrimaryKey($acuerdo_id);

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

		$acuerdo_mercado_id = (empty($acuerdo_mercado_id) || !is_array($acuerdo_mercado_id)) ? [] : $acuerdo_mercado_id ;
		$acuerdo_id_pais    = (empty($acuerdo_id_pais) || !is_array($acuerdo_id_pais)) ? [] : $acuerdo_id_pais ;

		if (
			empty($acuerdo_nombre) ||
			empty($acuerdo_descripcion) ||
			empty($acuerdo_intercambio) ||
			empty($acuerdo_fvigente) ||
			(empty($acuerdo_mercado_id) && empty($acuerdo_id_pais))
		) {
			$result = [
				'success' => false,
				'error'   => 'Incomplete data for this request.'
			];
			return $result;
		}

		$this->model->setAcuerdo_id($acuerdo_id);
		$this->model->setAcuerdo_nombre($acuerdo_nombre);
		$this->model->setAcuerdo_descripcion($acuerdo_descripcion);
		$this->model->setAcuerdo_intercambio($acuerdo_intercambio);
		$this->model->setAcuerdo_fvigente($acuerdo_fvigente);
		$this->model->setAcuerdo_mercado_id(implode(',', $acuerdo_mercado_id));
		$this->model->setAcuerdo_id_pais(implode(',', $acuerdo_id_pais));

		if ($action == 'create') {
			$this->model->setAcuerdo_uinsert($_SESSION['user_id']);
			$this->model->setAcuerdo_finsert(Helpers::getDateTimeNow());
		} elseif ($action == 'modify') {
			$this->model->setAcuerdo_uupdate($_SESSION['user_id']);
			$this->model->setAcuerdo_fupdate(Helpers::getDateTimeNow());
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
			$this->model->setAcuerdo_id(implode('", "', $query));
			$this->model->setAcuerdo_nombre(implode('", "', $query));
			$this->model->setAcuerdo_descripcion(implode('", "', $query));
			$this->model->setAcuerdo_intercambio(implode('", "', $query));
			$this->model->setAcuerdo_fvigente(implode('", "', $query));
			$this->model->setAcuerdo_uinsert(implode('", "', $query));
			$this->model->setAcuerdo_finsert(implode('", "', $query));
			$this->model->setAcuerdo_uupdate(implode('", "', $query));
			$this->model->setAcuerdo_fupdate(implode('", "', $query));
			$this->model->setAcuerdo_mercado_id(implode('", "', $query));
			$this->model->setAcuerdo_id_pais(implode('", "', $query));

			return $this->modelAdo->inSearch($this->model);
		}
		else {
			$this->model->setAcuerdo_id($query);
			$this->model->setAcuerdo_nombre($query);
			$this->model->setAcuerdo_descripcion($query);
			$this->model->setAcuerdo_intercambio($query);
			$this->model->setAcuerdo_fvigente($query);
			$this->model->setAcuerdo_uinsert($query);
			$this->model->setAcuerdo_finsert($query);
			$this->model->setAcuerdo_uupdate($query);
			$this->model->setAcuerdo_fupdate($query);
			$this->model->setAcuerdo_mercado_id($query);
			$this->model->setAcuerdo_id_pais($query);

			return $this->modelAdo->paginate($this->model, 'LIKE', $limit, $page);
		}

	}

	public function grid($params)
	{
		extract($params);
		/**/
		$start = ( isset($start) ) ? $start : 0;
		$limit = ( isset($limit) ) ? $limit : 30;
		$page  = ( $start==0 ) ? 1 : ( $start/$limit )+1;

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
				$this->model->setAcuerdo_id($query);
				$this->model->setAcuerdo_nombre($query);
				$this->model->setAcuerdo_descripcion($query);
				$this->model->setAcuerdo_fvigente($query);
				$this->model->setAcuerdo_mercado_id($query);
				$this->model->setAcuerdo_id_pais($query);
			}
			
		}

		$this->modelAdo->setColumns([
			'acuerdo_id',
			'acuerdo_nombre',
			'acuerdo_descripcion',
			'acuerdo_intercambio',
			'acuerdo_intercambio_title',
			'acuerdo_fvigente',
			'acuerdo_mercado_id',
			'acuerdo_id_pais'
		]);

		$result = $this->modelAdo->paginate($this->model, 'LIKE', $limit, $page);

		return $result;
	}

	public function listId($params)
	{
		extract($params);

		$result = $this->findPrimaryKey($acuerdo_id);

		if (!$result['success']) {
			return $result;
		}

		$row = array_shift($result['data']);

		$paisRepo = new PaisRepo;
		$params   = [
			'valuesqry' => true
		];

		if (empty($row['acuerdo_mercado_id'])) {
			$params['query'] = $row['acuerdo_id_pais'];
			
		} else {
			$mercadoRepo = new MercadoRepo;
			$result      = $mercadoRepo->findPrimaryKey($row['acuerdo_mercado_id']);

			if (!$result['success']) {
				return $result;
			}
			$rowMercado      = array_shift($result['data']);
			$params['query'] = str_replace(',', '|', $rowMercado['mercado_paises']);
		}
		
		$result = $paisRepo->listAll($params);

		if (!$result['success']) {
			return $result;
		}

		$result = [
			'success'      => true,
			'country_data' => $result['data'],
			'data'         => [$row]
		];

		return $result;
	}

}
