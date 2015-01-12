<?php

require PATH_APP.'min_agricultura/Repositories/PaisRepo.php';
//require PATH_APP.'min_agricultura/Repositories/UserRepo.php';

class PaisController {
	
	private $paisRepo;

	public function __construct()
	{
		$this->paisRepo = new PaisRepo;
		//$this->userRepo = new UserRepo;
	}
	
	public function listAction($urlParams, $postParams)
    {
        return $this->paisRepo->listAll($postParams);
    }

}
	

