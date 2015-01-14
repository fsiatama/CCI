<?php

require PATH_MODELS.'Repositories/AlertaRepo.php';
require PATH_MODELS.'Repositories/UserRepo.php';

class AlertaController {
	
	protected $alertaRepo;
	protected $userRepo;

	public function __construct()
	{
		$this->alertaRepo = new AlertaRepo;
		$this->userRepo        = new UserRepo;
	}
	
	public function listAction($urlParams, $postParams)
    {
        return $this->alertaRepo->listAll($postParams);
    }

}
	

