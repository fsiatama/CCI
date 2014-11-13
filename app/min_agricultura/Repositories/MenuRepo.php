<?php

require PATH_APP.'min_agricultura/Entities/Menu.php';
require PATH_APP.'min_agricultura/Ado/MenuAdo.php';
require PATH_APP.'min_agricultura/Repositories/SessionRepo.php';

require_once ('BaseRepo.php');

class MenuRepo extends BaseRepo {

	public function getModel()
	{
		return new Menu;
	}
	
	public function getModelAdo()
	{
		return new MenuAdo;
	}

	public function mainMenu()
	{
		$menuAdo = $this->modelAdo;
		$sessionRepo = new SessionRepo;
		$result = false;
		$arrMenu = array();
		if ($sessionRepo->validSession()) {
			require PATH_APP.'min_agricultura/Repositories/Category_menuRepo.php';
			
			$category_menuRepo = new Category_menuRepo;
			$result = $category_menuRepo->listAll();
			
			if ($result['success'] && $result['total'] > 0) {

				$categoryData = $result['data'];

				foreach ($categoryData as $key => $value) {
					$menu = $this->getModel();
					$menu->setMenu_category_menu_id($value['category_menu_id']);
					$result = $menuAdo->exactSearch($menu);

					if ($result['success'] && $result['total'] > 0) {
						$menuData     = $result['data'];
						$arrMenuItems = array();
						foreach ($menuData as $subkey => $subvalue) {
							$varMenuName = Inflector::cleanOutputString($subvalue['menu_name']);
							
							$arrMenuItems[] = array(
								'id'       => Inflector::underscore($varMenuName),
								'title'    => $subvalue['menu_name'],
								'iconCls'  => Inflector::slug($subvalue['menu_name']),
								'titleTab' => $subvalue['menu_name'],
								'url'      => $subvalue['menu_url'],
								'params'   => array(
									'id' => $subvalue['menu_id']
								)
							);
						}
						
						$varCategoryName = Inflector::cleanOutputString($value['category_menu_name']);
						$category = Inflector::underscore($varCategoryName);

						$arrMenu[$category] = array(
							'title' => $value['category_menu_name'],
							'iconCls'  => Inflector::slug($varCategoryName),
							'items' => $arrMenuItems
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

