<?php

require PATH_MODELS.'Repositories/DeclaraexpRepo.php';
require PATH_MODELS.'Repositories/UserRepo.php';

class DeclaraexpController {
	
	protected $declaraexpRepo;
	protected $userRepo;

	public function __construct()
	{
		$this->declaraexpRepo = new DeclaraexpRepo;
		$this->userRepo        = new UserRepo;
	}
	
	public function listAction($urlParams, $postParams)
    {
        return $this->declaraexpRepo->listAll($postParams);
    }

}
	

