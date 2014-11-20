<?php

require PATH_APP.'min_agricultura/Repositories/CorrelativaRepo.php';
require PATH_APP.'min_agricultura/Repositories/UserRepo.php';

class CorrelativaController {
	
	protected $correlativaRepo;

	public function __construct()
	{
		$this->correlativaRepo = new CorrelativaRepo;
		$this->userRepo        = new UserRepo;
	}
	
	public function listAction($urlParams, $postParams)
    {
        return $this->correlativaRepo->listAll($postParams);
    }

}
	

