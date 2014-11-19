<?php

require PATH_APP.'min_agricultura/Repositories/CorrelativaRepo.php';

class CorrelativaController {
	
	protected $correlativaRepo;

	public function __construct()
	{
		$this->correlativaRepo = new CorrelativaRepo;
	}
	
	public function listAction($urlParams, $postParams)
    {
        return $this->correlativaRepo->listAll($postParams);
    }

}
	

