<?php

require PATH_APP.'min_agricultura/Repositories/SectorRepo.php';
require PATH_APP.'min_agricultura/Repositories/UserRepo.php';

class SectorController {
	
	protected $sectorRepo;

	public function __construct()
	{
		$this->sectorRepo = new SectorRepo;
		$this->userRepo   = new UserRepo;
	}
	
	public function listAction($urlParams, $postParams)
    {
    	$result = $this->userRepo->validateMenu('list', $postParams);

    	if ($result['success']) {
    		$result = $this->sectorRepo->grid($postParams);
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
				$result = $this->sectorRepo->validateModify($postParams);
				if (!$result['success']) {
					return $result;
				}
			}
			$postParams['is_template'] = true;
			$params = array_merge($postParams, $result, compact('action'));
			
			//el template de adicionar y editar son los mismos
			$action = ($action == 'modify') ? 'create' : $action;

			return new View('jsCode/sector.'.$action, $params);
		}
		
		return $result;
	}

}
	

