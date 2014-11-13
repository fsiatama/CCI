<?php

require PATH_APP.'min_agricultura/Repositories/UserRepo.php';


/**
* UserController
*
* @category Controller
* @author   Fabian Siatama
* 
* 
*/
class UserController {
	
	protected $userRepo;

	public function __construct()
	{
		$this->userRepo = new UserRepo;
	}

}
