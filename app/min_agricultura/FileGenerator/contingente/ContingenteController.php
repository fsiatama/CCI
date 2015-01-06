<?php

require PATH_APP.'min_agricultura/Repositories/ContingenteRepo.php';
require PATH_APP.'min_agricultura/Repositories/UserRepo.php';

class ContingenteController {
	
	protected $contingenteRepo;

	public function __construct()
	{
		$this->contingenteRepo = new ContingenteRepo;
		$this->userRepo        = new UserRepo;
	}
	
	public function listAction($urlParams, $postParams)
    {
        return $this->contingenteRepo->listAll($postParams);
    }

}
	

