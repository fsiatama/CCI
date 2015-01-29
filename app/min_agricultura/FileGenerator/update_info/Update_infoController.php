<?php

require PATH_MODELS.'Repositories/Update_infoRepo.php';
require PATH_MODELS.'Repositories/UserRepo.php';

class Update_infoController {
	
	protected $update_infoRepo;
	protected $userRepo;

	public function __construct()
	{
		$this->update_infoRepo = new Update_infoRepo;
		$this->userRepo        = new UserRepo;
	}
	
	public function listAction($urlParams, $postParams)
    {
        return $this->update_infoRepo->listAll($postParams);
    }

}
	

