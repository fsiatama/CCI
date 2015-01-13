<?php

require PATH_MODELS.'Repositories/ContingenteRepo.php';
require PATH_MODELS.'Repositories/UserRepo.php';

class ContingenteController {
	
	protected $contingenteRepo;
	protected $userRepo;

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
	

