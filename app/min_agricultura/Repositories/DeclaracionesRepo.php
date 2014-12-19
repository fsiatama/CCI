<?php

require PATH_MODELS.'Entities/Declaraimp.php';
require PATH_MODELS.'Ado/DeclaraimpAdo.php';
require PATH_MODELS.'Entities/Declaraexp.php';
require PATH_MODELS.'Ado/DeclaraexpAdo.php';
require PATH_MODELS.'Repositories/SectorRepo.php';

require_once ('BaseRepo.php');

class DeclaracionesRepo extends BaseRepo {

	protected $arrFiltersValues;
	protected $columnValueImpo;
	protected $columnValueExpo;
	protected $rowIndicador;
	protected $filtersConfig;
	protected $year;
	protected $period;
	protected $range = false;
	protected $trade;
	protected $tipo_indicador_activador;
	protected $linesConfig;

	public function __construct($rowIndicador, $filtersConfig, $year, $period)
	{
		$this->rowIndicador  = $rowIndicador;
		$this->filtersConfig = $filtersConfig;
		$this->year          = $year;
		$this->period        = $period;

		extract($rowIndicador);

		$this->arrFiltersValues         = Helpers::filterValuesToArray($indicador_filtros);
		$this->tipo_indicador_activador = $tipo_indicador_activador;
		$this->setColumnValue();
		$this->linesConfig = Helpers::getRequire(PATH_APP.'lib/indicador.config.php');
	}

	public function getModel() {}
	public function getModelAdo() {}
	public function getPrimaryKey() {}
	public function setData($params, $action) {}

	public function getModelImpo()
	{
		return new Declaraimp;
	}
	
	public function getModelImpoAdo()
	{
		return new DeclaraimpAdo;
	}

	public function getModelExpo()
	{
		return new Declaraexp;
	}
	
	public function getModelExpoAdo()
	{
		return new DeclaraexpAdo;
	}

	protected function setRange($range)
	{
		$this->range = $range;
	}

	protected function setTrade($trade)
	{
		$this->trade = $trade;
	}

	protected function setColumnValue()
	{
		if ($this->tipo_indicador_activador == 'precio') {
			$this->columnValueImpo = 'valorcif';
			$this->columnValueExpo = 'valorfob';
		} else {
			$this->columnValueImpo = 'peso_neto';
			$this->columnValueExpo = 'peso_neto';
		}
	}

    /**
     * setFiltersValues
     * 
     * @param array  $arrFiltersValues Array con los filtros configurados por el usuario.
     * @param array  $filtersConfig    Configuracion de los filtros en app/lib/indicador.config.php
     * @param string $trade            impo o expo.
     * @param string $range            Puede contener ini o fin o vacio, sirve para asignar un rango de meses cuando el tipo_indicador lo necesite.
     *
     * @access public
     *
     * @return mixed Value.
     */
	public function setFiltersValues()
	{
		$arrFiltersValues = $this->arrFiltersValues;
		$filtersConfig    = $this->filtersConfig;
		$trade            = $this->trade;
		$range            = $this->range;

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

				} elseif (!empty($filter['yearRange'])) {

					//si es un rango de años debe unir el valor inicial y el final
					$setFilterValue = false;

					$filterValue = range($filterValue, $arrFiltersValues[$filter['yearRange'][0]]);
					$filterValue = implode(',', $filterValue);

					call_user_func_array([$this->model, $methodName], compact('filterValue'));

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

	public function findProductsBySector($sector)
	{
		$sector_id  = Helpers::arrayGet($this->linesConfig, $sector);
		$sectorRepo = new SectorRepo;
		$result     = $sectorRepo->findPrimaryKey($sector_id);

		if (!$result['success']) {
			return [
				'success' => false,
				'error'   => 'No existe configuración para el sector ' . $sector
			];
		}
		$row = array_shift($result['data']);
		return [
			'success' => true,
			'data'    => $row['sector_productos']
		];
	}

	public function findBalanzaData()
	{
		$year   = $this->year;
		$period = $this->period;
		$range  = $this->range;

		$this->setTrade('impo');

		$this->model      = $this->getModelImpo();
		$this->modelAdo   = $this->getModelImpoAdo();
		
		$rowField = Helpers::getPeriodColumnSql($period);

		//asigna los valores de filtro del indicador al modelo
		$this->setFiltersValues();

		$row = 'anio AS id';
		//si el periodo es diferente a anual debe filtrar por año
		if ($period != 12 && !empty($year)) {
			$this->model->setAnio($year);
			$row = 'periodo AS id';
		} else {
			if (array_key_exists('anio_'.$range, $this->arrFiltersValues)) {
				$year = $this->arrFiltersValues['anio_'.$range];
			}
		}

		if ($range !== false) {
			$row = 'periodo AS id';
		}

		$arrRowField = [$row, $rowField];


		$this->modelAdo->setPivotRowFields(implode(',', $arrRowField));
		$this->modelAdo->setPivotTotalFields($this->columnValueImpo);
		$this->modelAdo->setPivotGroupingFunction('SUM');

		$rsDeclaraimp = $this->modelAdo->pivotSearch($this->model);


		if (!$rsDeclaraimp['success']) {
			return $rsDeclaraimp;
		}
		
		$this->setTrade('expo');

		$this->model      = $this->getModelExpo();
		$this->modelAdo   = $this->getModelExpoAdo();
		//asigna los valores de filtro del indicador al modelo
		$this->setFiltersValues();

		//si el periodo es diferente a anual debe filtrar por año
		if ($period != 12 && !empty($year)) {
			$this->model->setAnio($year);
			$row = 'periodo AS id';
		} else {
			if (array_key_exists('anio_'.$range, $this->arrFiltersValues)) {
				$year = $this->arrFiltersValues['anio_'.$range];
			}
		}
		
		$this->modelAdo->setPivotRowFields(implode(',', $arrRowField));
		$this->modelAdo->setPivotTotalFields($this->columnValueExpo);
		$this->modelAdo->setPivotGroupingFunction('SUM');

		$rsDeclaraexp = $this->modelAdo->pivotSearch($this->model);

		if (!$rsDeclaraexp['success']) {
			return $rsDeclaraexp;
		}
		
		$arrData       = [];
		$arrPeriods    = [];

		foreach ($rsDeclaraexp['data'] as $keyExpo => $rowExpo) {
			
			$valor_impo = 0;

			foreach ($rsDeclaraimp['data'] as $keyImpo => $rowImpo) {

				if($rowImpo['periodo'] == $rowExpo['periodo']){
					$valor_impo   = $rowImpo[$this->columnValueImpo];
					$arrPeriods[] = $rowImpo['periodo'];
				}

			}

			$arrData[] = [
				'id'         => $rowExpo['id'],
				'periodo'    => $rowExpo['periodo'],
				'valor_expo' => $rowExpo[$this->columnValueExpo],
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

		//si el reporte no es anual y no encuentra informacion en algun periodo,
		//debe rrellenar con una fila en ceros
		$numberPeriods = (12 / $period);
		if (count($arrData) < $numberPeriods) {
			
			$arrFinal = [];
			$rangePeriods  = Helpers::getPeriodRange($period);

			foreach ($rangePeriods as $number => $range) {

				$findId = false;
				foreach ($arrData as $row) {

					if (in_array($row['id'], $range)) {
						$findId = true;
						$arrFinal[$number] = $row;
					}
				}

				if (!$findId) {
					$periodName = Helpers::getPeriodName($period, $number);

					$arrFinal[$number] = [
						'id'         => array_shift($range),
						'periodo'    => $year . ' ' . $periodName,
						'valor_expo' => 0,
						'valor_impo' => 0,
					];
				}
				
			}
			$arrData = $arrFinal;
		}

		return [
			'success' => true,
			'data'    => $arrData,
			'total'   => count($arrData)
		];
	}

	public function executeBalanza()
	{
		$result = $this->findBalanzaData();

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

	public function executeBalanzaRelativa()
	{
		$result = $this->findBalanzaData();

		if ($result['success']) {

			$arrData = [];

			foreach ($result['data'] as $key => $value) {

				$valor_balanza = (( $value['valor_expo'] + $value['valor_impo'] ) == 0) ? 0 : ( $value['valor_expo'] - $value['valor_impo'] ) / ( $value['valor_expo'] + $value['valor_impo'] );
				
				$arrData[] = array_merge($value, ['valor_balanza' => $valor_balanza]);

			}

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

	public function executeBalanzaVariacion()
	{
		$arrFiltersValues = $this->arrFiltersValues;


		$arrRangeIni = range($arrFiltersValues['desde_ini'], $arrFiltersValues['hasta_ini']);
		$arrRangeFin = range($arrFiltersValues['desde_fin'], $arrFiltersValues['hasta_fin']);

		$this->setRange('ini');
		$result = $this->findBalanzaData();
		if ($result['success']) {

			//calcula el valor de la balanza simple para el primer conjunto de resultados
			$firstRangeData = [];
			foreach ($result['data'] as $key => $value) {

				if ( in_array($value['id'], $arrRangeIni) ) {
					$valor_balanza = ( $value['valor_expo'] - $value['valor_impo'] );
					
					$firstRangeData[] = array_merge($value, ['valor_balanza' => $valor_balanza]);
				}

			}
			$this->setRange('fin');
			$result = $this->findBalanzaData();
			
			if ($result['success']) {
				
				//calcula el valor de la balanza simple para el segundo conjunto de resultados
				$lastRangeData = [];
				foreach ($result['data'] as $key => $value) {

					if ( in_array($value['id'], $arrRangeFin) ) {

						$valor_balanza = ( $value['valor_expo'] - $value['valor_impo'] );
						
						$lastRangeData[] = array_merge($value, ['valor_balanza' => $valor_balanza]);
					}

				}

				//une los conjuntos de resultados
				$arrKeys      = [];
				$arrData      = [];
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

					$rateVariation = ($firstRange['valor_balanza'] == 0) ? 0: (($lastValBalanza - $firstRange['valor_balanza']) / $firstRange['valor_balanza']);
					$rowIndex     += 1;

					$arrData[] = [
						'id'            => $firstRange['id'],
						'rowIndex'      => 'Q'.$rowIndex,
						'firstPeriod'   => $firstRange['periodo'],
						'firstValImpo'  => $firstRange['valor_impo'],
						'firstValExpo'  => $firstRange['valor_expo'],
						'firstValue'    => ( $firstRange['valor_expo'] - $firstRange['valor_impo'] ),
						'lastPeriod'    => $lastPeriod,
						'lastValImpo'   => $lastValImpo,
						'lastValExpo'   => $lastValExpo,
						'lastValue'     => ( $lastValExpo - $lastValImpo ),
						'rateVariation' => $rateVariation
					];


				}

				foreach ($lastRangeData as $keyLast => $lastRange) {
					if (!in_array($keyLast, $arrKeys)) {
						$rowIndex += 1;
						$arrData[] = [
							'id'            => $lastRange['id'],
							'rowIndex'      => 'Q'.$rowIndex,
							'firstPeriod'   => $lastRange['periodo'],
							'firstValImpo'  => 0,
							'firstValExpo'  => 0,
							'firstValue'    => 0,
							'lastPeriod'    => $lastRange['periodo'],
							'lastValImpo'   => $lastRange['valor_impo'],
							'lastValExpo'   => $lastRange['valor_expo'],
							'lastValue'     => ( $lastRange['valor_expo'] - $lastRange['valor_impo'] ),
							'rateVariation' => 0
						];
					}
				}

				$arrSeries = [
					'firstValue' => Lang::get('indicador.reports.initialRange'),
					'lastValue'  => Lang::get('indicador.reports.finalRange'),
				];

				$columnChart = Helpers::jsonChart(
					$arrData,
					'rowIndex',
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

	public function executeOfertaExportable()
	{
		$arrFiltersValues = $this->arrFiltersValues;
		$this->setTrade('expo');
		$this->setRange('ini');

		$this->model      = $this->getModelExpo();
		$this->modelAdo   = $this->getModelExpoAdo();
		//asigna los valores de filtro del indicador al modelo
		$this->setFiltersValues();

		//Trae los productos configurados como agricolas
		$result = $this->findProductsBySector('sectorIdAgriculture');
		if (!$result['success']) {
			return $result;
		}
		$productsAgriculture = $result['data'];

		$this->model->setId_posicion($productsAgriculture);


		$arrRowField = ['id', 'decl.id_posicion', 'posicion'];

		$this->modelAdo->setPivotRowFields(implode(',', $arrRowField));
		$this->modelAdo->setPivotTotalFields($this->columnValueExpo);
		$this->modelAdo->setPivotGroupingFunction('SUM');
		$this->modelAdo->setPivotSortColumn($this->columnValueExpo . ' DESC');

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


		$totalValue = 0;

		foreach ($rsDeclaraexp['data'] as $keyExpo => $rowExpo) {
			$totalValue += (float)$rowExpo[$this->columnValueExpo];
		}

		$arrData           = [];
		$othersValue       = 0;
		$othersId          = 0;
		$othersRate        = 0;
		$cumulativeRate    = 0;
		$ConcentrationRate = Helpers::arrayGet($this->linesConfig, 'ConcentrationExportableSupply');

		foreach ($rsDeclaraexp['data'] as $keyExpo => $rowExpo) {
				
			$rate = round( ($rowExpo[$this->columnValueExpo] / $totalValue ) * 100 , 2 );
			$cumulativeRate += $rate;
			if ($cumulativeRate <= 80) {
				$arrData[] = [
					'id'            => $keyExpo,
					'id_posicion'   => $rowExpo['id_posicion'],
					'posicion'      => $rowExpo['posicion'],
					'valor_expo'    => $rowExpo[$this->columnValueExpo],
					'participacion' => $rate
				];
			} else {
				$othersRate  += $rate;
				$othersValue += $rowExpo[$this->columnValueExpo];
				$othersId     = $keyExpo;
			}
		}


		//agrega la fila con el registro acumulado de las demas posiciones
		$arrData[] = [
			'id'            => $othersId,
			'id_posicion'   => Lang::get('indicador.reports.others'),
			'posicion'      => '*************************',
			'valor_expo'    => $othersValue,
			'participacion' => $othersRate
		];

		$arrSeries = [
			'valor_expo' => Lang::get('indicador.columns_title.valor_expo')
		];

		$pieChart = Helpers::jsonChart(
			$arrData,
			'id_posicion',
			$arrSeries,
			PIE
		);

		$result = [
			'success'         => true,
			'data'            => $arrData,
			'pieChartData'    => $pieChart,
			'total'           => count($arrData)
		];
		return $result;
	}

	public function executeNumeroProductos()
	{
		$arrFiltersValues = $this->arrFiltersValues;

		$arrRangeIni = range($arrFiltersValues['desde_ini'], $arrFiltersValues['hasta_ini']);
		$arrRangeFin = range($arrFiltersValues['desde_fin'], $arrFiltersValues['hasta_fin']);

		$this->setTrade('impo');
		$this->setRange('ini');

		$this->model      = $this->getModelImpo();
		$this->modelAdo   = $this->getModelImpoAdo();
		//asigna los valores de filtro del indicador al modelo
		$this->setFiltersValues();
		$result = $this->findProductsBySector('sectorIdAgriculture');
		if (!$result['success']) {
			return $result;
		}
		$productsAgriculture = $result['data'];
		$this->model->setId_posicion($productsAgriculture);

		$columnValue = 'decl.id_posicion';

		$rowField = Helpers::getPeriodColumnSql($this->period);
		$row = 'periodo AS id';

		$arrRowField   = [$row, $rowField];

		$this->modelAdo->setPivotRowFields(implode(',', $arrRowField));
		$this->modelAdo->setPivotTotalFields($columnValue);
		$this->modelAdo->setPivotGroupingFunction('COUNT_DISTINCT');

		//busca los datos del primer rango de fechas en importaciones
		$rsDeclaraimp = $this->modelAdo->pivotSearch($this->model);
		if (!$rsDeclaraimp['success']) {
			return $rsDeclaraimp;
		}

		$arrDataImp = $rsDeclaraimp['data'];

		//var_dump($arrDataImp);

		$this->setTrade('expo');
		$this->setRange('fin');

		$this->model      = $this->getModelExpo();
		$this->modelAdo   = $this->getModelExpoAdo();
		//asigna los valores de filtro del indicador al modelo
		$this->setFiltersValues();

		$this->model->setId_posicion($productsAgriculture);
		$this->modelAdo->setPivotRowFields(implode(',', $arrRowField));
		$this->modelAdo->setPivotTotalFields($columnValue);
		$this->modelAdo->setPivotGroupingFunction('COUNT_DISTINCT');

		//busca los datos del primer rango de fechas en exportaciones
		$rsDeclaraexp = $this->modelAdo->pivotSearch($this->model);
		if (!$rsDeclaraexp['success']) {
			return $rsDeclaraexp;
		}

		$arrDataExp = $rsDeclaraexp['data'];

		//var_dump($arrDataExp);

		//une los conjuntos de resultados
		$arrKeys      = [];
		$arrData      = [];
		$rowIndex     = 0;
		foreach ($arrDataImp as $keyImpo => $rowImpo) {

			$expoPeriod = '';
			$expoValue  = 0;

			foreach ($arrDataExp as $keyExpo => $rowExpo) {

				if ($keyImpo == $keyExpo) {
					$expoPeriod = $rowExpo['periodo'];
					$expoValue  = $rowExpo[$columnValue];
					$arrKeys[]  = $keyExpo;
				}

			}

			$variation = $rowImpo[$columnValue] - $expoValue;
			$rowIndex     += 1;

			$arrData[] = [
				'id'         => $rowImpo['id'],
				'rowIndex'   => 'Q'.$rowIndex,
				'impoPeriod' => $rowImpo['periodo'],
				'impoValue'  => $rowImpo[$columnValue],
				'expoPeriod' => $expoPeriod,
				'expoValue'  => $expoValue,
				'variation'  => $variation
			];

		}

		foreach ($arrDataExp as $keyExpo => $rowExpo) {
			if (!in_array($keyExpo, $arrKeys)) {
				$rowIndex += 1;
				$arrData[] = [
					'id'         => $rowExpo['id'],
					'rowIndex'   => 'Q'.$rowIndex,
					'impoPeriod' => $rowExpo['periodo'],
					'impoValue'  => 0,
					'expoPeriod' => $rowExpo['periodo'],
					'expoValue'  => $rowExpo[$columnValue],
					'variation'  => (0 - $rowExpo[$columnValue])
				];
			}
		}

		$arrSeries = [
			'impoValue' => Lang::get('indicador.reports.imports'),
			'expoValue'  => Lang::get('indicador.reports.exports'),
		];

		$columnChart = Helpers::jsonChart(
			$arrData,
			'rowIndex',
			$arrSeries,
			COLUMNAS
		);

		$result = [
			'success'         => true,
			'data'            => $arrData,
			'columnChartData' => $columnChart,
			'total'           => count($arrData)
		];

		return $result;

	}

	public function executeTasaCrecimientoProductosNuevos()
	{
		$arrFiltersValues = $this->arrFiltersValues;

		$trade            = ( empty($arrFiltersValues['intercambio']) ) ? 'impo' : $arrFiltersValues['intercambio'];

		$this->setTrade($trade);
		$this->setRange('ini');
		
		if ($trade == 'impo') {
			$this->model      = $this->getModelImpo();
			$this->modelAdo   = $this->getModelImpoAdo();
		} else {
			$this->model      = $this->getModelExpo();
			$this->modelAdo   = $this->getModelExpoAdo();
		}

		$this->setFiltersValues();

		$result = $this->findProductsBySector('sectorIdAgriculture');
		if (!$result['success']) {
			return $result;
		}
		$productsAgriculture = $result['data'];
		$this->model->setId_posicion($productsAgriculture);

		$columnValue = 'decl.id_posicion';

		$rowField = Helpers::getPeriodColumnSql($this->period);
		$row = 'periodo AS id';

		$arrRowField   = [$row, $rowField];

		$this->modelAdo->setPivotRowFields(implode(',', $arrRowField));
		$this->modelAdo->setPivotTotalFields($columnValue);
		$this->modelAdo->setPivotGroupingFunction('COUNT_DISTINCT');

		//busca los datos del primer rango de fechas en importaciones
		$rsDeclaraimp = $this->modelAdo->pivotSearch($this->model);
		if (!$rsDeclaraimp['success']) {
			return $rsDeclaraimp;
		}

		$arrDataFirst = $rsDeclaraimp['data'];

		$this->setRange('fin');

		if ($trade == 'impo') {
			$this->model      = $this->getModelImpo();
			$this->modelAdo   = $this->getModelImpoAdo();
		} else {
			$this->model      = $this->getModelExpo();
			$this->modelAdo   = $this->getModelExpoAdo();
		}
		//asigna los valores de filtro del indicador al modelo
		$this->setFiltersValues();

		$this->model->setId_posicion($productsAgriculture);
		$this->modelAdo->setPivotRowFields(implode(',', $arrRowField));
		$this->modelAdo->setPivotTotalFields($columnValue);
		$this->modelAdo->setPivotGroupingFunction('COUNT_DISTINCT');

		//busca los datos del primer rango de fechas en exportaciones
		$rsDeclaraimp = $this->modelAdo->pivotSearch($this->model);
		if (!$rsDeclaraimp['success']) {
			return $rsDeclaraimp;
		}

		$arrDataLast = $rsDeclaraimp['data'];

		//var_dump($arrDataExp);

		//une los conjuntos de resultados
		$arrKeys      = [];
		$arrData      = [];
		$rowIndex     = 0;
		foreach ($arrDataFirst as $keyFirst => $rowFirst) {

			$periodLast = '';
			$valueLast  = 0;

			foreach ($arrDataLast as $keyLast => $rowLast) {

				if ($keyFirst == $keyLast) {
					$periodLast = $rowLast['periodo'];
					$valueLast  = $rowLast[$columnValue];
					$arrKeys[]  = $keyLast;
				}

			}

			$variation  = ($valueLast == 0) ? 0 : (($rowFirst[$columnValue] - $valueLast) / $valueLast);
			$rowIndex  += 1;

			$arrData[] = [
				'id'          => $rowFirst['id'],
				'rowIndex'    => 'Q'.$rowIndex,
				'periodFirst' => $rowFirst['periodo'],
				'valueFirst'  => $rowFirst[$columnValue],
				'periodLast'  => $periodLast,
				'valueLast'   => $valueLast,
				'variation'   => $variation
			];

		}

		foreach ($arrDataLast as $keyLast => $rowLast) {
			if (!in_array($keyLast, $arrKeys)) {
				$rowIndex += 1;
				$arrData[] = [
					'id'          => $rowLast['id'],
					'rowIndex'    => 'Q'.$rowIndex,
					'periodFirst' => $rowLast['periodo'],
					'valueFirst'  => 0,
					'periodLast'  => $rowLast['periodo'],
					'valueLast'   => $rowLast[$columnValue],
					'variation'   => -1
				];
			}
		}

		if (count($arrData) == 0) {
			return [
				'success' => false,
				'error'   => Lang::get('error.no_records_found')
			];
		}

		$arrSeries = [
			'valueFirst' => Lang::get('indicador.reports.initialRange'),
			'valueLast'  => Lang::get('indicador.reports.finalRange'),
		];

		$columnChart = Helpers::jsonChart(
			$arrData,
			'rowIndex',
			$arrSeries,
			COLUMNAS
		);

		$result = [
			'success'         => true,
			'data'            => $arrData,
			'columnChartData' => $columnChart,
			'total'           => count($arrData)
		];

		return $result;

	}

	public function executeNumeroPaisesDestino()
	{
		$arrFiltersValues = $this->arrFiltersValues;
		$this->setTrade('expo');
		$this->setRange('ini');

		$this->model      = $this->getModelExpo();
		$this->modelAdo   = $this->getModelExpoAdo();
		//asigna los valores de filtro del indicador al modelo
		$this->setFiltersValues();

		//Trae los productos configurados como agricolas
		$result = $this->findProductsBySector('sectorIdAgriculture');
		if (!$result['success']) {
			return $result;
		}
		$productsAgriculture = $result['data'];

		$this->model->setId_posicion($productsAgriculture);

		$arrRowField = ['id', 'decl.id_paisdestino', 'pais'];
		
		$this->modelAdo->setPivotRowFields(implode(',', $arrRowField));
		$this->modelAdo->setPivotTotalFields($this->columnValueExpo);
		$this->modelAdo->setPivotGroupingFunction('SUM');
		$this->modelAdo->setPivotSortColumn($this->columnValueExpo . ' DESC');
		
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
		
		$totalValue = 0;
		
		foreach ($rsDeclaraexp['data'] as $keyExpo => $rowExpo) {
			$totalValue += (float)$rowExpo[$this->columnValueExpo];
		}
		
		$arrData           = [];
		
		foreach ($rsDeclaraexp['data'] as $keyExpo => $rowExpo) {
				
			$rate = round( ($rowExpo[$this->columnValueExpo] / $totalValue ) * 100 , 2 );
			$arrData[] = [
				'id'            => $keyExpo,
				'pais'          => $rowExpo['pais'],
				'valor_expo'    => $rowExpo[$this->columnValueExpo],
				'participacion' => $rate
			];
		}
		
		$result = [
			'success'         => true,
			'data'            => $arrData,
			'total'           => count($arrData)
		];
		return $result;
		
	}

	public function executeIHH()
	{
		$arrFiltersValues = $this->arrFiltersValues;
		$trade            = ( empty($arrFiltersValues['intercambio']) ) ? 'impo' : $arrFiltersValues['intercambio'];

		$this->setTrade($trade);
		$this->setRange('ini');
		
		if ($trade == 'impo') {
			$this->model      = $this->getModelImpo();
			$this->modelAdo   = $this->getModelImpoAdo();
			$columnValue      = $this->columnValueImpo;
		} else {
			$this->model      = $this->getModelExpo();
			$this->modelAdo   = $this->getModelExpoAdo();
			$columnValue      = $this->columnValueExpo;
		}

		//asigna los valores de filtro del indicador al modelo
		$this->setFiltersValues();

		//Trae los productos configurados como agricolas
		$result = $this->findProductsBySector('sectorIdAgriculture');
		if (!$result['success']) {
			return $result;
		}
		$productsAgriculture = $result['data'];

		$this->model->setId_posicion($productsAgriculture);

		$arrRowField   = ['id', 'decl.id_capitulo'];
		$arrFieldAlias = ['id', 'id_capitulo', $columnValue];

		$this->modelAdo->setPivotRowFields(implode(',', $arrRowField));
		$this->modelAdo->setPivotColumnFields('anio');
		$this->modelAdo->setPivotTotalFields($columnValue);
		$this->modelAdo->setPivotGroupingFunction('SUM');
		$this->modelAdo->setPivotSortColumn($columnValue . ' DESC');

		$rsDeclaraciones = $this->modelAdo->pivotSearch($this->model);

		if (!$rsDeclaraciones['success']) {
			return $rsDeclaraciones;
		}
		if ($rsDeclaraciones['total'] == 0) {
			return [
				'success' => false,
				'error'   => Lang::get('error.no_records_found')
			];
		}

		$arrData   = [];
		$arrTotals = [];
		$arrSeries = [];

		foreach ($rsDeclaraciones['data'] as $row) {
			foreach ($row as $key => $value) {
				if (!in_array($key, $arrFieldAlias)) {
					//suma las columnas que no estan en array de filas
					//es decir las columnas calculadas
					if (empty($arrTotals[$key])) {
						$arrTotals[$key] = 0;
					}
					$arrTotals[$key] += $value;
					$arrSeries[]      = $key;
				}
			}
		}

		$arrIHH = [];
		//calcula la participacion de cada capitulo y la eleva al cuadrado
		foreach ($rsDeclaraciones['data'] as $row) {
			foreach ($row as $key => $value) {
				if (in_array($key, $arrSeries)) {

					//var_dump($key, $value, $arrTotals[$key], ( $value / $arrTotals[$key] ),  (( $value / $arrTotals[$key] ) * ( $value / $arrTotals[$key] )) );

					$IHH = ( $value / $arrTotals[$key] );
					$IHH = pow($IHH, 2);
					if (empty($arrIHH[$key])) {
						$arrIHH[$key] = 0;
					}
					$arrIHH[$key] += $IHH;
				}
			}
		}

		$arrData = [];

		foreach ($arrIHH as $key => $value) {
			$arrData[] = [
				'periodo' => $key,
				'IHH'     => round($value * 100, 2)
			];
		}

		$arrSeries = [
			'IHH' => Lang::get('indicador.columns_title.IHH')
		];

		$columnChart = Helpers::jsonChart(
			$arrData,
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

		return $result;
	}

	public function executeParticipacionExpoSectorAgricola()
	{
		$arrFiltersValues = $this->arrFiltersValues;
		$this->setTrade('expo');
		$this->setRange('ini');

		$this->model      = $this->getModelExpo();
		$this->modelAdo   = $this->getModelExpoAdo();
		$columnValue      = $this->columnValueExpo;
		$this->setFiltersValues();

		//Trae los productos configurados como agricolas
		$result = $this->findProductsBySector('sectorIdAgriculture');
		if (!$result['success']) {
			return $result;
		}
		$productsAgriculture = $result['data'];

		//Trae los productos configurados como sector minero
		$result = $this->findProductsBySector('sectorIdMiningSector');
		if (!$result['success']) {
			return $result;
		}
		$energeticMiningSector = $result['data'];

		$this->model->setId_posicion($energeticMiningSector);

		$rowField = Helpers::getPeriodColumnSql($this->period);
		$row = 'periodo AS id';

		$arrRowField   = [$row, $rowField];

		$this->modelAdo->setPivotRowFields(implode(',', $arrRowField));
		$this->modelAdo->setPivotTotalFields($columnValue);
		$this->modelAdo->setPivotGroupingFunction('SUM');

		//busca los datos del sector energetico
		$result = $this->modelAdo->pivotSearch($this->model);

		if ($result['success']) {
			$arrDataEnergeticMiningSector = $result['data'];

			//busca los datos del sector agricola
			$this->model->setId_posicion($productsAgriculture);
			$result = $this->modelAdo->pivotSearch($this->model);

			if ($result['success']) {
				$arrDataProductsAgriculture = $result['data'];

				//busca el total de las exportaciones 
				$this->model->setId_posicion('');
				$result  = $this->modelAdo->pivotSearch($this->model);
				$arrData = [];

				if ($result['success']) {
					$arrDataTotal = $result['data'];

					foreach ($arrDataTotal as $rowTotal) {

						$rowProductsAgriculture = Helpers::findKeyInArrayMulti(
							$arrDataProductsAgriculture,
							'periodo',
							$rowTotal['periodo']
						);
						$rowEnergeticMiningSector = Helpers::findKeyInArrayMulti(
							$arrDataEnergeticMiningSector,
							'periodo',
							$rowTotal['periodo']
						);

						$totalProductsAgriculture   = ($rowProductsAgriculture   !== false) ? $rowProductsAgriculture[$columnValue]   : 0 ;
						$totalEnergeticMiningSector = ($rowEnergeticMiningSector !== false) ? $rowEnergeticMiningSector[$columnValue] : 0 ;

						$total = $rowTotal[$columnValue] - $totalEnergeticMiningSector;
						$total = ($total == 0) ? 1 : $total ;
						$rate  = round( ($totalProductsAgriculture / $total ) * 100 , 2 );

						$arrData[] = [
							'id'                  => $rowTotal['id'],
							'periodo'             => $rowTotal['periodo'],
							'valor_expo_agricola' => $totalProductsAgriculture,
							'valor_expo'          => $total,
							'participacion'       => $rate
						];
					}

					$arrSeries = [
						'valor_expo_agricola' => Lang::get('indicador.columns_title.valor_expo_agricola'),
						'valor_expo'          => Lang::get('indicador.columns_title.valor_expo'),
					];

					$columnChart = Helpers::jsonChart(
						$arrData,
						'periodo',
						$arrSeries,
						COLUMNAS
					);

					$result = [
						'success'         => true,
						'data'            => $arrData,
						'total'           => $result['total'],
						'columnChartData' => $columnChart,
						//'areaChartData'   => $areaChart,
					];
				}
			}
		}

		return $result;
	}

	public function executeParticipacionExpoNoTradicional()
	{
		$arrFiltersValues = $this->arrFiltersValues;
		$this->setTrade('expo');
		$this->setRange('ini');

		$this->model      = $this->getModelExpo();
		$this->modelAdo   = $this->getModelExpoAdo();
		$columnValue      = $this->columnValueExpo;
		$this->setFiltersValues();

		$result = $this->findProductsBySector('sectorIdAgriculture');
		if (!$result['success']) {
			return $result;
		}
		$productsAgriculture = $result['data'];

		$result = $this->findProductsBySector('sectorIdTraditional');
		if (!$result['success']) {
			return $result;
		}
		$productsTraditional = $result['data'];

		$this->model->setId_posicion($productsTraditional);

		$rowField = Helpers::getPeriodColumnSql($this->period);
		$row = 'periodo AS id';

		$arrRowField   = [$row, $rowField];

		$this->modelAdo->setPivotRowFields(implode(',', $arrRowField));
		$this->modelAdo->setPivotTotalFields($columnValue);
		$this->modelAdo->setPivotGroupingFunction('SUM');

		//busca los datos de los productos tradicionales
		$result = $this->modelAdo->pivotSearch($this->model);

		if ($result['success']) {
			$arrProductsTraditional = $result['data'];

			//busca los datos del sector agricola
			$this->model->setId_posicion($productsAgriculture);
			$result = $this->modelAdo->pivotSearch($this->model);

			if ($result['success']) {
				$arrData = [];

				$arrDataTotal = $result['data'];

				foreach ($arrDataTotal as $rowTotal) {

					$rowProductsTraditional = Helpers::findKeyInArrayMulti(
						$arrProductsTraditional,
						'periodo',
						$rowTotal['periodo']
					);

					$totalProductsTraditional    = ($rowProductsTraditional !== false) ? $rowProductsTraditional[$columnValue] : 0 ;
					$totalProductsNonTraditional = $rowTotal[$columnValue] - $totalProductsTraditional;
					
					$total = ($rowTotal[$columnValue] == 0) ? 1 : $rowTotal[$columnValue] ;
					$rate  = round( ($totalProductsNonTraditional / $total ) * 100 , 2 );

					$arrData[] = [
						'id'                  => $rowTotal['id'],
						'periodo'             => $rowTotal['periodo'],
						'valor_expo_no_tradi' => $totalProductsNonTraditional,
						'valor_expo'          => $total,
						'participacion'       => $rate
					];
				}

				$arrSeries = [
					'valor_expo_no_tradi' => Lang::get('indicador.columns_title.valor_expo_no_tradi'),
					'valor_expo'          => Lang::get('indicador.columns_title.valor_expo_agricola'),
				];

				$columnChart = Helpers::jsonChart(
					$arrData,
					'periodo',
					$arrSeries,
					COLUMNAS
				);

				$result = [
					'success'         => true,
					'data'            => $arrData,
					'total'           => $result['total'],
					'columnChartData' => $columnChart,
				];
			}
		}

		return $result;
	}

	public function executeParticipacionExpoPorProducto()
	{
		$arrFiltersValues = $this->arrFiltersValues;
		$this->setTrade('expo');
		$this->setRange('ini');

		$this->model      = $this->getModelExpo();
		$this->modelAdo   = $this->getModelExpoAdo();
		$columnValue      = $this->columnValueExpo;
		$this->setFiltersValues();


		$rowField = Helpers::getPeriodColumnSql($this->period);
		$row = 'periodo AS id';

		$arrRowField   = [$row, $rowField];

		$this->modelAdo->setPivotRowFields(implode(',', $arrRowField));
		$this->modelAdo->setPivotTotalFields($columnValue);
		$this->modelAdo->setPivotGroupingFunction('SUM');

		//busca los datos de los productos seleccionados
		$result = $this->modelAdo->pivotSearch($this->model);

		if ($result['success']) {

			if ($result['total'] == 0) {
				return [
					'success' => false,
					'error'   => Lang::get('error.no_records_found')
				];
			}
			$arrProduct = $result['data'];

			//busca los datos del total de exportaciones
			$this->model->setId_posicion('');
			$result = $this->modelAdo->pivotSearch($this->model);

			if ($result['success']) {
				$arrData = [];

				$arrDataTotal = $result['data'];

				foreach ($arrDataTotal as $rowTotal) {

					$rowProduct = Helpers::findKeyInArrayMulti(
						$arrProduct,
						'periodo',
						$rowTotal['periodo']
					);

					$totalProduct = ($rowProduct !== false) ? $rowProduct[$columnValue] : 0 ;
					
					$total = ($rowTotal[$columnValue] == 0) ? 1 : $rowTotal[$columnValue] ;
					$rate  = round( ($totalProduct / $total ) * 100 , 2 );

					$arrData[] = [
						'id'                => $rowTotal['id'],
						'periodo'           => $rowTotal['periodo'],
						'valor_expo_sector' => $totalProduct,
						'valor_expo'        => $total,
						'participacion'     => $rate
					];
				}

				$arrSeries = [
					'valor_expo_sector' => Lang::get('indicador.columns_title.valor_expo_sector'),
					'valor_expo'        => Lang::get('indicador.columns_title.valor_expo'),
				];

				$columnChart = Helpers::jsonChart(
					$arrData,
					'periodo',
					$arrSeries,
					COLUMNAS
				);

				$result = [
					'success'         => true,
					'data'            => $arrData,
					'total'           => $result['total'],
					'columnChartData' => $columnChart,
				];
			}
		}

		return $result;
	}

	public function executeCrecimientoExportadores()
	{
		$arrFiltersValues = $this->arrFiltersValues;
		$this->setTrade('expo');
		$this->setRange('ini');

		$this->model      = $this->getModelExpo();
		$this->modelAdo   = $this->getModelExpoAdo();
		$this->setFiltersValues();

		$columnValue = 'id_empresa';

		if (!array_key_exists('id_posicion', $arrFiltersValues)) {
			//si el reporte no tiene un producto seleccionado, debe seleccionar todo el sector agropecuario
			$result = $this->findProductsBySector('sectorIdAgriculture');
			if (!$result['success']) {
				return $result;
			}
			$productsAgriculture = $result['data'];
			$this->model->setId_posicion($productsAgriculture);
		}

		$rowField = Helpers::getPeriodColumnSql($this->period);
		$row = 'periodo AS id';

		$arrRowField   = [$row, $rowField];

		$this->modelAdo->setPivotRowFields(implode(',', $arrRowField));
		$this->modelAdo->setPivotTotalFields($columnValue);
		$this->modelAdo->setPivotGroupingFunction('COUNT_DISTINCT');

		//busca los datos del primer rango de fechas
		$result = $this->modelAdo->pivotSearch($this->model);

		if ($result['success']) {

			$firstRangeData = $result['data'];
			
			//busca los datos del segundo rango de fechas
			$this->setRange('fin');
			$this->setFiltersValues();

			$result = $this->modelAdo->pivotSearch($this->model);

			if ($result['success']) {
				$arrData  = [];
				$arrKeys  = [];
				$rowIndex = 0;

				$lastRangeData = $result['data'];

				foreach ($firstRangeData as $keyFirst => $firstRange) {

					$lastPeriod    = '';
					$lastValue     = 0;

					foreach ($lastRangeData as $keyLast => $lastRange) {

						if ($keyFirst == $keyLast) {
							$lastPeriod = $lastRange['periodo'];
							$lastValue  = $lastRange[$columnValue];
							$arrKeys[]  = $keyLast;
						}

					}

					$rateVariation = ($firstRange[$columnValue] == 0) ? 0: (($lastValue - $firstRange[$columnValue]) / $firstRange[$columnValue]);
					$rowIndex += 1;
					$arrData[] = [
						'id'            => $firstRange['id'],
						'rowIndex'      => 'Q'.$rowIndex,
						'firstPeriod'   => $firstRange['periodo'],
						'firstValue'    => $firstRange[$columnValue],
						'lastPeriod'    => $lastPeriod,
						'lastValue'     => $lastValue,
						'rateVariation' => $rateVariation
					];

				}

				foreach ($lastRangeData as $keyLast => $lastRange) {
					if (!in_array($keyLast, $arrKeys)) {
						$rowIndex += 1;
						$arrData[] = [
							'id'            => $lastRange['id'],
							'rowIndex'      => 'Q'.$rowIndex,
							'firstPeriod'   => $lastRange['periodo'],
							'firstValue'    => 0,
							'lastPeriod'    => $lastRange['periodo'],
							'lastValue'     => $lastRange[$columnValue],
							'rateVariation' => 1
						];
					}
				}

				$arrSeries = [
					'firstValue' => Lang::get('indicador.reports.initialRange'),
					'lastValue'  => Lang::get('indicador.reports.finalRange'),
				];

				$columnChart = Helpers::jsonChart(
					$arrData,
					'rowIndex',
					$arrSeries,
					COLUMNAS
				);

				$result = [
					'success'         => true,
					'data'            => $arrData,
					'total'           => $result['total'],
					'columnChartData' => $columnChart,
				];
			}
		}

		return $result;
	}
	public function executePromedioPonderadoArancel()
	{
		$arrFiltersValues = $this->arrFiltersValues;
		$this->setTrade('impo');
		$this->setRange('ini');

		$this->model      = $this->getModelImpo();
		$this->modelAdo   = $this->getModelImpoAdo();
		$this->setFiltersValues();

		$columnValue1 = 'valorarancel';
		$columnValue2 = 'arancel_pagado';

		if (!array_key_exists('id_posicion', $arrFiltersValues)) {
			//si el reporte no tiene un producto seleccionado, debe seleccionar todo el sector agropecuario
			$result = $this->findProductsBySector('sectorIdAgriculture');
			if (!$result['success']) {
				return $result;
			}
			$productsAgriculture = $result['data'];
			$this->model->setId_posicion($productsAgriculture);
		}

		$rowField = Helpers::getPeriodColumnSql($this->period);
		$row = 'periodo AS id';

		$arrRowField = ['id', 'decl.id_posicion', 'posicion', 'pais'];

		$this->modelAdo->setPivotRowFields(implode(',', $arrRowField));
		$this->modelAdo->setPivotTotalFields([$columnValue1, $columnValue2]);
		$this->modelAdo->setPivotGroupingFunction('SUM');
		$this->modelAdo->setPivotSortColumn($columnValue1 . ' DESC');

		$result = $this->modelAdo->pivotSearch($this->model);

		if ($result['success']) {

			$totalValue = 0;

			foreach ($result['data'] as $keyImpo => $rowImpo) {
				$totalValue += (float)$rowImpo[$columnValue1];
			}

			$arrData = [];
			$average = 0;
			
			foreach ($result['data'] as $keyImpo => $rowImpo) {
					
				$rate     = ((float)$rowImpo[$columnValue1] / $totalValue );
				$weighing = (float)$rowImpo[$columnValue1] * $rate;
				$average += $weighing;

				$arrData[] = [
					'id'             => $keyImpo,
					'id_posicion'    => $rowImpo['id_posicion'],
					'posicion'       => $rowImpo['posicion'],
					'pais'           => $rowImpo['pais'],
					'arancel_pagado' => (float)$rowImpo[$columnValue2],
					'valorarancel'   => (float)$rowImpo[$columnValue1],
					'participacion'  => ( $rate * 100 )
				];
			}
			$result = [
				'success'         => true,
				'data'            => $arrData,
				'average'         => $average,
				'total'           => count($arrData)
			];
		}
		
		return $result;
	}

	public function executeRelacionCrecimientoImpoExpo()
	{
		$result = $this->findBalanzaData();

		if (!$result['success']) {
			return $result;
		}
		$yearFirst = $this->arrFiltersValues['anio_ini'];

		foreach ($result['data'] as $key => $value) {

			if ($value['periodo'] == $yearFirst) {
				$valueExpoFirst = $value['valor_expo'];
				$valueImpoFirst = $value['valor_impo'];
			}
			$yearLast      = $value['periodo'];
			$valueExpoLast = $value['valor_expo'];
			$valueImpoLast = $value['valor_impo'];
		}

		$rangeYear     = range($yearFirst, $yearLast);
		$numberPeriods = count($rangeYear);

		$growthRateImpo = ( pow(($valueImpoLast / $valueImpoFirst), (1 / $numberPeriods)) - 1);
		$growthRateExpo = ( pow(($valueExpoLast / $valueExpoFirst), (1 / $numberPeriods)) - 1);

		//var_dump($growthRateImpo, $growthRateExpo);

		$result = [
			'success'        => true,
			'data'           => $result['data'],
			'growthRateImpo' => ($growthRateImpo * 100),
			'growthRateExpo' => ($growthRateExpo * 100),
			'rateVariation'  => ($growthRateExpo / $growthRateImpo),
			'total'          => count($result['data'])
		];

		return $result;
		
	}

	public function executeRelacionCrecimientoExpoAgroExpoTot()
	{
		$arrFiltersValues = $this->arrFiltersValues;
		$this->setTrade('expo');
		$this->setRange('ini');

		$this->model      = $this->getModelExpo();
		$this->modelAdo   = $this->getModelExpoAdo();
		$columnValue      = $this->columnValueExpo;
		$this->setFiltersValues();

		$rowField = Helpers::getPeriodColumnSql($this->period);
		$row = 'anio AS id';

		$arrRowField   = [$row, $rowField];

		$this->modelAdo->setPivotRowFields(implode(',', $arrRowField));
		$this->modelAdo->setPivotTotalFields($columnValue);
		$this->modelAdo->setPivotGroupingFunction('SUM');

		//busca los datos del sector agricola
		if (!array_key_exists('id_posicion', $arrFiltersValues)) {
			//si el reporte no tiene un producto seleccionado, debe seleccionar todo el sector agropecuario
			$result = $this->findProductsBySector('sectorIdAgriculture');
			if (!$result['success']) {
				return $result;
			}
			$productsAgriculture = $result['data'];
			$this->model->setId_posicion($productsAgriculture);
		}
		$result = $this->modelAdo->pivotSearch($this->model);

		if (!$result['success']) {
			return $result;
		}
		$arrDataProductsAgriculture = $result['data'];

		//Trae los productos configurados como sector minero
		$result = $this->findProductsBySector('sectorIdMiningSector');
		if (!$result['success']) {
			return $result;
		}
		$energeticMiningSector = $result['data'];
		//busca los datos del sector energetico
		$this->model->setId_posicion($energeticMiningSector);
		$result = $this->modelAdo->pivotSearch($this->model);

		if (!$result['success']) {
			return $result;
		}
		$arrDataEnergeticMiningSector = $result['data'];

		//busca el total de las exportaciones 
		$this->model->setId_posicion('');
		$result  = $this->modelAdo->pivotSearch($this->model);
		if (!$result['success']) {
			return $result;
		}
		$arrData = [];

		$arrDataTotal = $result['data'];

		$yearFirst = $arrFiltersValues['anio_ini'];

		foreach ($arrDataTotal as $rowTotal) {

			$rowProductsAgriculture = Helpers::findKeyInArrayMulti(
				$arrDataProductsAgriculture,
				'periodo',
				$rowTotal['periodo']
			);
			$rowEnergeticMiningSector = Helpers::findKeyInArrayMulti(
				$arrDataEnergeticMiningSector,
				'periodo',
				$rowTotal['periodo']
			);


			$yearLast                   = $rowTotal['periodo'];
			$valueLastAgriculture       = ($rowProductsAgriculture   !== false) ? (float)$rowProductsAgriculture[$columnValue]   : 0 ;
			$totalEnergeticMiningSector = ($rowEnergeticMiningSector !== false) ? (float)$rowEnergeticMiningSector[$columnValue] : 0 ;
			$valueLastTotal             = (float)$rowTotal[$columnValue] - $totalEnergeticMiningSector;
			$valueLastTotal             = ($valueLastTotal == 0) ? 1 : $valueLastTotal ;

			if ($rowTotal['periodo'] == $yearFirst) {
				$valueAgricultureFirst = $valueLastAgriculture;
				$valueFirstTotal       = $valueLastTotal;
			}

			$arrData[] = [
				'id'                  => $rowTotal['id'],
				'periodo'             => $rowTotal['periodo'],
				'valor_expo_agricola' => $valueLastAgriculture,
				'valor_expo'          => $valueLastTotal
			];
		}

		$rangeYear     = range($yearFirst, $yearLast);
		$numberPeriods = count($rangeYear);

		$growthRateAgriculture = ( pow(($valueLastAgriculture / $valueAgricultureFirst), (1 / $numberPeriods)) - 1);
		$growthRateExpo        = ( pow(($valueLastTotal / $valueFirstTotal), (1 / $numberPeriods)) - 1);

		$arrSeries = [
			'valor_expo_agricola' => Lang::get('indicador.columns_title.valor_expo_agricola'),
			'valor_expo'          => Lang::get('indicador.columns_title.valor_expo'),
		];

		$columnChart = Helpers::jsonChart(
			$arrData,
			'periodo',
			$arrSeries,
			COLUMNAS
		);

		$result = [
			'success'               => true,
			'data'                  => $arrData,
			'growthRateAgriculture' => ($growthRateAgriculture * 100),
			'growthRateExpo'        => ($growthRateExpo * 100),
			'rateVariation'         => ($growthRateAgriculture / $growthRateExpo),
			'columnChartData'       => $columnChart,
			'total'                 => count($arrData)
		];

		return $result;
	}
}	

