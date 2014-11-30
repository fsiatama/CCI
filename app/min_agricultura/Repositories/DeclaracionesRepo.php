<?php

//require PATH_APP.'min_agricultura/Ado/DeclaracionesAdo.php';

require_once ('BaseRepo.php');

class DeclaracionesRepo extends BaseRepo {

	public function getModel()
	{

	}
	
	public function getModelAdo()
	{

	}

	public function getPrimaryKey()
	{

	}

	public function setData($params, $action)
	{

	}

	public function setFiltersValues($arrFiltersValues, $filtersConfig, $trade)
	{
		foreach ($filtersConfig as $filter) {

			if (array_key_exists($filter['field'], $arrFiltersValues)) {
				$fieldName = ($trade == 'impo') ? $filter['field_impo'] : $filter['field_expo'] ;

				$filterValue = $arrFiltersValues[$filter['field']];

				$methodName = $this->getColumnMethodName('set', $fieldName);

				if (method_exists($this->model, $methodName)) {
					call_user_func_array([$this->model, $methodName], compact('filterValue'));
				}
			}
		}
	}

	public function findBalanzaData($filters, $filtersConfig)
	{
		require PATH_MODELS.'Entities/Declaraimp.php';
		require PATH_MODELS.'Ado/DeclaraimpAdo.php';

		$arrFiltersValues = Helpers::filterValuesToArray($filters);
		
		$this->model = new Declaraimp;
		$this->modelAdo = new DeclaraimpAdo;

		//asigna los valores de filtro del indicador al modelo
		$this->setFiltersValues($arrFiltersValues, $filtersConfig, 'impo');

		$this->modelAdo->setPivotRowFields('anio');
		$this->modelAdo->setPivotTotalFields('valorfob');
		$this->modelAdo->setPivotGroupingFunction('SUM');

		$rsDeclaraimp = $this->modelAdo->pivotSearch($this->model);

		if (!$rsDeclaraimp['success']) {
			return $rsDeclaraimp;
		}
		if ($rsDeclaraimp['total'] == 0) {
			return [
				'success' => false,
				'error'   => Lang::get('error.no_records_found')
			];
		}

		require PATH_MODELS.'Entities/Declaraexp.php';
		require PATH_MODELS.'Ado/DeclaraexpAdo.php';

		$this->model = new Declaraexp;
		$this->modelAdo = new DeclaraexpAdo;
		//asigna los valores de filtro del indicador al modelo
		$this->setFiltersValues($arrFiltersValues, $filtersConfig, 'expo');

		$this->modelAdo->setPivotRowFields('anio');
		$this->modelAdo->setPivotTotalFields('valorfob');
		$this->modelAdo->setPivotGroupingFunction('SUM');

		$rsDeclaraexp = $this->modelAdo->pivotSearch($this->model);

		if (!$rsDeclaraexp['success']) {
			return $rsDeclaraexp;
		}
		if ($rsDeclaraexp['total'] == 0) {
			return [
				'success' => false,
				'error'   => Lang::get('error.no_records_found')
			];
		}

		$arrData = [];

		foreach ($rsDeclaraexp['data'] as $keyImpo => $rowExpo) {
			
			foreach ($rsDeclaraimp['data'] as $keyExpo => $rowImpo) {

				if($rowImpo["anio"] == $rowExpo["anio"]){
					$arrData[] = [
						'anio'          => $rowImpo["anio"],
						'valor_expo'    => $rowExpo["valorfob"],
						'valor_impo'    => $rowImpo["valorfob"],
					];
				}

			}

		}

		return [
			'success' => true,
			'data'    => $arrData,
			'total'   => count($arrData)
		];
	}

	public function executeBalanza($rowIndicador, $filtersConfig)
	{
		extract($rowIndicador);

		$result = $this->findBalanzaData($indicador_filtros, $filtersConfig);
		if ($result['success']) {

			$arrData = [];

			foreach ($result['data'] as $key => $value) {

				$valor_balanza = ( $value['valor_expo'] - $value['valor_impo'] );
				
				$arrData[] = array_merge($value, ['valor_balanza' => $valor_balanza]);

			}

			$arrSeries = [
				'valor_expo'    => Lang::get('indicador.columns_title.valor_expo'),
				'valor_impo'    => Lang::get('indicador.columns_title.valor_impo'),
				'valor_balanza' => Lang::get('indicador.columns_title.valor_balanza')
			];

			$columnChart = Helpers::jsonChart(
				$arrData,
				'anio',
				$arrSeries,
				COLUMNAS
			);

			$arrSeries = [
				'valor_balanza' => Lang::get('indicador.columns_title.valor_balanza')
			];

			$areaChart = Helpers::jsonChart(
				$arrData,
				'anio',
				$arrSeries,
				AREA
			);

			$result = [
				'success'         => $result['success'],
				'data'            => $arrData,
				'total'           => $result['total'],
				'columnChartData' => $columnChart,
				'areaChartData'   => $areaChart,
			];

		}

		return $result;

	}

	public function executeBalanzaRelativa($rowIndicador, $filtersConfig)
	{
		extract($rowIndicador);

		$result = $this->findBalanzaData($indicador_filtros, $filtersConfig);
		if ($result['success']) {

			$arrData = [];

			foreach ($result['data'] as $key => $value) {

				$valor_balanza = ( $value['valor_expo'] - $value['valor_impo'] ) / ( $value['valor_expo'] + $value['valor_impo'] );
				
				$arrData[] = array_merge($value, ['valor_balanza' => $valor_balanza]);

			}

			$arrSeries = [
				'valor_expo'    => Lang::get('indicador.columns_title.valor_expo'),
				'valor_impo'    => Lang::get('indicador.columns_title.valor_impo'),
				'valor_balanza' => Lang::get('indicador.columns_title.valor_balanza')
			];

			$columnChart = Helpers::jsonChart(
				$arrData,
				'anio',
				$arrSeries,
				COLUMNAS
			);

			$arrSeries = [
				'valor_balanza' => Lang::get('indicador.columns_title.valor_balanza')
			];

			$areaChart = Helpers::jsonChart(
				$arrData,
				'anio',
				$arrSeries,
				AREA
			);

			$result = [
				'success'         => $result['success'],
				'data'            => $arrData,
				'total'           => $result['total'],
				'columnChartData' => $columnChart,
				'areaChartData'   => $areaChart,
			];

		}

		return $result;

	}
}	

