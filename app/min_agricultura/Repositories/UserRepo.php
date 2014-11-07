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

		$result = $this->modelAdo->lista($this->model);
		var_dump($result);

		return $result;
	}

}