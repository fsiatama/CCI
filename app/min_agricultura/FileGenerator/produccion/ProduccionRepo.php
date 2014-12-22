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
			empty($produccion_id) ||
			empty($produccion_sector_id) ||
			empty($produccion_anio) ||
			empty($produccion_peso_neto) ||
			empty($produccion_finsert) ||
			empty($produccion_uinsert) ||
			empty($produccion_fupdate) ||
			empty($produccion_uupdate)
		) {
			$result = array(
				'success' => false,
				'error'   => 'Incomplete data for this request.'
			);
			return $result;
		}
			$this->model->setProduccion_id($produccion_id);
			$this->model->setProduccion_sector_id($produccion_sector_id);
			$this->model->setProduccion_anio($produccion_anio);
			$this->model->setProduccion_peso_neto($produccion_peso_neto);
			$this->model->setProduccion_finsert($produccion_finsert);
			$this->model->setProduccion_uinsert($produccion_uinsert);
			$this->model->setProduccion_fupdate($produccion_fupdate);
			$this->model->setProduccion_uupdate($produccion_uupdate);
		

		if ($action == 'create') {
		}
		elseif ($action == 'modify') {
		}
		$result = array('success' => true);
		return $result;
	}

}	

