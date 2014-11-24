<?php

require PATH_APP.'min_agricultura/Repositories/UserRepo.php';
require PATH_APP.'min_agricultura/Repositories/UserRepo.php';

class UserController {
	
	protected $userRepo;

	public function __construct()
	{
		$this->userRepo = new UserRepo;
		$this->userRepo        = new UserRepo;
	}
	
	public function listAction($urlParams, $postParams)
    {
        return $this->userRepo->listAll($postParams);
    }

}
	

