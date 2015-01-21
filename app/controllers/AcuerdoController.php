<?php

require PATH_APP.'min_agricultura/Repositories/AcuerdoRepo.php';
require PATH_APP.'min_agricultura/Repositories/UserRepo.php';

class AcuerdoController {
	
	private $acuerdoRepo;

	public function __construct()
	{
		$this->acuerdoRepo = new AcuerdoRepo;
		$this->userRepo    = new UserRepo;
	}

	public function listAction($urlParams, $postParams)
	{
		$result = $this->userRepo->validateMenu('list', $postParams);

		if ($result['success']) {
			$result = $this->acuerdoRepo->grid($postParams);
		}
		return $result;
	}

	public function listIdAction($urlParams, $postParams)
	{
		return $this->acuerdoRepo->validateModify($postParams);
	}

	public function createAction($urlParams, $postParams)
	{
		$result = $this->userRepo->validateMenu('create', $postParams);

		if ($result['success']) {
			$result = $this->acuerdoRepo->create($postParams);
		}
		return $result;
	}

	public function modifyAction($urlParams, $postParams)
	{
		$result = $this->userRepo->validateMenu('modify', $postParams);

		if ($result['success']) {
			$result = $this->acuerdoRepo->modify($postParams);
		}
		return $result;
	}

	public function deleteAction($urlParams, $postParams)
	{
		$result = $this->userRepo->validateMenu('delete', $postParams);

		if ($result['success']) {
			$result = $this->acuerdoRepo->delete($postParams);
		}
		return $result;
	}

	public function jscodeAction($urlParams, $postParams)
	{
		$action = array_shift($urlParams);
		$action = (empty($action)) ? 'list' : $action;

		$result = $this->userRepo->validateMenu($action, $postParams);

		if ($result['success']) {
			if ($action == 'modify') {
				$result = $this->acuerdoRepo->validateModify($postParams);
				if (!$result['success']) {
					return $result;
				}
			}

			$lines = Helpers::getRequire(PATH_APP.'lib/indicador.config.php');
			$trade = Helpers::arrayGet($lines, 'trade');

			$postParams['is_template'] = true;
			$params = array_merge($postParams, $result, compact('action', 'trade'));
			
			//el template de adicionar y editar son los mismos
			$action = ($action == 'modify') ? 'create' : $action;

			return new View('jsCode/acuerdo.'.$action, $params);
		}
		
		return $result;
	}

	public function jscodeExecuteAction($urlParams, $postParams)
	{
		$action = array_shift($urlParams);
		$action = (empty($action)) ? 'list' : $action;

		$result = $this->userRepo->validateMenu($action, $postParams);

		if ($result['success']) {
			//verifica que exista el acuerdo y trae los datos
			$result = $this->acuerdoRepo->listId($postParams);
			if (!$result['success']) {
				return $result;
			}
			$rowAcuerdo  = array_shift($result['data']);
			$countryData = $result['country_data'];

			$postParams['is_template'] = true;

			$params = array_merge($postParams, $rowAcuerdo, compact('countryData'));

			$postParams['is_template'] = true;
			return new View('jsCode/acuerdo.execute', $params);
		}
	}

}
	

