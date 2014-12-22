<?php

require PATH_APP.'min_agricultura/Repositories/ProduccionRepo.php';
require PATH_APP.'min_agricultura/Repositories/UserRepo.php';

class ProduccionController {
	
	protected $produccionRepo;

	public function __construct()
	{
		$this->produccionRepo = new ProduccionRepo;
		$this->userRepo        = new UserRepo;
	}
	
	public function listAction($urlParams, $postParams)
    {
        return $this->produccionRepo->listAll($postParams);
    }

}
	

