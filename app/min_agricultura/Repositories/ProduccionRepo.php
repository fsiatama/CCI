<?php

require PATH_APP.'min_agricultura/Entities/Produccion.php';
require PATH_APP.'min_agricultura/Ado/ProduccionAdo.php';
require_once ('BaseRepo.php');

class ProduccionRepo extends BaseRepo {

	public function getModel()
	{
		return new Produccion;
	}

	public function getModelAdo()
	{
		return new ProduccionAdo;
	}

	public function getPrimaryKey()
	{
		return 'produccion_id';
	}

	public function validateModify($params)
	{
		extract($params);
		$result = $this->findPrimaryKey($produccion_id);

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

	public function grid($params)
	{
		extract($params);

		$start = ( isset($start) ) ? $start : 0;
		$limit = ( isset($limit) ) ? $limit : 30;
		$page  = ( $start==0 ) ? 1 : ( $start/$limit )+1;

		if (!empty($query)) {
			if (!empty($fullTextFields)) {

				$fullTextFields = json_decode(stripslashes($fullTextFields));

				foreach ($fullTextFields as $value) {
					$methodName = $this->getColumnMethodName('set', $value);

					//utiliza el metodo id para ralizar la busqueda textual en la tabla auxiliar
					if ($methodName == 'setSector_nombre') {
						$methodName = 'setProduccion_sector_id';
					}

					if (method_exists($this->model, $methodName)) {
						call_user_func_array([$this->model, $methodName], compact('query'));
					}
				}
			} else {
				$this->model->setProduccion_id($query);
				$this->model->setProduccion_sector_id($query);
				$this->model->setProduccion_anio($query);
				$this->model->setProduccion_peso_neto($query);
			}

		}
		$this->modelAdo->setColumns([
			'produccion_id',
			'produccion_sector_id',
			'sector_nombre',
			'produccion_anio',
			'produccion_peso_neto',
		]);

		$result = $this->modelAdo->paginate($this->model, 'LIKE', $limit, $page);

		return $result;
	}

	public function setData($params, $action)
	{
		extract($params);

		if ($action == 'modify') {
			$result = $this->findPrimaryKey($produccion_id);

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
			empty($produccion_sector_id) ||
			!is_array($produccion_sector_id) ||
			empty($produccion_anio) ||
			empty($produccion_peso_neto)
		) {
			$result = array(
				'success' => false,
				'error'   => 'Incomplete data for this request.'
			);
			return $result;
		}
		$this->model->setProduccion_id($produccion_id);
		$this->model->setProduccion_sector_id(implode(',', $produccion_sector_id));
		$this->model->setProduccion_anio($produccion_anio);
		$this->model->setProduccion_peso_neto($produccion_peso_neto);

		if ($action == 'create') {
			$this->model->setProduccion_finsert(Helpers::getDateTimeNow());
			$this->model->setProduccion_uinsert($_SESSION['user_id']);
		}
		elseif ($action == 'modify') {
			$this->model->setProduccion_fupdate(Helpers::getDateTimeNow());
			$this->model->setProduccion_uupdate($_SESSION['user_id']);
		}
		$result = ['success' => true];
		return $result;
	}

	public function listPeriodSector($params)
	{
		extract($params);
		if (empty($anio) || empty($sector_id)) {
			return [
				'success' => false,
				'error'   => 'Incomplete data for this request.'
			];
		}

		$this->model->setProduccion_sector_id($sector_id);
		$this->model->setProduccion_anio($anio);

		return $this->modelAdo->exactSearch($this->model);
	}

}

