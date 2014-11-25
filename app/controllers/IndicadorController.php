<?php

require PATH_APP.'min_agricultura/Repositories/Tipo_indicadorRepo.php';
require PATH_APP.'min_agricultura/Repositories/IndicadorRepo.php';
require PATH_APP.'min_agricultura/Repositories/UserRepo.php';

class IndicadorController {
	
	protected $indicadorRepo;
	protected $tipo_indicadorRepo;

	public function __construct()
	{
		$this->tipo_indicadorRepo = new Tipo_indicadorRepo;
		$this->indicadorRepo      = new IndicadorRepo;
		$this->userRepo           = new UserRepo;
	}

	public function listAction($urlParams, $postParams)
    {
        $result = $this->userRepo->validateMenu('list', $postParams);

		if ($result['success']) {
			$result = $this->indicadorRepo->listUserId($postParams);
		}
		return $result;
    }
	
	public function jscodeAction($urlParams, $postParams)
    {
        $action = array_shift($urlParams);
		$action = (empty($action)) ? 'list' : $action;

		$result = $this->userRepo->validateMenu($action, $postParams);

		if ($result['success']) {

			//verifica que exista el tipo de indicador y trae los datos
			$result = $this->tipo_indicadorRepo->validateModify($postParams);
			if (!$result['success']) {
				return $result;
			}
			$row = array_shift($result['data']);
			$postParams['is_template'] = true;
			$params = array_merge($postParams, $row, compact('action'));

			//var_dump($params);
			return new View('jsCode/indicador.'.$action, $params);
		}

		return $result;
    }

    public function jscodeCfgAction($urlParams, $postParams)
    {
        $action = array_shift($urlParams);
		$action = (empty($action)) ? 'list' : $action;

		$result = $this->userRepo->validateMenu($action, $postParams);

		if ($result['success']) {

			//verifica que exista el tipo de indicador y trae los datos
			$result = $this->tipo_indicadorRepo->validateModify($postParams);
			if (!$result['success']) {
				return $result;
			}
			$row = array_shift($result['data']);
			$postParams['is_template'] = true;
			$params = array_merge($postParams, $row, compact('action'));

			//el template de adicionar y editar son los mismos
			$action = ($action == 'modify') ? 'create' : $action;

			//var_dump($params);
			return new View('jsCode/indicador/indicador.'.$action, $params);
		}

		return $result;
    }

    public function jscodeExecuteAction($urlParams, $postParams)
    {
    	$action = array_shift($urlParams);
		$action = (empty($action)) ? 'list' : $action;

		$result = $this->userRepo->validateMenu($action, $postParams);

		if ($result['success']) {
			//verifica que exista el tipo de indicador y trae los datos
    		$result = $this->tipo_indicadorRepo->validateModify($postParams);
    		if (!$result['success']) {
    			return $result;
    		}
    		$row = array_shift($result['data']);
    		$postParams['is_template'] = true;
    		$params = array_merge($postParams, $row, compact('action'));
    		return new View('jsCode/indicador/indicador.execute', $params);
		}
		return $result;
    }

    public function createAction($urlParams, $postParams)
    {
    	$result = $this->userRepo->validateMenu('create', $postParams);

    	if ($result['success']) {
    		$result = $this->indicadorRepo->create($postParams);
    	}
    	return $result;
    }

}
	

