<?php

require_once PATH_MODELS.'Repositories/ContingenteRepo.php';
require_once PATH_MODELS.'Repositories/AcuerdoRepo.php';
require_once PATH_MODELS.'Repositories/Acuerdo_detRepo.php';
require_once PATH_MODELS.'Repositories/UserRepo.php';

class ContingenteController {
	
	private $contingenteRepo;
	private $userRepo;

	public function __construct()
	{
		$this->contingenteRepo = new ContingenteRepo;
		$this->userRepo        = new UserRepo;
	}
	
	public function listAction($urlParams, $postParams)
	{
		$result = $this->userRepo->validateMenu('list', $postParams);

		if ($result['success']) {
			$result = $this->contingenteRepo->grid($postParams);
		}
		return $result;
	}

	public function listIdAction($urlParams, $postParams)
	{
		return $this->contingenteRepo->validateModify($postParams);
	}

	public function createAction($urlParams, $postParams)
	{
		$result = $this->userRepo->validateMenu('create', $postParams);

		if ($result['success']) {
			$result = $this->contingenteRepo->create($postParams);
		}
		return $result;
	}

	public function modifyAction($urlParams, $postParams)
	{
		$result = $this->userRepo->validateMenu('modify', $postParams);

		if ($result['success']) {
			$result = $this->contingenteRepo->modify($postParams);
		}
		return $result;
	}

	public function deleteAction($urlParams, $postParams)
	{
		$result = $this->userRepo->validateMenu('delete', $postParams);

		if ($result['success']) {
			$result = $this->contingenteRepo->delete($postParams);
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
				$result = $this->contingenteRepo->validateModify($postParams);
				if (!$result['success']) {
					return $result;
				}
			}

			$postParams['is_template'] = true;
			$params = array_merge($postParams, $result, compact('country_data'), $rowAcuerdo, $rowAcuerdo_det, compact('action'));
			//var_dump($params);

			//el template de adicionar y editar son los mismos
			$action = ($action == 'modify') ? 'create' : $action;

			return new View('jsCode/contingente.'.$action, $params);
		}
		
		return $result;
	}

	public function jscodeExecuteAction($urlParams, $postParams)
	{
		$action = array_shift($urlParams);
		$action = (empty($action)) ? 'list' : $action;

		$result = $this->userRepo->validateMenu($action, $postParams);

		if ($result['success']) {
			$acuerdoRepo = new AcuerdoRepo;
			//verifica que exista el acuerdo y trae los datos
			$result = $acuerdoRepo->listId($postParams);
			if (!$result['success']) {
				return $result;
			}
			$rowAcuerdo  = array_shift($result['data']);

			$acuerdo_detRepo = new Acuerdo_detRepo;

			$result = $acuerdo_detRepo->listId($postParams);
			if (!$result['success']) {
				return $result;
			}
			$rowAcuerdo_det = array_shift($result['data']);
			$productsData   = $result['productsData'];

			$postParams['is_template'] = true;

			$lines          = Helpers::getRequire(PATH_APP.'lib/indicador.config.php');
			$yearsAvailable = Helpers::arrayGet($lines, 'yearsAvailable');
			$periods        = Helpers::arrayGet($lines, 'periods');

			$updateInfo     = Helpers::getUpdateInfo('aduanas', 'impo');
			$yearsAvailable = ($updateInfo !== false) ? $updateInfo['yearsAvailable'] : $yearsAvailable ;

			$params = array_merge($postParams, $rowAcuerdo, $rowAcuerdo_det, compact('productsData', 'yearsAvailable', 'periods'));

			$postParams['is_template'] = true;
			return new View('jsCode/contingente.execute', $params);
		}
	}

	public function executeAction($urlParams, $postParams)
	{
		$result = $this->userRepo->validateMenu('list', $postParams);
		if ($result['success']) {
			$result = $this->contingenteRepo->execute($postParams);
		}
		return $result;
	}

}
	

