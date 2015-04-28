<?php

require PATH_MODELS.'Repositories/Desgravacion_detRepo.php';
require PATH_MODELS.'Repositories/UserRepo.php';

class Desgravacion_detController {
	
	protected $desgravacion_detRepo;
	protected $userRepo;

	public function __construct()
	{
		$this->desgravacion_detRepo = new Desgravacion_detRepo;
		$this->userRepo        = new UserRepo;
	}
	
	public function listAction($urlParams, $postParams)
    {
        return $this->desgravacion_detRepo->listAll($postParams);
    }

}
	

