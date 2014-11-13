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

	public function mainMenu()
	{
		$permissionsAdo = $this->modelAdo;
		$sessionRepo    = new SessionRepo;
		$result         = false;
		$arrMenu        = array();
		$arrMenuItems   = array();

		if ($sessionRepo->validSession()) {

			$permissions = $this->getModel();
			$permissions->setPermissions_profile_id($_SESSION['session_profile']);
			$result = $permissionsAdo->exactSearch($permissions);


			if ($result['success'] && $result['total'] > 0) {
				foreach ($result['data'] as $key => $value) {

					$category        = Inflector::underscore($value['category_menu_name']);
					$varMenuName     = $value['menu_name'];

					$arrMenuItems[$category][] = array(
						'id'       => Inflector::underscore($varMenuName),
						'title'    => $varMenuName,
						'iconCls'  => Inflector::slug($varMenuName),
						'titleTab' => $varMenuName,
						'url'      => $value['menu_url'],
						'params'   => array(
							'id' => $value['menu_id']
						)
					);
				}

				foreach ($result['data'] as $key => $value) {
					
					$varCategoryName = $value['category_menu_name'];
					$category = Inflector::underscore($varCategoryName);

					if (empty($arrMenu[$category])) {
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

