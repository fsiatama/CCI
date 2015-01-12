<?php

require PATH_APP.'min_agricultura/Repositories/ProduccionRepo.php';
require PATH_APP.'min_agricultura/Repositories/UserRepo.php';

class ProduccionController {
	
	private $produccionRepo;
	private $userRepo;

	public function __construct()
	{
		$this->produccionRepo = new ProduccionRepo;
		$this->userRepo       = new UserRepo;
	}
	
	public function listAction($urlParams, $postParams)
	{
		$result = $this->userRepo->validateMenu('list', $postParams);

		if ($result['success']) {
			return $this->produccionRepo->grid($postParams);
		}
		return $result;
	}

	public function listIdAction($urlParams, $postParams)
	{
		return $this->produccionRepo->validateModify($postParams);
	}

	public function createAction($urlParams, $postParams)
	{
		$result = $this->userRepo->validateMenu('create', $postParams);

		if ($result['success']) {
			$result = $this->produccionRepo->create($postParams);
		}
		return $result;
	}

	public function modifyAction($urlParams, $postParams)
	{
		$result = $this->userRepo->validateMenu('modify', $postParams);

		if ($result['success']) {
			$result = $this->produccionRepo->modify($postParams);
		}
		return $result;
	}

	public function deleteAction($urlParams, $postParams)
	{
		$result = $this->userRepo->validateMenu('delete', $postParams);

		if ($result['success']) {
			$result = $this->produccionRepo->delete($postParams);
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
				$result = $this->produccionRepo->validateModify($postParams);
				if (!$result['success']) {
					return $result;
				}
			}
			$postParams['is_template'] = true;
			$lines = Helpers::getRequire(PATH_APP.'lib/indicador.config.php');
			$yearsAvailable = Helpers::arrayGet($lines, 'yearsAvailable');
			$params = array_merge($postParams, $result, compact('action', 'yearsAvailable'));
			
			//el template de adicionar y editar son los mismos
			$action = ($action == 'modify') ? 'create' : $action;

			return new View('jsCode/produccion.'.$action, $params);
		}
		
		return $result;
	}

}
	

