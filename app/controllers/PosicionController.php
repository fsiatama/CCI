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
        $result = array(
            'success' => true,
            'total' => 1,
            'data' => array(
                array(
                'id_posicion' => 1,
                'posicion' => 'listInAgreementAction not implemented'
                )
            )
        );
        
        return $result; //$this->posicionRepo->listInAgreement($postParams);
    }

}
	

