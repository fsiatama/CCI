<?php

require PATH_APP.'min_agricultura/Entities/Indicador.php';
require PATH_APP.'min_agricultura/Ado/IndicadorAdo.php';
require_once ('BaseRepo.php');

class IndicadorRepo extends BaseRepo {

	public function getModel()
	{
		return new Indicador;
	}
	
	public function getModelAdo()
	{
		return new IndicadorAdo;
	}

	public function getPrimaryKey()
	{
		return 'indicador_id';
	}

	public function validateModify($params)
	{
		extract($params);
		$result = $this->findPrimaryKey($indicador_id);

		if (!$result['success']) {
			$result = [
				'success'  => false,
				'closeTab' => true,
				'tab'      => 'tab-'.$module,
				'error'    => $result['error']
			];
		}
		return $result;
	}

	public function listId($params)
	{
		$this->modelAdo->setColumns([
			'indicador_id',
			'indicador_nombre',
			'indicador_filtros',
			'indicador_tipo_indicador_id',
		]);

		$result = $this->validateModify($params);
		if ($result['success']) {
			$row = array_shift($result['data']);

			$arrFiltersValues = Helpers::filterValuesToArray($row['indicador_filtros']);
			$result['data'][] = array_merge($row, $arrFiltersValues);
		}

		return $result;
	}

	public function listUserId($params)
	{
		extract($params);
		if (empty($tipo_indicador_id)) {
			return [
				'success' => false,
				'error'   => 'Incomplete data for this request.'
			];
			return $result;
		}
		$this->model->setIndicador_uinsert($_SESSION['user_id']);
		$this->model->setIndicador_tipo_indicador_id($tipo_indicador_id);

		$this->modelAdo->setColumns([
			'indicador_id',
			'indicador_nombre',
			'indicador_leaf',
			'indicador_parent'
		]);

		$result = $this->modelAdo->exactSearch($this->model);
		if ($result['success']) {
			$arr = [];
			foreach($result['data'] as $key => $data){
				$qtip = '';
				if($data["indicador_leaf"] == '1'){
					/*if($data["indicador_detalle"] == ""){
						$filtros  = $data["reportes_filtros"];			
						$_filtros = convierteArreglo($filtros);
						$html_tip = array();
						$html_tip[] = _INTERCAMBIO . " : " . ($data["reportes_intercambio"]==0?_IMPORTACION:_EXPORTACION);
						foreach($filtrosIntercambioSisduan[$data["reportes_intercambio"]] as $i => $filtro){
							foreach($_filtros as $j => $filtro_valores){
								if($filtro["filtro"] == $j){
									$html_tip[] = utf8_encode(traducir($filtro["nombre"])) . " : " . ($filtro_valores);
								}
							}
						}
						$qtip = implode("<br>",$html_tip);
					}
					else{
						$qtip .= str_replace("->","<br>",$data["reportes_detalle"]);
					}*/
				}
				//$arr_filas = ($data["reportes_filas"] == "")?false:explode("||",$data["reportes_filas"]);
				
				if($data["indicador_leaf"] == '0'){
					$css = "silk-folder";
				}
				else{
					$css = "silk-report-magnify";
				}
										
				$arr[] = [
					'id'        => $data['indicador_id'],
					'nodeID'    => $data['indicador_id'],
					'pnodeID'   => $data['indicador_parent'],
					'text'      => $data['indicador_nombre'],
					'leaf'      => ( $data['indicador_leaf'] == '0' ) ? 'false' : 'true',
					'qtip'      => $qtip,
					'iconCls'   => $css,
				];
			}

			$result = $arr;
		}
		return $result;

	}

	public function setData($params, $action)
	{
		extract($params);

		if ($action == 'modify') {
			$result = $this->findPrimaryKey($indicador_id);

			if (!$result['success']) {
				return [
					'success'  => false,
					'closeTab' => true,
					'tab'      => 'tab-'.$module,
					'error'    => $result['error']
				];
			}
		}

		$indicador_campos = $this->getDescriptionValue($description);

		$indicador_filtros = $this->getFiltersValue($params);

		if (
			empty($indicador_nombre) ||
			empty($indicador_tipo_indicador_id) ||
			empty($indicador_campos) ||
			empty($indicador_filtros)
		) {
			return [
				'success' => false,
				'error'   => 'Incomplete data for this request.'
			];
		}

		$this->model->setIndicador_nombre($indicador_nombre);
		$this->model->setIndicador_tipo_indicador_id($indicador_tipo_indicador_id);
		$this->model->setIndicador_campos($indicador_campos);
		$this->model->setIndicador_filtros($indicador_filtros);
		$this->model->setIndicador_leaf('1');
		
		if ($action == 'create') {
			$this->model->setIndicador_uinsert($_SESSION['user_id']);
			$this->model->setIndicador_finsert(Helpers::getDateTimeNow());
		}
		elseif ($action == 'modify') {
			$this->model->setIndicador_fupdate(Helpers::getDateTimeNow());
		}
		return [ 'success' => true ];
	}

	public function getDescriptionValue($description)
	{
		$arr = [];
		$arrDescription = json_decode($description, true);
		if (!empty($arrDescription)) {
			foreach ($arrDescription as $key => $value) {
				$arr[] = Inflector::cleanInputString($value['label']) . ': ' . Inflector::cleanInputString(implode(',', $value['values']));
			}
		}
		return implode('||', $arr);
	}

    /**
     * getFiltersValue
     * 
     * @param array $params Recibe los parametros que son enviados desde el formulario.
     *        de crear o modificar el indicador
     * @access public
     *
     * @return string con los filtros para almacenar en la base de datos.
     */
	public function getFiltersValue($params)
	{
		$lines = Helpers::getRequire(PATH_APP.'lib/indicador.config.php');

		$arrFiltersName = Helpers::arrayGet($lines, 'filters.'.$params['indicador_tipo_indicador_id']);

		$arrFiltersValue = [];
		if (!empty($arrFiltersName)) {
			foreach ($arrFiltersName as $filter) {
				$fieldName = $filter['field'];

				if ($filter['required'] && array_key_exists($fieldName, $params)) {
					
					if (is_array($params[$fieldName]) && !empty($params[$fieldName])) {
						
						$arrFiltersValue[] = $fieldName . ':' .implode(',', $params[$fieldName]);

					} elseif (!empty($params[$fieldName])) {
						$arrFiltersValue[] = $fieldName . ':' .$params[$fieldName];
					} else {
						//si el parametro no es un array, o esta vacio
						//retorna vacio para que genere error
						return '';
					}

				} elseif (array_key_exists($fieldName, $params)) {
					
					if (is_array($params[$fieldName]) && !empty($params[$fieldName])) {
						
						$arrFiltersValue[] = $fieldName . ':' .implode(',', $params[$fieldName]);
					
					}
				}
			}
		}

		return implode('||', $arrFiltersValue);
	}

	public function execute($params)
	{
		extract($params);
		
		if (empty($indicador_id) ||
			empty($year) ||
			empty($period)
		) {
			return [
				'success' => false,
				'error'   => 'Incomplete data for this request.'
			];
		}
		$this->model->setIndicador_id($indicador_id);
		$result = $this->modelAdo->exactSearch($this->model);
		if ($result['success']) {
			$row = array_shift($result['data']);

			$lines = Helpers::getRequire(PATH_APP.'lib/indicador.config.php');

			$arrExecuteConfig = Helpers::arrayGet($lines, 'executeConfig.'.$row['indicador_tipo_indicador_id']);
			$arrFiltersName   = Helpers::arrayGet($lines, 'filters.'.$row['indicador_tipo_indicador_id']);

			$repoFileName   = PATH_MODELS.'Repositories/'.$arrExecuteConfig['repoClassName'].'.php';
			$repoClassName  = $arrExecuteConfig['repoClassName'];
			$repoMethodName = 'execute' . $arrExecuteConfig['methodName'];

			if ( ! file_exists($repoFileName)) {
				return [
					'success' => false,
					'error'   => 'unavailable repo '. $repoClassName
				];
			}

			require $repoFileName;

			$repo = new $repoClassName();
			if (method_exists($repo, $repoMethodName)) {
				$result = call_user_func_array([$repo, $repoMethodName], compact('row', 'arrFiltersName', 'year', 'period'));
			} else {
				return [
					'success' => false,
					'error'   => 'unavailable method '. $repoMethodName
				];
			}			
		}
		return $result;
	}

}	

