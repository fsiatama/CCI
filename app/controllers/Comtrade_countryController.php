<?php

require PATH_APP.'min_agricultura/Repositories/Comtrade_countryRepo.php';
require PATH_APP.'min_agricultura/Repositories/UserRepo.php';

class Comtrade_countryController {
	
	protected $comtrade_countryRepo;

	public function __construct()
	{
		$this->comtrade_countryRepo = new Comtrade_countryRepo;
		$this->userRepo        = new UserRepo;
	}
	
	public function listAction($urlParams, $postParams)
    {
        return $this->comtrade_countryRepo->listAll($postParams);
    }

}
	
