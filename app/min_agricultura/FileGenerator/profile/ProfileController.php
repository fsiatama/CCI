<?php

require PATH_APP.'min_agricultura/Repositories/ProfileRepo.php';

class ProfileController {
	
	protected $profileRepo;

	public function __construct()
	{
		$this->profileRepo = new ProfileRepo;
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
	

