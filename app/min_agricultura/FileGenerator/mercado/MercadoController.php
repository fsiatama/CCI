<?php

require PATH_APP.'min_agricultura/Repositories/MercadoRepo.php';
require PATH_APP.'min_agricultura/Repositories/UserRepo.php';

class MercadoController {
	
	protected $mercadoRepo;

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
	

