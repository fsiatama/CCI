<?php

require PATH_APP.'min_agricultura/Entities/Correlativa.php';
require PATH_APP.'min_agricultura/Ado/CorrelativaAdo.php';
require_once ('BaseRepo.php');

class CorrelativaRepo extends BaseRepo {

	public function getModel()
	{
		return new Correlativa;
	}
	
	public function getModelAdo()
	{
		return new CorrelativaAdo;
	}

	public function getPrimaryKey()
	{
		return 'correlativa_id';
	}

	public function setData($params, $action)
	{
		extract($params);

		if ($action == 'modify') {
			$result = $this->findPrimaryKey($correlativa_id);

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
			empty($correlativa_id) ||
			empty($correlativa_fvigente) ||
			empty($correlativa_decreto) ||
			empty($correlativa_observacion) ||
			empty($correlativa_origen) ||
			empty($correlativa_destino) ||
			empty($correlativa_uinsert) ||
			empty($correlativa_finsert) ||
			empty($correlativa_uupdate) ||
			empty($correlativa_fupdate)
		) {
			$result = array(
				'success' => false,
				'error'   => 'Incomplete data for this request.'
			);
			return $result;
		}
			$this->model->setCorrelativa_id($correlativa_id);
			$this->model->setCorrelativa_fvigente($correlativa_fvigente);
			$this->model->setCorrelativa_decreto($correlativa_decreto);
			$this->model->setCorrelativa_observacion($correlativa_observacion);
			$this->model->setCorrelativa_origen($correlativa_origen);
			$this->model->setCorrelativa_destino($correlativa_destino);
			$this->model->setCorrelativa_uinsert($correlativa_uinsert);
			$this->model->setCorrelativa_finsert($correlativa_finsert);
			$this->model->setCorrelativa_uupdate($correlativa_uupdate);
			$this->model->setCorrelativa_fupdate($correlativa_fupdate);
		

		if ($action == 'create') {
		}
		elseif ($action == 'modify') {
		}
		$result = array('success' => true);
		return $result;
	}

	public function create($params)
	{
		$result = $this->setData($params, 'create');
		if (!$result['success']) {
			return $result;
		}

		$result = $this->modelAdo->create($this->model);
		if ($result['success']) {
			return ['success' => true];
		}
		return $result;
	}

	public function modify($params)
	{
		$result = $this->setData($params, 'modify');
		if (!$result['success']) {
			return $result;
		}

		$result = $this->modelAdo->update($this->model);
		if ($result['success']) {
			return ['success' => true];
		}
		return $result;
	}

}	

