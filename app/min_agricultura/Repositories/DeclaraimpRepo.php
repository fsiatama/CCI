<?php

require PATH_MODELS.'Entities/Declaraimp.php';
require PATH_MODELS.'Ado/DeclaraimpAdo.php';
require_once ('BaseRepo.php');

class DeclaraimpRepo extends BaseRepo {

	public function getModel()
	{
		return new Declaraimp;
	}
	
	public function getModelAdo()
	{
		return new DeclaraimpAdo;
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
			empty($fecha) ||
			empty($id_empresa) ||
			empty($id_paisorigen) ||
			empty($id_paiscompra) ||
			empty($id_paisprocedencia) ||
			empty($id_deptorigen) ||
			empty($id_capitulo) ||
			empty($id_partida) ||
			empty($id_subpartida) ||
			empty($id_posicion) ||
			empty($id_ciiu) ||
			empty($valorcif) ||
			empty($valorfob) ||
			empty($peso_neto) ||
			empty($arancel_pagado) ||
			empty($valorarancel)
		) {
			$result = [
				'success' => false,
				'error'   => 'Incomplete data for this request.'
			];
			return $result;
		}
		$this->model->setId($id);
		$this->model->setAnio($anio);
		$this->model->setPeriodo($periodo);
		$this->model->setFecha($fecha);
		$this->model->setId_empresa($id_empresa);
		$this->model->setId_paisorigen($id_paisorigen);
		$this->model->setId_paiscompra($id_paiscompra);
		$this->model->setId_paisprocedencia($id_paisprocedencia);
		$this->model->setId_deptorigen($id_deptorigen);
		$this->model->setId_capitulo($id_capitulo);
		$this->model->setId_partida($id_partida);
		$this->model->setId_subpartida($id_subpartida);
		$this->model->setId_posicion($id_posicion);
		$this->model->setId_ciiu($id_ciiu);
		$this->model->setValorcif($valorcif);
		$this->model->setValorfob($valorfob);
		$this->model->setPeso_neto($peso_neto);
		$this->model->setArancel_pagado($arancel_pagado);
		$this->model->setValorarancel($valorarancel);

		if ($action == 'create') {
		} elseif ($action == 'modify') {
		}
		$result = ['success' => true];
		return $result;
	}

}	

