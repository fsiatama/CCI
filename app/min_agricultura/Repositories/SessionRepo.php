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

}	

