<?php

require PATH_APP.'min_agricultura/Repositories/DeclaraimpRepo.php';
require PATH_APP.'min_agricultura/Repositories/UserRepo.php';

class DeclaraimpController {
	
	protected $declaraimpRepo;

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
	

