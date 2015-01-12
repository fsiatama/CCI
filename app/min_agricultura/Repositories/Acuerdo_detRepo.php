<?php

require PATH_MODELS.'Entities/Acuerdo_det.php';
require PATH_MODELS.'Ado/Acuerdo_detAdo.php';
require PATH_MODELS.'Repositories/ContingenteRepo.php';
require_once ('BaseRepo.php');

class Acuerdo_detRepo extends BaseRepo {

	public function getModel()
	{
		return new Acuerdo_det;
	}
	
	public function getModelAdo()
	{
		return new Acuerdo_detAdo;
	}

	public function getPrimaryKey()
	{
		return 'acuerdo_det_id';
	}

	public function validateModify($params)
	{
		extract($params);
		$result = $this->findPrimaryKey($acuerdo_det_id);

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

		$acuerdo_det_productos = (empty($acuerdo_det_productos) || !is_array($acuerdo_det_productos)) ? [] : $acuerdo_det_productos ;
		$acuerdo_det_contingente_acumulado_pais = (isset($acuerdo_det_contingente_acumulado_pais)) ? $acuerdo_det_contingente_acumulado_pais : '0' ;
		$acuerdo_det_contingente_acumulado_pais = ($acuerdo_det_contingente_acumulado_pais === '1') ? '1' : '0' ;

		$createQuota = true;

		if ($action == 'modify') {
			$result = $this->findPrimaryKey($acuerdo_det_id);

			if (!$result['success']) {
				$result = [
					'success'  => false,
					'closeTab' => true,
					'tab'      => 'tab-'.$module,
					'error'    => $result['error']
				];
				return $result;
			}

			$row = array_shift($result['data']);

			//si acuerdo_det_contingente_acumulado_pais es diferente debe borrar los contingentes y volverlos a crear
			if ($acuerdo_det_contingente_acumulado_pais != $row['acuerdo_det_contingente_acumulado_pais']) {
				$result = $this->deleteQuota();
				$createQuota = true;
			} else {
				$createQuota = false;
			}
		}

		//buscar el acuerdo y generar un



		if (
			empty($acuerdo_det_productos) ||
			empty($acuerdo_det_productos_desc) ||
			empty($acuerdo_det_administracion) ||
			empty($acuerdo_det_administrador) ||
			empty($acuerdo_det_nperiodos) ||
			empty($acuerdo_det_acuerdo_id)
		) {
			$result = [
				'success' => false,
				'error'   => 'Incomplete data for this request.'
			];
			return $result;
		}
		$this->model->setAcuerdo_det_id($acuerdo_det_id);
		$this->model->setAcuerdo_det_arancel_base($acuerdo_det_arancel_base);
		$this->model->setAcuerdo_det_productos(implode(',', $acuerdo_det_productos));
		$this->model->setAcuerdo_det_productos_desc($acuerdo_det_productos_desc);
		$this->model->setAcuerdo_det_administracion($acuerdo_det_administracion);
		$this->model->setAcuerdo_det_administrador($acuerdo_det_administrador);
		$this->model->setAcuerdo_det_nperiodos($acuerdo_det_nperiodos);
		$this->model->setAcuerdo_det_acuerdo_id($acuerdo_det_acuerdo_id);
		$this->model->setAcuerdo_det_contingente_acumulado_pais($acuerdo_det_contingente_acumulado_pais);

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
			$this->model->setAcuerdo_det_id(implode('", "', $query));
			$this->model->setAcuerdo_det_arancel_base(implode('", "', $query));
			$this->model->setAcuerdo_det_productos(implode('", "', $query));
			$this->model->setAcuerdo_det_productos_desc(implode('", "', $query));
			$this->model->setAcuerdo_det_administracion(implode('", "', $query));
			$this->model->setAcuerdo_det_administrador(implode('", "', $query));
			$this->model->setAcuerdo_det_nperiodos(implode('", "', $query));
			$this->model->setAcuerdo_det_acuerdo_id(implode('", "', $query));
			$this->model->setAcuerdo_det_contingente_acumulado_pais(implode('", "', $query));

			return $this->modelAdo->inSearch($this->model);
		}
		else {
			$this->model->setAcuerdo_det_id($query);
			$this->model->setAcuerdo_det_arancel_base($query);
			$this->model->setAcuerdo_det_productos($query);
			$this->model->setAcuerdo_det_productos_desc($query);
			$this->model->setAcuerdo_det_administracion($query);
			$this->model->setAcuerdo_det_administrador($query);
			$this->model->setAcuerdo_det_nperiodos($query);
			$this->model->setAcuerdo_det_acuerdo_id($query);
			$this->model->setAcuerdo_det_contingente_acumulado_pais($query);

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

		if (empty($acuerdo_det_acuerdo_id)) {
			$result = [
				'success' => false,
				'error'   => 'Incomplete data for this request.'
			];
			return $result;
		}
		$this->model->setAcuerdo_det_acuerdo_id($acuerdo_det_acuerdo_id);

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
				$this->model->setAcuerdo_det_id($query);
				$this->model->setAcuerdo_det_arancel_base($query);
				$this->model->setAcuerdo_det_productos($query);
				$this->model->setAcuerdo_det_productos_desc($query);
				$this->model->setAcuerdo_det_administracion($query);
				$this->model->setAcuerdo_det_administrador($query);
				$this->model->setAcuerdo_det_nperiodos($query);
				$this->model->setAcuerdo_det_contingente_acumulado_pais($query);
			}
			
		}

		$this->modelAdo->setColumns([
			'acuerdo_det_id',
			'acuerdo_det_arancel_base',
			'acuerdo_det_productos',
			'acuerdo_det_productos_desc',
			'acuerdo_det_administracion',
			'acuerdo_det_administrador',
			'acuerdo_det_nperiodos',
			'acuerdo_det_acuerdo_id',
			'acuerdo_nombre'
		]);

		$result = $this->modelAdo->paginate($this->model, 'LIKE', $limit, $page);

		return $result;
	}

}
