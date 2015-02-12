<?php

require PATH_MODELS.'Entities/Declaraimp.php';
require PATH_MODELS.'Ado/DeclaraimpAdo.php';
require PATH_MODELS.'Entities/Declaraexp.php';
require PATH_MODELS.'Ado/DeclaraexpAdo.php';
require PATH_MODELS.'Repositories/SectorRepo.php';
require_once PATH_MODELS.'Repositories/MercadoRepo.php';

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
	protected $scope;
	private $scale;
	private $divisor = 1;
	private $pYAxisName;

	public function __construct($rowIndicador, $filtersConfig, $year, $period, $scope, $scale = '1')
	{
		$this->rowIndicador  = $rowIndicador;
		$this->filtersConfig = $filtersConfig;
		$this->year          = $year;
		$this->period        = $period;
		$this->scope         = $scope;
		$this->scale         = $scale;

		extract($rowIndicador);

		$this->arrFiltersValues         = Helpers::filterValuesToArray($indicador_filtros);
		$this->tipo_indicador_activador = $tipo_indicador_activador;
		$this->setColumnValue();
		$this->setDivisor();
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


		if ($this->scale == '2') {
			//escala de miles
			//$this->xAxisname   = '';
			$this->pYAxisName  = Lang::get('indicador.reports.priceThousands');
		} elseif ($this->scale == '3') {
			//escala de millones
			//$this->xAxisname   = '';
			$this->pYAxisName  = Lang::get('indicador.reports.priceMillions');
		} else {
			//escala de unidades
			//$this->xAxisname   = '';
			$this->pYAxisName  = Lang::get('indicador.reports.priceUnit');
		}
	}

	private function setDivisor()
	{
		if ($this->scale == 2) {
			//escala de miles
			$this->divisor  = 1000;
		} elseif ($this->scale == 3) {
			//escala de millones
			$this->divisor  = 1000000;
		} else {
			//escala de unidades
			$this->divisor  = 1;
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

				if (!empty($filter['dateRange'])) {
					//si el filtro es un rango de fechas, debe unir los periodos que componen el rango

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

				} elseif ($filter['field'] == 'id_pais') {
					//si el filtro es el pais puede venir como parametro un pais o un mercado (grupo de paises)
					//Trae los paises configurados en el mercado seleccionado
					if (!empty($arrFiltersValues['mercado_id'])) {
						$result = $this->findCountriesByMarket($arrFiltersValues['mercado_id']);
						if (!$result['success']) {
							return $result;
						}
						$arr = explode(',', $result['data']);
						if (!empty($filterValue)) {
							$arr = array_merge(explode(',', $filterValue), $arr);
						}
						$filterValue = implode(',', $arr);
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

	public function findProductsBySector($sector)
	{
		$sector_id  = (is_numeric($sector)) ? $sector : Helpers::arrayGet($this->linesConfig, $sector);
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

	public function findCountriesByMarket($mercado_id)
	{
		$mercadoRepo = new MercadoRepo;
		$result     = $mercadoRepo->findPrimaryKey($mercado_id);

		if (!$result['success']) {
			return [
				'success' => false,
				'error'   => 'No existe configuración para el mercado ' . $mercado_id
			];
		}
		$row = array_shift($result['data']);
		return [
			'success' => true,
			'data'    => $row['mercado_paises']
		];
	}

	public function findBalanzaData()
	{
		$year    = $this->year;
		$period  = $this->period;
		$range   = $this->range;
		$divisor = $this->divisor;

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
				'valor_expo' => ( (float)$rowExpo[$this->columnValueExpo] / $divisor ),
				'valor_impo' => ( (float)$valor_impo / $divisor ),
			];
		}

		foreach ($rsDeclaraimp['data'] as $keyImpo => $rowImpo) {

			if(!in_array($rowImpo['periodo'], $arrPeriods)){
				$arrData[] = [
					'id'         => $rowImpo['id'],
					'periodo'    => $rowImpo['periodo'],
					'valor_expo' => 0,
					'valor_impo' => ( (float)$rowImpo[$this->columnValueImpo] / $divisor ),
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
				COLUMNAS,
				'',
				$this->pYAxisName
			);

			$arrSeries = [
				'valor_balanza' => Lang::get('indicador.columns_title.valor_balanza')
			];

			$areaChart = Helpers::jsonChart(
				$arrData,
				'periodo',
				$arrSeries,
				AREA,
				'',
				$this->pYAxisName
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
				AREA,
				'',
				Lang::get('indicador.reports.BCR')
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
					COLUMNAS,
					'',
					$this->pYAxisName
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
			$totalValue += ( (float)$rowExpo[$this->columnValueExpo] / $this->divisor );
		}

		$arrData           = [];
		$arrChartData      = [];
		$othersValue       = 0;
		$indexId           = 0;
		$othersRate        = 0;
		$cumulativeRate    = 0;
		$ConcentrationRate = Helpers::arrayGet($this->linesConfig, 'ConcentrationExportableSupply');

		foreach ($rsDeclaraexp['data'] as $keyExpo => $rowExpo) {

			$valueExpo = ( (float)$rowExpo[$this->columnValueExpo] / $this->divisor );
			$indexId  += 1;

			$rate = round( ( $valueExpo / $totalValue ) * 100 , 2 );
			$cumulativeRate += $rate;
			if ($cumulativeRate <= 80) {
				$arrData[] = [
					'id'            => $indexId,
					'numero'        => $indexId,
					'id_posicion'   => $rowExpo['id_posicion'],
					'posicion'      => $rowExpo['posicion'],
					'valor_expo'    => $valueExpo,
					'participacion' => $rate
				];
				$arrChartData[] = [
					'posicion'      => '(' . $rowExpo['id_posicion'] . ') ' . $rowExpo['posicion'],
					'valor_expo'    => $valueExpo,
					'participacion' => $rate
				];
			} else {
				$othersRate  += $rate;
				$othersValue += $valueExpo;
			}
		}

		//agrega la fila con el registro acumulado de las demas posiciones
		$indexId  += 1;
		$arrData[] = [
			'id'            => $indexId,
			'numero'        => '',
			'id_posicion'   => '*************************',
			'posicion'      => Lang::get('indicador.reports.others'),
			'valor_expo'    => $othersValue,
			'participacion' => $othersRate
		];
		$indexId  += 1;
		$arrData[] = [
			'id'            => $indexId,
			'numero'        => '',
			'id_posicion'   => '*************************',
			'posicion'      => Lang::get('indicador.columns_title.valor_expo'),
			'valor_expo'    => $totalValue,
			'participacion' => 100
		];
		$arrChartData[] = [
			'posicion'      => Lang::get('indicador.reports.others'),
			'valor_expo'    => $othersValue,
			'participacion' => $othersRate
		];

		$arrSeries = [
			'valor_expo' => Lang::get('indicador.columns_title.valor_expo')
		];

		$pieChart = Helpers::jsonChart(
			$arrChartData,
			'posicion',
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

			$variation     = $rowImpo[$columnValue] - $expoValue;
			$rateVariation = ( $rowImpo[$columnValue] == 0 ) ? 0: ( $variation / $rowImpo[$columnValue] );
			$rowIndex     += 1;

			$arrData[] = [
				'id'            => $rowImpo['id'],
				'rowIndex'      => 'Q'.$rowIndex,
				'impoPeriod'    => $rowImpo['periodo'],
				'impoValue'     => $rowImpo[$columnValue],
				'expoPeriod'    => $expoPeriod,
				'expoValue'     => $expoValue,
				'variation'     => $variation,
				'rateVariation' => $rateVariation,
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
			'impoValue' => Lang::get('indicador.columns_title.numero_productos_impo'),
			'expoValue' => Lang::get('indicador.columns_title.numero_productos_expo'),
			'variation' => Lang::get('indicador.reports.diferencia'),
		];

		$columnChart = Helpers::jsonChart(
			$arrData,
			'rowIndex',
			$arrSeries,
			COLUMNAS,
			'',
			Lang::get('indicador.columns_title.numero_productos')
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

			$variation  = ($rowFirst[$columnValue] == 0) ? 0 : ( ( $valueLast - $rowFirst[$columnValue] ) / $rowFirst[$columnValue]);
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
		$this->modelAdo->setPivotTotalFields([$columnValue]);
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

					//en este caso el indice es el año, debe suprimir el nombre de la column de totales que le pone por defecto la clase pivottable
					$index = str_replace(' '.$columnValue, '', $key);

					$IHH = ( $value / $arrTotals[$key] );
					$IHH = pow($IHH, 2);
					if (empty($arrIHH[$index])) {
						$arrIHH[$index] = 0;
					}
					$arrIHH[$index] += $IHH;
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
			COLUMNAS,
			'',
			Lang::get('indicador.columns_title.IHH')
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
		//$this->setRange('ini');

		$this->model      = $this->getModelExpo();
		$this->modelAdo   = $this->getModelExpoAdo();
		$columnValue      = $this->columnValueExpo;

		$this->setFiltersValues();


		$row = 'anio AS id';
		//si el periodo es diferente a anual debe filtrar por año
		if ($this->period != 12 && !empty($this->year)) {
			$this->model->setAnio($this->year);
			$row = 'periodo AS id';
		}

		if (!empty($arrFiltersValues['mercado_id'])) {
			$result = $this->findCountriesByMarket($arrFiltersValues['mercado_id']);
			if (!$result['success']) {
				return $result;
			}
			$arr = explode(',', $result['data']);
			if (!empty($arrFiltersValues['pais_id'])) {
				$arr = array_merge(explode(',', $arrFiltersValues['pais_id']), $arr);
			}
			$this->model->setId_paisdestino(implode(',', $arr));
		}

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
		//$row = 'periodo AS id';

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

						$totalProductsAgriculture   = ($rowProductsAgriculture   !== false) ? ( $rowProductsAgriculture[$columnValue] / $this->divisor )   : 0 ;
						$totalEnergeticMiningSector = ($rowEnergeticMiningSector !== false) ? ( $rowEnergeticMiningSector[$columnValue] / $this->divisor ) : 0 ;

						$total = ( $rowTotal[$columnValue]  / $this->divisor ) - $totalEnergeticMiningSector;
						$total = ( $total == 0 ) ? 1 : $total ;
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
						COLUMNAS,
						'',
						$this->pYAxisName
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
		//$this->setRange('ini');

		$this->model      = $this->getModelExpo();
		$this->modelAdo   = $this->getModelExpoAdo();
		$columnValue      = $this->columnValueExpo;
		$this->setFiltersValues();

		$row = 'anio AS id';
		//si el periodo es diferente a anual debe filtrar por año
		if ($this->period != 12 && !empty($this->year)) {
			$this->model->setAnio($this->year);
			$row = 'periodo AS id';
		}

		if (!empty($arrFiltersValues['mercado_id'])) {
			$result = $this->findCountriesByMarket($arrFiltersValues['mercado_id']);
			if (!$result['success']) {
				return $result;
			}
			$arr = explode(',', $result['data']);
			if (!empty($arrFiltersValues['pais_id'])) {
				$arr = array_merge(explode(',', $arrFiltersValues['pais_id']), $arr);
			}
			$this->model->setId_paisdestino(implode(',', $arr));
		}

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
		//$row = 'periodo AS id';

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

					$totalProductsTraditional    = ( $rowProductsTraditional !== false ) ? ( $rowProductsTraditional[$columnValue] / $this->divisor ) : 0 ;
					$totalProductsNonTraditional = ( $rowTotal[$columnValue] / $this->divisor ) - $totalProductsTraditional;

					$total = ($rowTotal[$columnValue] == 0) ? 1 : ( $rowTotal[$columnValue] / $this->divisor ) ;
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
					COLUMNAS,
					'',
					$this->pYAxisName
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

		$row = 'anio AS id';
		//si el periodo es diferente a anual debe filtrar por año
		if ($this->period != 12 && !empty($this->year)) {
			$this->model->setAnio($this->year);
			$row = 'periodo AS id';
		}

		$rowField = Helpers::getPeriodColumnSql($this->period);

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

			//busca los datos del total de exportaciones del sector agricola
			$result = $this->findProductsBySector('sectorIdAgriculture');
			if (!$result['success']) {
				return $result;
			}
			$productsAgriculture = $result['data'];
			$this->model->setId_posicion($productsAgriculture);
			//$this->model->setId_posicion('');
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

					$totalProduct = ($rowProduct !== false) ? ( $rowProduct[$columnValue] / $this->divisor ) : 0 ;

					$total = ($rowTotal[$columnValue] == 0) ? 1 : ( $rowTotal[$columnValue] / $this->divisor ) ;
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
					COLUMNAS,
					'',
					$this->pYAxisName
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
					COLUMNAS,
					'',
					Lang::get('indicador.columns_title.numero_empresas_expo')
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

		if (!empty($arrFiltersValues['mercado_id'])) {
			$result = $this->findCountriesByMarket($arrFiltersValues['mercado_id']);
			if (!$result['success']) {
				return $result;
			}
			$arr = explode(',', $result['data']);
			if (!empty($arrFiltersValues['pais_id'])) {
				$arr = array_merge(explode(',', $arrFiltersValues['pais_id']), $arr);
			}
			$this->model->setId_paisdestino(implode(',', $arr));
		}

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

	public function executeRelacionCrecimientoExpoAgroNoTradicionalExpoAgro()
	{
		$arrFiltersValues = $this->arrFiltersValues;
		$this->setTrade('expo');
		$this->setRange('ini');

		$this->model      = $this->getModelExpo();
		$this->modelAdo   = $this->getModelExpoAdo();
		$columnValue      = $this->columnValueExpo;
		$this->setFiltersValues();

		if (!empty($arrFiltersValues['mercado_id'])) {
			$result = $this->findCountriesByMarket($arrFiltersValues['mercado_id']);
			if (!$result['success']) {
				return $result;
			}
			$arr = explode(',', $result['data']);
			if (!empty($arrFiltersValues['pais_id'])) {
				$arr = array_merge(explode(',', $arrFiltersValues['pais_id']), $arr);
			}
			$this->model->setId_paisdestino(implode(',', $arr));
		}

		$rowField = Helpers::getPeriodColumnSql($this->period);
		$row = 'anio AS id';

		$arrRowField   = [$row, $rowField];

		$this->modelAdo->setPivotRowFields(implode(',', $arrRowField));
		$this->modelAdo->setPivotTotalFields($columnValue);
		$this->modelAdo->setPivotGroupingFunction('SUM');

		//Trae los productos configurados como productos tradicionales del sector agricola
		$result = $this->findProductsBySector('sectorIdTraditional');
		if (!$result['success']) {
			return $result;
		}
		$productsTraditional = $result['data'];

		$this->model->setId_posicion($productsTraditional);
		$result = $this->modelAdo->pivotSearch($this->model);

		if (!$result['success']) {
			return $result;
		}
		$arrDataProductsTraditional = $result['data'];

		//Trae los productos configurados como sector agricola
		$result = $this->findProductsBySector('sectorIdAgriculture');
		if (!$result['success']) {
			return $result;
		}
		$productsAgriculture = $result['data'];

		//busca los datos del sector energetico
		$this->model->setId_posicion($productsAgriculture);
		$result = $this->modelAdo->pivotSearch($this->model);

		if (!$result['success']) {
			return $result;
		}
		$arrDataProductsAgriculture = $result['data'];

		$arrData   = [];
		$yearFirst = $arrFiltersValues['anio_ini'];

		foreach ($arrDataProductsAgriculture as $rowTotal) {

			$rowProductsTraditional = Helpers::findKeyInArrayMulti(
				$arrDataProductsTraditional,
				'periodo',
				$rowTotal['periodo']
			);

			$yearLast                     = $rowTotal['periodo'];
			$valueLastTotal               = (float)$rowTotal[$columnValue];
			$valueLastTotal               = ($valueLastTotal == 0) ? 1 : $valueLastTotal ;
			$valueLastProductsTraditional = ($rowProductsTraditional !== false) ? (float)$rowProductsTraditional[$columnValue] : 0 ;
			$valueLastNonTraditional      = $valueLastTotal - $valueLastProductsTraditional;

			if ($rowTotal['periodo'] == $yearFirst) {
				$valueFirstNonTraditional = $valueLastNonTraditional;
				$valueFirstTotal          = $valueLastTotal;
			}

			$arrData[] = [
				'id'                  => $rowTotal['id'],
				'periodo'             => $rowTotal['periodo'],
				'valor_expo_no_tradi' => $valueLastNonTraditional,
				'valor_expo_agricola' => $valueLastTotal
			];
		}

		$rangeYear     = range($yearFirst, $yearLast);
		$numberPeriods = count($rangeYear);

		$growthRateAgricultureNonTraditional = ( pow(($valueLastNonTraditional / $valueFirstNonTraditional), (1 / $numberPeriods)) - 1);
		$growthRateAgriculture               = ( pow(($valueLastTotal / $valueFirstTotal), (1 / $numberPeriods)) - 1);

		$arrSeries = [
			'valor_expo_no_tradi' => Lang::get('indicador.columns_title.valor_expo_no_tradi'),
			'valor_expo_agricola' => Lang::get('indicador.columns_title.valor_expo_agricola'),
		];

		$columnChart = Helpers::jsonChart(
			$arrData,
			'periodo',
			$arrSeries,
			COLUMNAS
		);

		$result = [
			'success'                             => true,
			'data'                                => $arrData,
			'growthRateAgricultureNonTraditional' => ($growthRateAgricultureNonTraditional * 100),
			'growthRateAgriculture'               => ($growthRateAgriculture * 100),
			'rateVariation'                       => ($growthRateAgricultureNonTraditional / $growthRateAgriculture),
			'columnChartData'                     => $columnChart,
			'total'                               => count($arrData)
		];

		return $result;
	}

	public function executeParticipacionExpoSectorAgricolaPib()
	{
		$arrFiltersValues = $this->arrFiltersValues;
		$this->setTrade('expo');
		$this->setRange('ini');

		$this->model      = $this->getModelExpo();
		$this->modelAdo   = $this->getModelExpoAdo();
		$columnValue      = 'valor_pesos';
		$this->setFiltersValues();

		//Trae los productos configurados como agricolas
		$result = $this->findProductsBySector('sectorIdAgriculture');
		if (!$result['success']) {
			return $result;
		}
		$productsAgriculture = $result['data'];

		$this->model->setId_posicion($productsAgriculture);
		if ($this->period != 12 && !empty($this->year)) {
			$this->model->setAnio($this->year);
			$row = 'periodo AS id';
		}

		$rowField = Helpers::getPeriodColumnSql($this->period);
		$row      = 'anio AS id, anio';
		$arrRowField   = [$row, $rowField];

		$this->modelAdo->setPivotRowFields(implode(',', $arrRowField));
		$this->modelAdo->setPivotTotalFields($columnValue);
		$this->modelAdo->setPivotGroupingFunction('SUM');

		//busca los datos del sector agricola
		$result = $this->modelAdo->pivotSearch($this->model);

		if (!$result['success']) {
			return $result;
		}

		$arrDataProductsAgriculture = $result['data'];

		include PATH_MODELS.'Repositories/PibRepo.php';
		$pibRepo = new PibRepo;

		$arrData = [];
		$period  = $this->period;

		foreach ($arrDataProductsAgriculture as $row) {

			$anio    = $row['anio'];
			$periodo = $row['periodo'];

			$result = $pibRepo->listPeriod(compact('anio', 'period'));

			if (!$result['success']) {
				return $result;
			}

			if ($result['total'] == 0) {
				return [
					'success' => false,
					'error'   => 'No existe información del PIB para el periodo: ' . $periodo
				];
			}

			$rowPib = Helpers::findKeyInArrayMulti(
				$result['data'],
				'pib_periodo',
				$periodo
			);

			//el pib vienen en miles de millones por eso hay que multiplicar por 1000000000
			$pib_nacional = ($rowPib['pib_nacional'] == 0) ? 0 : ($rowPib['pib_nacional'] * 100000000);

			$rate = ($pib_nacional == 0) ? 0 : ($row[$columnValue] / $pib_nacional) ;

			$arrData[] = [
				'id'                      => $row['id'],
				'periodo'                 => $row['periodo'],
				'valor_expo_agricola_cop' => $row[$columnValue],
				'pib_nacional'            => $pib_nacional,
				'participacion'           => ( $rate * 100 )
			];

		}

		$arrSeries = [
			'valor_expo_agricola_cop' => Lang::get('indicador.columns_title.valor_expo_agricola_cop'),
			'pib_nacional'        => Lang::get('pib.columns_title.pib_nacional'),
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
			'total'           => count($arrData),
			'columnChartData' => $columnChart,
			//'areaChartData'   => $areaChart,
		];

		return $result;
	}

	public function executeParticipacionExpoSectorAgricolaPibAgricola()
	{
		$arrFiltersValues = $this->arrFiltersValues;
		$this->setTrade('expo');
		$this->setRange('ini');

		$this->model      = $this->getModelExpo();
		$this->modelAdo   = $this->getModelExpoAdo();
		$columnValue      = 'valor_pesos';
		$this->setFiltersValues();

		if ($this->period != 12 && !empty($this->year)) {
			$this->model->setAnio($this->year);
			$row = 'periodo AS id';
		}

		$rowField = Helpers::getPeriodColumnSql($this->period);
		$row      = 'anio AS id, anio';
		$arrRowField   = [$row, $rowField];

		$this->modelAdo->setPivotRowFields(implode(',', $arrRowField));
		$this->modelAdo->setPivotTotalFields($columnValue);
		$this->modelAdo->setPivotGroupingFunction('SUM');

		//busca los datos del producto agricola seleccionado
		$result = $this->modelAdo->pivotSearch($this->model);

		if (!$result['success']) {
			return $result;
		}

		$arrDataProductsAgriculture = $result['data'];

		include PATH_MODELS.'Repositories/PibRepo.php';
		$pibRepo = new PibRepo;

		$arrData = [];
		$period  = $this->period;

		foreach ($arrDataProductsAgriculture as $row) {

			$anio    = $row['anio'];
			$periodo = $row['periodo'];

			$result = $pibRepo->listPeriod(compact('anio', 'period'));

			if (!$result['success']) {
				return $result;
			}

			if ($result['total'] == 0) {
				return [
					'success' => false,
					'error'   => 'No existe información del PIB para el periodo: ' . $periodo
				];
			}

			$rowPib = Helpers::findKeyInArrayMulti(
				$result['data'],
				'pib_periodo',
				$periodo
			);

			//el pib vienen en miles de millones por eso hay que multiplicar por 1000000000
			$pib_agricultura = ($rowPib['pib_agricultura'] == 0) ? 0 : ($rowPib['pib_agricultura'] * 100000000);

			$rate = ($pib_agricultura == 0) ? 0 : ($row[$columnValue] / $pib_agricultura) ;

			$arrData[] = [
				'id'                      => $row['id'],
				'periodo'                 => $row['periodo'],
				'valor_expo_agricola_cop' => $row[$columnValue],
				'pib_agricultura'         => $pib_agricultura,
				'participacion'           => ( $rate * 100 )
			];

		}

		usort($arrData, Helpers::arraySortByValue('periodo'));
		
		$arrSeries = [
			'valor_expo_agricola_cop' => Lang::get('indicador.columns_title.valor_expo_agricola_cop'),
			'pib_agricultura'         => Lang::get('pib.columns_title.pib_agricultura'),
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
			'total'           => count($arrData),
			'columnChartData' => $columnChart,
			//'areaChartData'   => $areaChart,
		];

		return $result;
	}

	public function executeCoeficientePenetracionImpo()
	{
		$arrFiltersValues = $this->arrFiltersValues;
		$this->setTrade('expo');
		$this->setRange('ini');

		$this->model      = $this->getModelExpo();
		$this->modelAdo   = $this->getModelExpoAdo();

		$this->setFiltersValues();
		$rowField = Helpers::getPeriodColumnSql($this->period);
		$row      = 'anio AS id';
		$arrRowField   = [$row, $rowField];

		//Trae los productos configurados en el sector seleccionado
		$result = $this->findProductsBySector($arrFiltersValues['sector_id']);
		if (!$result['success']) {
			return $result;
		}
		$products = $result['data'];
		$this->model->setId_posicion($products);

		$this->modelAdo->setPivotRowFields(implode(',', $arrRowField));
		$this->modelAdo->setPivotTotalFields($this->columnValueExpo);
		$this->modelAdo->setPivotGroupingFunction('SUM');

		//busca los datos del producto agricola seleccionado
		$rsDeclaraexp = $this->modelAdo->pivotSearch($this->model);
		if (!$rsDeclaraexp['success']) {
			return $rsDeclaraexp;
		}

		$this->setTrade('impo');
		$this->model      = $this->getModelImpo();
		$this->modelAdo   = $this->getModelImpoAdo();
		$this->setFiltersValues();
		$this->model->setId_posicion($products);

		$this->modelAdo->setPivotRowFields(implode(',', $arrRowField));
		$this->modelAdo->setPivotTotalFields($this->columnValueImpo);
		$this->modelAdo->setPivotGroupingFunction('SUM');

		//busca los datos del producto agricola seleccionado
		$rsDeclaraimp = $this->modelAdo->pivotSearch($this->model);
		if (!$rsDeclaraimp['success']) {
			return $rsDeclaraimp;
		}

		$arrData    = [];
		$arrPeriods = [];
		$sector_id  = $arrFiltersValues['sector_id'];

		include PATH_MODELS.'Repositories/ProduccionRepo.php';
		$produccionRepo = new ProduccionRepo;

		foreach ($rsDeclaraexp['data'] as $keyExpo => $rowExpo) {

			$anio    = $rowExpo['periodo'];
			$result = $produccionRepo->listPeriodSector(compact('anio', 'sector_id'));
			if (!$result['success']) {
				return $result;
			}
			if ($result['total'] == 0) {
				return [
					'success' => false,
					'error'   => 'No existe información de Producción para el periodo: ' . $anio
				];
			}
			$rowProduccion = Helpers::findKeyInArrayMulti(
				$result['data'],
				'produccion_anio',
				$anio
			);

			$rowImpo = Helpers::findKeyInArrayMulti(
				$rsDeclaraimp['data'],
				'periodo',
				$rowExpo['periodo']
			);
			$valor_impo = 0;
			if ($rowImpo !== false) {
				$valor_impo   = $rowImpo[$this->columnValueImpo];
				$arrPeriods[] = $rowImpo['periodo'];
			}

			//la produccion viene en toneladas metricas por eso hay que multiplicar por 1000
			$produccion_peso_neto = ($rowProduccion['produccion_peso_neto'] == 0) ? 0 : ($rowProduccion['produccion_peso_neto'] * 1000);

			$divider = ($produccion_peso_neto + $valor_impo - $rowExpo[$this->columnValueExpo]);

			$PI = ($divider == 0) ? 0 : ($valor_impo / $divider) ;

			$arrData[] = [
				'id'              => $rowExpo['id'],
				'periodo'         => $rowExpo['periodo'],
				'peso_expo'       => $rowExpo[$this->columnValueExpo],
				'peso_impo'       => $valor_impo,
				'produccion_peso' => $produccion_peso_neto,
				'PI'              => ($PI * 100),
			];
		}

		foreach ($rsDeclaraimp['data'] as $keyImpo => $rowImpo) {

			if(!in_array($rowImpo['periodo'], $arrPeriods)){
				$anio    = $rowExpo['periodo'];
				$result = $produccionRepo->listPeriodSector(compact('anio', 'sector_id'));
				if (!$result['success']) {
					return $result;
				}
				if ($result['total'] == 0) {
					return [
						'success' => false,
						'error'   => 'No existe información de Producción para el periodo: ' . $anio
					];
				}
				$rowProduccion = Helpers::findKeyInArrayMulti(
					$result['data'],
					'produccion_anio',
					$anio
				);
				//la produccion viene en toneladas metricas por eso hay que multiplicar por 1000
				$produccion_peso_neto = ($rowProduccion['produccion_peso_neto'] == 0) ? 0 : ($rowProduccion['produccion_peso_neto'] * 1000);
				$arrData[] = [
					'id'              => $rowImpo['id'],
					'periodo'         => $rowImpo['periodo'],
					'peso_expo'       => 0,
					'peso_impo'       => $rowImpo[$this->columnValueImpo],
					'produccion_peso' => $produccion_peso_neto,
					'PI'              => 0,
				];
			}

		}

		if (count($arrData) == 0) {
			return [
				'success' => false,
				'error'   => Lang::get('error.no_records_found')
			];
		}

		usort($arrData, Helpers::arraySortByValue('periodo'));

		$arrSeries = [
			'PI' => Lang::get('indicador.columns_title.PI')
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
			'total'           => count($arrData),
			'columnChartData' => $columnChart,
			//'areaChartData'   => $areaChart,
		];

		return $result;
	}

	public function executeCoeficienteAperturaExpo()
	{
		$arrFiltersValues = $this->arrFiltersValues;
		$this->setTrade('expo');
		$this->setRange('ini');

		$this->model      = $this->getModelExpo();
		$this->modelAdo   = $this->getModelExpoAdo();

		$this->setFiltersValues();
		$rowField = Helpers::getPeriodColumnSql($this->period);
		$row      = 'anio AS id';
		$arrRowField   = [$row, $rowField];

		//Trae los productos configurados en el sector seleccionado
		$result = $this->findProductsBySector($arrFiltersValues['sector_id']);
		if (!$result['success']) {
			return $result;
		}
		$products = $result['data'];
		$this->model->setId_posicion($products);

		$this->modelAdo->setPivotRowFields(implode(',', $arrRowField));
		$this->modelAdo->setPivotTotalFields($this->columnValueExpo);
		$this->modelAdo->setPivotGroupingFunction('SUM');

		//busca los datos del producto agricola seleccionado
		$rsDeclaraexp = $this->modelAdo->pivotSearch($this->model);
		if (!$rsDeclaraexp['success']) {
			return $rsDeclaraexp;
		}

		$this->setTrade('impo');
		$this->model      = $this->getModelImpo();
		$this->modelAdo   = $this->getModelImpoAdo();
		$this->setFiltersValues();
		$this->model->setId_posicion($products);

		$this->modelAdo->setPivotRowFields(implode(',', $arrRowField));
		$this->modelAdo->setPivotTotalFields($this->columnValueImpo);
		$this->modelAdo->setPivotGroupingFunction('SUM');

		//busca los datos del producto agricola seleccionado
		$rsDeclaraimp = $this->modelAdo->pivotSearch($this->model);
		if (!$rsDeclaraimp['success']) {
			return $rsDeclaraimp;
		}

		$arrData    = [];
		$arrPeriods = [];
		$sector_id  = $arrFiltersValues['sector_id'];

		include PATH_MODELS.'Repositories/ProduccionRepo.php';
		$produccionRepo = new ProduccionRepo;

		foreach ($rsDeclaraexp['data'] as $keyExpo => $rowExpo) {

			$anio    = $rowExpo['periodo'];
			$result = $produccionRepo->listPeriodSector(compact('anio', 'sector_id'));
			if (!$result['success']) {
				return $result;
			}
			if ($result['total'] == 0) {
				return [
					'success' => false,
					'error'   => 'No existe información de Producción para el periodo: ' . $anio
				];
			}
			$rowProduccion = Helpers::findKeyInArrayMulti(
				$result['data'],
				'produccion_anio',
				$anio
			);

			$rowImpo = Helpers::findKeyInArrayMulti(
				$rsDeclaraimp['data'],
				'periodo',
				$rowExpo['periodo']
			);
			$valor_impo = 0;
			if ($rowImpo !== false) {
				$valor_impo   = $rowImpo[$this->columnValueImpo];
				$arrPeriods[] = $rowImpo['periodo'];
			}

			//la produccion viene en toneladas metricas por eso hay que multiplicar por 1000
			$produccion_peso_neto = ($rowProduccion['produccion_peso_neto'] == 0) ? 0 : ($rowProduccion['produccion_peso_neto'] * 1000);

			$divider = ($produccion_peso_neto + $valor_impo - $rowExpo[$this->columnValueExpo]);

			$AE = ($divider == 0) ? 0 : ($rowExpo[$this->columnValueExpo] / $divider) ;

			$arrData[] = [
				'id'              => $rowExpo['id'],
				'periodo'         => $rowExpo['periodo'],
				'peso_expo'       => $rowExpo[$this->columnValueExpo],
				'peso_impo'       => $valor_impo,
				'produccion_peso' => $produccion_peso_neto,
				'AE'              => ($AE * 100),
			];
		}

		foreach ($rsDeclaraimp['data'] as $keyImpo => $rowImpo) {

			if(!in_array($rowImpo['periodo'], $arrPeriods)){
				$anio    = $rowExpo['periodo'];
				$result = $produccionRepo->listPeriodSector(compact('anio', 'sector_id'));
				if (!$result['success']) {
					return $result;
				}
				if ($result['total'] == 0) {
					return [
						'success' => false,
						'error'   => 'No existe información de Producción para el periodo: ' . $anio
					];
				}
				$rowProduccion = Helpers::findKeyInArrayMulti(
					$result['data'],
					'produccion_anio',
					$anio
				);
				//la produccion viene en toneladas metricas por eso hay que multiplicar por 1000
				$produccion_peso_neto = ($rowProduccion['produccion_peso_neto'] == 0) ? 0 : ($rowProduccion['produccion_peso_neto'] * 1000);
				$arrData[] = [
					'id'              => $rowImpo['id'],
					'periodo'         => $rowImpo['periodo'],
					'peso_expo'       => 0,
					'peso_impo'       => $rowImpo[$this->columnValueImpo],
					'produccion_peso' => $produccion_peso_neto,
					'AE'              => 0,
				];
			}
		}

		if (count($arrData) == 0) {
			return [
				'success' => false,
				'error'   => Lang::get('error.no_records_found')
			];
		}

		usort($arrData, Helpers::arraySortByValue('periodo'));

		$arrSeries = [
			'AE' => Lang::get('indicador.columns_title.AE')
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
			'total'           => count($arrData),
			'columnChartData' => $columnChart,
			//'areaChartData'   => $areaChart,
		];

		return $result;
	}

	public function executeConsumoAparente()
	{
		$arrFiltersValues = $this->arrFiltersValues;
		$this->setTrade('expo');
		$this->setRange('ini');

		$this->model      = $this->getModelExpo();
		$this->modelAdo   = $this->getModelExpoAdo();

		$this->setFiltersValues();
		$rowField = Helpers::getPeriodColumnSql($this->period);
		$row      = 'anio AS id';
		$arrRowField   = [$row, $rowField];

		//Trae los productos configurados en el sector seleccionado
		$result = $this->findProductsBySector($arrFiltersValues['sector_id']);
		if (!$result['success']) {
			return $result;
		}
		$products = $result['data'];
		$this->model->setId_posicion($products);

		$this->modelAdo->setPivotRowFields(implode(',', $arrRowField));
		$this->modelAdo->setPivotTotalFields($this->columnValueExpo);
		$this->modelAdo->setPivotGroupingFunction('SUM');

		//busca los datos del producto agricola seleccionado
		$rsDeclaraexp = $this->modelAdo->pivotSearch($this->model);
		if (!$rsDeclaraexp['success']) {
			return $rsDeclaraexp;
		}

		$this->setTrade('impo');
		$this->model      = $this->getModelImpo();
		$this->modelAdo   = $this->getModelImpoAdo();
		$this->setFiltersValues();
		$this->model->setId_posicion($products);

		$this->modelAdo->setPivotRowFields(implode(',', $arrRowField));
		$this->modelAdo->setPivotTotalFields($this->columnValueImpo);
		$this->modelAdo->setPivotGroupingFunction('SUM');

		//busca los datos del producto agricola seleccionado
		$rsDeclaraimp = $this->modelAdo->pivotSearch($this->model);
		if (!$rsDeclaraimp['success']) {
			return $rsDeclaraimp;
		}

		$arrData    = [];
		$arrPeriods = [];
		$sector_id  = $arrFiltersValues['sector_id'];

		include PATH_MODELS.'Repositories/ProduccionRepo.php';
		$produccionRepo = new ProduccionRepo;

		foreach ($rsDeclaraexp['data'] as $keyExpo => $rowExpo) {

			$anio    = $rowExpo['periodo'];
			$result = $produccionRepo->listPeriodSector(compact('anio', 'sector_id'));
			if (!$result['success']) {
				return $result;
			}
			if ($result['total'] == 0) {
				return [
					'success' => false,
					'error'   => 'No existe información de Producción para el periodo: ' . $anio
				];
			}
			$rowProduccion = Helpers::findKeyInArrayMulti(
				$result['data'],
				'produccion_anio',
				$anio
			);

			$rowImpo = Helpers::findKeyInArrayMulti(
				$rsDeclaraimp['data'],
				'periodo',
				$rowExpo['periodo']
			);
			$valor_impo = 0;
			if ($rowImpo !== false) {
				$valor_impo   = $rowImpo[$this->columnValueImpo];
				$arrPeriods[] = $rowImpo['periodo'];
			}

			//la produccion viene en toneladas metricas por eso hay que multiplicar por 1000
			$produccion_peso_neto = ($rowProduccion['produccion_peso_neto'] == 0) ? 0 : ($rowProduccion['produccion_peso_neto'] * 1000);

			$CA  = ($produccion_peso_neto + $valor_impo - $rowExpo[$this->columnValueExpo]);
			$CAA = ($produccion_peso_neto / $CA);

			$arrData[] = [
				'id'              => $rowExpo['id'],
				'periodo'         => $rowExpo['periodo'],
				'peso_expo'       => $rowExpo[$this->columnValueExpo],
				'peso_impo'       => $valor_impo,
				'produccion_peso' => $produccion_peso_neto,
				'CA'              => $CA,
				'CAA'             => ($CAA * 100),
			];
		}

		foreach ($rsDeclaraimp['data'] as $keyImpo => $rowImpo) {

			if(!in_array($rowImpo['periodo'], $arrPeriods)){
				$anio    = $rowExpo['periodo'];
				$result = $produccionRepo->listPeriodSector(compact('anio', 'sector_id'));
				if (!$result['success']) {
					return $result;
				}
				if ($result['total'] == 0) {
					return [
						'success' => false,
						'error'   => 'No existe información de Producción para el periodo: ' . $anio
					];
				}
				$rowProduccion = Helpers::findKeyInArrayMulti(
					$result['data'],
					'produccion_anio',
					$anio
				);
				//la produccion viene en toneladas metricas por eso hay que multiplicar por 1000
				$produccion_peso_neto = ($rowProduccion['produccion_peso_neto'] == 0) ? 0 : ($rowProduccion['produccion_peso_neto'] * 1000);

				$CA  = ($produccion_peso_neto + $rowImpo[$this->columnValueImpo]);
				$CAA = ($produccion_peso_neto / $CA);

				$arrData[] = [
					'id'              => $rowImpo['id'],
					'periodo'         => $rowImpo['periodo'],
					'peso_expo'       => 0,
					'peso_impo'       => $rowImpo[$this->columnValueImpo],
					'produccion_peso' => $produccion_peso_neto,
					'CA'              => $CA,
					'CAA'             => ($CAA * 100),
				];
			}

		}

		if (count($arrData) == 0) {
			return [
				'success' => false,
				'error'   => Lang::get('error.no_records_found')
			];
		}

		usort($arrData, Helpers::arraySortByValue('periodo'));

		$arrSeries = [
			'CA' => Lang::get('indicador.columns_title.CA')
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
			'total'           => count($arrData),
			'columnChartData' => $columnChart,
			//'areaChartData'   => $areaChart,
		];

		return $result;
	}

	public function executeComtradePenetracionMercado()
	{
		$arrFiltersValues = $this->arrFiltersValues;
		$this->setRange('ini');
		$yearFirst = $arrFiltersValues['anio_ini'];
		$yearLast  = $arrFiltersValues['anio_fin'];
		$rangeYear = range($yearFirst, $yearLast);

		$id_pais_origen  = $arrFiltersValues['id_pais_origen'];
		$id_pais_destino = empty($arrFiltersValues['id_pais_destino']) ? 0 : $arrFiltersValues['id_pais_destino'];
		$id_subpartida   = $arrFiltersValues['id_subpartida'];

		if (
			empty($yearFirst) ||
			empty($yearLast) ||
			empty($id_pais_origen) ||
			empty($id_subpartida)
		) {
			$result = [
				'success' => false,
				'error'   => 'Incomplete data for this request.'
			];
			return $result;
		}
		
		$baseUrl = Helpers::arrayGet($this->linesConfig, 'urlApiComtrade');
		$colombiaIdComtrade = Helpers::arrayGet($this->linesConfig, 'colombiaIdComtrade');

		//curl 'http://comtrade.un.org/api/get?max=500&type=C&freq=A&px=HS&ps=2013%2C2010%2C2011%2C2012&r=170%2C218&p=0&rg=1%2C2&cc=01&token=56233621927079056ea4d1e49ecf8f11' -H 'Host: comtrade.un.org' -H 'User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64; rv:34.0) Gecko/20100101 Firefox/34.0' -H 'Accept: application/json, text/javascript, */*; q=0.01' -H 'Accept-Language: es-MX,es-ES;q=0.8,es-AR;q=0.7,es;q=0.5,en-US;q=0.3,en;q=0.2' -H 'Accept-Encoding: gzip, deflate' -H 'X-Requested-With: XMLHttpRequest' -H 'Referer: http://comtrade.un.org/data/' -H 'Cookie: _ga=GA1.2.2137246786.1419954195; ASPSESSIONIDAACRDDSB=HGLJBMEDNOAGEKOAKDFCLCBC; _gat=1; _gali=preview'

		$parameters = [
			'max'  => 5000,
			'type' => 'C',
			'freq' => 'A', //frecuancia anual
			'px'   => 'HS',
			'rg'   => '1,2', //impo y expo
			'ps'   => implode(',', $rangeYear),
			'r'    => $id_pais_origen . ',' . $colombiaIdComtrade,
			'p'    => $id_pais_destino,
			'cc'   => $id_subpartida,
		];

		$url = $baseUrl . '?' . http_build_query($parameters);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		$result = json_decode(curl_exec($ch), true);

		if (empty($result['dataset'])) {
			$result = [
				'success' => false,
				'error'   => Lang::get('error.no_records_found_comtrade')
			];
			return $result;
		}

		$arrDataColImp = [];
		$arrDataColExp = [];
		$arrDataImp    = [];
		$arrDataExp    = [];
		$arrData       = [];
		$columnValue   = 'TradeValue';

		foreach ($result['dataset'] as $key => $row) {
			//var_dump($row['rgCode'], $row['rtCode'], $colombiaIdComtrade);
			if ($row['rgCode'] == 1) {
				
				if ($row['rtCode'] == $colombiaIdComtrade) {
					$arrDataColImp[] = [
						'id'         => $row['yr'],
						'periodo'    => $row['period'],
						'valor_impo' => $row[$columnValue],
					];
				} else {
					$arrDataImp[] = [
						'id'         => $row['yr'],
						'periodo'    => $row['period'],
						'valor_impo' => $row[$columnValue],
					];
				}
				
			} elseif ($row['rgCode'] == 2) {
				
				if ($row['rtCode'] == $colombiaIdComtrade) {
					$arrDataColExp[] = [
						'id'         => $row['yr'],
						'periodo'    => $row['period'],
						'valor_expo' => $row[$columnValue],
					];
				} else {
					$arrDataExp[] = [
						'id'         => $row['yr'],
						'periodo'    => $row['period'],
						'valor_expo' => $row[$columnValue],
					];
				}
			}
		}

		foreach ($arrDataImp as $key => $rowImpo) {
			$rowExpo = Helpers::findKeyInArrayMulti(
				$arrDataExp,
				'periodo',
				$rowImpo['periodo']
			);
			$rowImpoCol = Helpers::findKeyInArrayMulti(
				$arrDataColImp,
				'periodo',
				$rowImpo['periodo']
			);
			$rowExpoCol = Helpers::findKeyInArrayMulti(
				$arrDataColExp,
				'periodo',
				$rowImpo['periodo']
			);

			$totalExpo    = ($rowExpo    !== false) ? $rowExpo['valor_expo']    : 0 ;
			$totalImpoCol = ($rowImpoCol !== false) ? $rowImpoCol['valor_impo'] : 0 ;
			$totalExpoCol = ($rowExpoCol !== false) ? $rowExpoCol['valor_expo'] : 0 ;

			$rate = ($totalExpoCol == 0) ? 0 : (($totalExpo - $rowImpo['valor_impo']) / $totalExpoCol) ;

			$arrData[] = [
				'id'             => $rowImpo['id'],
				'periodo'        => $rowImpo['periodo'],
				'valor_impo'     => $rowImpo['valor_impo'],
				'valor_expo'     => $totalExpo,
				'valor_expo_col' => $totalExpoCol,
				'valor_impo_col' => $totalImpoCol,
				'IEI'  => ($rate * 100)
			];

		}

		if (count($arrData) == 0) {
			return [
				'success' => false,
				'error'   => Lang::get('error.no_records_found')
			];
		}

		usort($arrData, Helpers::arraySortByValue('periodo'));

		$arrSeries = [
			'IEI' => Lang::get('indicador.columns_title.IEI')
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
			'total'           => count($arrData),
			'columnChartData' => $columnChart,
			//'areaChartData'   => $areaChart,
		];

		return $result;
	}

	public function executeComtradePuestoColombiaProveedor()
	{
		$year   = $this->year;
		$period = $this->period;
		$arrFiltersValues = $this->arrFiltersValues;
		
		$id_pais_origen  = $arrFiltersValues['id_pais_origen'];
		$id_subpartida   = $arrFiltersValues['id_subpartida'];

		if ($period != 12) {
			$freq = 'M';
		} else {
			$freq = 'A';
		}

		if (
			empty($year) ||
			empty($period) ||
			empty($id_pais_origen) ||
			empty($id_subpartida)
		) {
			$result = [
				'success' => false,
				'error'   => 'Incomplete data for this request.'
			];
			return $result;
		}
		
		$baseUrl = Helpers::arrayGet($this->linesConfig, 'urlApiComtrade');
		$colombiaIdComtrade = Helpers::arrayGet($this->linesConfig, 'colombiaIdComtrade');

		$parameters = [
			'max'  => 5000,
			'type' => 'C',
			'freq' => 'A', //frecuancia anual
			'px'   => 'HS',
			'rg'   => '1', //impo
			'ps'   => $year,
			'r'    => $id_pais_origen,
			'p'    => 'all',
			'cc'   => $id_subpartida,
		];

		$url = $baseUrl . '?' . http_build_query($parameters);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		$result = json_decode(curl_exec($ch), true);

		if (empty($result['dataset'])) {
			$result = [
				'success' => false,
				'error'   => Lang::get('error.no_records_found_comtrade')
			];
			return $result;
		}

		$rangePeriods   = Helpers::getPeriodRange($period);
		$arrDataExp     = [];
		$arrData        = [];
		$columnValue    = 'TradeValue';
		$totalExpo      = [];
		$oldPeriod      = '';
		$countCountries = 0;
		$totalReg       = count($result['dataset']);
		
		foreach ($result['dataset'] as $key => $row) {
			$period    = $row['period'];
			$oldPeriod = (empty($oldPeriod)) ? $period : $oldPeriod ;
			if ($row['ptCode'] == 0) {//datos acumulados de todo el mundo
				$totalExpo[$period]['value'] = $row[$columnValue];
			} else {
				
				if ($oldPeriod != $period || $key == ($totalReg - 1) ) {
					$totalExpo[$oldPeriod]['count'] = $countCountries;
					//$arrDataExp[$period]            = [];
					$countCountries                 = 0;
					$oldPeriod                      = $period;
				}
				$countCountries += 1;
				$arrDataExp/*[$period]*/[] = [
					'id'         => $row['yr'],
					'periodo'    => $row['period'],
					'pais'       => $row['ptTitle'],
					'id_pais'    => $row['ptCode'],
					'valor_expo' => $row[$columnValue],
				];
			}
			
		}

		usort($arrDataExp, Helpers::arraySortByValue('valor_expo', true));
		$arrChartData = [];
		$othersValue  = 0;

		foreach ($arrDataExp as $key => $rowExpo) {
			$period = $row['period'];
			//foreach ($arrExpo as $key => $rowExpo) {
				if (!empty($totalExpo[$period])) {
					if ($colombiaIdComtrade == $rowExpo['id_pais']) {
						$arrChartData[] = [
							'pais' => $rowExpo['pais'],
							'valor_expo' => $rowExpo['valor_expo']
						];
					} else {
						$othersValue += $rowExpo['valor_expo'];
					}
					$rate = $rowExpo['valor_expo'] / $totalExpo[$period]['value'];

					$arrData[] = array_merge(
						$rowExpo, [
						'position' => ($key + 1) . ' de ' . ($totalExpo[$period]['count'] + 1),
						'participacion' => ($rate * 100)
						]
					);
				}
			//}
		}

		if (count($arrData) == 0) {
			return [
				'success' => false,
				'error'   => Lang::get('error.no_records_found')
			];
		}

		$arrChartData[] = [
			'pais'   => Lang::get('indicador.reports.others'),
			'valor_expo' => $othersValue
		];

		$arrSeries = [
			'valor_expo' => Lang::get('indicador.comtrade_columns_title.TradeValue')
		];

		$pieChart = Helpers::jsonChart(
			$arrChartData,
			'pais',
			$arrSeries,
			PIE
		);

		/*$columnChart = Helpers::jsonChart(
			$arrChartData,
			'pais',
			$arrSeries,
			COLUMNAS
		);*/

		$result = [
			'success'           => true,
			'data'              => $arrData,
			'total'             => count($arrData),
			//'columnChartData' => $columnChart,
			'pieChartData'      => $pieChart,
		];

		return $result;
	}

	public function executeAcumuladoPosicionPais()
	{
		$arrFiltersValues = $this->arrFiltersValues;
		$trade            = ( empty($arrFiltersValues['intercambio']) ) ? 'impo' : $arrFiltersValues['intercambio'];

		$this->setTrade($trade);

		if ( $trade == 'impo' ) {
			$this->model    = $this->getModelImpo();
			$this->modelAdo = $this->getModelImpoAdo();
			$columnValue    = $this->columnValueImpo;
		} else {
			$this->model    = $this->getModelExpo();
			$this->modelAdo = $this->getModelExpoAdo();
			$columnValue    = $this->columnValueExpo;
		}

		$rowField = Helpers::getPeriodColumnSql($this->period);

		//asigna los valores de filtro del indicador al modelo
		$this->setFiltersValues();
		$this->model->setAnio($this->year);
		$row = 'anio AS id';
		//si el periodo es diferente a anual debe cambiar el group by
		if ($this->period != 12 && !empty($this->year)) {
			$row = 'periodo AS id';
		}

		$arrRowField = [$row, $rowField];

		$this->modelAdo->setPivotRowFields(implode(',', $arrRowField));
		$this->modelAdo->setPivotTotalFields($columnValue);
		$this->modelAdo->setPivotGroupingFunction('SUM');

		$rsDeclaraciones = $this->modelAdo->pivotSearch($this->model);

		$arrData = $rsDeclaraciones['data'];

		//si el reporte no es anual y no encuentra informacion en algun periodo,
		//debe rrellenar con una fila en ceros
		$numberPeriods = (12 / $this->period);
		if (count($arrData) < $numberPeriods && $numberPeriods > 1) {

			$arrFinal = [];
			$rangePeriods  = Helpers::getPeriodRange($this->period);

			foreach ($rangePeriods as $number => $range) {

				$findId = false;
				foreach ($arrData as $row) {

					if (in_array($row['id'], $range)) {
						$findId = true;
						$arrFinal[$number] = $row;
					}
				}

				if ( ! $findId ) {

					$periodName = Helpers::getPeriodName($this->period, $number);
					$arrFinal[$number] = [
						'id'         => array_shift($range),
						'periodo'    => $this->year . ' ' . $periodName,
						'peso_neto'  => 0,
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

	public function executeAcumuladoContingente()
	{
		$arrFiltersValues = $this->arrFiltersValues;
		$trade            = ( empty($arrFiltersValues['intercambio']) ) ? 'impo' : $arrFiltersValues['intercambio'];

		$this->setTrade($trade);
		//$this->setRange('ini');

		if ( $trade == 'impo' ) {
			$this->model    = $this->getModelImpo();
			$this->modelAdo = $this->getModelImpoAdo();
			$columnValue    = $this->columnValueImpo;
		} else {
			$this->model    = $this->getModelExpo();
			$this->modelAdo = $this->getModelExpoAdo();
			$columnValue    = $this->columnValueExpo;
		}

		$rowField = Helpers::getPeriodColumnSql($this->period);

		//asigna los valores de filtro del indicador al modelo
		$this->setFiltersValues();
		$this->model->setAnio($this->year);
		$row = 'anio AS id';
		//si el periodo es diferente a anual debe cambiar el group by
		if ($this->period != 12 && !empty($this->year)) {
			$row = 'periodo AS id';
		}

		$filter = Helpers::findKeyInArrayMulti(
			$this->filtersConfig,
			'field',
			'id_pais'
		);
		$fieldName = ($trade == 'impo') ? $filter['field_impo'] : $filter['field_expo'] ;
		$arrRowField = [$row, $rowField];

		$this->modelAdo->setPivotRowFields(implode(',', $arrRowField));
		$this->modelAdo->setPivotTotalFields($columnValue);
		$this->modelAdo->setPivotColumnFields('CONCAT('.$fieldName.', "_", pais)');
		$this->modelAdo->setPivotGroupingFunction('SUM');

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

		$arrFieldAlias = ['id', 'periodo'];
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
					$arrTotals[$key] += (float)($value / 1000);
					$arrSeries[]      = $key;
				}
			}
		}

		foreach ($rsDeclaraciones['data'] as $row) {
			$arr = [];
			foreach ($row as $key => $value) {
				//suprimir la palabra " peso_neto" que le pone por defecto la clase pivottable a las columnas calculadas
				$index = str_replace(' '.$columnValue, '', $key);
				
				if (in_array($key, $arrSeries)) {
					//debe convertir el peso en toneladas metricas, por lo cual divide por 1000
					$value = (float)($value / 1000);
					if ($key == $columnValue) {
						$rate = ($arrTotals[$key] == 0) ? 0 : ( $value / $arrTotals[$key]) ;
					} else {
						$rate = ($row[$columnValue] == 0) ? 0 : ( $value / (float)($row[$columnValue] / 1000) ) ;
					}
					$arr[$index] = $value;
					//calcula la participacion y la agrega como columna
					$arr['rate_'.$index] = $rate * 100;
				} else {
					$arr[$index] = $value;
				}
			}
			$arrData[] = $arr;
		}

		$row        = current($arrData);
		$arrKeys    = array_keys($row);
		$arrColumns = [];
		$arrFields  = [];
		//var_dump(count($arrKeys), $arrKeys);
		$hidden     = (count($arrKeys) > 20 || count($arrKeys) < 7) ? true : false ;

		foreach ($arrKeys as $key) {
			$arrFields[] = ['name' => $key, 'type' => 'string'];
			if ($key == 'id') {
				# code...
			} elseif ($key == 'periodo') {
				$arrColumns[] = ['header' => Lang::get('indicador.columns_title.periodo'), 'dataIndex' => $key];
			} elseif ($key == $columnValue) {
				$arrColumns[] = ['header' => Lang::get('indicador.columns_title.peso_tm'), 'dataIndex' => $key, 'renderer' => 'numberFormat'];
			} elseif ($key == 'rate_'.$columnValue) {
				$arrColumns[] = ['header' => Lang::get('indicador.columns_title.participacion'), 'dataIndex' => $key, 'renderer' => 'rateFormat'];
			} elseif (substr($key,0,5) == 'rate_') {
				$arrColumns[] = ['header' => Lang::get('indicador.columns_title.participacion'), 'dataIndex' => $key, 'renderer' => 'rateFormat', 'hidden' => true];
			} else {
				$arr   = explode('_', $key);
				$title = (empty($arr[1])) ? $key : $arr[1] . ' (Tm)' ;

				$arrColumns[] = ['header' => $title, 'dataIndex' => $key, 'renderer' => 'numberFormat', 'hidden' => true];
			}
		}

		$result = [
			'success'        => true,
			'data'           => $arrData,
			'cumulativeData' => $arrTotals,
			'columns'        => $arrColumns,
			'total'          => $rsDeclaraciones['total'],
			'metaData'       => [
				'idProperty'    => 'id',
				'totalProperty' => 'total',
				'root'          => 'data',
				'successProperty' => 'success',
				'fields'        => $arrFields,
				'sortInfo'      => ['field' => 'id', 'direction' => 'ASC'],
			]
		];

		return $result;

	}
}

