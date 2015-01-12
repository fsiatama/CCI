<?php

require PATH_APP.'min_agricultura/Repositories/CorrelativaRepo.php';
require PATH_APP.'min_agricultura/Repositories/UserRepo.php';

class CorrelativaController {
	
	private $correlativaRepo;
	private $userRepo;

	public function __construct()
	{
		$this->correlativaRepo = new CorrelativaRepo;
		$this->userRepo        = new UserRepo;
	}
	
	public function jscodeAction($urlParams, $postParams)
	{
		$action = array_shift($urlParams);
		$action = (empty($action)) ? 'list' : $action;
		$result = $this->userRepo->validateMenu($action, $postParams);

		if ($result['success']) {
			if ($action == 'modify') {
				$result = $this->correlativaRepo->validateModify($postParams);
				if (!$result['success']) {
					return $result;
				}
			}
			$postParams['is_template'] = true;
			$params = array_merge($postParams, $result, compact('action'));
			
			//el template de adicionar y editar son los mismos
			$action = ($action == 'modify') ? 'create' : $action;

			return new View('jsCode/correlativa.'.$action, $params);
		}
		
		return $result;
	}

	public function listAction($urlParams, $postParams)
	{
		$result = $this->userRepo->validateMenu('list', $postParams);

		if ($result['success']) {
			$result = $this->correlativaRepo->grid($postParams);
		}
		return $result;
	}

	public function listIdAction($urlParams, $postParams)
	{
		return $this->correlativaRepo->validateModify($postParams);
	}

	public function createAction($urlParams, $postParams)
	{
		$result = $this->userRepo->validateMenu('create', $postParams);

		if ($result['success']) {
			$result = $this->correlativaRepo->create($postParams);
		}
		return $result;
	}

	public function modifyAction($urlParams, $postParams)
	{
		$result = $this->userRepo->validateMenu('modify', $postParams);

		if ($result['success']) {
			$result = $this->correlativaRepo->modify($postParams);
		}
		return $result;
	}

	public function deleteAction($urlParams, $postParams)
	{
		$result = $this->userRepo->validateMenu('delete', $postParams);

		if ($result['success']) {
			$result = $this->correlativaRepo->delete($postParams);
		}
		return $result;
	}

}
	

