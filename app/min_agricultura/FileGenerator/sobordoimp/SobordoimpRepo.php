<?php

require PATH_MODELS.'Entities/Sobordoimp.php';
require PATH_MODELS.'Ado/SobordoimpAdo.php';
require_once ('BaseRepo.php');

class SobordoimpRepo extends BaseRepo {

	public function getModel()
	{
		return new Sobordoimp;
	}
	
	public function getModelAdo()
	{
		return new SobordoimpAdo;
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
			empty($id_paisprocedencia) ||
			empty($id_capitulo) ||
			empty($id_partida) ||
			empty($id_subpartida) ||
			empty($peso_neto)
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
		$this->model->setId_paisprocedencia($id_paisprocedencia);
		$this->model->setId_capitulo($id_capitulo);
		$this->model->setId_partida($id_partida);
		$this->model->setId_subpartida($id_subpartida);
		$this->model->setPeso_neto($peso_neto);

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
			$this->model->setId(implode('", "', $query));
			$this->model->setAnio(implode('", "', $query));
			$this->model->setPeriodo(implode('", "', $query));
			$this->model->setFecha(implode('", "', $query));
			$this->model->setId_paisprocedencia(implode('", "', $query));
			$this->model->setId_capitulo(implode('", "', $query));
			$this->model->setId_partida(implode('", "', $query));
			$this->model->setId_subpartida(implode('", "', $query));
			$this->model->setPeso_neto(implode('", "', $query));

			return $this->modelAdo->inSearch($this->model);
		}
		else {
			$this->model->setId($query);
			$this->model->setAnio($query);
			$this->model->setPeriodo($query);
			$this->model->setFecha($query);
			$this->model->setId_paisprocedencia($query);
			$this->model->setId_capitulo($query);
			$this->model->setId_partida($query);
			$this->model->setId_subpartida($query);
			$this->model->setPeso_neto($query);

			return $this->modelAdo->paginate($this->model, 'LIKE', $limit, $page);
		}

	}

}
