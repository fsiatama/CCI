<?php

require PATH_APP.'min_agricultura/Repositories/PermissionsRepo.php';

class PermissionsController {
	
	protected $permissionsRepo;

	public function __construct()
	{
		$this->permissionsRepo = new PermissionsRepo;
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
        return true;
    }

}
	

