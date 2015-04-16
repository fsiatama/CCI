<?php

require PATH_MODELS.'Repositories/DeclaraimpRepo.php';
require PATH_MODELS.'Repositories/UserRepo.php';

class DeclaraimpController {
	
	protected $declaraimpRepo;
	protected $userRepo;

	public function __construct()
	{
		$this->declaraimpRepo = new DeclaraimpRepo;
		$this->userRepo        = new UserRepo;
	}
	
	public function listAction($urlParams, $postParams)
    {
        return $this->declaraimpRepo->listAll($postParams);
    }

}
	

