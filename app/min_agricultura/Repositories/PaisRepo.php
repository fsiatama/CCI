<?php

require PATH_APP.'min_agricultura/Entities/Pais.php';
require PATH_APP.'min_agricultura/Ado/PaisAdo.php';
require_once ('BaseRepo.php');

class PaisRepo extends BaseRepo {

	public function getModel()
	{
		return new Pais;
	}
	
	public function getModelAdo()
	{
		return new PaisAdo;
	}

	public function getPrimaryKey()
	{
		return 'id_pais';
	}

	public function validateModify($params)
	{
		extract($params);
		$result = $this->findPrimaryKey($id_pais);

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
			$result = $this->findPrimaryKey($id_pais);

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
			empty($id_pais) ||
			empty($pais)
		) {
			$result = array(
				'success' => false,
				'error'   => 'Incomplete data for this request.'
			);
			return $result;
		}
			$this->model->setId_pais($id_pais);
			$this->model->setPais($pais);
		

		if ($action == 'create') {
		}
		elseif ($action == 'modify') {
		}
		$result = array('success' => true);
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
			$this->model->setId_pais(implode('", "', $query));
			return $this->modelAdo->inSearch($this->model);
		}
		else {
			$this->model->setId_pais($query);
			$this->model->setPais($query);
			return $this->modelAdo->paginate($this->model, 'LIKE', $limit, $page);
		}

	}

}	

