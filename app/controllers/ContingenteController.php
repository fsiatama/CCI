<?php

require PATH_MODELS.'Repositories/ContingenteRepo.php';
require PATH_MODELS.'Repositories/AcuerdoRepo.php';
require PATH_MODELS.'Repositories/UserRepo.php';

class ContingenteController {
	
	private $contingenteRepo;
	private $userRepo;

	public function __construct()
	{
		$this->contingenteRepo = new ContingenteRepo;
		$this->userRepo        = new UserRepo;
	}
	
	public function listAction($urlParams, $postParams)
	{
		$result = $this->userRepo->validateMenu('list', $postParams);

		if ($result['success']) {
			$result = $this->contingenteRepo->grid($postParams);
		}
		return $result;
	}

	public function listIdAction($urlParams, $postParams)
	{
		return $this->contingenteRepo->validateModify($postParams);
	}

	public function createAction($urlParams, $postParams)
	{
		$result = $this->userRepo->validateMenu('create', $postParams);

		if ($result['success']) {
			$result = $this->contingenteRepo->create($postParams);
		}
		return $result;
	}

	public function modifyAction($urlParams, $postParams)
	{
		$result = $this->userRepo->validateMenu('modify', $postParams);

		if ($result['success']) {
			$result = $this->contingenteRepo->modify($postParams);
		}
		return $result;
	}

	public function deleteAction($urlParams, $postParams)
	{
		$result = $this->userRepo->validateMenu('delete', $postParams);

		if ($result['success']) {
			$result = $this->contingenteRepo->delete($postParams);
		}
		return $result;
	}

	public function jscodeAction($urlParams, $postParams)
	{
		$action = array_shift($urlParams);
		$action = (empty($action)) ? 'list' : $action;

		$acuerdo_id = (!isset($postParams['acuerdo_id'])) ? $postParams['acuerdo_det_acuerdo_id'] : $postParams['acuerdo_id'] ;

		$result = $this->userRepo->validateMenu($action, $postParams);

		if ($result['success']) {
			
			$acuerdoRepo = new AcuerdoRepo;
			//verifica que exista el acuerdo y trae los datos incluido un array con los datos de los paises del acuerdo
			$rs = $acuerdoRepo->listId(compact('acuerdo_id'));
			if (!$rs['success']) {
				return $rs;
			}
			$rowAcuerdo   = array_shift($rs['data']);
			$country_data = $rs['country_data'];

			if ($action == 'modify') {
				$result = $this->contingenteRepo->validateModify($postParams);
				if (!$result['success']) {
					return $result;
				}
			}

			$postParams['is_template'] = true;
			$params = array_merge($postParams, $result, compact('country_data'), $rowAcuerdo, compact('action'));
			//var_dump($params);

			//el template de adicionar y editar son los mismos
			$action = ($action == 'modify') ? 'create' : $action;

			return new View('jsCode/contingente.'.$action, $params);
		}
		
		return $result;
	}

}
	

