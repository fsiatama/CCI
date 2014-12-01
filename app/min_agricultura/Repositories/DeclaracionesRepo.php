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

	public function setFiltersValues($arrFiltersValues, $filtersConfig, $trade, $range)
	{

		foreach ($filtersConfig as $filter) {

			if (array_key_exists($filter['field'], $arrFiltersValues)) {
				$fieldName = ($trade == 'impo') ? $filter['field_impo'] : $filter['field_expo'] ;

				$filterValue = $arrFiltersValues[$filter['field']];

				$methodName = $this->getColumnMethodName('set', $fieldName);

				$setFilterValue = true;

				//si el filtro es un rango de fechas, debe unir los periodos que componen el rango
				if (!empty($filter['dateRange'])) {

					$setFilterValue = false;

					$rangeYear = ($range == 'ini') ? 'anio_ini' : 'anio_fin';

					//la configuracion y las variables pueden traer dos rangos
					//debe asignar solo uno

					if ($rangeYear == $filter['field']) {
						//asigna el año
						if (method_exists($this->model, $methodName)) {
							call_user_func_array([$this->model, $methodName], compact('filterValue'));
						}

						//asigna el rango de periodos
						$methodName = $this->getColumnMethodName('set', 'periodo');

						//esta linea crea un rango entre el periodo inicial y el final
						$filterValue = range($arrFiltersValues[$filter['dateRange'][0]], $arrFiltersValues[$filter['dateRange'][1]]);
						$filterValue = implode(',', $filterValue);

						call_user_func_array([$this->model, $methodName], compact('filterValue'));
					}

				} elseif (!empty($filter['itComplements'])) {
					//si el filtro es complemento de otro no lo debe tener en cuenta
					$setFilterValue = false;
				}

				if (method_exists($this->model, $methodName) && $setFilterValue) {
					//var_dump($methodName);
					call_user_func_array([$this->model, $methodName], compact('filterValue'));
				}
			}
		}
	}

	public function findBalanzaData($filters, $filtersConfig, $year, $period, $range = false)
	{
		require_once PATH_MODELS.'Entities/Declaraimp.php';
		require_once PATH_MODELS.'Ado/DeclaraimpAdo.php';

		$arrFiltersValues = Helpers::filterValuesToArray($filters);
		

		$this->model = new Declaraimp;
		$this->modelAdo = new DeclaraimpAdo;
		
		$rowField = Helpers::getPeriodColumnSql($period);

		//asigna los valores de filtro del indicador al modelo
		$this->setFiltersValues($arrFiltersValues, $filtersConfig, 'impo', $range);

		//si el periodo es diferente a anual debe filtrar por año
		if ($period != 12 && !empty($year)) {
			$this->model->setAnio($year);
		}

		$arrRowField = ['periodo AS id', $rowField];

		$this->modelAdo->setPivotRowFields(implode(',', $arrRowField));
		$this->modelAdo->setPivotTotalFields('valorfob');
		$this->modelAdo->setPivotGroupingFunction('SUM');

		$rsDeclaraimp = $this->modelAdo->pivotSearch($this->model);

		if (!$rsDeclaraimp['success']) {
			return $rsDeclaraimp;
		}
		/*if ($rsDeclaraimp['total'] == 0) {
			return [
				'success' => false,
				'error'   => Lang::get('error.no_records_found')
			];
		}*/

		require_once PATH_MODELS.'Entities/Declaraexp.php';
		require_once PATH_MODELS.'Ado/DeclaraexpAdo.php';

		$this->model = new Declaraexp;
		$this->modelAdo = new DeclaraexpAdo;
		//asigna los valores de filtro del indicador al modelo
		$this->setFiltersValues($arrFiltersValues, $filtersConfig, 'expo', $range);

		//si el periodo es diferente a anual debe filtrar por año
		if ($period != 12 && !empty($year)) {
			$this->model->setAnio($year);
		}

		$arrRowField = ['periodo AS id', $rowField];

		$this->modelAdo->setPivotRowFields(implode(',', $arrRowField));
		$this->modelAdo->setPivotTotalFields('valorfob');
		$this->modelAdo->setPivotGroupingFunction('SUM');

		$rsDeclaraexp = $this->modelAdo->pivotSearch($this->model);

		if (!$rsDeclaraexp['success']) {
			return $rsDeclaraexp;
		}
		/*if ($rsDeclaraexp['total'] == 0) {
			return [
				'success' => false,
				'error'   => Lang::get('error.no_records_found')
			];
		}*/

		$arrData = [];
		$arrPeriods = [];

		foreach ($rsDeclaraexp['data'] as $keyExpo => $rowExpo) {
			
			$valor_impo = 0;

			foreach ($rsDeclaraimp['data'] as $keyImpo => $rowImpo) {

				if($rowImpo['periodo'] == $rowExpo['periodo']){
					$valor_impo = $rowImpo['valorfob'];
					$arrPeriods[] = $rowImpo['periodo'];
				}

			}

			$arrData[] = [
				'id'         => $rowExpo['id'],
				'periodo'    => $rowExpo['periodo'],
				'valor_expo' => $rowExpo['valorfob'],
				'valor_impo' => $valor_impo,
			];
		}

		foreach ($rsDeclaraimp['data'] as $keyImpo => $rowImpo) {
			
			if(!in_array($rowImpo['periodo'], $arrPeriods)){
				$arrData[] = [
					'id'         => $rowImpo['id'],
					'periodo'    => $rowImpo['periodo'],
					'valor_expo' => 0,
					'valor_impo' => $rowImpo['valorfob'],
				];
			}

		}

		if (count($arrData) == 0) {
			return [
				'success' => false,
				'error'   => Lang::get('error.no_records_found')
			];
		}

		return [
			'success' => true,
			'data'    => $arrData,
			'total'   => count($arrData)
		];
	}

	public function executeBalanza($rowIndicador, $filtersConfig, $year, $period)
	{
		extract($rowIndicador);

		$result = $this->findBalanzaData($indicador_filtros, $filtersConfig, $year, $period);
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
				'periodo',
				$arrSeries,
				COLUMNAS
			);

			$arrSeries = [
				'valor_balanza' => Lang::get('indicador.columns_title.valor_balanza')
			];

			$areaChart = Helpers::jsonChart(
				$arrData,
				'periodo',
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

	public function executeBalanzaRelativa($rowIndicador, $filtersConfig, $year, $period)
	{
		extract($rowIndicador);

		$result = $this->findBalanzaData($indicador_filtros, $filtersConfig, $year, $period);
		if ($result['success']) {

			$arrData = [];

			foreach ($result['data'] as $key => $value) {

				$valor_balanza = ( $value['valor_expo'] - $value['valor_impo'] ) / ( $value['valor_expo'] + $value['valor_impo'] );
				
				$arrData[] = array_merge($value, ['valor_balanza' => $valor_balanza]);

			}

			/*$arrSeries = [
				'valor_expo'    => Lang::get('indicador.columns_title.valor_expo'),
				'valor_impo'    => Lang::get('indicador.columns_title.valor_impo'),
				'valor_balanza' => Lang::get('indicador.columns_title.valor_balanza')
			];

			$columnChart = Helpers::jsonChart(
				$arrData,
				'periodo',
				$arrSeries,
				COLUMNAS
			);*/

			$arrSeries = [
				'valor_balanza' => Lang::get('indicador.columns_title.valor_balanza')
			];

			$areaChart = Helpers::jsonChart(
				$arrData,
				'periodo',
				$arrSeries,
				AREA
			);

			$result = [
				'success'         => $result['success'],
				'data'            => $arrData,
				'total'           => $result['total'],
				//'columnChartData' => $columnChart,
				'areaChartData'   => $areaChart,
			];

		}

		return $result;

	}

	public function executeBalanzaVariacion($rowIndicador, $filtersConfig, $year, $period)
	{
		//var_dump($rowIndicador, $filtersConfig, $year, $period);

		extract($rowIndicador);

		$result = $this->findBalanzaData($indicador_filtros, $filtersConfig, $year, $period, 'ini');
		if ($result['success']) {

			//calcula el valor de la balanza simple para el primer conjunto de resultados
			$firstRangeData = [];
			foreach ($result['data'] as $key => $value) {

				$valor_balanza = ( $value['valor_expo'] - $value['valor_impo'] );
				
				$firstRangeData[] = array_merge($value, ['valor_balanza' => $valor_balanza]);

			}

			$result = $this->findBalanzaData($indicador_filtros, $filtersConfig, $year, $period, 'fin');
			
			if ($result['success']) {
				
				//calcula el valor de la balanza simple para el segundo conjunto de resultados
				$lastRangeData = [];
				foreach ($result['data'] as $key => $value) {

					$valor_balanza = ( $value['valor_expo'] - $value['valor_impo'] );
					
					$lastRangeData[] = array_merge($value, ['valor_balanza' => $valor_balanza]);

				}

				//une los conjuntos de resultados
				$arrKeys      = [];
				$arrData      = [];
				$arrChartData = [];
				$rowIndex     = 0;
				foreach ($firstRangeData as $keyFirst => $firstRange) {

					$lastPeriod     = 0;
					$lastValImpo    = 0;
					$lastValExpo    = 0;
					$lastValBalanza = 0;

					foreach ($lastRangeData as $keyLast => $lastRange) {

						if ($keyFirst == $keyLast) {
							//var_dump(array_merge($firstRange, $lastRange));
							$lastPeriod    = $lastRange['periodo'];
							$lastValImpo   = $lastRange['valor_impo'];
							$lastValExpo   = $lastRange['valor_expo'];
							$lastValBalanza = $lastRange['valor_balanza'];

							$arrKeys[] = $keyLast;
						}

					}

					$valor_balanza = (($lastValBalanza - $firstRange['valor_balanza']) / $firstRange['valor_balanza']);

					$arrData[] = [
						'id'            => $firstRange['id'],
						'firstPeriod'   => $firstRange['periodo'],
						'firstValImpo'  => $firstRange['valor_impo'],
						'firstValExpo'  => $firstRange['valor_expo'],
						'lastPeriod'    => $lastPeriod,
						'lastValImpo'   => $lastValImpo,
						'lastValExpo'   => $lastValExpo,
						'valor_balanza' => $valor_balanza
					];

					$rowIndex += 1;

					$arrChartData[] = [
						'periodo' => 'Q'.$rowIndex,
						'valor_balanza' => $valor_balanza
					];

				}

				foreach ($lastRangeData as $keyLast => $lastRange) {
					if (!in_array($keyLast, $arrKeys)) {
						$arrData[] = [
							'id'            => $lastRange['id'],
							'firstPeriod'   => $lastRange['periodo'],
							'firstValImpo'  => 0,
							'firstValExpo'  => 0,
							'lastPeriod'    => $lastRange['periodo'],
							'lastValImpo'   => $lastRange['valor_impo'],
							'lastValExpo'   => $lastRange['valor_expo'],
							'valor_balanza' => 0
						];

						$rowIndex += 1;

						$arrChartData[] = [
							'periodo' => 'Q'.$rowIndex,
							'valor_balanza' => 0
						];
					}
				}

				$arrSeries = [
					'valor_balanza' => Lang::get('indicador.columns_title.valor_balanza')
				];

				$columnChart = Helpers::jsonChart(
					$arrChartData,
					'periodo',
					$arrSeries,
					COLUMNAS
				);

				$result = [
					'success'         => true,
					'data'            => $arrData,
					'columnChartData' => $columnChart,
					'total'           => count($arrData)
				];

			}

		}

		return $result;
	}
}	

