<?php

require PATH_MODELS.'Repositories/PaisRepo.php';
//require PATH_MODELS.'Repositories/UserRepo.php';

class PaisController {
	
	private $paisRepo;

	public function __construct()
	{
		$this->paisRepo = new PaisRepo;
		//$this->userRepo = new UserRepo;
	}
	
	public function listAction($urlParams, $postParams)
    {
        return $this->paisRepo->listAll($postParams);
    }

    public function listInAgreementAction($urlParams, $postParams)
    {
        return $this->paisRepo->listInAgreement($postParams);
    }

}
	

