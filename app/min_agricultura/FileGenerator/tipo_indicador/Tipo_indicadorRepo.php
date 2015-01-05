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
			empty($tipo_indicador_definicion) ||
			empty($tipo_indicador_html)
		) {
			$result = [
				'success' => false,
				'error'   => 'Incomplete data for this request.'
			];
			return $result;
		}
		$this->model->setTipo_indicador_id($tipo_indicador_id);
		$this->model->setTipo_indicador_nombre($tipo_indicador_nombre);
		$this->model->setTipo_indicador_abrev($tipo_indicador_abrev);
		$this->model->setTipo_indicador_activador($tipo_indicador_activador);
		$this->model->setTipo_indicador_calculo($tipo_indicador_calculo);
		$this->model->setTipo_indicador_definicion($tipo_indicador_definicion);
		$this->model->setTipo_indicador_html($tipo_indicador_html);

		if ($action == 'create') {
		} elseif ($action == 'modify') {
		}
		$result = ['success' => true];
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
			$this->model->setTipo_indicador_id(implode('", "', $query));
			$this->model->setTipo_indicador_nombre(implode('", "', $query));
			$this->model->setTipo_indicador_abrev(implode('", "', $query));
			$this->model->setTipo_indicador_activador(implode('", "', $query));
			$this->model->setTipo_indicador_calculo(implode('", "', $query));
			$this->model->setTipo_indicador_definicion(implode('", "', $query));
			$this->model->setTipo_indicador_html(implode('", "', $query));

			return $this->modelAdo->inSearch($this->model);
		}
		else {
			$this->model->setTipo_indicador_id($query);
			$this->model->setTipo_indicador_nombre($query);
			$this->model->setTipo_indicador_abrev($query);
			$this->model->setTipo_indicador_activador($query);
			$this->model->setTipo_indicador_calculo($query);
			$this->model->setTipo_indicador_definicion($query);
			$this->model->setTipo_indicador_html($query);

			return $this->modelAdo->paginate($this->model, 'LIKE', $limit, $page);
		}

	}

}
