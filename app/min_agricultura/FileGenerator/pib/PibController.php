<?php

require PATH_APP.'min_agricultura/Repositories/PibRepo.php';
require PATH_APP.'min_agricultura/Repositories/UserRepo.php';

class PibController {
	
	protected $pibRepo;

	public function __construct()
	{
		$this->pibRepo = new PibRepo;
		$this->userRepo        = new UserRepo;
	}
	
	public function listAction($urlParams, $postParams)
    {
        return $this->pibRepo->listAll($postParams);
    }

}
	

