<?php

require PATH_APP.'min_agricultura/Repositories/Desgravacion_detRepo.php';
require PATH_APP.'min_agricultura/Repositories/UserRepo.php';

class Desgravacion_detController {
	
	protected $desgravacion_detRepo;

	public function __construct()
	{
		$this->desgravacion_detRepo = new Desgravacion_detRepo;
		$this->userRepo        = new UserRepo;
	}

	public function listAction($urlParams, $postParams)
	{
		$result = $this->userRepo->validateMenu('list', $postParams);

		if ($result['success']) {
			$result = $this->desgravacion_detRepo->grid($postParams);
		}
		return $result;
	}

	public function saveGridAction($urlParams, $postParams)
	{
		$result = $this->userRepo->validateMenu('list', $postParams);

		if ($result['success']) {
			$result = $this->desgravacion_detRepo->updateByAgreementDet($postParams);
		}

		return $result;
	}


}
	

