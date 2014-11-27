<?php

require PATH_APP.'min_agricultura/Entities/Declaraexp.php';
require PATH_APP.'min_agricultura/Ado/DeclaraexpAdo.php';
require_once ('BaseRepo.php');

class DeclaraexpRepo extends BaseRepo {

	public function getModel()
	{
		return new Declaraexp;
	}
	
	public function getModelAdo()
	{
		return new DeclaraexpAdo;
	}

	public function getPrimaryKey()
	{
		return 'id';
	}

	public function validateModify($params)
	{
		extract($params);
		$result = $this->findPrimaryKey($id);

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
			$result = $this->findPrimaryKey($id);

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
			empty($id) ||
			empty($anio) ||
			empty($periodo) ||
			empty($id_empresa) ||
			empty($id_paisdestino) ||
			empty($id_capitulo) ||
			empty($id_partida) ||
			empty($id_subpartida) ||
			empty($id_posicion) ||
			empty($id_ciiu) ||
			empty($valorfob) ||
			empty($valorcif) ||
			empty($peso_neto)
		) {
			$result = array(
				'success' => false,
				'error'   => 'Incomplete data for this request.'
			);
			return $result;
		}
			$this->model->setId($id);
			$this->model->setAnio($anio);
			$this->model->setPeriodo($periodo);
			$this->model->setId_empresa($id_empresa);
			$this->model->setId_paisdestino($id_paisdestino);
			$this->model->setId_capitulo($id_capitulo);
			$this->model->setId_partida($id_partida);
			$this->model->setId_subpartida($id_subpartida);
			$this->model->setId_posicion($id_posicion);
			$this->model->setId_ciiu($id_ciiu);
			$this->model->setValorfob($valorfob);
			$this->model->setValorcif($valorcif);
			$this->model->setPeso_neto($peso_neto);
		

		if ($action == 'create') {
		}
		elseif ($action == 'modify') {
		}
		$result = array('success' => true);
		return $result;
	}

}	

