<?php

require PATH_APP.'min_agricultura/Repositories/Tipo_indicadorRepo.php';
require PATH_APP.'min_agricultura/Repositories/IndicadorRepo.php';
require PATH_APP.'min_agricultura/Repositories/UserRepo.php';

class IndicadorController {
	
	private $indicadorRepo;
	private $tipo_indicadorRepo;
	private $userRepo;

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

	public function listIdAction($urlParams, $postParams)
	{
		$result = $this->userRepo->validateMenu('list', $postParams);

		if ($result['success']) {
			$result = $this->indicadorRepo->listId($postParams);
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

			$updateInfoImpo = Helpers::getUpdateInfo('aduanas', 'impo');
			$updateInfoExpo = Helpers::getUpdateInfo('aduanas', 'expo');

			$params = array_merge($postParams, $row, compact('action', 'updateInfoImpo', 'updateInfoExpo'));

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

			$lines          = Helpers::getRequire(PATH_APP.'lib/indicador.config.php');
			$trade          = Helpers::arrayGet($lines, 'trade');
			$yearsAvailable =  Helpers::arrayGet($lines, 'yearsAvailable');
			
			$updateInfo     = Helpers::getUpdateInfo('aduanas', 'impo');
			$yearsAvailable = ($updateInfo !== false) ? $updateInfo['yearsAvailable'] : $yearsAvailable ;

			$params = array_merge($postParams, $row, compact('action', 'yearsAvailable', 'trade'));

			$tipo_indicador = $row['tipo_indicador_id'];

			//el template de adicionar y editar son los mismos
			$action = ($action == 'modify') ? 'create' : $action;

			//var_dump($params);
			return new View('jsCode/indicador/indicador.create.'.$tipo_indicador, $params);
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
			$rowTipoIndicador = array_shift($result['data']);

			//verifica que exista el indicador y trae los datos
			$result = $this->indicadorRepo->validateModify($postParams);
			if (!$result['success']) {
				return $result;
			}
			$rowIndicador = array_shift($result['data']);

			$postParams['is_template'] = true;

			$lines          = Helpers::getRequire(PATH_APP.'lib/indicador.config.php');
			$yearsAvailable = Helpers::arrayGet($lines, 'yearsAvailable');
			$periods        = Helpers::arrayGet($lines, 'periods');
			$scopes         = Helpers::arrayGet($lines, 'scopes');
			$scales         = Helpers::arrayGet($lines, 'scales');

			$updateInfo     = Helpers::getUpdateInfo('aduanas', 'impo');
			$yearsAvailable = ($updateInfo !== false) ? $updateInfo['yearsAvailable'] : $yearsAvailable ;

			$params = array_merge($postParams, $rowTipoIndicador, $rowIndicador, compact('action', 'yearsAvailable', 'periods', 'scopes', 'scales'));

			//var_dump($params);

			$tipo_indicador = $rowTipoIndicador['tipo_indicador_id'];

			return new View('jsCode/indicador/indicador.execute.'.$tipo_indicador, $params);
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

	public function modifyAction($urlParams, $postParams)
	{
		$result = $this->userRepo->validateMenu('modify', $postParams);

		if ($result['success']) {
			$result = $this->indicadorRepo->modify($postParams);
		}
		return $result;
	}

	public function executeAction($urlParams, $postParams)
	{
		$result = $this->userRepo->validateMenu('list', $postParams);
		if ($result['success']) {
			$result = $this->indicadorRepo->execute($postParams);
		}
		return $result;
	}

	/**
	 * treeAdminAction
	 * 
	 * @param array $urlParams  Parametrso pasados por url.
	 * @param array $postParams parametros via POST (action, tipo_indicador_id, id de la opcion de menu, entre otros).
	 *
	 * @access public
	 *
	 * @return mixed Value.
	 */
	public function treeAdminAction($urlParams, $postParams)
	{
		$action = (empty($postParams['actionId'])) ? '' : $postParams['actionId'] ;
		//por el funcionamiento del plugin TreeRemoteComponent
		//por este controlador llegan todos los metodos para administrar las carpetas y los reportes
		$result = $this->userRepo->validateMenu('list', $postParams);

		if ($result['success']) {
			switch ($action) {
				case 'list':
					$result = $this->indicadorRepo->listUserId($postParams);
				break;
				case 'insertTreeChild':
					$result = $this->userRepo->validateMenu('create', $postParams);
					if ($result['success']) {
						$result = $this->indicadorRepo->createFolder($postParams);
					}
				break;
				case 'moveTreeNode':
					$result = $this->userRepo->validateMenu('modify', $postParams);
					if ($result['success']) {
						$result = $this->indicadorRepo->moveNode($postParams);
					}
				break;
				case 'removeTreeNode':
					$result = $this->userRepo->validateMenu('delete', $postParams);
					if ($result['success']) {
						$result = $this->indicadorRepo->removeNode($postParams);
					}
				break;
				case 'renameTreeNode':
					$result = $this->userRepo->validateMenu('modify', $postParams);
					if ($result['success']) {
						$result = $this->indicadorRepo->renameNode($postParams);
					}
				break;
			}
		}
		return $result;
	}

	public function publicQuadrantsAction($urlParams, $postParams)
	{

		return $this->indicadorRepo->executeQuadrants($postParams);

	}

	public function publicReportsAction($urlParams, $postParams)
	{

		return $this->indicadorRepo->executePublicReports($postParams);

	}

}
	

