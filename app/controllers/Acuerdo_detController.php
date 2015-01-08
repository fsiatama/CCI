<?php

require PATH_APP.'min_agricultura/Repositories/Acuerdo_detRepo.php';
require PATH_APP.'min_agricultura/Repositories/AcuerdoRepo.php';
require PATH_APP.'min_agricultura/Repositories/UserRepo.php';

class Acuerdo_detController {
	
	protected $acuerdo_detRepo;

	public function __construct()
	{
		$this->acuerdo_detRepo = new Acuerdo_detRepo;
		$this->acuerdoRepo     = new AcuerdoRepo;
		$this->userRepo        = new UserRepo;
	}
	
	public function listAction($urlParams, $postParams)
	{
		$result = $this->userRepo->validateMenu('list', $postParams);

		if ($result['success']) {
			$result = $this->acuerdo_detRepo->grid($postParams);
		}
		return $result;
	}

	public function listIdAction($urlParams, $postParams)
	{
		return $this->acuerdo_detRepo->validateModify($postParams);
	}

	public function createAction($urlParams, $postParams)
	{
		$result = $this->userRepo->validateMenu('create', $postParams);

		if ($result['success']) {
			$result = $this->acuerdo_detRepo->create($postParams);
		}
		return $result;
	}

	public function modifyAction($urlParams, $postParams)
	{
		$result = $this->userRepo->validateMenu('modify', $postParams);

		if ($result['success']) {
			$result = $this->acuerdo_detRepo->modify($postParams);
		}
		return $result;
	}

	public function deleteAction($urlParams, $postParams)
	{
		$result = $this->userRepo->validateMenu('delete', $postParams);

		if ($result['success']) {
			$result = $this->acuerdo_detRepo->delete($postParams);
		}
		return $result;
	}

	public function jscodeAction($urlParams, $postParams)
	{
		$action = array_shift($urlParams);
		$action = (empty($action)) ? 'list' : $action;

		$acuerdo_id = (!isset($acuerdo_id)) ? $acuerdo_det_acuerdo_id : $acuerdo_id ;

		$result = $this->userRepo->validateMenu($action, $postParams);

		if ($result['success']) {
			
			//verifica que exista el acuerdo y trae los datos
			$result = $this->acuerdoRepo->validateModify(compact('acuerdo_id'));
			if (!$result['success']) {
				return $result;
			}
			$rowAcuerdo = array_shift($result['data']);

			if ($action == 'modify') {
				$result = $this->acuerdo_detRepo->validateModify($postParams);
				if (!$result['success']) {
					return $result;
				}
			}

			$postParams['is_template'] = true;
			$params = array_merge($postParams, $result, $rowAcuerdo, compact('action'));

			//el template de adicionar y editar son los mismos
			$action = ($action == 'modify') ? 'create' : $action;

			return new View('jsCode/acuerdo_det.'.$action, $params);
		}
		
		return $result;
	}

}
	

