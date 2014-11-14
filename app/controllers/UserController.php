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
		$sessionRepo = new SessionRepo;
		$result = $this->userRepo->validateMenu($postParams);

		if ($result['success']) {
			var_dump($result);
			$postParams['is_template'] = true;

			return new View('jsCode/user', $postParams);
		}
		
		return $result;
	}

}
