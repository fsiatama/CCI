<?php

require PATH_MODELS.'Entities/Indicador.php';
require PATH_MODELS.'Ado/IndicadorAdo.php';
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

		$module = (empty($module)) ? '' : $module ;

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
			'indicador_leaf',
		]);

		$result = $this->validateModify($params);
		if ($result['success']) {
			$row = array_shift($result['data']);
			$arrFiltersValues = [];

			if (!empty($row['indicador_filtros'])) {
				$arrFiltersValues = Helpers::filterValuesToArray($row['indicador_filtros']);
			}

			$result['data'][] = array_merge($row, $arrFiltersValues);
		}

		return $result;
	}

	public function listUserId($params)
	{
		extract($params);

		$indicador_parent = ($node == $module . 'root') ? '0' : $node ;

		if (empty($tipo_indicador_id)) {
			return [
				'success' => false,
				'error'   => 'Incomplete data for this request.'
			];
		}
		$this->model->setIndicador_parent($indicador_parent);
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
					'leaf'      => ( $data['indicador_leaf'] == '0' ) ? false : true,
					'qtip'      => $qtip,
					'iconCls'   => $css,
				];
			}

			$result = $arr;
		}
		return $result;

	}

	public function createFolder($params)
	{
		extract($params);

		if (
			empty($text) ||
			empty($tipo_indicador_id) ||
			empty($parentId) ||
			empty($module)
		) {
			return [
				'success' => false,
				'error'   => 'Incomplete data for this request.'
			];
		}

		$indicador_parent = ($parentId == $module . 'root') ? 0 : $parentId ;

		$this->model->setIndicador_nombre($text);
		$this->model->setIndicador_tipo_indicador_id($tipo_indicador_id);
		$this->model->setIndicador_leaf('0');
		$this->model->setIndicador_parent($indicador_parent);
		$this->model->setIndicador_uinsert($_SESSION['user_id']);
		$this->model->setIndicador_finsert(Helpers::getDateTimeNow());

		$result = $this->modelAdo->create($this->model);
		if ($result['success']) {
			return [
				'success' => true,
				'id' => $result['insertId']
			];
		}

		return $result;
	}

	public function moveNode($params)
	{
		extract($params);

		$indicador_id = $parentId;

		//verifica que exista el indicador
		$result = $this->validateModify(compact('indicador_id'));
		if (!$result['success']) {
			return $result;
		}

		if (
			empty($target) ||
			empty($parentId)
		) {
			return [
				'success' => false,
				'error'   => 'Incomplete data for this request.'
			];
		}

		$this->model->setIndicador_parent($target);

		$result = $this->modelAdo->update($this->model);

		if ($result['success']) {
			return ['success' => true];
		}

		return $result;
	}

	public function removeNode($params)
	{
		extract($params);

		$this->model->setIndicador_parent($parentId);
		$this->model->setIndicador_uinsert($_SESSION['user_id']);
		$this->model->setIndicador_tipo_indicador_id($tipo_indicador_id);

		$this->modelAdo->setColumns([
			'indicador_id',
			'indicador_nombre',
			'indicador_leaf',
			'indicador_parent'
		]);

		$result = $this->modelAdo->exactSearch($this->model);
		if (!$result['success']) {
			return $result;
		}

		foreach ($result['data'] as $key => $row) {

			$this->model = $this->getModel();

			if ($row['indicador_leaf'] == '0') {
				//si se trata de una carpeta debe borrar todos los hijos
				$params = [
					'id' => $id,
					'module' => $module,
					'parentId' => $row['indicador_id'],
					'tipo_indicador_id' => $tipo_indicador_id,
				];

				$result = $this->removeNode($params);
				if (!$result['success']) {
					return $result;
				}

			} else {
				$indicador_id = $row['indicador_id'];
				$result = $this->validateModify(compact('indicador_id'));
				if (!$result['success']) {
					return $result;
				}

				$result = $this->modelAdo->delete($this->model);

				if (!$result['success']) {
					return $result;
				}
			}

		}

		$indicador_id = $parentId;
		$this->model = $this->getModel();

		//verifica que exista el indicador
		$result = $this->validateModify(compact('indicador_id'));
		if (!$result['success']) {
			return $result;
		}

		$result = $this->modelAdo->delete($this->model);

		if ($result['success']) {
			return ['success' => true];
		}

		return $result;
	}

	public function renameNode($params)
	{
		extract($params);

		if (!empty($parentId) && substr($parentId, -4) == 'root') {
			return [
				'success' => false,
				'error'   => Lang::get('error.root_folder_is_not_editable'),
			];
		}

		$indicador_id = $parentId;

		//verifica que exista el indicador
		$result = $this->validateModify(compact('indicador_id'));
		if (!$result['success']) {
			return $result;
		}

		if (empty($newText)) {
			return [
				'success' => false,
				'error'   => 'Incomplete data for this request.'
			];
		}

		$this->model->setIndicador_nombre($newText);

		$result = $this->modelAdo->update($this->model);

		if ($result['success']) {
			return ['success' => true];
		}

		return $result;
	}

	public function setData($params, $action)
	{
		extract($params);

		$indicador_parent = ($parentId == $module . 'root') ? 0 : $parentId ;

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
		$this->model->setIndicador_parent($indicador_parent);

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
		$arrDescription = json_decode(stripslashes($description), true);
		if (!empty($arrDescription)) {
			foreach ($arrDescription as $key => $value) {
				$label  = ( empty($value['label']) ) ? '' : Inflector::cleanInputString($value['label']) ;
				$values = ( is_array($value['values']) ) ? implode(',', $value['values']) : $value['values'] ;
				$values = Inflector::cleanInputString( $values ) ;

				$arr[] = $label . ': ' . $values;
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

		if (empty($params['indicador_tipo_indicador_id'])) {
			return '';
		}

		$lines = Helpers::getRequire(PATH_APP.'lib/indicador.config.php');
		$arrFiltersName = Helpers::arrayGet($lines, 'filters.'.$params['indicador_tipo_indicador_id']);

		$arrFiltersValue = [];
		if (!empty($arrFiltersName)) {
			foreach ($arrFiltersName as $filter) {
				$fieldName = $filter['field'];

				$values = (is_array($params[$fieldName])) ? implode(',', $params[$fieldName]) : $params[$fieldName] ;
				
				if ($filter['required']) {

					if (!array_key_exists($fieldName, $params)) {
						//si el campo es requerido y el valor no viene dentro de los parametros
						//retorna vacio para que genere error
						return '';
					}

					if (!empty($values)) {
						$arrFiltersValue[] = $fieldName . ':' .$values;
					} else {
						//si el campo es requerido y el valor no viene vacio
						//retorna vacio para que genere error
						return '';
					}

				} elseif (!empty($filter['requiredComplement'])) {
					//si el filtro es requerido en complemento con otros campos, 
					//quiere decir que alguno de los campos debe ser diferente de blanco
					$complement = false;
					if (empty($values)) {
						foreach ($filter['complement'] as $field) {
							$fieldName2 = $field;
							$values2    = '';

							if (array_key_exists($fieldName2, $params)) {
								$values2 = (is_array($params[$fieldName2])) ? implode(',', $params[$fieldName2]) : $params[$fieldName2] ;
							}

							if (!empty($values2)) {
								$complement = true;
							}
						}
					}
					
					if (empty($values) && !$complement) {
						//por lo tanto si el primer campo y todos los demas complementos vienen vacios
						//retorna vacio para que genere error
						return '';
					}
					$arrFiltersValue[] = $fieldName . ':' .$values;

				} elseif (array_key_exists($fieldName, $params)) {
					if (!empty($values)) {
						//si el campo es opcional y el valor no viene vacio, lo agraga a la configuracion
						$arrFiltersValue[] = $fieldName . ':' .$values;
					}
				}
			}
		}

		return implode('||', $arrFiltersValue);
	}

	public function execute($params)
	{
		extract($params);
		$year      = (empty($year)) ? '' : $year ;
		$period    = (empty($period)) ? 12 : $period ;
		$format    = (empty($format)) ? false : $format ;
		$fields    = (empty($fields)) ? [] : json_decode(stripslashes($fields), true) ;
		$scope     = (empty($scope)) ? 1 : $scope ;
		$scale     = (empty($scale)) ? 1 : $scale ;
		$chartType = (empty($chartType)) ? AREA : $chartType ;
		

		if (empty($indicador_id)) {
			return [
				'success' => false,
				'error'   => 'Incomplete data for this request.'
			];
		}
		$this->model->setIndicador_id($indicador_id);
		$result = $this->modelAdo->exactSearch($this->model);
		if ($result['success']) {
			$row = array_shift($result['data']);

			$typeIndicator  = ( empty($typeIndicator) ) ? $row['tipo_indicador_activador'] : $typeIndicator ;

			$lines = Helpers::getRequire(PATH_APP.'lib/indicador.config.php');

			$arrExecuteConfig = Helpers::arrayGet($lines, 'executeConfig.'.$row['indicador_tipo_indicador_id']);
			$arrFiltersName   = Helpers::arrayGet($lines, 'filters.'.$row['indicador_tipo_indicador_id']);

			if (empty($arrExecuteConfig)) {
				return [
					'success' => false,
					'error'   => 'There is no configuration for this method'
				];
			}

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

			$repo = new $repoClassName(
				$row,
				$arrFiltersName,
				$year,
				$period,
				$scope,
				$scale,
				$typeIndicator,
				$chartType
			);
			if ( !method_exists($repo, $repoMethodName) ) {
				return [
					'success' => false,
					'error'   => 'unavailable method '. $repoMethodName
				];
			}
			$result = call_user_func_array([$repo, $repoMethodName], []);

			if ($format !== false && !empty($fields) && $result['total'] > 0) {
				$arrDescription   = [];
				$arrDescription['title'] = $row['tipo_indicador_nombre'];
				foreach (explode('||', $row['indicador_campos']) as $value) {
					$arr  = explode(':', $value);
					if (!empty($arr[1])) {
						$arrDescription[$arr[0]] = $value;
					}
				}

				if ( ! empty($result['pYAxisName']) ) {
					$scaleName = $result['pYAxisName'];
				} else {
					if ($scale == '2') {
						$scaleName  = ($typeIndicator == 'precio') ? Lang::get('indicador.reports.priceThousands') : Lang::get('indicador.reports.quantityThousands') ;
					} elseif ($scale == '3') {
						$scaleName  = ($typeIndicator == 'precio') ? Lang::get('indicador.reports.priceMillions') : Lang::get('indicador.reports.quantityMillions');
					} else {
						$scaleName  = ($typeIndicator == 'precio') ? Lang::get('indicador.reports.priceUnit') : Lang::get('indicador.reports.quantityUnit');
					}
				}

				$arrDescription['values'] = Lang::get('indicador.reports.valuesPresentedIn') . ': ' . $scaleName;

				$excel = new Excel (
					$result,
					$format,
					$fields,
					$row['indicador_nombre'],
					$arrDescription
				);
				$result = $excel->write();
			}
		}
		return $result;
	}

	public function executeQuadrants($params)
	{
		extract($params);

		$products  = ( !empty($products) && is_array($products) ) ? $products : [] ;
		$countries = ( !empty($countries) && is_array($countries) ) ? $countries : [] ;

		if (empty($products)) {
			$result = [
				'success' => false,
				'error'   => 'Incomplete data for this request.'
			];
			return $result;
		}

		$lines            = Helpers::getRequire(PATH_APP.'lib/indicador.config.php');
		$arrExecuteConfig = Helpers::arrayGet($lines, 'executeConfig.cuadrantes');
		$arrFiltersName   = Helpers::arrayGet($lines, 'filters.cuadrantes');

		if (empty($arrExecuteConfig)) {
			return [
				'success' => false,
				'error'   => 'There is no configuration for this method'
			];
		}

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

		$now = new DateTime;
  		$now->modify( '-2year' );
  		$yearLast = $now->format('Y'); //toma el año inmediatamente anterior
  		$now->modify( '-4year' );
  		$yearFirst = $now->format('Y'); //toma 5 hacia a tras

		$arrFilters = [
			'id_subpartida:'  . implode(',', $products),
			'id_pais_destino:'. implode(',', $countries),
			'anio_ini:'       . $yearFirst,
			'anio_fin:'       . $yearLast,
		];
		$params       = [
			'indicador_filtros'        => implode('||', $arrFilters),
			'tipo_indicador_activador' => 'volumen',
		];

		$repo = new $repoClassName(
			$params,
			$arrFiltersName,
			'',
			12,
			1
		);
		if (!method_exists($repo, $repoMethodName)) {
			return [
				'success' => false,
				'error'   => 'unavailable method '. $repoMethodName
			];
		}
		$rsExecuted = call_user_func_array([$repo, $repoMethodName], []);
		//if ( ! $rsExecuted['success'] ) {
			return $rsExecuted;
		//}

	}

	public function executePublicReports($params)
	{
		extract($params);

		if (empty($report)) {
			$result = [
				'success' => false,
				'error'   => 'Incomplete data for this request.'
			];
			return $result;
		}

		$methodName       = Inflector::lowerCamel($report);
		$lines            = Helpers::getRequire(PATH_APP.'lib/indicador.config.php');
		$arrExecuteConfig = Helpers::arrayGet($lines, 'executeConfig.'.$methodName);
		$arrFiltersName   = Helpers::arrayGet($lines, 'filters.'.$methodName);

		if (empty($arrExecuteConfig)) {
			return [
				'success' => false,
				'error'   => 'There is no configuration for this method'
			];
		}

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

		$updateInfo = Helpers::getUpdateInfo('aduanas', 'impo');
		$yearLast   = $updateInfo['dateTo']->format('Y');
  		$updateInfo['dateTo']->modify( '-4year' );
  		$yearFirst = $updateInfo['dateTo']->format('Y'); //toma 5 hacia a tras

		$arrFilters = [
			'anio_ini:' . $yearFirst,
			'anio_fin:' . $yearLast,
		];

		//var_dump($arrFilters);
		$params       = [
			'indicador_filtros'        => implode('||', $arrFilters),
			'tipo_indicador_activador' => 'volumen',
		];

		$repo = new $repoClassName(
			$params,
			$arrFiltersName,
			'',
			12,
			1,
			2
		);
		if (!method_exists($repo, $repoMethodName)) {
			return [
				'success' => false,
				'error'   => 'unavailable method '. $repoMethodName
			];
		}
		$rsExecuted = call_user_func_array([$repo, $repoMethodName], []);
		//if ( ! $rsExecuted['success'] ) {
			return $rsExecuted;
		//}

	}

}

