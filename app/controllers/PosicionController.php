<?php

require PATH_APP.'min_agricultura/Repositories/PosicionRepo.php';

class PosicionController {
	
	private $posicionRepo;

	public function __construct()
	{
		$this->posicionRepo = new PosicionRepo;
	}
	
	public function listAction($urlParams, $postParams)
    {
        return $this->posicionRepo->listAll($postParams);
    }

    public function listInAgreementAction($urlParams, $postParams)
    {
        return $this->posicionRepo->listInAgreement($postParams);
    }

}
	

