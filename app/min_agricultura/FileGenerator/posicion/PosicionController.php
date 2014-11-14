<?php

require PATH_APP.'min_agricultura/Repositories/PosicionRepo.php';

class PosicionController {
	
	protected $posicionRepo;

	public function __construct()
	{
		$this->posicionRepo = new PosicionRepo;
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
	

