<?php

require PATH_APP.'min_agricultura/Repositories/SubpartidaRepo.php';

class SubpartidaController {
	
	protected $subpartidaRepo;

	public function __construct()
	{
		$this->subpartidaRepo = new SubpartidaRepo;
	}
	
	public function listAction($urlParams, $postParams)
    {
        return $this->subpartidaRepo->listAll($postParams);
    }

}
	

