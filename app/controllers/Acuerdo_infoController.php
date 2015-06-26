<?php

require PATH_MODELS.'Repositories/Acuerdo_infoRepo.php';
require PATH_MODELS.'Repositories/UserRepo.php';

class Acuerdo_infoController {
	
	protected $acuerdo_infoRepo;
	protected $userRepo;

	public function __construct()
	{
		$this->acuerdo_infoRepo = new Acuerdo_infoRepo;
		$this->userRepo        = new UserRepo;
	}
	
	public function listAction($urlParams, $postParams)
	{
		$result = $this->userRepo->validateMenu('list', $postParams);

		if ($result['success']) {
			$result = $this->acuerdo_infoRepo->grid($postParams);
		}
		return $result;
	}

	public function publicSearchAction($urlParams, $postParams)
	{
		return $this->acuerdo_infoRepo->publicSearch($postParams);
	}

	public function listIdAction($urlParams, $postParams)
	{
		return $this->acuerdo_infoRepo->validateModify($postParams);
	}

	public function listIdPublicAction($urlParams, $postParams)
	{
		$result = $this->acuerdo_infoRepo->listId($postParams);
		if ($result['success']) {
			$is_template = true;

			$row = array_shift($result['data']);

			$params = compact('is_template', 'row');

			$view = new View('descripcion_acuerdo', $params);

			ob_start();

			$view->execute();
			$html = ob_get_clean();

			$result = [
				'success' => true,
				'html'    => $html
			];
		}
		return $result;

	}

	public function createAction($urlParams, $postParams)
	{
		$result = $this->userRepo->validateMenu('create', $postParams);

		if ($result['success']) {
			$result = $this->acuerdo_infoRepo->create($postParams);
		}
		return $result;
	}

	public function modifyAction($urlParams, $postParams)
	{
		$result = $this->userRepo->validateMenu('modify', $postParams);

		if ($result['success']) {
			$result = $this->acuerdo_infoRepo->modify($postParams);
		}
		return $result;
	}

	public function deleteAction($urlParams, $postParams)
	{
		$result = $this->userRepo->validateMenu('delete', $postParams);

		if ($result['success']) {
			$result = $this->acuerdo_infoRepo->delete($postParams);
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
				$result = $this->acuerdo_infoRepo->validateModify($postParams);
				if (!$result['success']) {
					return $result;
				}
			}

			$state                     = lang::get('acuerdo_info.acuerdo_estado');
			$postParams['is_template'] = true;
			$params                    = array_merge($postParams, $result, compact('action', 'state'));
			
			//el template de adicionar y editar son los mismos
			$action = ($action == 'modify') ? 'create' : $action;

			return new View('jsCode/acuerdo_info.'.$action, $params);
		}
		
		return $result;
	}

}
	

