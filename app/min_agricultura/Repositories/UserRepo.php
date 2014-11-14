<?php

require PATH_APP.'min_agricultura/Entities/User.php';
require PATH_APP.'min_agricultura/Ado/UserAdo.php';
require PATH_APP.'min_agricultura/Repositories/PermissionsRepo.php';

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

		if (empty($email) || empty($password)) {
			$result = array(
				'success' => false,
				'error'   => 'Incomplete data for this request.'
			);
			return $result;
		}

		$user->setUser_email($email);
		$user->setUser_password($password);
		$user->setUser_active('1');

		$result = $userAdo->exactSearch($user);
		
		if ($result['success']) {
			
			if ($result['total'] > 0) {
			
				$row = array_shift($result['data']);
				$permissionsRepo = new PermissionsRepo;
				$result = $permissionsRepo->listProfileMenu($row['user_id']);

				if ($result['success'] && $result['total'] > 0) {

					$_SESSION['user_id']         = $row['user_id'];
					$_SESSION['session_name']    = $row['user_full_name'];
					$_SESSION['session_email']   = $row['user_email'];
					$_SESSION['session_profile'] = $row['user_profile_id'];
					$_SESSION['start']           = time();
					$_SESSION['user_token']      = uniqid();

					foreach ($result['data'] as $key => $value) {
						
						$_SESSION['session_menu'][$value['menu_id']]['list']   = $value['permissions_list'];
						$_SESSION['session_menu'][$value['menu_id']]['modify'] = $value['permissions_modify'];
						$_SESSION['session_menu'][$value['menu_id']]['create'] = $value['permissions_create'];
						$_SESSION['session_menu'][$value['menu_id']]['delete'] = $value['permissions_delete'];
						$_SESSION['session_menu'][$value['menu_id']]['export'] = $value['permissions_export'];
						
					}

					//crea o actualiza el registro de session
					$sessionRepo = new SessionRepo;
					$result = $sessionRepo->login($row);
					
					if ($result['success']) {
						$result['url'] = URL_INGRESO;
					}
				}
				elseif ($result['total'] == 0) {
					$result = array(
						'success' => false,
						'error'   => 'You have not enabled products'
					);
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
				'name' => $_SESSION['session_name'],
				'email' => $_SESSION['session_email']
			];
		}
		return $result;
	}

	public function validateMenu($params)
	{
		extract($params);

		$sessionRepo = new SessionRepo;
		$result['success'] = false;
		if ($sessionRepo->validSession()) {
			if (empty($_SESSION['session_menu'][$id])) {
				$result = [
					'success' => false,
					'closeTab' => true,
					'tab' => 'tab-'.$id,
					'error' => ''
				];
			}
			else {

				$permissions_list   = ($_SESSION['session_menu'][$id]['list']   == '1') ? true : false;
				$permissions_modify = ($_SESSION['session_menu'][$id]['modify'] == '1') ? true : false;
				$permissions_create = ($_SESSION['session_menu'][$id]['create'] == '1') ? true : false;
				$permissions_delete = ($_SESSION['session_menu'][$id]['delete'] == '1') ? true : false;
				$permissions_export = ($_SESSION['session_menu'][$id]['export'] == '1') ? true : false;

				$success = true;
				$result = compact('success','permissions_list', 'permissions_modify', 'permissions_create', 'permissions_delete', 'permissions_export');
			}
		}
		return $result;
	}

}