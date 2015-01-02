<?php

require PATH_APP.'min_agricultura/Repositories/SubpartidaRepo.php';
require PATH_APP.'min_agricultura/Repositories/UserRepo.php';

class SubpartidaController {
	
	protected $subpartidaRepo;

	public function __construct()
	{
		$this->subpartidaRepo = new SubpartidaRepo;
		$this->userRepo        = new UserRepo;
	}
	
	public function listAction($urlParams, $postParams)
    {
        return $this->subpartidaRepo->listAll($postParams);
    }

}
	

