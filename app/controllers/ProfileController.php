<?php

require PATH_APP.'min_agricultura/Repositories/ProfileRepo.php';

class ProfileController {
	
	private $profileRepo;

	public function __construct()
	{
		$this->profileRepo = new ProfileRepo;
	}
	
	public function listAction($urlParams, $postParams)
    {
        return $this->profileRepo->listAll($postParams);
    }

}
	

