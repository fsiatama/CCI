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
class AuthController {
	
	protected $userRepo;

	public function __construct()
	{
		$this->userRepo = new UserRepo;
	}

	public function loginAction($urlParams, $postParams)
	{
		$result = $this->userRepo->login($postParams);
		
		return $result;
	}

	public function logoutAction()
	{
		$result = $this->sessionRepo->logout();
		
		return $result;
	}

	public function headerMenuAction()
	{
		$result = $this->userRepo->headerMenu();
		
		return $result;
	}

}
