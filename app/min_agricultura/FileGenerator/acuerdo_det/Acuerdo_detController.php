<?php

require PATH_MODELS.'Repositories/Acuerdo_detRepo.php';
require PATH_MODELS.'Repositories/UserRepo.php';

class Acuerdo_detController {
	
	protected $acuerdo_detRepo;

	public function __construct()
	{
		$this->acuerdo_detRepo = new Acuerdo_detRepo;
		$this->userRepo        = new UserRepo;
	}
	
	public function listAction($urlParams, $postParams)
    {
        return $this->acuerdo_detRepo->listAll($postParams);
    }

}
	

