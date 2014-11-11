<?php

require PATH_APP.'min_agricultura/Entities/User.php';
require PATH_APP.'min_agricultura/Ado/UserAdo.php';
require PATH_APP.'min_agricultura/Repositories/SessionRepo.php';

require_once ('BaseRepo.php');

class UserRepo extends BaseRepo {

	public function getModel()
	{
		return new User;
	}
	
	public function getModelAdo()
	{
		return new UserAdo;
	}

	public function login($params)
	{
		extract($params);
		$user    = $this->model;
		$userAdo = $this->modelAdo;

		$user->setUser_email($email);
		$user->setUser_password($password);
		$user->setUser_active('1');

		$result = $userAdo->exactSearch($user);
		
		if ($result['success']) {
			if ($result['total'] > 0) {
			
				$row = array_shift($result['data']);

				$sessionRepo = new SessionRepo;
				
				$result = $sessionRepo->login($row);
				if ($result['success']){
					$_SESSION['user_id']         = $row['user_id'];
					$_SESSION['session_name']    = $row['user_full_name'];
					$_SESSION['session_email']   = $row['user_email'];
					$_SESSION['session_profile'] = $row['user_profile_id'];
					$_SESSION['start']           = time();

					$result['url'] = URL_INGRESO;
				}
			}
			else {
				$result = array(
					'success' => false,
					'error'   => 'Your email address and password did not match. Please try again.'
				);
			}
		}

		return $result;
	}

	public function headerMenu()
	{
		$sessionRepo = new SessionRepo;
		$result = false;
		if ($sessionRepo->validSession()) {
			$result = [
				'text' => $_SESSION['session_name']
			];
		}
		return $result;
	}

}