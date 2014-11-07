<?php

require PATH_APP.'min_agricultura/Ado/UserAdo.php';
require PATH_APP.'min_agricultura/Entities/User.php';
require 'BaseRepo.php';

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
		
		$this->model->setUser_email($email);
		$this->model->setUser_password($password);
		$this->model->setUser_active('1');

		$result = $this->modelAdo->exactSearch($this->model);
		
		if ($result['success'] && $result['total'] > 0) {

			$row = array_shift($result['data']);

			$this->model = $this->getModel();
			$this->model->setUser_id($row['user_id']);
			$this->model->setUser_session(session_id());

			$result = $this->modelAdo->update($this->model);

			$_SESSION['user_id']  = $row['usuario_id'];
			var_dump($row);
		}

		return $result;
	}

}