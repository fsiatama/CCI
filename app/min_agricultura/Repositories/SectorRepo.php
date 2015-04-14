<?php

require PATH_MODELS.'Entities/Sector.php';
require PATH_MODELS.'Ado/SectorAdo.php';
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

	public function grid($params)
	{
		extract($params);

		$start = ( isset($start) ) ? $start : 0;
		$limit = ( isset($limit) ) ? $limit : 30;
		$page  = ( $start==0 ) ? 1 : ( $start/$limit )+1;

		if (!empty($query)) {
			if (!empty($fullTextFields)) {

				$fullTextFields = json_decode(stripslashes($fullTextFields));

				foreach ($fullTextFields as $value) {
					$methodName = $this->getColumnMethodName('set', $value);

					if (method_exists($this->model, $methodName)) {
						call_user_func_array([$this->model, $methodName], compact('query'));
					}
				}
			} else {
				$this->model->setSector_id($query);
				$this->model->setSector_nombre($query);
				$this->model->setSector_productos($query);
			}

		}
		$this->modelAdo->setColumns([
			'sector_id',
			'sector_nombre',
			'sector_productos'
		]);

		$result = $this->modelAdo->paginate($this->model, 'LIKE', $limit, $page);

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
			empty($sector_nombre) ||
			empty($sector_productos) ||
			!is_array($sector_productos)
		) {
			$result = array(
				'success' => false,
				'error'   => 'Incomplete data for this request.'
			);
			return $result;
		}
		$this->model->setSector_id($sector_id);
		$this->model->setSector_nombre($sector_nombre);
		$this->model->setSector_productos(implode(',', $sector_productos));

		if ($action == 'create') {
			$this->model->setSector_uinsert($_SESSION['user_id']);
			$this->model->setSector_finsert(Helpers::getDateTimeNow());
		} elseif ($action == 'modify') {
			$this->model->setSector_uupdate($_SESSION['user_id']);
			$this->model->setSector_fupdate(Helpers::getDateTimeNow());
		}
		$result = array('success' => true);
		return $result;
	}

}

