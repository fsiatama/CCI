<?php

require PATH_MODELS.'Repositories/DesgravacionRepo.php';
require PATH_MODELS.'Repositories/UserRepo.php';

class DesgravacionController {
	
	protected $desgravacionRepo;
	protected $userRepo;

	public function __construct()
	{
		$this->desgravacionRepo = new DesgravacionRepo;
		$this->userRepo        = new UserRepo;
	}
	
	public function listAction($urlParams, $postParams)
    {
        return $this->desgravacionRepo->listAll($postParams);
    }

}
	

