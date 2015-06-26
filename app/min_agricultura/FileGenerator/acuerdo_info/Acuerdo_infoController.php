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
        return $this->acuerdo_infoRepo->listAll($postParams);
    }

}
	

