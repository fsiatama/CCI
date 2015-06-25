<?php

require PATH_MODELS.'Entities/Declaraimp.php';
require PATH_MODELS.'Ado/DeclaraimpAdo.php';
require PATH_MODELS.'Entities/Declaraexp.php';
require PATH_MODELS.'Ado/DeclaraexpAdo.php';
require PATH_MODELS.'Ado/ComtradeTempAdo.php';
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
	protected $typeIndicator;
	protected $linesConfig;
	protected $scope;
	private $scale;
	private $chartType;
	private $start;
	private $limit;
	private $divisor = 1;
	private $pYAxisName;

	public function __construct(
		$rowIndicador,
		$filtersConfig,
		$year,
		$period,
		$scope,
		$scale = '1',
		$typeIndicator = '',
		$chartType = '',
		$start = 0,
		$limit = 0
	){
		$this->rowIndicador  = $rowIndicador;
		$this->filtersConfig = $filtersConfig;
		$this->year          = $year;
		$this->period        = $period;
		$this->scope         = $scope;
		$this->scale         = $scale;
		$this->chartType     = $chartType;
		$this->start         = (int)$start;
		$this->limit         = (int)$limit;

		extract($rowIndicador);

		$this->arrFiltersValues = Helpers::filterValuesToArray($indicador_filtros);
		$this->typeIndicator = ( empty($typeIndicator) ) ? $tipo_indicador_activador : $typeIndicator ;
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

	private function getColumnValueImpo()
	{
		return 'valorcif';
	}

	private function getColumnValueExpo()
	{
		return 'valorfob';
	}

	private function getColumnVolumeImpo()
	{
		return 'peso_neto';
	}

	private function getColumnVolumeExpo()
	{
		return 'peso_neto';
	}

	private function getFloatValue($value)
	{
		//$floatValue = ( $this->typeIndicator == 'precio' ) ? (float)$value : ( (float)$value / 1000 );
		return ( (float)$value / $this->divisor );
	}

	private function getColumnValueImpoTitle()
	{
		return ( $this->typeIndicator == 'precio' ) ? Lang::get('indicador.columns_title.valor_impo') : Lang::get('indicador.columns_title.peso_impo') ;
	}

	private function getColumnValueExpoTitle()
	{
		return ( $this->typeIndicator == 'precio' ) ? Lang::get('indicador.columns_title.valor_expo') : Lang::get('indicador.columns_title.peso_expo') ;
	}

	private function getColumnValueExpoAgroTitle()
	{
		return ( $this->typeIndicator == 'precio' ) ? Lang::get('indicador.columns_title.valor_expo_agricola') : Lang::get('indicador.columns_title.peso_expo_agricola') ;
	}
	
	private function getColumnBalanceTitle()
	{
		return ( $this->typeIndicator == 'precio' ) ? Lang::get('indicador.columns_title.valor_balanza') : Lang::get('indicador.columns_title.peso_balanza') ;
	}

	protected function setColumnValue()
	{
		if ($this->typeIndicator == 'precio') {
			$this->columnValueImpo = $this->getColumnValueImpo();
			$this->columnValueExpo = $this->getColumnValueExpo();
		} else {
			$this->columnValueImpo = $this->getColumnVolumeImpo();
			$this->columnValueExpo = $this->getColumnVolumeExpo();
		}


		if ($this->scale == '2') {
			//escala de miles
			//$this->xAxisname   = '';
			$this->pYAxisName  = ($this->typeIndicator != 'precio') ? Lang::get('indicador.reports.quantityThousands') : Lang::get('indicador.reports.priceThousands') ;
		} elseif ($this->scale == '3') {
			//escala de millones
			//$this->xAxisname   = '';
			$this->pYAxisName  = ($this->typeIndicator != 'precio') ? Lang::get('indicador.reports.quantityMillions') : Lang::get('indicador.reports.priceMillions') ;
		} else {
			//escala de unidades
			//$this->xAxisname   = '';
			$this->pYAxisName  = ($this->typeIndicator != 'precio') ? Lang::get('indicador.reports.quantityUnit') : Lang::get('indicador.reports.priceUnit') ;
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

				//var_dump($filter['field'], $arrFiltersValues, $fieldName, $methodName);
				/*if (!empty($filter['dateRange'])) {
					//si el filtro es un rango de fechas, debe unir los periodos que componen el rango

					$setFilterValue = false;

					$rangeYear = ($range == 'ini') ? 'desde_ini' : 'desde_fin';

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

				} else*/ if (!empty($filter['dateRange'])) {

					//si es un rango de fechas debe unir el valor inicial y el final


					//Apartir de abril de 2015 se genero un cambio para poder seleccionar periodos en años diferentes
					//pero los reportes construidos con anterioridad solo tienen el año

					$setFilterValue = false;

					if ( substr($filter['field'], -3) == $range || empty($range) ) {
						
						//var_dump(substr($filter['field'], -3), $trade, $range, $filterValue, $arrFiltersValues[$filter['dateRange'][0]]);
						
						$arrDate  = explode('-', $filterValue);
						$yearIni  = $arrDate[0];
						$monthIni = empty($arrDate[1]) ? '01' : $arrDate[1];

						$arrDate  = explode('-', $arrFiltersValues[$filter['dateRange'][0]]);
						$yearFin  = $arrDate[0];
						$monthFin = empty($arrDate[1]) ? '12' : $arrDate[1];

						$endDate  = new DateTime($yearFin . '-' . $monthFin . '-01');

						$filterValue = 'DATE("' . $yearIni . '-' . $monthIni . '-01") AND DATE("' . $endDate->format('Y-m-t') . '")';

						$methodName = $this->getColumnMethodName('set', 'fecha');

						call_user_func_array([$this->model, $methodName], compact('filterValue'));

					}


				} elseif ($filter['field'] == 'id_pais'  || $filter['field'] == 'mercado_id') {
					//si el filtro es el pais puede venir como parametro un pais o un mercado (grupo de paises)
					//Trae los paises configurados en el mercado seleccionado

					if (!empty($arrFiltersValues['mercado_id'])) {

						$arrTmp = Helpers::findKeyInArrayMulti($filtersConfig, 'field', 'id_pais');

						$result = $this->findCountriesByMarket($arrFiltersValues['mercado_id']);
						if (!$result['success']) {
							return $result;
						}
						$fieldName = ($trade == 'impo') ? $arrTmp['field_impo'] : $arrTmp['field_expo'] ;
						$methodName = $this->getColumnMethodName('set', $fieldName);
						
						$arr = explode(',', $result['data']);
						if (!empty($filterValue)) {
							$arr = array_merge(explode(',', $filterValue), $arr);
						}
						$filterValue = implode(',', $arr);
					}

				} elseif ($filter['field'] == 'sector_id') {

					//Trae los productos configurados en el sector seleccionado
					$result = $this->findProductsBySector($arrFiltersValues['sector_id']);
					if (!$result['success']) {
						return $result;
					}
					$products = $result['data'];
					$this->model->setId_posicion($products);
					
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
			'data'    => $row['sector_productos'],
			'name'    => $row['sector_nombre']
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
		if ($period != 12) {
			//$this->model->setAnio($year);
			$row = 'fecha AS id';
		} else {
			if (array_key_exists('anio_ini', $this->arrFiltersValues)) {
				$rowField = '"' . $this->arrFiltersValues['anio_ini'] . ' - ' . $this->arrFiltersValues['anio_fin'] . '" AS periodo';
			} elseif ( array_key_exists('desde_'.$range, $this->arrFiltersValues) ) {
				$rowField = '"' . $this->arrFiltersValues['desde_'.$range] . ' - ' . $this->arrFiltersValues['hasta_'.$range] . '" AS periodo';
			}
		}

		if ($range !== false) {
			$row = 'fecha AS id';
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

		$this->modelAdo->setPivotRowFields(implode(',', $arrRowField));
		$this->modelAdo->setPivotTotalFields($this->columnValueExpo);
		$this->modelAdo->setPivotGroupingFunction('SUM');

		$rsDeclaraexp = $this->modelAdo->pivotSearch($this->model);

		if (!$rsDeclaraexp['success']) {
			return $rsDeclaraexp;
		}

		$arrData    = [];

		$rsHigher          = $rsDeclaraexp['data'];
		$keyHigher         = 'valor_expo';
		$columnValueHigher = $this->columnValueExpo;
		$rsLower           = $rsDeclaraimp['data'];
		$keyLower          = 'valor_impo';
		$columnValueLower  = $this->columnValueImpo;
		
		if ( $rsDeclaraexp['total'] < $rsDeclaraimp['total'] ) {
			$rsHigher          = $rsDeclaraimp['data'];
			$rsLower           = $rsDeclaraexp['data'];
			$keyHigher         = 'valor_impo';
			$keyLower          = 'valor_expo';
			$columnValueHigher = $this->columnValueImpo;
			$columnValueLower  = $this->columnValueExpo;
		}

		//var_dump($rsHigher, $rsLower);

		foreach ($rsHigher as $rowHigher) {

			$valueLower = 0;

			foreach ($rsLower as $rowLower) {

				if ( $rowLower['periodo'] == $rowHigher['periodo'] ) {
					$valueLower = $this->getFloatValue( $rowLower[$columnValueLower] );
				}

			}

			$valueHigher = $this->getFloatValue( $rowHigher[$columnValueHigher] );

			$arrData[] = [
				'id'       => $rowHigher['id'],
				'periodo'  => $rowHigher['periodo'],
				$keyHigher => $valueHigher,
				$keyLower  => $valueLower,
			];
		}

		if (count($arrData) == 0) {
			return [
				'success' => false,
				'error'   => Lang::get('error.no_records_found')
			];
		}

		/* dado el ajuste de abril de 2015 para poder seleccionar periodos en años diferentes
		/* es imposible calcular los periodos vacios

			//si el reporte no es anual y no encuentra informacion en algun periodo,
			//debe rrellenar con una fila en ceros
			$numberPeriods = (12 / $period);
			if (count($arrData) < $numberPeriods) {

				$arrFinal = [];
				$rangePeriods  = Helpers::getPeriodRange($period);

				var_dump($rangePeriods);

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
		*/


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
				'valor_expo'    => $this->getColumnValueExpoTitle(),
				'valor_impo'    => $this->getColumnValueImpoTitle(),
				'valor_balanza' => $this->getColumnBalanceTitle()
			];

			$chartData = Helpers::jsonChart(
				$arrData,
				'periodo',
				$arrSeries,
				$this->chartType,
				'',
				$this->pYAxisName
			);

			$result = [
				'success'   => $result['success'],
				'data'      => $arrData,
				'total'     => $result['total'],
				'chartData' => $chartData,
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
				'valor_balanza' => $this->getColumnBalanceTitle()
			];

			$chartData = Helpers::jsonChart(
				$arrData,
				'periodo',
				$arrSeries,
				$this->chartType,
				'',
				Lang::get('indicador.reports.BCR')
			);

			$result = [
				'success'   => $result['success'],
				'data'      => $arrData,
				'total'     => $result['total'],
				'chartData' => $chartData,
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
		if (!$result['success']) {
			return $result;
		}

		//calcula el valor de la balanza simple para el primer conjunto de resultados
		$firstRangeData = [];
		foreach ($result['data'] as $key => $value) {

			//if ( in_array($value['id'], $arrRangeIni) ) {
				$valor_balanza    = ( $value['valor_expo'] - $value['valor_impo'] );
				$firstRangeData[] = array_merge($value, ['valor_balanza' => $valor_balanza]);
			//}

		}
		$this->setRange('fin');
		$result = $this->findBalanzaData();

		if (!$result['success']) {
			return $result;
		}

		//calcula el valor de la balanza simple para el segundo conjunto de resultados
		$lastRangeData = [];
		foreach ($result['data'] as $key => $value) {

			//if ( in_array($value['id'], $arrRangeFin) ) {
				$valor_balanza   = ( $value['valor_expo'] - $value['valor_impo'] );
				$lastRangeData[] = array_merge($value, ['valor_balanza' => $valor_balanza]);
			//}

		}

		//var_dump($firstRangeData, $lastRangeData);

		//une los conjuntos de resultados
		$arrKeys  = [];
		$arrData  = [];
		$rowIndex = 0;
		foreach ($firstRangeData as $keyFirst => $firstRange) {

			$lastPeriod     = 0;
			$lastValImpo    = 0;
			$lastValExpo    = 0;
			$lastValBalanza = 0;

			foreach ($lastRangeData as $keyLast => $lastRange) {

				if ($keyFirst == $keyLast) {
					//var_dump(array_merge($firstRange, $lastRange));
					$lastPeriod     = $lastRange['periodo'];
					$lastValImpo    = $lastRange['valor_impo'];
					$lastValExpo    = $lastRange['valor_expo'];
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

		$chartData = Helpers::jsonChart(
			$arrData,
			'rowIndex',
			$arrSeries,
			$this->chartType,
			'',
			$this->pYAxisName
		);

		$result = [
			'success'   => $result['success'],
			'data'      => $arrData,
			'total'     => $result['total'],
			'chartData' => $chartData,
		];

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

		$result = $this->findProductsBySector('sectorIdAgriculture');
		if (!$result['success']) {
			return $result;
		}
		$productsAgriculture     = $result['data'];
		$productsAgricultureName = $result['name'];
		$totalTitle = Lang::get('indicador.reports.sectorSelected');

		if (!array_key_exists('id_posicion', $arrFiltersValues) && !array_key_exists('sector_id', $arrFiltersValues)) {
			//si el reporte no tiene un producto seleccionado, debe seleccionar todo el sector agropecuario
			$this->model->setId_posicion($productsAgriculture);
			$totalTitle =  $productsAgricultureName;
		}

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

		if (array_key_exists('id_posicion', $arrFiltersValues) || array_key_exists('sector_id', $arrFiltersValues)) {
			//si el reporte tiene un producto seleccionado, debe sacar el total de todo el sector agropecuario
			$this->model->setId_posicion($productsAgriculture);
			$rsDeclaraexpTotal = $this->modelAdo->pivotSearch($this->model);
			if (!$rsDeclaraexpTotal['success']) {
				return $rsDeclaraexpTotal;
			}
			$arrDataTotal = $rsDeclaraexpTotal['data'];
		} else {
			$arrDataTotal = $rsDeclaraexp['data'];
		}


		foreach ($arrDataTotal as $keyExpo => $rowExpo) {
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
			if ($cumulativeRate <= 80 || $indexId === 1) {
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
		if ($othersValue > 0) {
			$indexId  += 1;
			$arrData[] = [
				'id'            => $indexId,
				'numero'        => '',
				'id_posicion'   => '*************************',
				'posicion'      => Lang::get('indicador.reports.others'),
				'valor_expo'    => $othersValue,
				'participacion' => $othersRate
			];
			$arrChartData[] = [
				'posicion'      => Lang::get('indicador.reports.others'),
				'valor_expo'    => $othersValue,
				'participacion' => $othersRate
			];
		}
		$indexId  += 1;
		//$totalTitle = $this->getColumnValueExpoTitle() . ' [ ' . $productsAgricultureName . ' ]';
		
		
		$arrData[] = [
			'id'            => $indexId,
			'numero'        => '',
			'id_posicion'   => '*************************',
			'posicion'      => $totalTitle,
			'valor_expo'    => $totalValue,
			'participacion' => 100
		];

		$arrSeries = [
			'valor_expo' => $totalTitle
		];

		$chartData = Helpers::jsonChart(
			$arrChartData,
			'posicion',
			$arrSeries,
			$this->chartType,
			'',
			$this->pYAxisName
		);

		$result = [
			'success'   => true,
			'data'      => $arrData,
			'total'     => count($arrData),
			'chartData' => $chartData,
		];

		return $result;
	}

	public function executeNumeroProductos()
	{
		$arrFiltersValues = $this->arrFiltersValues;

		$arrRangeIni = range($arrFiltersValues['desde_ini'], $arrFiltersValues['hasta_ini']);
		$arrRangeFin = range($arrFiltersValues['desde_fin'], $arrFiltersValues['hasta_fin']);
		$trade       = ( empty($arrFiltersValues['intercambio']) ) ? 'impo' : $arrFiltersValues['intercambio'];

		$this->setTrade($trade);
		$this->setRange('ini');

		if ($trade == 'impo') {
			$this->model    = $this->getModelImpo();
			$this->modelAdo = $this->getModelImpoAdo();
			$columnValue    = $this->columnValueImpo;
		} else {
			$this->model    = $this->getModelExpo();
			$this->modelAdo = $this->getModelExpoAdo();
			$columnValue    = $this->columnValueExpo;
		}

		//asigna los valores de filtro del indicador al modelo
		$this->setFiltersValues();
		if (!array_key_exists('id_posicion', $arrFiltersValues) && !array_key_exists('sector_id', $arrFiltersValues)) {
			//si el reporte no tiene un producto seleccionado, debe seleccionar todo el sector agropecuario
			$result = $this->findProductsBySector('sectorIdAgriculture');
			if (!$result['success']) {
				return $result;
			}
			$productsAgriculture = $result['data'];
			$this->model->setId_posicion($productsAgriculture);
		}

		//$columnValue = 'decl.id';
		$arrRowField = ['id', 'decl.id_posicion', 'posicion'];

		$this->modelAdo->setPivotRowFields(implode(',', $arrRowField));
		$this->modelAdo->setPivotTotalFields($columnValue);
		$this->modelAdo->setPivotGroupingFunction('SUM');
		$this->modelAdo->setPivotSortColumn($columnValue .' DESC');

		//busca los datos del primer rango de fechas
		$rsDeclaraciones = $this->modelAdo->pivotSearch($this->model);
		if (!$rsDeclaraciones['success']) {
			return $rsDeclaraciones;
		}

		$arrDataFirst = $rsDeclaraciones['data'];

		$this->setRange('fin');
		//asigna los valores de fecha del rango final al indicador
		$this->setFiltersValues();

		//busca los datos del segundo rango de fechas
		$rsDeclaraciones = $this->modelAdo->pivotSearch($this->model);
		if (!$rsDeclaraciones['success']) {
			return $rsDeclaraciones;
		}

		$arrDataLast = $rsDeclaraciones['data'];
		$newProducts = 0;
		$arrData     = [];

		foreach ($arrDataLast as $rowLast) {
			$rowFirst = Helpers::findKeyInArrayMulti(
				$arrDataFirst,
				'id_posicion',
				$rowLast['id_posicion']
			);

			$valueFirst = 0;

			if ($rowFirst === false) {
				$newProducts += 1;
			} else {
				$valueFirst = $rowFirst[$columnValue];
			}

			//$variation     = $rowLast[$columnValue] - $valueFirst;
			//$rateVariation = ( $rowLast[$columnValue] == 0 ) ? 0: ( $variation / $rowLast[$columnValue] );

			$arrData[] = [
				'id'            => $rowLast['id'],
				'id_posicion'   => $rowLast['id_posicion'],
				'posicion'      => $rowLast['posicion'],
				'valueFirst'    => $valueFirst,
				'valueLast'     => $rowLast[$columnValue],
				//'variation'     => $variation,
				//'rateVariation' => ( $rateVariation * 100 ),
			];
		}

		if (count($arrData) == 0) {
			return [
				'success' => false,
				'error'   => Lang::get('error.no_records_found')
			];
		}

		$arrSeries = [
			/*'id_posicion' => Lang::get('indicador.columns_title.numero_productos'),*/
			'valueLast'   => Lang::get('indicador.columns_title.posicion'),
		];

		$chartData = Helpers::jsonChart(
			$arrData,
			'id_posicion',
			$arrSeries,
			$this->chartType,
			'',
			$this->pYAxisName . ' ' . Lang::get('indicador.reports.finalRange')
		);

		$title = Lang::get('indicador.reports.total') . ' ' . $this->pYAxisName;

		$result = [
			'success'         => true,
			'data'            => $arrData,
			'total'           => count($arrData),
			'chartData'       => $chartData,
			'newProducts'     => $newProducts,
			'titleValueFirst' => $title . ' ' . Lang::get('indicador.reports.initialRange'),
			'titleValueLast'  => $title . ' ' . Lang::get('indicador.reports.finalRange')
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

		if (!array_key_exists('id_posicion', $arrFiltersValues) && !array_key_exists('sector_id', $arrFiltersValues)) {
			//si el reporte no tiene un producto seleccionado, debe seleccionar todo el sector agropecuario
			$result = $this->findProductsBySector('sectorIdAgriculture');
			if (!$result['success']) {
				return $result;
			}
			$productsAgriculture = $result['data'];
			$this->model->setId_posicion($productsAgriculture);
		}

		$columnValue = 'decl.id_posicion';

		$rowField = Helpers::getPeriodColumnSql($this->period);
		$row = 'fecha AS id';

		$arrRowField   = [$row, $rowField];

		$this->modelAdo->setPivotRowFields(implode(',', $arrRowField));
		$this->modelAdo->setPivotTotalFields($columnValue);
		$this->modelAdo->setPivotGroupingFunction('COUNT_DISTINCT');

		//busca los datos del primer rango de fechas
		$rsDeclaraciones = $this->modelAdo->pivotSearch($this->model);
		if (!$rsDeclaraciones['success']) {
			return $rsDeclaraciones;
		}

		$arrDataFirst = $rsDeclaraciones['data'];

		$this->setRange('fin');
		//asigna los valores de filtro del indicador al modelo
		$this->setFiltersValues();

		//busca los datos del primer rango de fechas en exportaciones
		$rsDeclaraimp = $this->modelAdo->pivotSearch($this->model);
		if (!$rsDeclaraimp['success']) {
			return $rsDeclaraimp;
		}

		$arrDataLast = $rsDeclaraimp['data'];

		//une los conjuntos de resultados
		$arrKeys  = [];
		$arrData  = [];
		$rowIndex = 0;
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
				'variation'   => ( $variation * 100 )
			];

		}

		foreach ($arrDataLast as $keyLast => $rowLast) {
			if (!in_array($keyLast, $arrKeys)) {
				$rowIndex += 1;
				$arrData[] = [
					'id'          => $rowLast['id'],
					'rowIndex'    => 'Q'.$rowIndex,
					'periodFirst' => '',
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

		$chartData = Helpers::jsonChart(
			$arrData,
			'rowIndex',
			$arrSeries,
			$this->chartType,
			'',
			Lang::get('indicador.columns_title.numero_productos')
		);

		$result = [
			'success'   => true,
			'data'      => $arrData,
			'total'     => count($arrData),
			'chartData' => $chartData,
		];

		return $result;

	}

	public function executeNumeroPaisesDestino()
	{
		$arrFiltersValues = $this->arrFiltersValues;

		if (empty($arrFiltersValues['desde_fin']) || empty($arrFiltersValues['hasta_fin'])) {
			return [
				'success' => false,
				'error'   => 'Por favor edite este reporte para adicionar un periodo inicial y uno final'
			];
		}

		$arrRangeIni = range($arrFiltersValues['desde_ini'], $arrFiltersValues['hasta_ini']);
		$arrRangeFin = range($arrFiltersValues['desde_fin'], $arrFiltersValues['hasta_fin']);
		$trade       = ( empty($arrFiltersValues['intercambio']) ) ? 'impo' : $arrFiltersValues['intercambio'];

		$this->setTrade('expo');
		$this->setRange('ini');

		$this->model      = $this->getModelExpo();
		$this->modelAdo   = $this->getModelExpoAdo();

		//asigna los valores de filtro del indicador al modelo
		$this->setFiltersValues();
		if (!array_key_exists('id_posicion', $arrFiltersValues) && !array_key_exists('sector_id', $arrFiltersValues)) {
			//si el reporte no tiene un producto seleccionado, debe seleccionar todo el sector agropecuario
			$result = $this->findProductsBySector('sectorIdAgriculture');
			if (!$result['success']) {
				return $result;
			}
			$productsAgriculture = $result['data'];
			$this->model->setId_posicion($productsAgriculture);
		}

		$columnValue = $this->getColumnValueExpo();
		$arrRowField = ['id', 'decl.id_paisdestino', 'pais'];

		$this->modelAdo->setPivotRowFields(implode(',', $arrRowField));
		$this->modelAdo->setPivotTotalFields($columnValue);
		$this->modelAdo->setPivotGroupingFunction('SUM');
		$this->modelAdo->setPivotSortColumn($columnValue . ' DESC');

		//busca los datos del primer rango de fechas
		$rsDeclaraciones = $this->modelAdo->pivotSearch($this->model);
		if (!$rsDeclaraciones['success']) {
			return $rsDeclaraciones;
		}

		$arrDataFirst = $rsDeclaraciones['data'];

		$this->setRange('fin');
		//asigna los valores de fecha del rango final al indicador
		$this->setFiltersValues();

		//busca los datos del segundo rango de fechas
		$rsDeclaraciones = $this->modelAdo->pivotSearch($this->model);
		if (!$rsDeclaraciones['success']) {
			return $rsDeclaraciones;
		}

		$arrDataLast = $rsDeclaraciones['data'];
		$newProducts = 0;
		$arrData     = [];

		foreach ($arrDataLast as $rowLast) {
			$rowFirst = Helpers::findKeyInArrayMulti(
				$arrDataFirst,
				'id_paisdestino',
				$rowLast['id_paisdestino']
			);

			$valueFirst = 0;

			if ($rowFirst === false) {
				$newProducts += 1;
			} else {
				$valueFirst = $rowFirst[$columnValue];
			}

			//$variation     = $rowLast[$columnValue] - $valueFirst;
			//$rateVariation = ( $rowLast[$columnValue] == 0 ) ? 0: ( $variation / $rowLast[$columnValue] );

			$arrData[] = [
				'id'             => $rowLast['id'],
				'id_paisdestino' => $rowLast['id_paisdestino'],
				'pais'           => $rowLast['pais'],
				'valueFirst'     => $valueFirst,
				'valueLast'      => $rowLast[$columnValue],
				//'variation'      => $variation,
				//'rateVariation'  => ( $rateVariation * 100 ),
			];
		}

		if (count($arrData) == 0) {
			return [
				'success' => false,
				'error'   => Lang::get('error.no_records_found')
			];
		}

		$arrSeries = [
			/*'id_posicion' => Lang::get('indicador.columns_title.numero_productos'),*/
			'valueLast'   => Lang::get('indicador.columns_title.pais_destino'),
		];

		$chartData = Helpers::jsonChart(
			$arrData,
			'pais',
			$arrSeries,
			$this->chartType,
			'',
			$this->pYAxisName . ' ' . Lang::get('indicador.reports.finalRange')
		);

		$title = Lang::get('indicador.reports.total') . ' ' . $this->pYAxisName;

		$result = [
			'success'         => true,
			'data'            => $arrData,
			'total'           => count($arrData),
			'chartData'       => $chartData,
			'newProducts'     => $newProducts,
			'titleValueFirst' => $title . ' ' . Lang::get('indicador.reports.initialRange'),
			'titleValueLast'  => $title . ' ' . Lang::get('indicador.reports.finalRange')
		];

		return $result;
	}

	/*public function executeNumeroPaisesDestino()
	{
		$arrFiltersValues = $this->arrFiltersValues;
		$this->setTrade('expo');
		$this->setRange('ini');

		$this->model      = $this->getModelExpo();
		$this->modelAdo   = $this->getModelExpoAdo();
		//asigna los valores de filtro del indicador al modelo
		$this->setFiltersValues();

		//var_dump($arrFiltersValues, $this->model);

		if (!array_key_exists('id_posicion', $arrFiltersValues) && !array_key_exists('sector_id', $arrFiltersValues)) {
			//si el reporte no tiene un producto seleccionado, debe seleccionar todo el sector agropecuario
			$result = $this->findProductsBySector('sectorIdAgriculture');
			if (!$result['success']) {
				return $result;
			}
			$productsAgriculture = $result['data'];
			$this->model->setId_posicion($productsAgriculture);
		}

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
			$value       = $this->getFloatValue( $rowExpo[$this->columnValueExpo] );
			$totalValue += $value;
		}

		$arrData           = [];

		foreach ($rsDeclaraexp['data'] as $keyExpo => $rowExpo) {

			$value = $this->getFloatValue( $rowExpo[$this->columnValueExpo] );

			$rate = round( ( $value / $totalValue ) * 100 , 2 );
			$arrData[] = [
				'id'            => $keyExpo,
				'pais'          => $rowExpo['pais'],
				'valor_expo'    => $value,
				'participacion' => $rate
			];
		}

		$result = [
			'success'         => true,
			'data'            => $arrData,
			'total'           => count($arrData)
		];
		return $result;

	}*/

	public function executeIHH()
	{
		$arrFiltersValues = $this->arrFiltersValues;
		$arrYear          = range($arrFiltersValues['anio_ini'], $arrFiltersValues['anio_fin']);
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

		$arrRowField   = ['id', 'decl.id_posicion'];
		$arrFieldAlias = ['id', 'id_posicion', $columnValue];

		$this->modelAdo->setPivotRowFields(implode(',', $arrRowField));
		$this->modelAdo->setPivotColumnFields('anio');
		$this->modelAdo->setPivotColumnValues($arrYear);
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

					$val   = (float)$value;
					$total = (float)$arrTotals[$key];
					$rate  = ( $val / $total ) * 100;

					//var_dump($key, $value, $arrTotals[$key], ( $value / $arrTotals[$key] ),  (( $value / $arrTotals[$key] ) * ( $value / $arrTotals[$key] )) );

					//en este caso el indice es el año, debe suprimir el nombre de la column de totales que le pone por defecto la clase pivottable
					$index = str_replace(' '.$columnValue, '', $key);

					$IHH = ( pow($rate, 2) );
					//var_dump(compact('val', 'total', 'rate', 'IHH'));

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
				'IHH'     => round($value, 2)
			];
		}

		$arrSeries = [
			'IHH' => Lang::get('indicador.columns_title.IHH')
		];

		$chartData = Helpers::jsonChart(
			$arrData,
			'periodo',
			$arrSeries,
			$this->chartType,
			'',
			Lang::get('indicador.columns_title.IHH')
		);

		$result = [
			'success'   => true,
			'data'      => $arrData,
			'total'     => count($arrData),
			'chartData' => $chartData,
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
		if ($this->period != 12) {
			//$this->model->setAnio($year);
			$row = 'fecha AS id';
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

		if (!$result['success']) {
			return $result;
		}

		$arrDataEnergeticMiningSector = $result['data'];

		//busca los datos del sector agricola
		$this->model->setId_posicion($productsAgriculture);
		$result = $this->modelAdo->pivotSearch($this->model);

		if (!$result['success']) {
			return $result;
		}

		$arrDataProductsAgriculture = $result['data'];

		//busca el total de las exportaciones
		$this->model->setId_posicion('');
		$result  = $this->modelAdo->pivotSearch($this->model);

		if (!$result['success']) {
			return $result;
		}

		$arrData = [];
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

			$totalProductsAgriculture   = ($rowProductsAgriculture   !== false) ? $this->getFloatValue( $rowProductsAgriculture[$columnValue] )   : 0 ;
			$totalEnergeticMiningSector = ($rowEnergeticMiningSector !== false) ? $this->getFloatValue( $rowEnergeticMiningSector[$columnValue] ) : 0 ;

			$total = ( $this->getFloatValue( $rowTotal[$columnValue] ) ) - $totalEnergeticMiningSector;
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
			'valor_expo_agricola' => $this->getColumnValueExpoAgroTitle(),
			'valor_expo'          => $this->getColumnValueExpoTitle(),
		];

		$chartData = Helpers::jsonChart(
			$arrData,
			'periodo',
			$arrSeries,
			$this->chartType,
			'',
			$this->pYAxisName
		);

		$result = [
			'success'   => true,
			'data'      => $arrData,
			'total'     => count($arrData),
			'chartData' => $chartData,
		];

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
		if ($this->period != 12) {
			//$this->model->setAnio($this->year);
			$row = 'fecha AS id';
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

		if (!$result['success']) {
			return $result;
		}

		$arrProductsTraditional = $result['data'];

		//busca los datos del sector agricola
		$this->model->setId_posicion($productsAgriculture);
		$result = $this->modelAdo->pivotSearch($this->model);

		if (!$result['success']) {
			return $result;
		}

		$arrData      = [];
		$arrDataTotal = $result['data'];

		foreach ($arrDataTotal as $rowTotal) {

			$rowProductsTraditional = Helpers::findKeyInArrayMulti(
				$arrProductsTraditional,
				'periodo',
				$rowTotal['periodo']
			);

			$value = $this->getFloatValue( $rowTotal[$columnValue] );


			$totalProductsTraditional    = ( $rowProductsTraditional !== false ) ? $this->getFloatValue( $rowProductsTraditional[$columnValue] ) : 0 ;
			$totalProductsNonTraditional = ( $value ) - $totalProductsTraditional;

			$total = ($rowTotal[$columnValue] == 0) ? 1 : $value ;
			$rate  = round( ($totalProductsNonTraditional / $total ) * 100 , 2 );

			$arrData[] = [
				'id'                  => $rowTotal['id'],
				'periodo'             => $rowTotal['periodo'],
				'valor_expo_no_tradi' => $totalProductsNonTraditional,
				'valor_expo'          => $total,
				'participacion'       => $rate
			];
		}

		$titleAgroNT = ( $this->typeIndicator == 'precio' ) ? Lang::get('indicador.columns_title.valor_expo_no_tradi') : Lang::get('indicador.columns_title.peso_expo_no_tradi') ;

		$arrSeries = [
			'valor_expo_no_tradi' => $titleAgroNT,
			'valor_expo'          => $this->getColumnValueExpoAgroTitle(),
		];

		$chartData = Helpers::jsonChart(
			$arrData,
			'periodo',
			$arrSeries,
			$this->chartType,
			'',
			$this->pYAxisName
		);

		$result = [
			'success'   => true,
			'data'      => $arrData,
			'total'     => count($arrData),
			'chartData' => $chartData,
		];

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
		if ($this->period != 12) {
			//$this->model->setAnio($this->year);
			$row = 'fecha AS id';
		}

		$rowField = Helpers::getPeriodColumnSql($this->period);

		$arrRowField   = [$row, $rowField];

		$this->modelAdo->setPivotRowFields(implode(',', $arrRowField));
		$this->modelAdo->setPivotTotalFields($columnValue);
		$this->modelAdo->setPivotGroupingFunction('SUM');

		//busca los datos de los productos seleccionados
		$result = $this->modelAdo->pivotSearch($this->model);

		if (!$result['success']) {
			return $result;
		}

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

		if (!$result['success']) {
			return $result;
		}

		$arrData = [];

		$arrDataTotal = $result['data'];

		foreach ($arrDataTotal as $rowTotal) {

			$rowProduct = Helpers::findKeyInArrayMulti(
				$arrProduct,
				'periodo',
				$rowTotal['periodo']
			);

			$totalProduct = ($rowProduct !== false) ? $this->getFloatValue( $rowProduct[$columnValue] ) : 0 ;

			$total = ($rowTotal[$columnValue] == 0) ? 1 : $this->getFloatValue( $rowTotal[$columnValue] ) ;
			$rate  = round( ($totalProduct / $total ) * 100 , 2 );

			$arrData[] = [
				'id'                => $rowTotal['id'],
				'periodo'           => $rowTotal['periodo'],
				'valor_expo_sector' => $totalProduct,
				'valor_expo'        => $total,
				'participacion'     => $rate
			];
		}


		$titleSector = Lang::get('indicador.reports.total') . ' ' . $this->pYAxisName . ' ' . Lang::get('indicador.reports.sectorSelected');
		$titleTotal  = Lang::get('indicador.reports.total') . ' ' . $this->pYAxisName . ' ' . Lang::get('indicador.reports.sectorAgriculture');

		//$titleSector = ( $this->typeIndicator == 'precio' ) ? Lang::get('indicador.columns_title.valor_expo_sector') : Lang::get('indicador.columns_title.peso_expo_sector') ;

		$arrSeries = [
			'valor_expo_sector' => $titleSector,
			'valor_expo'        => $this->getColumnValueExpoAgroTitle(),
		];

		$chartData = Helpers::jsonChart(
			$arrData,
			'periodo',
			$arrSeries,
			$this->chartType,
			'',
			$this->pYAxisName
		);

		$result = [
			'success'     => true,
			'data'        => $arrData,
			'total'       => count($arrData),
			'chartData'   => $chartData,
			'titleSector' => $titleSector,
			'titleTotal'  => $titleTotal
		];

		return $result;
	}

	public function executeCrecimientoExportadores()
	{
		$arrFiltersValues = $this->arrFiltersValues;

		$arrRangeIni = range($arrFiltersValues['desde_ini'], $arrFiltersValues['hasta_ini']);
		$arrRangeFin = range($arrFiltersValues['desde_fin'], $arrFiltersValues['hasta_fin']);

		$this->setTrade('expo');
		$this->setRange('ini');

		$this->model      = $this->getModelExpo();
		$this->modelAdo   = $this->getModelExpoAdo();
		
		//asigna los valores de filtro del indicador al modelo
		$this->setFiltersValues();
		if (!array_key_exists('id_posicion', $arrFiltersValues) && !array_key_exists('sector_id', $arrFiltersValues)) {
			//si el reporte no tiene un producto seleccionado, debe seleccionar todo el sector agropecuario
			$result = $this->findProductsBySector('sectorIdAgriculture');
			if (!$result['success']) {
				return $result;
			}
			$productsAgriculture = $result['data'];
			$this->model->setId_posicion($productsAgriculture);
		}

		$columnValue = $this->getColumnValueExpo();
		$arrRowField = ['id', 'decl.id_empresa', 'empresa'];

		$this->modelAdo->setPivotRowFields(implode(',', $arrRowField));
		$this->modelAdo->setPivotTotalFields($columnValue);
		$this->modelAdo->setPivotGroupingFunction('SUM');
		$this->modelAdo->setPivotSortColumn($columnValue . ' DESC');

		//busca los datos del primer rango de fechas
		$rsDeclaraciones = $this->modelAdo->pivotSearch($this->model);
		if (!$rsDeclaraciones['success']) {
			return $rsDeclaraciones;
		}

		$arrDataFirst = $rsDeclaraciones['data'];

		$this->setRange('fin');
		//asigna los valores de fecha del rango final al indicador
		$this->setFiltersValues();

		//busca los datos del segundo rango de fechas
		$rsDeclaraciones = $this->modelAdo->pivotSearch($this->model);
		if (!$rsDeclaraciones['success']) {
			return $rsDeclaraciones;
		}

		$arrDataLast = $rsDeclaraciones['data'];
		$newProducts = 0;
		$arrData     = [];

		foreach ($arrDataLast as $rowLast) {
			$rowFirst = Helpers::findKeyInArrayMulti(
				$arrDataFirst,
				'id_empresa',
				$rowLast['id_empresa']
			);

			$valueFirst = 0;

			if ($rowFirst === false) {
				$newProducts += 1;
			} else {
				$valueFirst = $rowFirst[$columnValue];
			}

			//$variation     = $rowLast[$columnValue] - $valueFirst;
			//$rateVariation = ( $rowLast[$columnValue] == 0 ) ? 0: ( $variation / $rowLast[$columnValue] );

			$arrData[] = [
				'id'            => $rowLast['id'],
				'id_empresa'    => $rowLast['id_empresa'],
				'empresa'       => $rowLast['empresa'],
				'valueFirst'    => $valueFirst,
				'valueLast'     => $rowLast[$columnValue],
				//'variation'     => $variation,
				//'rateVariation' => ( $rateVariation * 100 ),
			];
		}

		if (count($arrData) == 0) {
			return [
				'success' => false,
				'error'   => Lang::get('error.no_records_found')
			];
		}

		$arrSeries = [
			'valueLast' => Lang::get('indicador.columns_title.id_empresa'),
		];

		$chartData = Helpers::jsonChart(
			$arrData,
			'id_empresa',
			$arrSeries,
			$this->chartType,
			'',
			$this->pYAxisName . ' ' . Lang::get('indicador.reports.finalRange')
		);

		$title = Lang::get('indicador.reports.total') . ' ' . $this->pYAxisName;

		$result = [
			'success'         => true,
			'data'            => $arrData,
			'total'           => count($arrData),
			'chartData'       => $chartData,
			'newProducts'     => $newProducts,
			'titleValueFirst' => $title . ' ' . Lang::get('indicador.reports.initialRange'),
			'titleValueLast'  => $title . ' ' . Lang::get('indicador.reports.finalRange')
		];

		return $result;
	}

	/*public function executeCrecimientoExportadores()
	{
		$arrFiltersValues = $this->arrFiltersValues;
		$this->setTrade('expo');
		$this->setRange('ini');

		$this->model      = $this->getModelExpo();
		$this->modelAdo   = $this->getModelExpoAdo();
		$this->setFiltersValues();

		$columnValue = 'id_empresa';

		if (!array_key_exists('id_posicion', $arrFiltersValues) && !array_key_exists('sector_id', $arrFiltersValues)) {
			//si el reporte no tiene un producto seleccionado, debe seleccionar todo el sector agropecuario
			$result = $this->findProductsBySector('sectorIdAgriculture');
			if (!$result['success']) {
				return $result;
			}
			$productsAgriculture = $result['data'];
			$this->model->setId_posicion($productsAgriculture);
		}

		$rowField = Helpers::getPeriodColumnSql($this->period);
		$row = 'fecha AS id';

		$arrRowField   = [$row, $rowField];

		$this->modelAdo->setPivotRowFields(implode(',', $arrRowField));
		$this->modelAdo->setPivotTotalFields($columnValue);
		$this->modelAdo->setPivotGroupingFunction('COUNT_DISTINCT');

		//busca los datos del primer rango de fechas
		$result = $this->modelAdo->pivotSearch($this->model);

		if (!$result['success']) {
			return $result;
		}
		
		$firstRangeData = $result['data'];

		//busca los datos del segundo rango de fechas
		$this->setRange('fin');
		$this->setFiltersValues();

		$result = $this->modelAdo->pivotSearch($this->model);

		if (!$result['success']) {
			return $result;
		}

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

		$chartData = Helpers::jsonChart(
			$arrData,
			'rowIndex',
			$arrSeries,
			$this->chartType,
			'',
			Lang::get('indicador.columns_title.numero_empresas_expo')
		);

		$result = [
			'success'   => true,
			'data'      => $arrData,
			'total'     => count($arrData),
			'chartData' => $chartData,
		];

		return $result;
	}*/

	public function executePromedioPonderadoArancel()
	{
		$arrFiltersValues = $this->arrFiltersValues;
		$this->setTrade('impo');
		$this->setRange('ini');

		$this->model      = $this->getModelImpo();
		$this->modelAdo   = $this->getModelImpoAdo();
		$columnValue      = $this->columnValueImpo;
		$this->setFiltersValues();


		if (!array_key_exists('id_posicion', $arrFiltersValues) && !array_key_exists('sector_id', $arrFiltersValues)) {
			//si el reporte no tiene un producto seleccionado, debe seleccionar todo el sector agropecuario
			$result = $this->findProductsBySector('sectorIdAgriculture');
			if (!$result['success']) {
				return $result;
			}
			$productsAgriculture = $result['data'];
			$this->model->setId_posicion($productsAgriculture);
		}

		$this->modelAdo->setSortColumn($columnValue . ' DESC');
		$result = $this->modelAdo->inSearch($this->model);

		if (!$result['success']) {
			return $result;
		}

		$totalValue = 0;
		$arrData    = [];
		$average    = 0;
		foreach ($result['data'] as $keyImpo => $rowImpo) {
			$totalValue += (float)$rowImpo[$columnValue];
		}

		foreach ($result['data'] as $keyImpo => $rowImpo) {
			
			$rate     = ( (float)$rowImpo[$columnValue] / $totalValue );
			$weighing = ( (float)$rowImpo[$columnValue] * $rate );
			$average += $weighing;

			if ($keyImpo >= $this->start && $keyImpo < ( $this->start + $this->limit )) {
				$arrData[] = [
					'pais'               => $rowImpo['pais'],
					'id_posicion'        => $rowImpo['id_posicion'],
					'posicion'           => $rowImpo['posicion'],
					'valor_impo'         => $this->getFloatValue( $rowImpo[$columnValue] ),
					'valorarancel'       => $this->getFloatValue( $rowImpo['valorarancel'] ),
					'porcentaje_arancel' => $rowImpo['porcentaje_arancel'],
					'participacion'      => ( $rate * 100 )
				];
			}
		}

		$title = $this->getColumnValueImpoTitle() ;

		$result = [
			'success'    => true,
			'data'       => $arrData,
			'total'      => $result['total'],
			'average'    => $average,
			'titleValue' => $title
		];

		return $result;

		var_dump($arrData, $average);




		$columnValue1 = 'valorarancel';
		$columnValue2 = 'arancel_pagado';
		$columnValue3 = 'valor_pesos';


		$rowField = Helpers::getPeriodColumnSql($this->period);
		$row = 'fecha AS id';

		$arrRowField = ['id', 'decl.id_posicion', 'posicion', 'pais'];

		$this->modelAdo->setPivotRowFields(implode(',', $arrRowField));
		$this->modelAdo->setPivotTotalFields([$columnValue1, $columnValue2/*, $columnValue3*/]);
		$this->modelAdo->setPivotGroupingFunction('SUM');
		$this->modelAdo->setPivotSortColumn($columnValue1 . ' DESC');

		$result = $this->modelAdo->pivotSearch($this->model);

		if (!$result['success']) {
			return $result;
		}


		foreach ($result['data'] as $keyImpo => $rowImpo) {
			$totalValue += (float)$rowImpo[$columnValue1];
		}

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
				//'valor_impo'     => (float)$rowImpo[$columnValue3],
				'participacion'  => ( $rate * 100 )
			];
		}
		$arrSeries = [
			'valorarancel' => Lang::get('indicador.columns_title.participacion_arancel')
		];

		$chartData = Helpers::jsonChart(
			$arrData,
			'posicion',
			$arrSeries,
			$this->chartType,
			'',
			$this->pYAxisName
		);

		$result = [
			'success'   => true,
			'data'      => $arrData,
			'total'     => count($arrData),
			'chartData' => $chartData,
			'average'   => $average,
		];

		return $result;
	}

	public function executeComtradeRelacionCrecimientoExpoColombiaImpoPais()
	{

		$arrFiltersValues = $this->arrFiltersValues;
		$this->setRange('ini');
		$yearFirst = empty($arrFiltersValues['anio_ini']) ? '' : $arrFiltersValues['anio_ini'];
		$yearLast  = empty($arrFiltersValues['anio_fin']) ? '' : $arrFiltersValues['anio_fin'];
		$rangeYear = range($yearFirst, $yearLast);

		$id_pais_destino = empty($arrFiltersValues['id_pais_destino']) ? '' : $arrFiltersValues['id_pais_destino'];
		$id_subpartida   = empty($arrFiltersValues['id_subpartida']) ? '' : $arrFiltersValues['id_subpartida'];

		if (
			empty($yearFirst) ||
			empty($yearLast) ||
			empty($id_pais_destino) ||
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
			'rg'   => '1', //impo
			'ps'   => implode(',', $rangeYear),
			'r'    => $id_pais_destino,
			'p'    => '0,' . $colombiaIdComtrade, //0 = world; 
			'cc'   => $id_subpartida,
		];

		$cache  = phpFastCache();
		$url    = $baseUrl . '?' . http_build_query($parameters);
		$key    = md5($url);
		$result = $cache->get($key);

		if (is_null($result)) {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_TIMEOUT, 30);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			$result = json_decode(curl_exec($ch), true);
			curl_close($ch);
			$cache->set($key, $result, 3600*24*30);
		}


		$validation = ( empty($result['validation']['status']['name']) ) ? 'ok' : $result['validation']['status']['name'] ;
		$message = ( empty($result['validation']['message']) ) ? '' : $result['validation']['message'] ;

		if ( $validation != 'ok' && ! empty( $message ) ) {
			$result = [
				'success' => false,
				'error'   => $message
			];
			return $result;
		}

		if (empty($result['dataset'])) {
			$result = [
				'success' => false,
				'error'   => Lang::get('error.no_records_found_comtrade')
			];
			return $result;
		}

		$arrDataColombia    = [];
		$arrDataWorld       = [];
		$arrData            = [];
		$columnValue        = 'TradeValue';

		usort($result['dataset'], Helpers::arraySortByValue('yr'));

		foreach ($result['dataset'] as $key => $row) {

			$totalValue = ( (float)$row[$columnValue] / $this->divisor );
			$naturalLog = Helpers::naturalLogarithm($totalValue);
			
			if ($row['ptCode'] == $colombiaIdComtrade) { //datos de importaciones acumuladas de colombia
				$arrDataColombia[] = [
					'id'         => $row['yr'],
					'periodo'    => $row['period'],
					'valor_impo' => $totalValue,
					'naturalLog' => $naturalLog,
				];

			} else { //datos de importaciones acumuladas del mundo
				$arrDataWorld[] = [
					'id'         => $row['yr'],
					'periodo'    => $row['period'],
					'valor_impo' => $totalValue,
					'naturalLog' => $naturalLog,
				];
			}
		}

		$arrNaturalLog      = Helpers::arrayColumn( $arrDataColombia, 'naturalLog');
		$growthRateColombia = Helpers::linearRegression($arrNaturalLog);

		$arrNaturalLog      = Helpers::arrayColumn( $arrDataWorld, 'naturalLog');
		$growthRateWorld    = Helpers::linearRegression($arrNaturalLog);

		foreach ($arrDataWorld as $key => $rowImpo) {
			
			$rowImpoColombia = Helpers::findKeyInArrayMulti(
				$arrDataColombia,
				'periodo',
				$rowImpo['periodo']
			);

			$totalImpoColombia = ($rowImpoColombia !== false) ? $rowImpoColombia['valor_impo'] : 0 ;
			
			$arrData[] = [
				'id'                  => $rowImpo['id'],
				'periodo'             => $rowImpo['periodo'],
				'valor_impo_colombia' => $totalImpoColombia,
				'valor_impo_world'    => $rowImpo['valor_impo'],
			];
		}

		if (count($arrData) == 0) {
			return [
				'success' => false,
				'error'   => Lang::get('error.no_records_found')
			];
		}

		$result = [
			'success'            => true,
			'data'               => $arrData,
			'growthRateColombia' => ($growthRateColombia['m'] * 100),
			'growthRateWorld'    => ($growthRateWorld['m'] * 100),
			'total'              => count($arrData)
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
		if (!array_key_exists('id_posicion', $arrFiltersValues) && !array_key_exists('sector_id', $arrFiltersValues)) {
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

			$valueLastTotal              = $this->getFloatValue( $rowTotal[$columnValue] );
			$valueLastAgriculture        = ( $rowProductsAgriculture   !== false ) ? $this->getFloatValue( $rowProductsAgriculture[$columnValue] ) : 0 ;
			$totalEnergeticMiningSector  = ( $rowEnergeticMiningSector !== false ) ? $this->getFloatValue( $rowEnergeticMiningSector[$columnValue] ) : 0 ;
			$valueLastTotalWithoutMining = ( $valueLastTotal - $totalEnergeticMiningSector );
			$valueLastTotalWithoutMining = ( $valueLastTotalWithoutMining == 0 ) ? 1 : $valueLastTotalWithoutMining ;

			$arrData[] = [
				'id'                    => $rowTotal['id'],
				'periodo'               => $rowTotal['periodo'],
				'valor_expo_agricola'   => $valueLastAgriculture,
				'valor_expo_sin_minero' => $valueLastTotalWithoutMining,
				'valor_expo'            => $valueLastTotal,
			];
		}

		$arrY                  = Helpers::arrayColumn( $arrData, 'valor_expo_agricola');
		$arrNaturalLog         = array_map('Helpers::naturalLogarithm', $arrY);
		$growthRateAgriculture = Helpers::linearRegression($arrNaturalLog);

		$arrY                  = Helpers::arrayColumn( $arrData, 'valor_expo_sin_minero');
		$arrNaturalLog         = array_map('Helpers::naturalLogarithm', $arrY);
		$growthRateExpoWithoutMining = Helpers::linearRegression($arrNaturalLog);

		$arrY                  = Helpers::arrayColumn( $arrData, 'valor_expo');
		$arrNaturalLog         = array_map('Helpers::naturalLogarithm', $arrY);
		$growthRateExpo        = Helpers::linearRegression($arrNaturalLog);

		$titleMiningSector = ( $this->typeIndicator == 'precio' ) ? Lang::get('indicador.columns_title.valor_expo_sin_minero') : Lang::get('indicador.columns_title.peso_expo_sin_minero') ;

		$arrSeries = [
			'valor_expo_agricola'   => $this->getColumnValueExpoAgroTitle(),
			'valor_expo_sin_minero' => $titleMiningSector,
			'valor_expo'            => $this->getColumnValueExpoTitle(),
		];

		$chartData = Helpers::jsonChart(
			$arrData,
			'periodo',
			$arrSeries,
			$this->chartType,
			'',
			$this->pYAxisName
		);

		$result = [
			'success'                     => true,
			'data'                        => $arrData,
			'growthRateAgriculture'       => ( $growthRateAgriculture['m'] * 100 ),
			'growthRateExpo'              => ( $growthRateExpo['m'] * 100 ),
			'growthRateExpoWithoutMining' => ( $growthRateExpoWithoutMining['m'] * 100 ),
			'chartData'                   => $chartData,
			'total'                       => count($arrData)
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
			$pib_nacional = ($rowPib['pib_nacional'] == 0) ? 0 : (float)( $rowPib['pib_nacional'] * 100000000 );
			$pib_nacional = $this->getFloatValue( $pib_nacional );
			$totalValue   = $this->getFloatValue( $row[$columnValue] );

			$rate = ($pib_nacional == 0) ? 0 : ( $totalValue / $pib_nacional ) ;

			$arrData[] = [
				'id'                      => $row['id'],
				'periodo'                 => $row['periodo'],
				'valor_expo_agricola_cop' => $totalValue,
				'pib_nacional'            => $pib_nacional,
				'participacion'           => ( $rate * 100 )
			];

		}

		$arrSeries = [
			'valor_expo_agricola_cop' => Lang::get('indicador.columns_title.valor_expo_agricola_cop'),
			'pib_nacional'        => Lang::get('pib.columns_title.pib_nacional'),
		];

		if ($this->scale == '2') {
			//escala de miles
			$pYAxisName  = Lang::get('indicador.reports.copThousands') ;
		} elseif ($this->scale == '3') {
			//escala de millones
			$pYAxisName  = Lang::get('indicador.reports.copMillions') ;
		} else {
			//escala de unidades
			$pYAxisName  = Lang::get('indicador.reports.copUnit') ;
		}

		$chartData = Helpers::jsonChart(
			$arrData,
			'periodo',
			$arrSeries,
			$this->chartType,
			'',
			$pYAxisName
		);

		$result = [
			'success'    => true,
			'data'       => $arrData,
			'total'      => count($arrData),
			'chartData'  => $chartData,
			'pYAxisName' => $pYAxisName,
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
			$pib_agricultura = $this->getFloatValue( $pib_agricultura );

			$totalValue   = $this->getFloatValue( $row[$columnValue] );

			$rate = ($pib_agricultura == 0) ? 0 : ( $totalValue / $pib_agricultura ) ;

			$arrData[] = [
				'id'                      => $row['id'],
				'periodo'                 => $row['periodo'],
				'valor_expo_agricola_cop' => $totalValue,
				'pib_agricultura'         => $pib_agricultura,
				'participacion'           => ( $rate * 100 )
			];

		}

		usort($arrData, Helpers::arraySortByValue('periodo'));
		
		$arrSeries = [
			'valor_expo_agricola_cop' => Lang::get('indicador.columns_title.valor_expo_agricola_cop'),
			'pib_agricultura'         => Lang::get('pib.columns_title.pib_agricultura'),
		];

		if ($this->scale == '2') {
			//escala de miles
			$pYAxisName  = Lang::get('indicador.reports.copThousands') ;
		} elseif ($this->scale == '3') {
			//escala de millones
			$pYAxisName  = Lang::get('indicador.reports.copMillions') ;
		} else {
			//escala de unidades
			$pYAxisName  = Lang::get('indicador.reports.copUnit') ;
		}

		$chartData = Helpers::jsonChart(
			$arrData,
			'periodo',
			$arrSeries,
			$this->chartType,
			'',
			$pYAxisName
		);

		$result = [
			'success'    => true,
			'data'       => $arrData,
			'total'      => count($arrData),
			'chartData'  => $chartData,
			'pYAxisName' => $pYAxisName,
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

		include_once PATH_MODELS.'Repositories/ProduccionRepo.php';
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
				$valor_impo   = $this->getFloatValue( $rowImpo[$this->columnValueImpo] );
				$arrPeriods[] = $rowImpo['periodo'];
			}

			//la produccion viene en toneladas metricas por eso hay que multiplicar por 1000
			$produccion_peso_neto = ( $rowProduccion['produccion_peso_neto'] == 0 ) ? 0 : ($rowProduccion['produccion_peso_neto'] * 1000);
			$produccion_peso_neto = $this->getFloatValue( $produccion_peso_neto );
			$valor_expo           = $this->getFloatValue( $rowExpo[$this->columnValueExpo] );
			$divider              = ( $produccion_peso_neto + $valor_impo - $valor_expo );
			$PI                   = ( $divider == 0 ) ? 0 : ( $valor_impo / $divider ) ;

			$arrData[] = [
				'id'              => $rowExpo['id'],
				'periodo'         => $rowExpo['periodo'],
				'peso_expo'       => $valor_expo,
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
				$produccion_peso_neto = $this->getFloatValue( $produccion_peso_neto );
				$valor_impo           = $this->getFloatValue( $rowImpo[$this->columnValueImpo] );

				$arrData[] = [
					'id'              => $rowImpo['id'],
					'periodo'         => $rowImpo['periodo'],
					'peso_expo'       => 0,
					'peso_impo'       => $valor_impo,
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

		$chartData = Helpers::jsonChart(
			$arrData,
			'periodo',
			$arrSeries,
			$this->chartType,
			'',
			Lang::get('indicador.columns_title.PI')
		);

		$result = [
			'success'   => true,
			'data'      => $arrData,
			'total'     => count($arrData),
			'chartData' => $chartData,
		];

		return $result;
	}

	public function executeCoeficienteAperturaExpo()
	{
		$arrFiltersValues = $this->arrFiltersValues;
		$this->setTrade('expo');
		$this->setRange('ini');

		$this->model    = $this->getModelExpo();
		$this->modelAdo = $this->getModelExpoAdo();

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
				$valor_impo   = $this->getFloatValue( $rowImpo[$this->columnValueImpo] );
				$arrPeriods[] = $rowImpo['periodo'];
			}

			//la produccion viene en toneladas metricas por eso hay que multiplicar por 1000
			$produccion_peso_neto = ( $rowProduccion['produccion_peso_neto'] == 0 ) ? 0 : ($rowProduccion['produccion_peso_neto'] * 1000);
			$produccion_peso_neto = $this->getFloatValue( $produccion_peso_neto );
			$valor_expo           = $this->getFloatValue( $rowExpo[$this->columnValueExpo] );
			$divider              = ( $produccion_peso_neto + $valor_impo - $valor_expo );
			$AE                   = ( $divider == 0 ) ? 0 : ( $valor_expo / $divider ) ;

			$arrData[] = [
				'id'              => $rowExpo['id'],
				'periodo'         => $rowExpo['periodo'],
				'peso_expo'       => $valor_expo,
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
				$produccion_peso_neto = $this->getFloatValue( $produccion_peso_neto );
				$valor_impo           = $this->getFloatValue( $rowImpo[$this->columnValueImpo] );

				$arrData[] = [
					'id'              => $rowImpo['id'],
					'periodo'         => $rowImpo['periodo'],
					'peso_expo'       => 0,
					'peso_impo'       => $valor_impo,
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

		$chartData = Helpers::jsonChart(
			$arrData,
			'periodo',
			$arrSeries,
			$this->chartType,
			'',
			Lang::get('indicador.columns_title.AE')
		);

		$result = [
			'success'   => true,
			'data'      => $arrData,
			'total'     => count($arrData),
			'chartData' => $chartData,
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
			$valueLastTotal               = $this->getFloatValue( $rowTotal[$columnValue] );
			$valueLastTotal               = ($valueLastTotal == 0) ? 1 : $valueLastTotal ;
			$valueLastProductsTraditional = ($rowProductsTraditional !== false) ? $this->getFloatValue( $rowProductsTraditional[$columnValue] ) : 0 ;
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


		$titleAgroNT = ( $this->typeIndicator == 'precio' ) ? Lang::get('indicador.columns_title.valor_expo_no_tradi') : Lang::get('indicador.columns_title.peso_expo_no_tradi') ;

		$arrSeries = [
			'valor_expo_no_tradi' => $titleAgroNT,
			'valor_expo_agricola' => $this->getColumnValueExpoAgroTitle(),
		];

		$chartData = Helpers::jsonChart(
			$arrData,
			'periodo',
			$arrSeries,
			$this->chartType,
			'',
			$this->pYAxisName
		);

		$result = [
			'success'                             => true,
			'data'                                => $arrData,
			'growthRateAgricultureNonTraditional' => ($growthRateAgricultureNonTraditional * 100),
			'growthRateAgriculture'               => ($growthRateAgriculture * 100),
			'rateVariation'                       => ($growthRateAgricultureNonTraditional / $growthRateAgriculture),
			'chartData'                           => $chartData,
			'total'                               => count($arrData)
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

		include_once(PATH_MODELS.'Repositories/ProduccionRepo.php');
		$produccionRepo = new ProduccionRepo;

		foreach ($rsDeclaraexp['data'] as $keyExpo => $rowExpo) {

			$anio   = $rowExpo['periodo'];
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
				$valor_impo   = $this->getFloatValue( $rowImpo[$this->columnValueImpo] );
				$arrPeriods[] = $rowImpo['periodo'];
			}

			//la produccion viene en toneladas metricas por eso hay que multiplicar por 1000
			$produccion_peso_neto = ($rowProduccion['produccion_peso_neto'] == 0) ? 0 : ($rowProduccion['produccion_peso_neto'] * 1000);
			$produccion_peso_neto = $this->getFloatValue( $produccion_peso_neto );
			$valor_expo           = $this->getFloatValue( $rowExpo[$this->columnValueExpo] );

			$CA  = ($produccion_peso_neto + $valor_impo - $valor_expo);
			$CAA = ($produccion_peso_neto / $CA);

			$arrData[] = [
				'id'              => $rowExpo['id'],
				'periodo'         => $rowExpo['periodo'],
				'peso_expo'       => $valor_expo,
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
				$produccion_peso_neto = ( $rowProduccion['produccion_peso_neto'] == 0 ) ? 0 : ( $rowProduccion['produccion_peso_neto'] * 1000 );
				$produccion_peso_neto = $this->getFloatValue( $produccion_peso_neto );
				$valor_impo           = $this->getFloatValue( $rowImpo[$this->columnValueImpo] );

				$CA  = ( $produccion_peso_neto + $valor_impo );
				$CAA = ( $produccion_peso_neto / $CA );

				$arrData[] = [
					'id'              => $rowImpo['id'],
					'periodo'         => $rowImpo['periodo'],
					'peso_expo'       => 0,
					'peso_impo'       => $valor_impo,
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

		$chartData = Helpers::jsonChart(
			$arrData,
			'periodo',
			$arrSeries,
			$this->chartType,
			'',
			$this->pYAxisName
		);

		$result = [
			'success'   => true,
			'data'      => $arrData,
			'total'     => count($arrData),
			'chartData' => $chartData,
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
		//$id_pais_destino = empty($arrFiltersValues['id_pais_destino']) ? 0 : $arrFiltersValues['id_pais_destino'];
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
			'p'    => 0,
			'cc'   => $id_subpartida,
		];

		$cache  = phpFastCache();
		$url    = $baseUrl . '?' . http_build_query($parameters);
		$key    = md5($url);
		$result = $cache->get($key);

		if (is_null($result)) {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_TIMEOUT, 30);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			$result = json_decode(curl_exec($ch), true);
			curl_close($ch);
			$cache->set($key, $result, 3600*24*30);
		}

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
			if ($row['rgCode'] == 1) { //Importaciones
				
				if ($row['rtCode'] == $colombiaIdComtrade) {
					$arrDataColImp[] = [
						'id'         => $row['yr'],
						'periodo'    => $row['period'],
						'valor_impo' => $this->getFloatValue($row[$columnValue]),
					];
				} else {
					$arrDataImp[] = [
						'id'         => $row['yr'],
						'periodo'    => $row['period'],
						'valor_impo' => $this->getFloatValue($row[$columnValue]),
					];
				}
				
			} elseif ($row['rgCode'] == 2) { //Exportaciones
				
				if ($row['rtCode'] == $colombiaIdComtrade) {
					$arrDataColExp[] = [
						'id'         => $row['yr'],
						'periodo'    => $row['period'],
						'valor_expo' => $this->getFloatValue($row[$columnValue]),
					];
				} else {
					$arrDataExp[] = [
						'id'         => $row['yr'],
						'periodo'    => $row['period'],
						'valor_expo' => $this->getFloatValue($row[$columnValue]),
					];
				}
			}
		}

		//var_dump($arrDataImp, $arrDataExp, $arrDataColExp);

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
			$totalExpoCol = ($rowExpoCol !== false) ? $rowExpoCol['valor_expo'] : 1 ;

			$rate = ($totalExpoCol == 0) ? 0 : (($rowImpo['valor_impo'] - $totalExpo) / $totalExpoCol) ;

			$arrData[] = [
				'id'             => $rowImpo['id'],
				'periodo'        => $rowImpo['periodo'],
				'valor_impo'     => $rowImpo['valor_impo'],
				'valor_expo'     => $totalExpo,
				'valor_expo_col' => $totalExpoCol,
				'valor_impo_col' => $totalImpoCol,
				'IEI'  => $rate
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

		$chartData = Helpers::jsonChart(
			$arrData,
			'periodo',
			$arrSeries,
			$this->chartType,
			'',
			Lang::get('indicador.columns_title.IEI'),
			'COMTRADE'
		);

		$result = [
			'success'   => true,
			'data'      => $arrData,
			'total'     => count($arrData),
			'chartData' => $chartData,
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

		$cache  = phpFastCache();
		$url    = $baseUrl . '?' . http_build_query($parameters);
		$key    = md5($url);
		$result = $cache->get($key);

		if (is_null($result)) {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_TIMEOUT, 30);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			$result = json_decode(curl_exec($ch), true);
			curl_close($ch);
			$cache->set($key, $result, 3600*24*30);
		}

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

		$chartData = Helpers::jsonChart(
			$arrChartData,
			'pais',
			$arrSeries,
			$this->chartType,
			'',
			$this->pYAxisName,
			'COMTRADE'
		);

		/*$columnChart = Helpers::jsonChart(
			$arrChartData,
			'pais',
			$arrSeries,
			COLUMNAS
		);*/

		$result = [
			'success'   => true,
			'data'      => $arrData,
			'total'     => count($arrData),
			'chartData' => $chartData,
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

		if ( $trade == 'impo' ) {
			$arrRowField[] = 'porcentaje_arancel';
		}

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
						'id'                 => array_shift($range),
						'periodo'            => $this->year . ' ' . $periodName,
						'porcentaje_arancel' => 0,
						'peso_neto'          => 0,
					];

					if ( $trade == 'impo' ) {
						$arrFinal[$number]['porcentaje_arancel'] = 0;
					}
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

	public function executeComtradeCuadrantes()
	{
		$arrFiltersValues = $this->arrFiltersValues;
		//var_dump($arrFiltersValues);
		$this->setRange('ini');

		$yearFirst = $arrFiltersValues['anio_ini'];
		$yearLast  = $arrFiltersValues['anio_fin'];
		$rangeYear = range($yearFirst, $yearLast);

		$id_pais_destino = $arrFiltersValues['id_pais_destino'];
		$id_subpartida   = $arrFiltersValues['id_subpartida'];

		if (
			empty($yearFirst) ||
			empty($yearLast) ||
			( empty($id_subpartida) && empty($id_pais_destino) )
		) {
			$result = [
				'success' => false,
				'error'   => 'Incomplete data for this request.'
			];
			return $result;
		}
		
		$baseUrl = Helpers::arrayGet($this->linesConfig, 'urlApiComtrade');

		$reporter = 'all';
		$partner  = '0';

		/*if ( !empty($id_pais_destino) ) {
			$reporter = $id_pais_destino;
		}
		if ( !empty($id_subpartida) ) {
			$partner = 'all';
		}*/

		//curl 'http://comtrade.un.org/api/get?max=500&type=C&freq=A&px=HS&ps=2013%2C2010%2C2011%2C2012&r=170%2C218&p=0&rg=1%2C2&cc=01&token=56233621927079056ea4d1e49ecf8f11' -H 'Host: comtrade.un.org' -H 'User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64; rv:34.0) Gecko/20100101 Firefox/34.0' -H 'Accept: application/json, text/javascript, */*; q=0.01' -H 'Accept-Language: es-MX,es-ES;q=0.8,es-AR;q=0.7,es;q=0.5,en-US;q=0.3,en;q=0.2' -H 'Accept-Encoding: gzip, deflate' -H 'X-Requested-With: XMLHttpRequest' -H 'Referer: http://comtrade.un.org/data/' -H 'Cookie: _ga=GA1.2.2137246786.1419954195; ASPSESSIONIDAACRDDSB=HGLJBMEDNOAGEKOAKDFCLCBC; _gat=1; _gali=preview'

		$parameters = [
			'max'  => 50000,
			'type' => 'C',
			'freq' => 'A', //frecuancia anual
			'px'   => 'HS',
			'rg'   => '1', //impo
			'ps'   => implode(',', $rangeYear),
			'r'    => $reporter,
			'p'    => $partner,
			'cc'   => $id_subpartida,
		];

		$cache  = phpFastCache();
		$url    = $baseUrl . http_build_query($parameters);
		$key    = md5($url);
		$result = $cache->get($key);

		if (is_null($result)) {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_TIMEOUT, 30);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			$result = json_decode(curl_exec($ch), true);
			curl_close($ch);
			$cache->set($key, $result, 3600*24*30);
		}

		//var_dump($result, $url);

		if (empty($result['dataset'])) {
			$result = [
				'success' => false,
				'error'   => Lang::get('error.no_records_found_comtrade')
			];
			return $result;
		}

		$arrFields = [
			'id'         => 'SMALLINT(4) UNSIGNED NOT NULL',
			'yr'         => 'SMALLINT(4) UNSIGNED NOT NULL',
			'rtCode'     => 'SMALLINT(4) UNSIGNED NOT NULL',
			'rtTitle'    => 'VARCHAR(100) NOT NULL',
			'ptCode'     => 'SMALLINT(4) UNSIGNED NOT NULL',
			'ptTitle'    => 'VARCHAR(100) NOT NULL',
			'TradeValue' => 'DECIMAL(20,2) UNSIGNED NOT NULL',
		];

		$this->modelAdo = new ComtradeTempAdo ('', $arrFields, $result['dataset']);

		//$rowFields   = ( empty($id_pais_destino) ) ? 'rtCode, rtTitle' : 'ptCode, ptTitle' ;
		//$rowFieldId  = ( empty($id_pais_destino) ) ? 'rtCode' : 'ptCode' ;
		$rowFields   = 'rtCode, rtTitle';
		$rowFieldId  = 'rtCode';

		$columnValue = 'TradeValue';

		$this->modelAdo->setPivotRowFields('id, '.$rowFields);
		$this->modelAdo->setPivotTotalFields($columnValue);
		$this->modelAdo->setPivotGroupingFunction('SUM');
		$this->modelAdo->setPivotSortColumn($columnValue . ' DESC');
		$this->modelAdo->setPivotColumnFields('yr');

		$result = $this->modelAdo->pivotSearch();

		if (!$result['success']) {
			return $result;
		}

		$arrData = [];
		$acummulatedAvg   = 0;
		$acummulatedSlope = 0;
		$numberRecords    = 0;

		foreach ($result['data'] as $row) {

			$arrCalculatedColumns = array_diff_key($row, $arrFields);

			//verifica que no existan registros con valores en cero
			$isValid = true;
			foreach ($arrCalculatedColumns as $key => $value) {
				if ( empty($value) ) {
					$isValid = false;
				}
				$row[$key] = $this->getFloatValue( $value );
			}
			if ($isValid) {
				if ( $row[$rowFieldId] == '0' ) {
					//captura la fila acumulada del todo el mundo

					//var_dump($row);

				} else {

					$avg = array_sum($arrCalculatedColumns) / count($arrCalculatedColumns);
					$avg = $this->getFloatValue($avg);

					$arrY = array_map('Helpers::naturalLogarithm', $arrCalculatedColumns);

					$linearRegression = Helpers::linearRegression($arrY);
					$slope            = ( $linearRegression['m'] * 100 );
					$acummulatedAvg   += $avg;
					$acummulatedSlope += $slope;
					$numberRecords    += 1;

					if ( ( !empty($id_pais_destino) && in_array($row[$rowFieldId], explode(',', $id_pais_destino))) || empty($id_pais_destino) ) {
						$arrData[] = array_merge( $row, compact('slope', 'avg') );
					}

				}
			}
		}

		$arrQuadrant1 = [];
		$arrQuadrant2 = [];
		$arrQuadrant3 = [];
		$arrQuadrant4 = [];

		if ($numberRecords > 0) {
			$totalAvg   = ($acummulatedAvg / $numberRecords) / 1000;
			$totalSlope = ($acummulatedSlope / $numberRecords);
			
			foreach ($arrData as $row) {
				$avg      = (float)$row['avg'] / 1000;
				$slope    = (float)$row['slope'];
				$quadrant = 'cuadrante_3';

				$pais = $row['rtTitle'];
				
				if ( $avg > $totalAvg && $slope > $totalSlope ) {
					//$quadrant = 'cuadrante_1';
					$arrQuadrant1[] = $row;
				} elseif ( $avg > $totalAvg && $slope < $totalSlope ) {
					//$quadrant = 'cuadrante_4';
					$arrQuadrant4[] = $row;
				} elseif ( $avg < $totalAvg && $slope > $totalSlope ) {
					$arrQuadrant2[] = $row;
					//$quadrant = 'cuadrante_2';
				} else {
					$arrQuadrant3[] = $row;
				}
				//var_dump(compact('avg', 'totalAvg', 'slope', 'totalSlope','pais', 'quadrant', 'acummulatedSlope'));
			}

			//$arrData = $arr;
		}

		$arrChartQuadrant1 = [];
		$arrChartQuadrant2 = [];
		$arrChartQuadrant3 = [];
		$arrChartQuadrant4 = [];

		/************************** Chart 1 *******************************/

		$arrChartColumns1   = [];
		$arrChartColumns1[] = ['id' => 'x', 'label' => 'Vr. Prom. Anual', 'type' => 'number'];
		foreach ($arrQuadrant1 as $key => $row) {
			$avg      = (float)$row['avg'] / 1000;
			$slope    = (float)$row['slope'];

			$pais = $row['rtTitle'];
			$arrChartColumns1[] = ['id' => '', 'label' => $pais, 'type' => 'number'];

			$arr = [];
			$arr['c'][] = [ 'v' => $avg ];
			for ($i=2; $i <= count($arrQuadrant1) + 1; $i++) {
				if ($i == count($arrChartColumns1)) {
					$arr['c'][] = [ 'v' => number_format($slope ,2) ];
				} else {
					$arr['c'][] = [ 'v' => null ];
				}
			}

			$arrChartQuadrant1['rows'][] = $arr;
		}

		$arrChartQuadrant1['cols'] = $arrChartColumns1;

		/************************** Chart 2 *******************************/
		$arrChartColumns2   = [];
		$arrChartColumns2[] = ['id' => 'x', 'label' => 'Vr. Prom. Anual', 'type' => 'number'];
		foreach ($arrQuadrant2 as $key => $row) {
			$avg      = (float)$row['avg'] / 1000;
			$slope    = (float)$row['slope'];

			$pais = $row['rtTitle'];
			$arrChartColumns2[] = ['id' => '', 'label' => $pais, 'type' => 'number'];

			$arr = [];
			$arr['c'][] = [ 'v' => $avg ];
			for ($i=2; $i <= count($arrQuadrant2) + 1; $i++) {
				if ($i == count($arrChartColumns2)) {
					$arr['c'][] = [ 'v' => number_format($slope ,2) ];
				} else {
					$arr['c'][] = [ 'v' => null ];
				}
			}

			$arrChartQuadrant2['rows'][] = $arr;
		}

		$arrChartQuadrant2['cols'] = $arrChartColumns2;

		/************************** Chart 3 *******************************/

		$arrChartColumns3   = [];
		$arrChartColumns3[] = ['id' => 'x', 'label' => 'Vr. Prom. Anual', 'type' => 'number'];
		foreach ($arrQuadrant3 as $key => $row) {
			$avg      = (float)$row['avg'] / 1000;
			$slope    = (float)$row['slope'];

			$pais = $row['rtTitle'];
			$arrChartColumns3[] = ['id' => '', 'label' => $pais, 'type' => 'number'];

			$arr = [];
			$arr['c'][] = [ 'v' => $avg ];
			for ($i=2; $i <= count($arrQuadrant3) + 1; $i++) {
				if ($i == count($arrChartColumns3)) {
					$arr['c'][] = [ 'v' => number_format($slope ,2) ];
				} else {
					$arr['c'][] = [ 'v' => null ];
				}
			}

			$arrChartQuadrant3['rows'][] = $arr;
		}

		$arrChartQuadrant3['cols'] = $arrChartColumns3;

		/************************** Chart 4 *******************************/

		$arrChartColumns4   = [];
		$arrChartColumns4[] = ['id' => 'x', 'label' => 'Vr. Prom. Anual', 'type' => 'number'];
		foreach ($arrQuadrant4 as $key => $row) {
			$avg      = (float)$row['avg'] / 1000;
			$slope    = (float)$row['slope'];

			$pais = $row['rtTitle'];
			$arrChartColumns4[] = ['id' => '', 'label' => $pais, 'type' => 'number'];

			$arr = [];
			$arr['c'][] = [ 'v' => $avg ];
			for ($i=2; $i <= count($arrQuadrant4) + 1; $i++) {
				if ($i == count($arrChartColumns4)) {
					$arr['c'][] = [ 'v' => number_format($slope ,2) ];
				} else {
					$arr['c'][] = [ 'v' => null ];
				}
			}

			$arrChartQuadrant4['rows'][] = $arr;
		}

		$arrChartQuadrant4['cols'] = $arrChartColumns4;

		$result = [
			'success'      => true,
			'data'         => $arrData,
			'total'        => count($arrData),
			'arrQuadrant1' => $arrChartQuadrant1,
			'arrQuadrant2' => $arrChartQuadrant2,
			'arrQuadrant3' => $arrChartQuadrant3,
			'arrQuadrant4' => $arrChartQuadrant4,
			'totalSlope'   => $totalSlope,
			'totalAvg'     => $totalAvg,
			'yearLast'     => $yearLast,
			'yearFirst'    => $yearFirst,
		];

		return $result;
	}

	public function executeColombiaAlMundo( $pareto )
	{
		$arrFiltersValues = $this->arrFiltersValues;
		$arrYear          = range($arrFiltersValues['anio_ini'], $arrFiltersValues['anio_fin']);
		$this->setTrade('expo');

		$this->model    = $this->getModelExpo();
		$this->modelAdo = $this->getModelExpoAdo();
		$columnValue    = $this->columnValueExpo;

		//Trae los productos configurados como agricolas
		$result = $this->findProductsBySector('sectorIdAgriculture');
		if (!$result['success']) {
			return $result;
		}
		$productsAgriculture = $result['data'];

		$this->model->setId_posicion($productsAgriculture);
		
		//asigna los valores de filtro del indicador al modelo
		$this->setFiltersValues();

		$this->modelAdo->setPivotRowFields('id, decl.id_subpartida, subpartida');
		$this->modelAdo->setPivotTotalFields($columnValue);
		$this->modelAdo->setPivotColumnFields('anio');
		$this->modelAdo->setPivotColumnValues($arrYear);
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

		$arrFields        = ['id' => 'id', 'id_subpartida' => 'id_subpartida', 'subpartida' => 'subpartida', $columnValue => $columnValue];
		$arrDeclaraciones = $rsDeclaraciones['data'];
		$arrData          = [];
		$arrChartData     = [];
		$counter          = 0;
		$othersValue      = 0;
		$avgOthersValue   = 0;
		$arrOthersVal     = [];

		$arrColumnValue = Helpers::arrayColumn($arrDeclaraciones, $columnValue);
		$totalValue     = $this->getFloatValue( array_sum($arrColumnValue) );

		$htmlColumns                = [];
		$htmlColumns['id_subpartida'] = Lang::get('indicador.columns_title.subpartida');
		$htmlColumns['subpartida']    = Lang::get('indicador.columns_title.desc_posicion');
		$arrCalculatedColumns       = array_diff_key($arrDeclaraciones[0], $arrFields);

		foreach ($arrCalculatedColumns as $key => $val) {
			$index               = str_replace(' '.$columnValue, '', $key);
			$htmlColumns[$index] = $index . ' ' . $this->pYAxisName;
		}

		$htmlColumns[$columnValue]         = 'Promedio ' . $this->pYAxisName;
		$htmlColumns['rate_'.$columnValue] = Lang::get('indicador.columns_title.participacion');
		$htmlColumns['growthRate']         = Lang::get('indicador.reports.growRate');

		foreach ($arrDeclaraciones as $row) {

			$arrCalculatedColumns = array_diff_key($row, $arrFields);

			$avgCalculatedColumns = array_sum($arrCalculatedColumns) / count($arrYear);
			$avgCalculatedColumns = $this->getFloatValue($avgCalculatedColumns);

			if( $counter < $pareto ) {

				$counter += 1;
				$arr      = [];

				//adiciona las columnas de texto
				foreach ($arrFields as $key => $val) {
					if ( $key != $columnValue ) {
						$arr[$key] = $row[$key];
					}
				}

				foreach ($arrCalculatedColumns as $key => $val) {
					$index               = str_replace(' '.$columnValue, '', $key);
					$arr[$index]         = $this->getFloatValue($row[$key]);
				}

				$value = $this->getFloatValue( $row[$columnValue] );

				$arrY                      = array_map('Helpers::naturalLogarithm', $arrCalculatedColumns);
				$linearRegression          = Helpers::linearRegression($arrY);
				$slope                     = ( $linearRegression['m'] * 100 );
				$rate                      = ($totalValue == 0) ? 0 : ( $value / $totalValue) ;
				$arr[$columnValue]         = $avgCalculatedColumns; //debe mostrar el promedio y NO el ACUMULADO
				$arr['rate_'.$columnValue] = $rate * 100;
				$arr['growthRate']         = $slope;

				$arrData[]      = $arr;
				$subpartida     = '(' . $row['id_subpartida'] . ') ' . $row['subpartida'];
				$arrChartData[] = [
					'id_subpartida' => $row['id_subpartida'] ,
					'subpartida'    => $subpartida,
					'valor_expo'    => $avgCalculatedColumns,
					'growthRate'    => $slope
				];
			} else {

				$othersValue += $this->getFloatValue( $row[$columnValue] );

				foreach ($arrCalculatedColumns as $key => $val) {
					$index = str_replace(' '.$columnValue, '', $key);
					
					if ( empty($arrOthersVal[$index]) ) {
						$arrOthersVal[$index] = 0;
					}

					$arrOthersVal[$index] += $this->getFloatValue( $row[$key] );
				}

			}
		}

		$avgOthersValue = array_sum($arrOthersVal) / count($arrYear);

		//inserta la fila "otros"
		$arr                  = [];
		$arr['id']            = 0;
		$arr['id_subpartida'] = '';
		$arr['subpartida']    = Lang::get('indicador.reports.others');

		$arr = $arr + $arrOthersVal;

		$arrY                      = array_map('Helpers::naturalLogarithm', $arrOthersVal);
		$linearRegression          = Helpers::linearRegression($arrY);
		$slope                     = ( $linearRegression['m'] * 100 );
		$rate                      = ($totalValue == 0) ? 0 : ( $othersValue / $totalValue) ;
		$arr[$columnValue]         = $avgOthersValue;
		$arr['rate_'.$columnValue] = $rate * 100;
		$arr['growthRate']         = $slope;
		$arrData[]                 = $arr;
		$arrChartData[]            = [
			'id_subpartida' => Lang::get('indicador.reports.others') ,
			'subpartida'    => Lang::get('indicador.reports.others'),
			'valor_expo'    => $avgOthersValue,
			'growthRate'    => $slope
		];

		//var_dump($arrChartData);

		$pieChart              = [];
		$pieChart['cols'][]    = ['id' => 'subpartida', 'label' => Lang::get('indicador.columns_title.subpartida'), 'type' => 'string'];
		$pieChart['cols'][]    = ['id' => 'valor_expo', 'label' => $this->pYAxisName, 'type' => 'number'];
		$columnChart           = [];
		$columnChart['cols'][] = ['id' => 'subpartida', 'label' => Lang::get('indicador.columns_title.subpartida'), 'type' => 'string'];
		$columnChart['cols'][] = ['id' => 'growthRate', 'label' => Lang::get('indicador.reports.growRate'), 'type' => 'number'];

		foreach ($arrChartData as $key => $row) {
			$arr                = [];
			$arr['c'][]         = [ 'v' => $row['subpartida'], 'f' => null ];
			$arr['c'][]         = [ 'v' => $row['valor_expo'], 'f' => null ];
			$pieChart['rows'][] = $arr;

			$arr                   = [];
			$arr['c'][]            = [ 'v' => $row['subpartida'], 'f' => null ];
			$arr['c'][]            = [ 'v' => $row['growthRate'], 'f' => null ];
			$columnChart['rows'][] = $arr;
		}

		$result = [
			'success'     => true,
			'data'        => $arrData,
			'arrYear'     => $arrYear,
			'total'       => count($arrData),
			'columnChart' => $columnChart,
			'pieChart'    => $pieChart,
			'htmlColumns' => $htmlColumns,
		];

		return $result;
	}

	public function executePrincipalesDestinos( $pareto )
	{
		$arrFiltersValues = $this->arrFiltersValues;
		$arrYear          = range($arrFiltersValues['anio_ini'], $arrFiltersValues['anio_fin']);
		$this->setTrade('expo');

		$this->model    = $this->getModelExpo();
		$this->modelAdo = $this->getModelExpoAdo();
		$columnValue    = $this->columnValueExpo;

		//Trae los productos configurados como agricolas
		$result = $this->findProductsBySector('sectorIdAgriculture');
		if (!$result['success']) {
			return $result;
		}
		$productsAgriculture = $result['data'];

		$this->model->setId_posicion($productsAgriculture);
		
		//asigna los valores de filtro del indicador al modelo
		$this->setFiltersValues();

		$this->modelAdo->setPivotRowFields('id, decl.id_paisdestino, pais');
		$this->modelAdo->setPivotTotalFields($columnValue);
		$this->modelAdo->setPivotColumnFields('anio');
		$this->modelAdo->setPivotColumnValues($arrYear);
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

		$arrFields        = ['id' => 'id', 'id_paisdestino' => 'id_paisdestino', 'pais' => 'pais', $columnValue => $columnValue];
		$arrDeclaraciones = $rsDeclaraciones['data'];
		$arrData      = [];
		$arrChartData = [];
		$counter      = 0;
		$othersValue  = 0;
		$arrOthersVal = [];

		$arrColumnValue = Helpers::arrayColumn($arrDeclaraciones, $columnValue);
		$totalValue     = $this->getFloatValue( array_sum($arrColumnValue) );

		$htmlColumns          = [];
		$htmlColumns['pais']  = Lang::get('indicador.columns_title.pais');
		$arrCalculatedColumns = array_diff_key($arrDeclaraciones[0], $arrFields);

		foreach ($arrCalculatedColumns as $key => $val) {
			$index               = str_replace(' '.$columnValue, '', $key);
			$htmlColumns[$index] = $index . ' ' . $this->pYAxisName;
		}

		$htmlColumns[$columnValue]         = 'Promedio ' . $this->pYAxisName;
		$htmlColumns['rate_'.$columnValue] = Lang::get('indicador.columns_title.participacion');
		$htmlColumns['growthRate']         = Lang::get('indicador.reports.growRate');

		foreach ($arrDeclaraciones as $row) {

			$arrCalculatedColumns = array_diff_key($row, $arrFields);

			$avgCalculatedColumns = array_sum($arrCalculatedColumns) / count($arrYear);
			$avgCalculatedColumns = $this->getFloatValue($avgCalculatedColumns);

			if( $counter < $pareto ) {

				$counter += 1;
				$arr      = [];

				//adiciona las columnas de texto
				foreach ($arrFields as $key => $val) {
					if ( $key != $columnValue ) {
						$arr[$key] = $row[$key];
					}
				}

				foreach ($arrCalculatedColumns as $key => $val) {
					$index               = str_replace(' '.$columnValue, '', $key);
					$arr[$index]         = $this->getFloatValue($row[$key]);
				}

				$value = $this->getFloatValue( $row[$columnValue] );

				$arrY                      = array_map('Helpers::naturalLogarithm', $arrCalculatedColumns);
				$linearRegression          = Helpers::linearRegression($arrY);
				$slope                     = ( $linearRegression['m'] * 100 );
				$rate                      = ($totalValue == 0) ? 0 : ( $value / $totalValue) ;
				$arr[$columnValue]         = $avgCalculatedColumns; //debe mostrar el promedio y NO el ACUMULADO
				$arr['rate_'.$columnValue] = $rate * 100;
				$arr['growthRate']         = $slope;

				$arrData[]      = $arr;
				$arrChartData[] = [
					'pais'       => $row['pais'],
					'valor_expo' => $avgCalculatedColumns,
					'growthRate' => $slope
				];
			} else {

				$othersValue += $this->getFloatValue( $row[$columnValue] );

				foreach ($arrCalculatedColumns as $key => $val) {
					$index = str_replace(' '.$columnValue, '', $key);
					
					if ( empty($arrOthersVal[$index]) ) {
						$arrOthersVal[$index] = 0;
					}

					$arrOthersVal[$index] += $this->getFloatValue( $row[$key] );
				}

			}
		}

		$avgOthersValue = array_sum($arrOthersVal) / count($arrYear);

		//inserta la fila "otros"
		$arr         = [];
		$arr['id']   = 0;
		$arr['pais'] = Lang::get('indicador.reports.others');

		$arr = $arr + $arrOthersVal;

		$arrY                      = array_map('Helpers::naturalLogarithm', $arrOthersVal);
		$linearRegression          = Helpers::linearRegression($arrY);
		$slope                     = ( $linearRegression['m'] * 100 );
		$rate                      = ($totalValue == 0) ? 0 : ( $othersValue / $totalValue) ;
		$arr[$columnValue]         = $avgOthersValue;
		$arr['rate_'.$columnValue] = $rate * 100;
		$arr['growthRate']         = $slope;
		$arrData[]                 = $arr;
		$arrChartData[]            = [
			'pais' => Lang::get('indicador.reports.others') ,
			'valor_expo'  => $avgOthersValue,
			'growthRate'  => $slope
		];

		$pieChart              = [];
		$pieChart['cols'][]    = ['id' => 'pais', 'label' => Lang::get('indicador.columns_title.pais'), 'type' => 'string'];
		$pieChart['cols'][]    = ['id' => 'valor_expo', 'label' => Lang::get('indicador.columns_title.valor_expo'), 'type' => 'number'];
		$columnChart           = [];
		$columnChart['cols'][] = ['id' => 'pais', 'label' => Lang::get('indicador.columns_title.pais'), 'type' => 'string'];
		$columnChart['cols'][] = ['id' => 'growthRate', 'label' => Lang::get('indicador.reports.growRate'), 'type' => 'number'];

		foreach ($arrChartData as $key => $row) {
			$arr                = [];
			$arr['c'][]         = [ 'v' => $row['pais'], 'f' => null ];
			$arr['c'][]         = [ 'v' => $row['valor_expo'], 'f' => null ];
			$pieChart['rows'][] = $arr;

			$arr                   = [];
			$arr['c'][]            = [ 'v' => $row['pais'], 'f' => null ];
			$arr['c'][]            = [ 'v' => $row['growthRate'], 'f' => null ];
			$columnChart['rows'][] = $arr;
		}

		$result = [
			'success'     => true,
			'data'        => $arrData,
			'arrYear'     => $arrYear,
			'total'       => count($arrData),
			'columnChart' => $columnChart,
			'pieChart'    => $pieChart,
			'htmlColumns' => $htmlColumns,
		];

		return $result;
	}

	public function executePrincipalesOrigenes( $pareto )
	{
		$arrFiltersValues = $this->arrFiltersValues;
		$arrYear          = range($arrFiltersValues['anio_ini'], $arrFiltersValues['anio_fin']);
		$this->setTrade('impo');

		$this->model    = $this->getModelImpo();
		$this->modelAdo = $this->getModelImpoAdo();
		$columnValue    = $this->columnValueExpo;

		//Trae los productos configurados como agricolas
		$result = $this->findProductsBySector('sectorIdAgriculture');
		if (!$result['success']) {
			return $result;
		}
		$productsAgriculture = $result['data'];

		$this->model->setId_posicion($productsAgriculture);
		
		//asigna los valores de filtro del indicador al modelo
		$this->setFiltersValues();

		$this->modelAdo->setPivotRowFields('id, decl.id_paisprocedencia, pais');
		$this->modelAdo->setPivotTotalFields($columnValue);
		$this->modelAdo->setPivotColumnFields('anio');
		$this->modelAdo->setPivotColumnValues($arrYear);
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

		$arrFields        = ['id' => 'id', 'id_paisprocedencia' => 'id_paisprocedencia', 'pais' => 'pais', $columnValue => $columnValue];
		$arrDeclaraciones = $rsDeclaraciones['data'];
		$arrData      = [];
		$arrChartData = [];
		$counter      = 0;
		$othersValue  = 0;
		$arrOthersVal = [];

		$arrColumnValue = Helpers::arrayColumn($arrDeclaraciones, $columnValue);
		$totalValue     = $this->getFloatValue( array_sum($arrColumnValue) );

		$htmlColumns          = [];
		$htmlColumns['pais']  = Lang::get('indicador.columns_title.pais');
		$arrCalculatedColumns = array_diff_key($arrDeclaraciones[0], $arrFields);

		foreach ($arrCalculatedColumns as $key => $val) {
			$index               = str_replace(' '.$columnValue, '', $key);
			$htmlColumns[$index] = $index . ' ' . $this->pYAxisName;
		}

		$htmlColumns[$columnValue]         = 'Promedio ' . $this->pYAxisName;
		$htmlColumns['rate_'.$columnValue] = Lang::get('indicador.columns_title.participacion');
		$htmlColumns['growthRate']         = Lang::get('indicador.reports.growRate');

		foreach ($arrDeclaraciones as $row) {

			$arrCalculatedColumns = array_diff_key($row, $arrFields);

			$avgCalculatedColumns = array_sum($arrCalculatedColumns) / count($arrYear);
			$avgCalculatedColumns = $this->getFloatValue($avgCalculatedColumns);

			if( $counter < $pareto ) {

				$counter += 1;
				$arr      = [];

				//adiciona las columnas de texto
				foreach ($arrFields as $key => $val) {
					if ( $key != $columnValue ) {
						$arr[$key] = $row[$key];
					}
				}

				foreach ($arrCalculatedColumns as $key => $val) {
					$index               = str_replace(' '.$columnValue, '', $key);
					$arr[$index]         = $this->getFloatValue($row[$key]);
				}

				$value = $this->getFloatValue( $row[$columnValue] );

				$arrY                      = array_map('Helpers::naturalLogarithm', $arrCalculatedColumns);
				$linearRegression          = Helpers::linearRegression($arrY);
				$slope                     = ( $linearRegression['m'] * 100 );
				$rate                      = ($totalValue == 0) ? 0 : ( $value / $totalValue) ;
				$arr[$columnValue]         = $avgCalculatedColumns; //debe mostrar el promedio y NO el ACUMULADO
				$arr['rate_'.$columnValue] = $rate * 100;
				$arr['growthRate']         = $slope;

				$arrData[]      = $arr;
				$arrChartData[] = [
					'pais'       => $row['pais'],
					'valor_expo' => $avgCalculatedColumns,
					'growthRate' => $slope
				];
			} else {

				$othersValue += $this->getFloatValue( $row[$columnValue] );

				foreach ($arrCalculatedColumns as $key => $val) {
					$index = str_replace(' '.$columnValue, '', $key);
					
					if ( empty($arrOthersVal[$index]) ) {
						$arrOthersVal[$index] = 0;
					}

					$arrOthersVal[$index] += $this->getFloatValue( $row[$key] );
				}

			}
		}

		$avgOthersValue = array_sum($arrOthersVal) / count($arrYear);

		//inserta la fila "otros"
		$arr         = [];
		$arr['id']   = 0;
		$arr['pais'] = Lang::get('indicador.reports.others');

		$arr = $arr + $arrOthersVal;

		$arrY                      = array_map('Helpers::naturalLogarithm', $arrOthersVal);
		$linearRegression          = Helpers::linearRegression($arrY);
		$slope                     = ( $linearRegression['m'] * 100 );
		$rate                      = ($totalValue == 0) ? 0 : ( $othersValue / $totalValue) ;
		$arr[$columnValue]         = $avgOthersValue;
		$arr['rate_'.$columnValue] = $rate * 100;
		$arr['growthRate']         = $slope;
		$arrData[]                 = $arr;
		$arrChartData[]            = [
			'pais' => Lang::get('indicador.reports.others') ,
			'valor_expo'  => $avgOthersValue,
			'growthRate'  => $slope
		];

		$pieChart              = [];
		$pieChart['cols'][]    = ['id' => 'pais', 'label' => Lang::get('indicador.columns_title.pais'), 'type' => 'string'];
		$pieChart['cols'][]    = ['id' => 'valor_expo', 'label' => Lang::get('indicador.columns_title.valor_expo'), 'type' => 'number'];
		$columnChart           = [];
		$columnChart['cols'][] = ['id' => 'pais', 'label' => Lang::get('indicador.columns_title.pais'), 'type' => 'string'];
		$columnChart['cols'][] = ['id' => 'growthRate', 'label' => Lang::get('indicador.reports.growRate'), 'type' => 'number'];

		foreach ($arrChartData as $key => $row) {
			$arr                = [];
			$arr['c'][]         = [ 'v' => $row['pais'], 'f' => null ];
			$arr['c'][]         = [ 'v' => $row['valor_expo'], 'f' => null ];
			$pieChart['rows'][] = $arr;

			$arr                   = [];
			$arr['c'][]            = [ 'v' => $row['pais'], 'f' => null ];
			$arr['c'][]            = [ 'v' => $row['growthRate'], 'f' => null ];
			$columnChart['rows'][] = $arr;
		}

		$result = [
			'success'     => true,
			'data'        => $arrData,
			'arrYear'     => $arrYear,
			'total'       => count($arrData),
			'columnChart' => $columnChart,
			'pieChart'    => $pieChart,
			'htmlColumns' => $htmlColumns,
		];

		return $result;
	}
}

