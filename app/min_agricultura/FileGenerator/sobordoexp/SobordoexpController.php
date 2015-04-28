<?php

require PATH_MODELS.'Repositories/SobordoexpRepo.php';
require PATH_MODELS.'Repositories/UserRepo.php';

class SobordoexpController {
	
	protected $sobordoexpRepo;
	protected $userRepo;

	public function __construct()
	{
		$this->sobordoexpRepo = new SobordoexpRepo;
		$this->userRepo        = new UserRepo;
	}
	
	public function listAction($urlParams, $postParams)
    {
        return $this->sobordoexpRepo->listAll($postParams);
    }

}
	

