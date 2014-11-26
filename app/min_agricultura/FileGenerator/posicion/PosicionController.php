<?php

require PATH_APP.'min_agricultura/Repositories/PosicionRepo.php';
require PATH_APP.'min_agricultura/Repositories/UserRepo.php';

class PosicionController {
	
	protected $posicionRepo;

	public function __construct()
	{
		$this->posicionRepo = new PosicionRepo;
		$this->userRepo        = new UserRepo;
	}
	
	public function listAction($urlParams, $postParams)
    {
        return $this->posicionRepo->listAll($postParams);
    }

}
	

