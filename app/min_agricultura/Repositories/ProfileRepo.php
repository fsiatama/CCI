<?php

require PATH_APP.'min_agricultura/Entities/Profile.php';
require PATH_APP.'min_agricultura/Ado/ProfileAdo.php';
require_once ('BaseRepo.php');

class ProfileRepo extends BaseRepo {

	public function getModel()
	{
		return new Profile;
	}
	
	public function getModelAdo()
	{
		return new ProfileAdo;
	}

	public function getPrimaryKey()
	{
		return 'profile_id';
	}

	public function setData($params, $action)
	{

	}

	public function listAll($params)
	{
		$user    = $this->model;
		$userAdo = $this->modelAdo;
		return $userAdo->exactSearch($user);
	}

}	

