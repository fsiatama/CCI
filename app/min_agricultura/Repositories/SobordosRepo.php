<?php

require PATH_MODELS.'Entities/Sobordoimp.php';
require PATH_MODELS.'Ado/SobordoimpAdo.php';
require PATH_MODELS.'Entities/Sobordoexp.php';
require PATH_MODELS.'Ado/SobordoexpAdo.php';
require PATH_MODELS.'Ado/ComtradeTempAdo.php';
require PATH_MODELS.'Repositories/SectorRepo.php';
require_once PATH_MODELS.'Repositories/MercadoRepo.php';

require_once ('BaseRepo.php');

class SobordosRepo extends BaseRepo {

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
		$chartType = '')
	{
		$this->rowIndicador  = $rowIndicador;
		$this->filtersConfig = $filtersConfig;
		$this->year          = $year;
		$this->period        = $period;
		$this->scope         = $scope;
		$this->scale         = $scale;
		$this->chartType     = $chartType;

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
		return new Sobordoimp;
	}

	public function getModelImpoAdo()
	{
		return new SobordoimpAdo;
	}

	public function getModelExpo()
	{
		return new Sobordoexp;
	}

	public function getModelExpoAdo()
	{
		return new SobordoexpAdo;
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

				if (!empty($filter['dateRange'])) {

					//si es un rango de fechas debe unir el valor inicial y el final


					$setFilterValue = false;

					if ( substr($filter['field'], -3) == $range || empty($range) ) {
						
						$arrDate  = explode('-', $filterValue);
						$yearIni  = $arrDate[0];
						$monthIni = empty($arrDate[1]) ? '01' : $arrDate[1];

						$arrDate  = explode('-', $arrFiltersValues[$filter['dateRange'][0]]);
						$yearFin  = $arrDate[0];
						$monthFin = empty($arrDate[1]) ? '12' : $arrDate[1];


						$filterValue = 'DATE("' . $yearIni . '-' . $monthIni . '-01") AND DATE("' . $yearFin . '-' . $monthFin . '-01")';

						$methodName = $this->getColumnMethodName('set', 'fecha');

						call_user_func_array([$this->model, $methodName], compact('filterValue'));

					}


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

				} elseif ($filter['field'] == 'sector_id') {

					//Trae los productos configurados en el sector seleccionado
					$result = $this->findProductsBySector($arrFiltersValues['sector_id']);
					if (!$result['success']) {
						return $result;
					}
					$products = $result['data'];
					$this->model->setId_subpartida($products);

				} elseif ($filter['field'] == 'id_posicion') {

					$setFilterValue = false;
					$this->model->setId_subpartida($filterValue);

					
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

	public function executeAcumuladoSubpartidaPais()
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

		$rsSobordos = $this->modelAdo->pivotSearch($this->model);

		$arrData = $rsSobordos['data'];

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

