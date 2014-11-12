<?php

require PATH_APP.'min_agricultura/Repositories/UserRepo.php';


/**
* AuthController
*
* @category Controller
* @author   Fabian Siatama
* 
* contiene los metodos para autenticarse o salir de la aplicacion
* 
*/
class UserController {
	
	protected $userRepo;
	protected $sessionRepo;

	public function __construct()
	{
		$this->userRepo = new UserRepo;
		$this->sessionRepo = new SessionRepo;
	}

	public function mainMenuAction($urlParams, $postParams)
	{
		$result = $this->userRepo->mainMenu($postParams);
		
		return $result;
	}

}
