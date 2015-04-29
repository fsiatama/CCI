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

	public function getPrimaryKey()
	{
		return 'user_id';
	}

	public function validateModify($params)
	{
		extract($params);
		$result = $this->findPrimaryKey($user_id);

		if (!$result['success']) {
			$result = [
				'success'  => false,
				'closeTab' => true,
				'tab'      => 'tab-'.$module,
				'error'    => $result['error']
			];
		}
		return $result;
	}

	public function login($params)
	{
		extract($params);
		$user    = $this->model;
		$userAdo = $this->modelAdo;

		if (empty($userName) || empty($password)) {
			$result = array(
				'success' => false,
				'error'   => 'Incomplete data for this request.'
			);
			return $result;
		}
		try {
			$adldap = new \adLDAP\adLDAP();
		}
		catch (adLDAPException $e) {
			$result = array(
				'success' => false,
				'error'   => $e
			);
			return $result;
		}

		$authUser = $adldap->user()->authenticate($userName, $password);
		if ($authUser !== true) {
			$result = array(
				'success' => false,
				'error'   => $adldap->getLastError()
			);
			return $result;
		}

		$userInfo = $adldap->user()->info($userName);
		//toma solo el primer registro
		$userInfo  = $userInfo[0];
		//print_r($userInfo['displayname'][0]);

		//exit();

		$password = md5($password);


		$user->setUser_email($userName);
		//$user->setUser_password($password);
		//$user->setUser_active('1');

		$result = $userAdo->exactSearch($user);

		if ($result['success']) {

			if ($result['total'] == 0) {
				//si el usuario no existe, lo crea
				//$this->model     = $this->getModel();
				//$admin_user = explode(',', _ADMIN_USERS);
				//$profile_id = in_array($userName, $admin_user) ? 1 : 2 ;
				$user_full_name  = $userInfo['displayname'][0];
				$user_email      = $userName;
				$user_password   = $password;
				$user_profile_id = '2';
				$user_active     = '1';

				$result = $this->create( compact('user_full_name', 'user_email', 'user_password', 'user_profile_id', 'user_active') );

				if (!$result['success']) {
					$result = [
						'success'  => false,
						'error'    => $result['error']
					];
					return $result;
				}
				$result = $userAdo->exactSearch($user);
				if (!$result['success']) {
					$result = [
						'success'  => false,
						'error'    => $result['error']
					];
					return $result;
				}
			}

				$row = array_shift($result['data']);
				$permissionsRepo = new PermissionsRepo;
				$result = $permissionsRepo->listProfileMenu($row['user_profile_id']);

				if ($result['success'] && $result['total'] > 0) {

					$_SESSION['user_id']         = $row['user_id'];
					$_SESSION['session_name']    = $row['user_full_name'];
					$_SESSION['session_email']   = $row['user_email'];
					$_SESSION['session_profile'] = $row['user_profile_id'];
					$_SESSION['lang']            = DEFAULT_LANGUAGE;
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
			/*}
			else {
				$result = array(
					'success' => false,
					'error'   => 'Your email address and password did not match. Please try again.'
				);
			}*/
		}

		return $result;
	}

	public function headerMenu()
	{
		$sessionRepo = new SessionRepo;
		$result = $sessionRepo->validSession();
		if ($result['success']) {
			$result = [
				'name' => $_SESSION['session_name'],
				'email' => $_SESSION['session_email']
			];
		}
		return $result;
	}

	public function validateMenu($action, $params)
	{
		extract($params);

		$sessionRepo = new SessionRepo;
		$result = $sessionRepo->validSession();
		if ($result['success']) {
			if (empty($_SESSION['session_menu'][$id])) {
				$result = [
					'success'  => false,
					'closeTab' => true,
					'tab'      => 'tab-'.$module,
					'error'    => 'Su perfil no tiene habilitado esta opción'
				];
			}
			else {

				//var_dump($_SESSION['session_menu'][$id][$action], $params);

				$permissions = ($_SESSION['session_menu'][$id][$action] == '1') ? true : false;

				/*$permissions_list   = ($_SESSION['session_menu'][$id]['list']   == '1') ? true : false;
				$permissions_modify = ($_SESSION['session_menu'][$id]['modify'] == '1') ? true : false;
				$permissions_create = ($_SESSION['session_menu'][$id]['create'] == '1') ? true : false;
				$permissions_delete = ($_SESSION['session_menu'][$id]['delete'] == '1') ? true : false;
				$permissions_export = ($_SESSION['session_menu'][$id]['export'] == '1') ? true : false;

				$permissions = compact('permissions_list', 'permissions_modify', 'permissions_create', 'permissions_delete', 'permissions_export');
				if (!in_array(true, $permissions)) {*/
				if (!$permissions) {
					$result = [
						'success'  => false,
						'closeTab' => true,
						'tab'      => 'tab-'.$module,
						'error'    => 'Su perfil no tiene permisos habilitados para esta opción'
					];
				}
				else {
					$success = true;
					$result = compact('success', 'permissions_list', 'permissions_modify', 'permissions_create', 'permissions_delete', 'permissions_export');
				}
			}
		}
		return $result;
	}

	public function grid($params)
	{
		extract($params);
		$user    = $this->model;
		$userAdo = $this->modelAdo;

		/**/
		$start = ( isset($start) ) ? $start : 0;
		$limit = ( isset($limit) ) ? $limit : 30;
		$page  = ( $start==0 ) ? 1 : ( $start/$limit )+1;

		if (!empty($query)) {
			if (!empty($fullTextFields)) {

				$fullTextFields = json_decode(stripslashes($fullTextFields));

				foreach ($fullTextFields as $value) {
					$methodName = $this->getColumnMethodName('set', $value);

					if (method_exists($user, $methodName)) {
						call_user_func_array([$user, $methodName], compact('query'));
					}
				}
			} else {
				$user->setUser_email($query);
				$user->setUser_full_name($query);
			}

		}

		$userAdo->setColumns([
			'user_id',
			'user_full_name',
			'user_email',
			'user_active',
			'user_active_title',
			'user_profile_id',
			'profile_name',
		]);

		$result = $userAdo->paginate($user, 'LIKE', $limit, $page);

		return $result;
	}

	public function setData($params, $action)
	{
		extract($params);

		$user_active = ( empty($user_active) ) ? '1' : $user_active ;

		if ($action == 'modify') {
			$result = $this->findPrimaryKey($user_id);

			if (!$result['success']) {
				$result = [
					'success'  => false,
					'closeTab' => true,
					'tab'      => 'tab-'.$module,
					'error'    => $result['error']
				];
				return $result;
			}

			$row = array_shift($result['data']);

			if (!empty($user_change_password) && $user_change_password === 1)  {
				$user_password = $user_password;
			} else {
				$user_password = $row['user_password'];
			}
		}

		if (
			empty($user_full_name) ||
			empty($user_email) ||
			empty($user_password) ||
			empty($user_profile_id)
		) {
			$result = array(
				'success' => false,
				'error'   => 'Incomplete data for this request.'
			);
			return $result;
		}

		$this->model->setUser_full_name($user_full_name);
		$this->model->setUser_email($user_email);
		$this->model->setUser_password($user_password);
		$this->model->setUser_active($user_active);
		$this->model->setUser_profile_id($user_profile_id);

		$result = $this->validateUniqueEmail();
		if (!$result['success']) {
			return $result;
		}
		//si no existe datos de session en el user_id es porque se esta creando directamente del Active Directory
		$user_id = (empty($_SESSION['user_id'])) ? 99999999 : $_SESSION['user_id'] ;

		if ($action == 'create') {
			$this->model->setUser_uinsert($user_id);
			$this->model->setUser_finsert(Helpers::getDateTimeNow());
		}
		elseif ($action == 'modify') {
			$this->model->setUser_uupdate($user_id);
			$this->model->setUser_fupdate(Helpers::getDateTimeNow());
		}
		$result = array('success' => true);
		return $result;
	}

	public function validateUniqueEmail()
	{
		$result = $this->modelAdo->findUniqueEmail($this->model);

		if ($result['success']) {
			if ($result['total'] == 0) {
				$result = ['success' => true];
			} else {
				$result = [
					'success' => false,
					'error'   => 'This email is already registered'
				];
			}
		}

		return $result;
	}

	public function create($params)
	{
		//implementa su propio metodo create para saltar el registro de auditoria
		//ya que cuando crea directamente desde el directorio activa, genera confilcto
		
		$result = $this->setData($params, 'create');
		if (!$result['success']) {
			return $result;
		}

		$result = $this->modelAdo->create($this->model);
		/*if ($result['success']) {
			return ['success' => true];
		}*/

		return $result;
	}

}