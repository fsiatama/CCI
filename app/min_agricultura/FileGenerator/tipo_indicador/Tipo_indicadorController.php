<?php

require PATH_APP.'min_agricultura/Repositories/Tipo_indicadorRepo.php';
require PATH_APP.'min_agricultura/Repositories/UserRepo.php';

class Tipo_indicadorController {
	
	protected $tipo_indicadorRepo;

	public function __construct()
	{
		$this->tipo_indicadorRepo = new Tipo_indicadorRepo;
		$this->userRepo        = new UserRepo;
	}
	
	public function listAction($urlParams, $postParams)
    {
        return $this->tipo_indicadorRepo->listAll($postParams);
    }

}
	

