<?php

require PATH_APP.'min_agricultura/Repositories/PosicionRepo.php';

class PosicionController {
	
	protected $posicionRepo;

	public function __construct()
	{
		$this->posicionRepo = new PosicionRepo;
	}
	
	public function listAction($urlParams, $postParams)
    {
        return $this->posicionRepo->listAll($postParams);
    }

}
	

