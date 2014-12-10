<?php

require PATH_APP.'min_agricultura/Repositories/SectorRepo.php';
require PATH_APP.'min_agricultura/Repositories/UserRepo.php';

class SectorController {
	
	protected $sectorRepo;

	public function __construct()
	{
		$this->sectorRepo = new SectorRepo;
		$this->userRepo        = new UserRepo;
	}
	
	public function listAction($urlParams, $postParams)
    {
        return $this->sectorRepo->listAll($postParams);
    }

}
	

