<?php

require PATH_MODELS.'Repositories/MercadoRepo.php';
require PATH_MODELS.'Repositories/UserRepo.php';

class MercadoController {
	
	private $mercadoRepo;
	private $userRepo;

	public function __construct()
	{
		$this->mercadoRepo = new MercadoRepo;
		$this->userRepo    = new UserRepo;
	}
	
	public function listAction($urlParams, $postParams)
	{
		$result = $this->userRepo->validateMenu('list', $postParams);

		if ($result['success']) {
			$result = $this->mercadoRepo->grid($postParams);
		}
		return $result;
	}

	public function listIdAction($urlParams, $postParams)
	{
		$result = $this->userRepo->validateMenu('list', $postParams);

		if ($result['success']) {
			$result = $this->mercadoRepo->listId($postParams);
		}

		return $result;
	}

	public function createAction($urlParams, $postParams)
	{
		$result = $this->userRepo->validateMenu('create', $postParams);

		if ($result['success']) {
			$result = $this->mercadoRepo->create($postParams);
		}
		return $result;
	}

	public function modifyAction($urlParams, $postParams)
	{
		$result = $this->userRepo->validateMenu('modify', $postParams);

		if ($result['success']) {
			$result = $this->mercadoRepo->modify($postParams);
		}
		return $result;
	}

	public function deleteAction($urlParams, $postParams)
	{
		$result = $this->userRepo->validateMenu('delete', $postParams);

		if ($result['success']) {
			$result = $this->mercadoRepo->delete($postParams);
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
				$result = $this->mercadoRepo->validateModify($postParams);
				if (!$result['success']) {
					return $result;
				}
			}
			$postParams['is_template'] = true;
			$params = array_merge($postParams, $result, compact('action'));
			
			//el template de adicionar y editar son los mismos
			$action = ($action == 'modify') ? 'create' : $action;

			return new View('jsCode/mercado.'.$action, $params);
		}
		
		return $result;
	}

}
	

