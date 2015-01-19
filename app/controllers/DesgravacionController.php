<?php

require_once PATH_MODELS.'Repositories/DesgravacionRepo.php';
require_once PATH_MODELS.'Repositories/AcuerdoRepo.php';
require_once PATH_MODELS.'Repositories/Acuerdo_detRepo.php';
require_once PATH_MODELS.'Repositories/UserRepo.php';

class DesgravacionController {
	
	protected $desgravacionRepo;
	protected $userRepo;

	public function __construct()
	{
		$this->desgravacionRepo = new DesgravacionRepo;
		$this->userRepo        = new UserRepo;
	}
	
    public function listAction($urlParams, $postParams)
    {
    	$result = $this->userRepo->validateMenu('list', $postParams);

    	if ($result['success']) {
    		$result = $this->desgravacionRepo->grid($postParams);
    	}
    	return $result;
    }

    public function modifyAction($urlParams, $postParams)
	{
		$result = $this->userRepo->validateMenu('modify', $postParams);

		if ($result['success']) {
			$result = $this->desgravacionRepo->modify($postParams);
		}
		return $result;
	}

    public function jscodeAction($urlParams, $postParams)
	{
		$action = array_shift($urlParams);
		$action = (empty($action)) ? 'list' : $action;

		$acuerdo_id     = (!isset($postParams['acuerdo_id'])) ? $postParams['acuerdo_det_acuerdo_id'] : $postParams['acuerdo_id'] ;
		$acuerdo_det_id = (!isset($postParams['acuerdo_det_id'])) ? '' : $postParams['acuerdo_det_id'] ;

		$result = $this->userRepo->validateMenu($action, $postParams);

		if ($result['success']) {
			
			$acuerdoRepo = new AcuerdoRepo;
			//verifica que exista el acuerdo y trae los datos incluido un array con los datos de los paises del acuerdo
			$rs = $acuerdoRepo->listId(compact('acuerdo_id'));
			if (!$rs['success']) {
				return $rs;
			}
			$rowAcuerdo   = array_shift($rs['data']);
			$country_data = $rs['country_data'];

			$acuerdo_detRepo = new Acuerdo_detRepo;
			//verifica que exista el acuerdo y trae los datos incluido un array con los datos de los paises del acuerdo
			$rs = $acuerdo_detRepo->findPrimaryKey($acuerdo_det_id);
			if (!$rs['success']) {
				return $rs;
			}
			$rowAcuerdo_det = array_shift($rs['data']);

			if ($action == 'modify') {
				$result = $this->desgravacionRepo->validateModify($postParams);
				if (!$result['success']) {
					return $result;
				}
			}

			$postParams['is_template'] = true;
			$params = array_merge($postParams, $result, compact('country_data'), $rowAcuerdo, $rowAcuerdo_det, compact('action'));
			//var_dump($params);

			//el template de adicionar y editar son los mismos
			$action = ($action == 'modify') ? 'create' : $action;

			return new View('jsCode/desgravacion.'.$action, $params);
		}
		
		return $result;
	}

    /*public function listIdAction($urlParams, $postParams)
    {
    	return $this->desgravacionRepo->validateModify($postParams);
    }*/

}
	

