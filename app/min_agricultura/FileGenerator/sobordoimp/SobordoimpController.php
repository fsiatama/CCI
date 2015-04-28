<?php

require PATH_MODELS.'Repositories/SobordoimpRepo.php';
require PATH_MODELS.'Repositories/UserRepo.php';

class SobordoimpController {
	
	protected $sobordoimpRepo;
	protected $userRepo;

	public function __construct()
	{
		$this->sobordoimpRepo = new SobordoimpRepo;
		$this->userRepo        = new UserRepo;
	}
	
	public function listAction($urlParams, $postParams)
    {
        return $this->sobordoimpRepo->listAll($postParams);
    }

}
	

