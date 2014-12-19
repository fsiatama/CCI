<?php

require PATH_APP.'min_agricultura/Entities/Pib.php';
require PATH_APP.'min_agricultura/Ado/PibAdo.php';
require_once ('BaseRepo.php');

class PibRepo extends BaseRepo {

	public function getModel()
	{
		return new Pib;
	}
	
	public function getModelAdo()
	{
		return new PibAdo;
	}

	public function getPrimaryKey()
	{
		return 'pib_id';
	}

	public function validateModify($params)
	{
		extract($params);
		$result = $this->findPrimaryKey($pib_id);

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
			$result = $this->findPrimaryKey($pib_id);

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
			empty($pib_id) ||
			empty($pib_anio) ||
			empty($pib_periodo) ||
			empty($pib_agricultura) ||
			empty($pib_nacional) ||
			empty($pib_finsert) ||
			empty($pib_uinsert) ||
			empty($pib_fupdate) ||
			empty($pib_uupdate)
		) {
			$result = array(
				'success' => false,
				'error'   => 'Incomplete data for this request.'
			);
			return $result;
		}
			$this->model->setPib_id($pib_id);
			$this->model->setPib_anio($pib_anio);
			$this->model->setPib_periodo($pib_periodo);
			$this->model->setPib_agricultura($pib_agricultura);
			$this->model->setPib_nacional($pib_nacional);
			$this->model->setPib_finsert($pib_finsert);
			$this->model->setPib_uinsert($pib_uinsert);
			$this->model->setPib_fupdate($pib_fupdate);
			$this->model->setPib_uupdate($pib_uupdate);
		

		if ($action == 'create') {
		}
		elseif ($action == 'modify') {
		}
		$result = array('success' => true);
		return $result;
	}

}	

