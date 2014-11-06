<?php

require PATH_APP.'min_agricultura/Entities/User.php';
require 'BaseRepo.php';

class UserRepo extends BaseRepo {

	public function getModel()
	{
		return new User;
	}

	public function login($params)
	{
		var_dump($this->connection);
		extract($params);
		$result = $this->model->login($id);
		return $email;
	}

}