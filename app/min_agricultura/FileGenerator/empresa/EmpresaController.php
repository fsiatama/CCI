<?php

require PATH_APP.'min_agricultura/Repositories/EmpresaRepo.php';
require PATH_APP.'min_agricultura/Repositories/UserRepo.php';

class EmpresaController {
	
	protected $empresaRepo;

	public function __construct()
	{
		$this->empresaRepo = new EmpresaRepo;
		$this->userRepo        = new UserRepo;
	}
	
	public function listAction($urlParams, $postParams)
    {
        return $this->empresaRepo->listAll($postParams);
    }

}
	

