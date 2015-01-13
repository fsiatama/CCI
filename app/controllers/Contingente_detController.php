<?php

require PATH_APP.'min_agricultura/Repositories/Contingente_detRepo.php';
require PATH_APP.'min_agricultura/Repositories/UserRepo.php';

class Contingente_detController {
	
	protected $contingente_detRepo;

	public function __construct()
	{
		$this->contingente_detRepo = new Contingente_detRepo;
		$this->userRepo        = new UserRepo;
	}

	public function listAction($urlParams, $postParams)
	{
		$result = $this->userRepo->validateMenu('list', $postParams);

		if ($result['success']) {
			$result = $this->contingente_detRepo->grid($postParams);
		}
		return $result;
	}

	public function saveGridAction($urlParams, $postParams)
	{
		$result = $this->userRepo->validateMenu('list', $postParams);

		if ($result['success']) {
			$result = $this->contingente_detRepo->updateByAgreementDet($postParams);
		}

		return $result;
	}


}
	

