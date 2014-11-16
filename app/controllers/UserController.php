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
	public function jsCodeAction($urlParams, $postParams)
	{
		$result = $this->userRepo->validateMenu($postParams);

		if ($result['success']) {
			$postParams['is_template'] = true;
			$params = array_merge($postParams, $result);

			return new View('jsCode/user', $params);
		}
		
		return $result;
	}

	public function listAction($urlParams, $postParams)
	{
		$result = $this->userRepo->grid($postParams);
		return $result;
	}

}
