<?php

require PATH_APP.'min_agricultura/Repositories/CorrelativaRepo.php';
require PATH_APP.'min_agricultura/Repositories/UserRepo.php';

class CorrelativaController {
	
	protected $correlativaRepo;
	protected $userRepo;

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
			$postParams['is_template'] = true;
			$params = array_merge($postParams, $result);

			//el template de adicionar y editar son los mismos
			//action = ($action == 'modify') ? 'create' : $action;

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

	public function createAction($urlParams, $postParams)
	{
		$result = $this->userRepo->validateMenu('create', $postParams);

		if ($result['success']) {
			$result = $this->correlativaRepo->create($postParams);
		}
		return $result;
	}

}
	

