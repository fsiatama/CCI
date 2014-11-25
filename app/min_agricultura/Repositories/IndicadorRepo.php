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

	public function listUserId($params)
	{
		extract($params);
		if (empty($tipo_indicador_id)) {
			$result = array(
				'success' => false,
				'error'   => 'Incomplete data for this request.'
			);
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
										
				$arr[] = array(
					'id'        => $data['indicador_id'],
					'nodeID'    => $data['indicador_id'],
					'pnodeID'   => $data['indicador_parent'],
					'text'      => $data['indicador_nombre'],
					'leaf'      => ( $data['indicador_leaf'] == '0' ) ? 'false' : 'true',
					'qtip'      => $qtip,
					'iconCls'   => $css,
				);
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
				$result = [
					'success'  => false,
					'closeTab' => true,
					'tab'      => 'tab-'.$module,
					'error'    => $result['error']
				];
				return $result;
			}
		}

		$indicador_filtros = $this->getFiltersValue($params);

		if (
			empty($indicador_nombre) ||
			empty($indicador_tipo_indicador_id) ||
			empty($indicador_filtros)
		) {
			$result = array(
				'success' => false,
				'error'   => 'Incomplete data for this request.'
			);
			return $result;
		}

		$this->model->setIndicador_nombre($indicador_nombre);
		$this->model->setIndicador_tipo_indicador_id($indicador_tipo_indicador_id);
		$this->model->setIndicador_filtros($indicador_filtros);
		$this->model->setIndicador_leaf('1');
		
		if ($action == 'create') {
			$this->model->setIndicador_uinsert($_SESSION['user_id']);
			$this->model->setIndicador_finsert(Helpers::getDateTimeNow());
		}
		elseif ($action == 'modify') {
			$this->model->setIndicador_fupdate(Helpers::getDateTimeNow());
		}
		$result = array('success' => true);
		return $result;
	}

	public function getFiltersValue($params)
	{
		$lines = Helpers::getRequire(PATH_APP.'lib/indicador.config.php');

		$arrFiltersName = Helpers::arrayGet($lines, 'filters.'.$params['indicador_tipo_indicador_id']);

		$arrFiltersValue = [];

		foreach ($arrFiltersName as $key) {
			if (array_key_exists($key, $params)) {
				
				if (is_array($params[$key])) {
					$arrFiltersValue[] = $key . ':' .implode(',', $params[$key]);
				} else {
					$arrFiltersValue[] = $key . ':' .$params[$key];
				}
				
			} else {
				//retorna una cadena vacia ya que los filtros no estan completos
				return '';
			}
		}
		return implode('||', $arrFiltersValue);
	}

}	
