<?php

require PATH_APP.'min_agricultura/Entities/Tipo_indicador.php';
require PATH_APP.'min_agricultura/Ado/Tipo_indicadorAdo.php';
require_once ('BaseRepo.php');

class Tipo_indicadorRepo extends BaseRepo {

	public function getModel()
	{
		return new Tipo_indicador;
	}
	
	public function getModelAdo()
	{
		return new Tipo_indicadorAdo;
	}

	public function getPrimaryKey()
	{
		return 'tipo_indicador_id';
	}

	public function validateModify($params)
	{
		extract($params);
		$result = $this->findPrimaryKey($tipo_indicador_id);

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
			$result = $this->findPrimaryKey($tipo_indicador_id);

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
			empty($tipo_indicador_id) ||
			empty($tipo_indicador_nombre) ||
			empty($tipo_indicador_abrev) ||
			empty($tipo_indicador_activador) ||
			empty($tipo_indicador_calculo) ||
			empty($tipo_indicador_definicion)
		) {
			$result = array(
				'success' => false,
				'error'   => 'Incomplete data for this request.'
			);
			return $result;
		}
			$this->model->setTipo_indicador_id($tipo_indicador_id);
			$this->model->setTipo_indicador_nombre($tipo_indicador_nombre);
			$this->model->setTipo_indicador_abrev($tipo_indicador_abrev);
			$this->model->setTipo_indicador_activador($tipo_indicador_activador);
			$this->model->setTipo_indicador_calculo($tipo_indicador_calculo);
			$this->model->setTipo_indicador_definicion($tipo_indicador_definicion);
		

		if ($action == 'create') {
		}
		elseif ($action == 'modify') {
		}
		$result = array('success' => true);
		return $result;
	}

}	

