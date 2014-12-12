<?php

require PATH_APP.'min_agricultura/Entities/Sector.php';
require PATH_APP.'min_agricultura/Ado/SectorAdo.php';
require_once ('BaseRepo.php');

class SectorRepo extends BaseRepo {

	public function getModel()
	{
		return new Sector;
	}
	
	public function getModelAdo()
	{
		return new SectorAdo;
	}

	public function getPrimaryKey()
	{
		return 'sector_id';
	}

	public function validateModify($params)
	{
		extract($params);
		$result = $this->findPrimaryKey($sector_id);

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
			$result = $this->findPrimaryKey($sector_id);

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
			empty($sector_id) ||
			empty($sector_nombre) ||
			empty($sector_productos) ||
			empty($sector_uinsert) ||
			empty($sector_finsert) ||
			empty($sector_uupdate) ||
			empty($sector_fupdate)
		) {
			$result = array(
				'success' => false,
				'error'   => 'Incomplete data for this request.'
			);
			return $result;
		}
			$this->model->setSector_id($sector_id);
			$this->model->setSector_nombre($sector_nombre);
			$this->model->setSector_productos($sector_productos);
			$this->model->setSector_uinsert($sector_uinsert);
			$this->model->setSector_finsert($sector_finsert);
			$this->model->setSector_uupdate($sector_uupdate);
			$this->model->setSector_fupdate($sector_fupdate);
		

		if ($action == 'create') {
		}
		elseif ($action == 'modify') {
		}
		$result = array('success' => true);
		return $result;
	}

}	
