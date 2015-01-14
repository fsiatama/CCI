<?php

require PATH_MODELS.'Repositories/AuditRepo.php';
require PATH_MODELS.'Repositories/UserRepo.php';

class AuditController {
	
	protected $auditRepo;
	protected $userRepo;

	public function __construct()
	{
		$this->auditRepo = new AuditRepo;
		$this->userRepo        = new UserRepo;
	}
	
	public function listAction($urlParams, $postParams)
    {
        return $this->auditRepo->listAll($postParams);
    }

}
	

