<?php

require PATH_APP.'min_agricultura/Entities/Indicador.php';
require PATH_APP.'min_agricultura/Ado/IndicadorAdo.php';
require_once ('BaseRepo.php');

class IndicadorRepo extends BaseRepo {

	public function getModel()
	{
		return new Indicador;
	}
	
	public function getModelAdo()
	{
		return new IndicadorAdo;
	}

	public function getPrimaryKey()
	{
		return 'indicador_id';
	}

	public function validateModify($params)
	{
		extract($params);
		$result = $this->findPrimaryKey($indicador_id);

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
			$result = $this->findPrimaryKey($indicador_id);

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
			empty($indicador_id) ||
			empty($indicador_nombre) ||
			empty($indicador_tipo_indicador_id) ||
			empty($indicador_campos) ||
			empty($indicador_filtros) ||
			empty($indicador_leaf) ||
			empty($indicador_parent) ||
			empty($indicador_uinsert) ||
			empty($indicador_finsert) ||
			empty($indicador_fupdate)
		) {
			$result = array(
				'success' => false,
				'error'   => 'Incomplete data for this request.'
			);
			return $result;
		}
			$this->model->setIndicador_id($indicador_id);
			$this->model->setIndicador_nombre($indicador_nombre);
			$this->model->setIndicador_tipo_indicador_id($indicador_tipo_indicador_id);
			$this->model->setIndicador_campos($indicador_campos);
			$this->model->setIndicador_filtros($indicador_filtros);
			$this->model->setIndicador_leaf($indicador_leaf);
			$this->model->setIndicador_parent($indicador_parent);
			$this->model->setIndicador_uinsert($indicador_uinsert);
			$this->model->setIndicador_finsert($indicador_finsert);
			$this->model->setIndicador_fupdate($indicador_fupdate);
		

		if ($action == 'create') {
		}
		elseif ($action == 'modify') {
		}
		$result = array('success' => true);
		return $result;
	}

}	

