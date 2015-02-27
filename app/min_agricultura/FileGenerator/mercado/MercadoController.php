<?php

require PATH_MODELS.'Repositories/MercadoRepo.php';
require PATH_MODELS.'Repositories/UserRepo.php';

class MercadoController {
	
	protected $mercadoRepo;
	protected $userRepo;

	public function __construct()
	{
		$this->mercadoRepo = new MercadoRepo;
		$this->userRepo        = new UserRepo;
	}
	
	public function listAction($urlParams, $postParams)
    {
        return $this->mercadoRepo->listAll($postParams);
    }

}
	

