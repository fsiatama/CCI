<?php

require PATH_APP.'min_agricultura/Repositories/MenuRepo.php';
require PATH_APP.'min_agricultura/Repositories/PermissionsRepo.php';


/**
* MenuController
*
* @category Controller
* @author   Fabian Siatama
* 
* 
*/
class MenuController {
	
	private $menuRepo;

	public function __construct()
	{
		$this->menuRepo = new MenuRepo;
	}

    /**
     * indexAction
     * Metodo por defecto que sera llamado si no se especifica otro en la url
     *
     * @param array $urlParams  parametros adicionales pasados por la url.
     * @param array $postParams Parametros enviados via Post.
     *
     * @access public
     *
     * @return mixed Value.
     */
	public function indexAction($urlParams, $postParams)
    {
        $permissionsRepo = new PermissionsRepo;
        $result = $permissionsRepo->mainMenu($postParams);
		
		return $result;
    }

}
