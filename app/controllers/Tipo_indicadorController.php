<?php

require PATH_APP.'min_agricultura/Repositories/Tipo_indicadorRepo.php';
require PATH_APP.'min_agricultura/Repositories/UserRepo.php';

class Tipo_indicadorController {
	
	private $tipo_indicadorRepo;

	public function __construct()
	{
		$this->tipo_indicadorRepo = new Tipo_indicadorRepo;
		$this->userRepo        = new UserRepo;
	}
	
	public function listAction($urlParams, $postParams)
	{
		$result = $this->userRepo->validateMenu('list', $postParams);

		if ($result['success']) {
			$result = $this->tipo_indicadorRepo->grid($postParams);
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
				$result = $this->tipo_indicadorRepo->validateModify($postParams);
				if (!$result['success']) {
					return $result;
				}
			}

			$postParams['is_template'] = true;
			
			$updateInfoImpo = Helpers::getUpdateInfo('aduanas', 'impo');
			$updateInfoExpo = Helpers::getUpdateInfo('aduanas', 'expo');

			$params = array_merge($postParams, $result, compact('action', 'updateInfoImpo', 'updateInfoExpo'));

			//el template de adicionar y editar son los mismos
			$action = ($action == 'modify') ? 'create' : $action;

			return new View('jsCode/tipo_indicador.'.$action, $params);
		}
		
		return $result;
	}

}
	

