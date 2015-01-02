<?php

require PATH_APP.'min_agricultura/Entities/Subpartida.php';
require PATH_APP.'min_agricultura/Ado/SubpartidaAdo.php';
require_once ('BaseRepo.php');

class SubpartidaRepo extends BaseRepo {

	public function getModel()
	{
		return new Subpartida;
	}
	
	public function getModelAdo()
	{
		return new SubpartidaAdo;
	}

	public function getPrimaryKey()
	{
		return 'id_subpartida';
	}

	public function validateModify($params)
	{
		extract($params);
		$result = $this->findPrimaryKey($id_subpartida);

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
			$result = $this->findPrimaryKey($id_subpartida);

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
			empty($id_subpartida) ||
			empty($subpartida) ||
			empty($id_capitulo) ||
			empty($id_partida)
		) {
			$result = [
				'success' => false,
				'error'   => 'Incomplete data for this request.'
			];
			return $result;
		}
		$this->model->setId_subpartida($id_subpartida);
		$this->model->setSubpartida($subpartida);
		$this->model->setId_capitulo($id_capitulo);
		$this->model->setId_partida($id_partida);

		if ($action == 'create') {
		} elseif ($action == 'modify') {
		}
		$result = ['success' => true];
		return $result;
	}

}	

