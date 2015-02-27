<?php

require PATH_MODELS.'Repositories/AcuerdoRepo.php';
require PATH_MODELS.'Repositories/UserRepo.php';

class AcuerdoController {
	
	protected $acuerdoRepo;
	protected $userRepo;

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
	

