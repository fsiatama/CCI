<?php

require PATH_APP.'min_agricultura/Entities/Permissions.php';
require PATH_APP.'min_agricultura/Ado/PermissionsAdo.php';
require PATH_APP.'min_agricultura/Repositories/SessionRepo.php';
require_once ('BaseRepo.php');

class PermissionsRepo extends BaseRepo {

	public function getModel()
	{
		return new Permissions;
	}

	public function getModelAdo()
	{
		return new PermissionsAdo;
	}

	public function getPrimaryKey()
	{
		return 'permissions_id';
	}

	public function setData($params, $action)
	{

	}

	public function listProfileMenu($profile_id)
	{
		$permissionsAdo = $this->modelAdo;
		$permissions    = $this->model;
		$permissions->setPermissions_profile_id($profile_id);
		$result = $permissionsAdo->exactSearch($permissions);

		return $result;
	}

	public function mainMenu()
	{
		$sessionRepo    = new SessionRepo;
		$result         = false;
		$arrMenu        = array();
		$arrMenuItems   = array();
		$result         = $sessionRepo->validSession();

		if ($result['success']) {

			$result = $this->listProfileMenu($_SESSION['session_profile']);

			if ($result['success'] && $result['total'] > 0) {
				foreach ($result['data'] as $key => $value) {

					$category        = Inflector::underscore($value['category_menu_name']);
					$varMenuName     = $value['menu_name'];

					if ($value['menu_hidden'] == '0') {
						$arrMenuItems[$category][] = array(
							'id'       => Inflector::underscore($varMenuName).'_'.$value['menu_id'],
							'title'    => $varMenuName,
							'iconCls'  => Inflector::slug($varMenuName),
							'titleTab' => $varMenuName,
							'url'      => $value['menu_url'],
							'params'   => array(
								'id'     => $value['menu_id'],
								'title'  => $varMenuName,
								'module' => Inflector::underscore($varMenuName)
							)
						);
					}
				}

				foreach ($result['data'] as $key => $value) {

					$varCategoryName = $value['category_menu_name'];
					$category = Inflector::underscore($varCategoryName);

					if (empty($arrMenu[$category]) && !empty($arrMenuItems[$category])) {
						$arrMenu[$category] = array(
							'title' => $varCategoryName,
							'iconCls'  => Inflector::slug($varCategoryName),
							'items' => $arrMenuItems[$category]
						);
					}

				}
				if (!empty($arrMenu)) {
					$result['data'] = $arrMenu;
				}
			}
		}
		return $result;
	}

}

