<?php

require PATH_APP.'min_agricultura/Repositories/Salvaguardia_detRepo.php';
require PATH_APP.'min_agricultura/Repositories/UserRepo.php';

class Salvaguardia_detController {
	
	protected $salvaguardia_detRepo;

	public function __construct()
	{
		$this->salvaguardia_detRepo = new Salvaguardia_detRepo;
		$this->userRepo        = new UserRepo;
	}
	
	public function listAction($urlParams, $postParams)
    {
        return $this->salvaguardia_detRepo->listAll($postParams);
    }

}
	

