<?php

require PATH_APP.'min_agricultura/Repositories/AcuerdoRepo.php';
require PATH_APP.'min_agricultura/Repositories/UserRepo.php';

class AcuerdoController {
	
	protected $acuerdoRepo;

	public function __construct()
	{
		$this->acuerdoRepo = new AcuerdoRepo;
		$this->userRepo        = new UserRepo;
	}
	
	public function listAction($urlParams, $postParams)
    {
        return $this->acuerdoRepo->listAll($postParams);
    }

}
	

