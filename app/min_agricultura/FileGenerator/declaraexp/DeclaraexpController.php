<?php

require PATH_APP.'min_agricultura/Repositories/DeclaraexpRepo.php';
require PATH_APP.'min_agricultura/Repositories/UserRepo.php';

class DeclaraexpController {
	
	protected $declaraexpRepo;

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
	

