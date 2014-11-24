<?php

require PATH_APP.'min_agricultura/Entities/User.php';
require PATH_APP.'min_agricultura/Ado/UserAdo.php';
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

	public function setData($params, $action)
	{
		extract($params);

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
		}

		if (
			empty($user_id) ||
			empty($user_full_name) ||
			empty($user_email) ||
			empty($user_password) ||
			empty($user_active) ||
			empty($user_profile_id) ||
			empty($user_uinsert) ||
			empty($user_finsert) ||
			empty($user_uupdate) ||
			empty($user_fupdate)
		) {
			$result = array(
				'success' => false,
				'error'   => 'Incomplete data for this request.'
			);
			return $result;
		}
			$this->model->setUser_id($user_id);
			$this->model->setUser_full_name($user_full_name);
			$this->model->setUser_email($user_email);
			$this->model->setUser_password($user_password);
			$this->model->setUser_active($user_active);
			$this->model->setUser_profile_id($user_profile_id);
			$this->model->setUser_uinsert($user_uinsert);
			$this->model->setUser_finsert($user_finsert);
			$this->model->setUser_uupdate($user_uupdate);
			$this->model->setUser_fupdate($user_fupdate);
		

		if ($action == 'create') {
		}
		elseif ($action == 'modify') {
		}
		$result = array('success' => true);
		return $result;
	}

}	

