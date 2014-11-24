<?php

require PATH_APP.'min_agricultura/Repositories/IndicadorRepo.php';
require PATH_APP.'min_agricultura/Repositories/UserRepo.php';

class IndicadorController {
	
	protected $indicadorRepo;

	public function __construct()
	{
		$this->indicadorRepo = new IndicadorRepo;
		$this->userRepo        = new UserRepo;
	}
	
	public function listAction($urlParams, $postParams)
    {
        return $this->indicadorRepo->listAll($postParams);
    }

}
	

