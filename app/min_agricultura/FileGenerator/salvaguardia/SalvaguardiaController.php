<?php

require PATH_APP.'min_agricultura/Repositories/SalvaguardiaRepo.php';
require PATH_APP.'min_agricultura/Repositories/UserRepo.php';

class SalvaguardiaController {
	
	protected $salvaguardiaRepo;

	public function __construct()
	{
		$this->salvaguardiaRepo = new SalvaguardiaRepo;
		$this->userRepo        = new UserRepo;
	}
	
	public function listAction($urlParams, $postParams)
    {
        return $this->salvaguardiaRepo->listAll($postParams);
    }

}
	

