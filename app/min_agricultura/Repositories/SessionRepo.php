<?php
require PATH_APP.'min_agricultura/Entities/Session.php';
require PATH_APP.'min_agricultura/Ado/SessionAdo.php';
require_once ('BaseRepo.php');

class SessionRepo extends BaseRepo {

	public function getModel()
	{
		return new Session;
	}
	
	public function getModelAdo()
	{
		return new SessionAdo;
	}

	public function login($data)
	{
		$session    = $this->model;
		$sessionAdo = $this->modelAdo;
		
		$session->setSession_user_id($data["user_id"]);
		$result = $sessionAdo->exactSearch($session);
		
		if ($result['success']) {
			$session->setSession_php_id(session_id());
			$session->setSession_date(Helpers::getDateTimeNow());
			$session->setSession_active('1');
			if ($result['total'] > 0) {
				//actualizar la session
				$result = $sessionAdo->update($session);
			} else {
				//Primer ingreso - crear registro
				$result = $sessionAdo->create($session);
			}
		}

		return $result;
	}

	public function logout()
	{
		/*header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");
		exit();*/
		$result['success'] = false;
		if ($this->validSession()) {
			$session    = $this->model;
			$sessionAdo = $this->modelAdo;

			$session->setSession_user_id($_SESSION['user_id']);
			$session->setSession_php_id(null);
			$session->setSession_active('0');
			$result = $sessionAdo->update($session);
			if ($result['success']) {

			    $_SESSION = array();
			    session_destroy();
			    $result['url'] = URL_RAIZ;
			}
		}
		return $result;
	}

	public function validSession()
	{
		$session    = $this->model;
		$sessionAdo = $this->modelAdo;

		if (!empty($_SESSION)) {
			$session->setSession_user_id($_SESSION['user_id']);
			$session->setSession_php_id(session_id());
			$session->setSession_active('1');
			$result = $sessionAdo->exactSearch($session);
			if ($result['success'] && $result['total'] > 0) {
				return true;
			}
		}
		
		header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");
		exit();
	}

}	

