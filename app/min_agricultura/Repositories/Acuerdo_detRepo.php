<?php

require PATH_MODELS.'Entities/Acuerdo_det.php';
require PATH_MODELS.'Ado/Acuerdo_detAdo.php';
require_once PATH_MODELS.'Repositories/ContingenteRepo.php';
require_once PATH_MODELS.'Repositories/DesgravacionRepo.php';
require_once PATH_MODELS.'Repositories/PosicionRepo.php';
require_once ('BaseRepo.php');

class Acuerdo_detRepo extends BaseRepo {

	private $contingenteRepo;
	private $desgravacionRepo;

	public function getModel()
	{
		return new Acuerdo_det;
	}
	
	public function getModelAdo()
	{
		return new Acuerdo_detAdo;
	}

	public function getPrimaryKey()
	{
		return 'acuerdo_det_id';
	}

	public function validateModify($params)
	{
		extract($params);
		$result = $this->findPrimaryKey($acuerdo_det_id);

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

	public function deleteByParent($params)
	{
		extract($params);
		$this->model = $this->getModel();

		if (empty($acuerdo_id)) {
			$result = [
				'success' => false,
				'error'   => 'Incomplete data for this request.'
			];
			return $result;
		}

		$this->model->setAcuerdo_det_acuerdo_id($acuerdo_id);
		$result = $this->modelAdo->exactSearch($this->model);
		if (!$result['success']) {
			return $result;
		}

		$arrData = $result['data'];

		//realiza el borrado de cada acuerdo_det
		foreach ($arrData as $key => $row) {
			$this->model = $this->getModel();
			$result = $this->delete($row);
			if (!$result['success']) {
				return $result;
			}
		}

		return $result;
	}

	private function deleteQuota($acuerdo_det_id, $acuerdo_det_acuerdo_id)
	{
		$result = $this->contingenteRepo->deleteByParent(
			compact('acuerdo_det_id', 'acuerdo_det_acuerdo_id')
		);
		return $result;
	}

	private function deleteDeduction($acuerdo_det_id, $acuerdo_det_acuerdo_id)
	{
		$result = $this->desgravacionRepo->deleteByParent(
			compact('acuerdo_det_id', 'acuerdo_det_acuerdo_id')
		);
		return $result;
	}

	private function createQuota($acuerdo_det_id, $acuerdo_det_acuerdo_id, $acuerdo_det_contingente_acumulado_pais)
	{
		$result = $this->contingenteRepo->createByAgreement(
			compact('acuerdo_det_id', 'acuerdo_det_acuerdo_id', 'acuerdo_det_contingente_acumulado_pais')
		);
		return $result;
	}

	private function createDeduction($acuerdo_det_id, $acuerdo_det_acuerdo_id, $acuerdo_det_desgravacion_igual_pais)
	{
		$result = $this->desgravacionRepo->createByAgreement(
			compact('acuerdo_det_id', 'acuerdo_det_acuerdo_id', 'acuerdo_det_desgravacion_igual_pais')
		);
		return $result;
	}

	public function delete($params)
	{

		extract($params);

		$this->contingenteRepo  = new ContingenteRepo;
		$this->desgravacionRepo = new DesgravacionRepo;

		if (empty($acuerdo_det_id) || empty($acuerdo_det_acuerdo_id)) {
			$result = [
				'success' => false,
				'error'   => 'Incomplete data for this request.'
			];
			return $result;
		}
		$result = $this->deleteQuota(
			$acuerdo_det_id,
			$acuerdo_det_acuerdo_id
		);
		if (!$result['success']) {
			return $result;
		}

		$result = $this->deleteDeduction(
			$acuerdo_det_id,
			$acuerdo_det_acuerdo_id
		);
		if (!$result['success']) {
			return $result;
		}

		$result = parent::delete($params);
		return $result;
	}

	public function create($params)
	{
		$result = parent::create($params);

		if (!$result['success']) {
			return $result;
		}
		
		$acuerdo_det_id                         = $result['insertId'];
		$acuerdo_det_acuerdo_id                 = $this->model->getAcuerdo_det_acuerdo_id();
		$acuerdo_det_contingente_acumulado_pais = $this->model->getAcuerdo_det_contingente_acumulado_pais();
		$acuerdo_det_desgravacion_igual_pais    = $this->model->getAcuerdo_det_desgravacion_igual_pais();

		//generar los contingentes en blanco para cada pais o mercado del acuerdo
		$result = $this->createQuota(
			$acuerdo_det_id,
			$acuerdo_det_acuerdo_id,
			$acuerdo_det_contingente_acumulado_pais
		);
		if (!$result['success']) {
			return $result;
		}

		$result = $this->createDeduction(
			$acuerdo_det_id,
			$acuerdo_det_acuerdo_id,
			$acuerdo_det_desgravacion_igual_pais
		);
		if (!$result['success']) {
			return $result;
		}

		return ['success' => true];
	}

	public function setData($params, $action)
	{
		extract($params);
		$this->contingenteRepo  = new ContingenteRepo;
		$this->desgravacionRepo = new DesgravacionRepo;

		$acuerdo_det_productos = (empty($acuerdo_det_productos) || !is_array($acuerdo_det_productos)) ? [] : $acuerdo_det_productos ;
		$acuerdo_det_contingente_acumulado_pais = (isset($acuerdo_det_contingente_acumulado_pais)) ? $acuerdo_det_contingente_acumulado_pais : '0' ;
		$acuerdo_det_contingente_acumulado_pais = ($acuerdo_det_contingente_acumulado_pais == '1') ? '1' : '0' ;

		$acuerdo_det_desgravacion_igual_pais = (isset($acuerdo_det_desgravacion_igual_pais)) ? $acuerdo_det_desgravacion_igual_pais : '0' ;
		$acuerdo_det_desgravacion_igual_pais = ($acuerdo_det_desgravacion_igual_pais == '1') ? '1' : '0' ;

		if (
			empty($acuerdo_det_productos) ||
			empty($acuerdo_det_productos_desc) ||
			empty($acuerdo_det_nperiodos) ||
			empty($acuerdo_det_acuerdo_id)
		) {
			$result = [
				'success' => false,
				'error'   => 'Incomplete data for this request.'
			];
			return $result;
		}

		if ($action == 'modify') {
			$result = $this->findPrimaryKey($acuerdo_det_id);

			if (!$result['success']) {
				$result = [
					'success'  => false,
					'closeTab' => true,
					'tab'      => 'tab-'.$module,
					'error'    => $result['error']
				];
				return $result;
			}

			$row = array_shift($result['data']);

			//si acuerdo_det_contingente_acumulado_pais o acuerdo_det_nperiodos es diferente debe borrar los contingentes y volverlos a crear
			if (
				$acuerdo_det_contingente_acumulado_pais != $row['acuerdo_det_contingente_acumulado_pais'] || 
				$acuerdo_det_nperiodos != $row['acuerdo_det_nperiodos']
			) {
				$result = $this->deleteQuota(
					$acuerdo_det_id,
					$acuerdo_det_acuerdo_id
				);
				if (!$result['success']) {
					return $result;
				}
				$result = $this->createQuota(
					$acuerdo_det_id,
					$acuerdo_det_acuerdo_id,
					$acuerdo_det_contingente_acumulado_pais
				);
				if (!$result['success']) {
					return $result;
				}
			}

			//si acuerdo_det_desgravacion_igual_pais o acuerdo_det_nperiodos es diferente debe borrar la informacion de desgravacion y volverla a crear
			if (
				$acuerdo_det_desgravacion_igual_pais != $row['acuerdo_det_desgravacion_igual_pais'] || 
				$acuerdo_det_nperiodos != $row['acuerdo_det_nperiodos']
			) {
				$result = $this->deleteDeduction(
					$acuerdo_det_id,
					$acuerdo_det_acuerdo_id
				);
				if (!$result['success']) {
					return $result;
				}
				$result = $this->createDeduction(
					$acuerdo_det_id,
					$acuerdo_det_acuerdo_id,
					$acuerdo_det_desgravacion_igual_pais
				);
				if (!$result['success']) {
					return $result;
				}
			}
		}

		$this->model->setAcuerdo_det_id($acuerdo_det_id);
		$this->model->setAcuerdo_det_arancel_base($acuerdo_det_arancel_base);
		$this->model->setAcuerdo_det_productos(implode(',', $acuerdo_det_productos));
		$this->model->setAcuerdo_det_productos_desc($acuerdo_det_productos_desc);
		$this->model->setAcuerdo_det_administracion($acuerdo_det_administracion);
		$this->model->setAcuerdo_det_administrador($acuerdo_det_administrador);
		$this->model->setAcuerdo_det_nperiodos($acuerdo_det_nperiodos);
		$this->model->setAcuerdo_det_acuerdo_id($acuerdo_det_acuerdo_id);
		$this->model->setAcuerdo_det_contingente_acumulado_pais($acuerdo_det_contingente_acumulado_pais);
		$this->model->setAcuerdo_det_desgravacion_igual_pais($acuerdo_det_desgravacion_igual_pais);

		if ($action == 'create') {
		} elseif ($action == 'modify') {
		}
		$result = ['success' => true];
		return $result;
	}

	public function listAll($params)
	{
		extract($params);
		$start = ( isset($start) ) ? $start : 0;
		$limit = ( isset($limit) ) ? $limit : MAXREGEXCEL;
		$page  = ( $start==0 ) ? 1 : ( $start/$limit )+1;

		if (!empty($valuesqry) && $valuesqry) {
			$query = explode('|',$query);
			$this->model->setAcuerdo_det_id(implode('", "', $query));
			$this->model->setAcuerdo_det_arancel_base(implode('", "', $query));
			$this->model->setAcuerdo_det_productos(implode('", "', $query));
			$this->model->setAcuerdo_det_productos_desc(implode('", "', $query));
			$this->model->setAcuerdo_det_administracion(implode('", "', $query));
			$this->model->setAcuerdo_det_administrador(implode('", "', $query));
			$this->model->setAcuerdo_det_nperiodos(implode('", "', $query));
			$this->model->setAcuerdo_det_acuerdo_id(implode('", "', $query));
			$this->model->setAcuerdo_det_contingente_acumulado_pais(implode('", "', $query));
			$this->model->setAcuerdo_det_desgravacion_igual_pais(implode('", "', $query));

			return $this->modelAdo->inSearch($this->model);
		}
		else {
			$this->model->setAcuerdo_det_id($query);
			$this->model->setAcuerdo_det_arancel_base($query);
			$this->model->setAcuerdo_det_productos($query);
			$this->model->setAcuerdo_det_productos_desc($query);
			$this->model->setAcuerdo_det_administracion($query);
			$this->model->setAcuerdo_det_administrador($query);
			$this->model->setAcuerdo_det_nperiodos($query);
			$this->model->setAcuerdo_det_acuerdo_id($query);
			$this->model->setAcuerdo_det_contingente_acumulado_pais($query);
			$this->model->setAcuerdo_det_desgravacion_igual_pais($query);

			return $this->modelAdo->paginate($this->model, 'LIKE', $limit, $page);
		}

	}

	public function listByProduct($params)
	{
		extract($params);

		if (empty($product)) {
			$result = [
				'success' => false,
				'error'   => 'Incomplete data for this request.'
			];
			return $result;
		}

		
		$this->model->setAcuerdo_det_productos($product);
		
		return $this->modelAdo->exactSearch($this->model);
		
	}

	public function grid($params)
	{
		extract($params);
		/**/
		$start = ( isset($start) ) ? $start : 0;
		$limit = ( isset($limit) ) ? $limit : MAXREGEXCEL;
		$page  = ( $start==0 ) ? 1 : ( $start/$limit )+1;

		if (empty($acuerdo_det_acuerdo_id)) {
			$result = [
				'success' => false,
				'error'   => 'Incomplete data for this request.'
			];
			return $result;
		}
		$this->model->setAcuerdo_det_acuerdo_id($acuerdo_det_acuerdo_id);

		if (!empty($query)) {
			if (!empty($fullTextFields)) {
				
				$fullTextFields = json_decode(stripslashes($fullTextFields));
				
				foreach ($fullTextFields as $value) {
					$methodName = $this->getColumnMethodName('set', $value);
					
					if (method_exists($this->model, $methodName)) {
						call_user_func_array([$this->model, $methodName], compact('query'));
					}
				}
			} else {
				$this->model->setAcuerdo_det_id($query);
				$this->model->setAcuerdo_det_arancel_base($query);
				$this->model->setAcuerdo_det_productos($query);
				$this->model->setAcuerdo_det_productos_desc($query);
				$this->model->setAcuerdo_det_administracion($query);
				$this->model->setAcuerdo_det_administrador($query);
				$this->model->setAcuerdo_det_nperiodos($query);
				$this->model->setAcuerdo_det_contingente_acumulado_pais($query);
				$this->model->setAcuerdo_det_desgravacion_igual_pais($query);
			}
			
		}

		$this->modelAdo->setColumns([
			'acuerdo_det_id',
			'acuerdo_det_arancel_base',
			'acuerdo_det_productos',
			'acuerdo_det_productos_desc',
			'acuerdo_det_administracion',
			'acuerdo_det_administrador',
			'acuerdo_det_nperiodos',
			'acuerdo_det_acuerdo_id',
			'acuerdo_nombre'
		]);

		$result = $this->modelAdo->paginate($this->model, 'LIKE', $limit, $page);

		return $result;
	}

	public function gridDetailed($params)
	{
		extract($params);
		/**/
		$start = ( isset($start) ) ? $start : 0;
		$limit = ( isset($limit) ) ? $limit : MAXREGEXCEL;
		$page  = ( $start==0 ) ? 1 : ( $start/$limit )+1;

		if (empty($acuerdo_id)) {
			$result = [
				'success' => false,
				'error'   => 'Incomplete data for this request.'
			];
			return $result;
		}
		$this->model->setAcuerdo_det_acuerdo_id($acuerdo_id);

		if (!empty($query)) {
			if (!empty($fullTextFields)) {
				
				$fullTextFields = json_decode(stripslashes($fullTextFields));
				
				foreach ($fullTextFields as $value) {
					$methodName = $this->getColumnMethodName('set', $value);
					
					if (method_exists($this->model, $methodName)) {
						call_user_func_array([$this->model, $methodName], compact('query'));
					}
				}
			} else {
				$this->model->setAcuerdo_det_id($query);
				$this->model->setAcuerdo_det_arancel_base($query);
				$this->model->setAcuerdo_det_productos($query);
				$this->model->setAcuerdo_det_productos_desc($query);
				$this->model->setAcuerdo_det_administracion($query);
				$this->model->setAcuerdo_det_administrador($query);
				$this->model->setAcuerdo_det_nperiodos($query);
				$this->model->setAcuerdo_det_contingente_acumulado_pais($query);
				$this->model->setAcuerdo_det_desgravacion_igual_pais($query);
			}
			
		}

		$updateInfo = Helpers::getUpdateInfo('aduanas', 'impo');

		$year = ( $updateInfo !== false ) ? $updateInfo['dateTo']->format('Y') : date('y') ;

		$result = $this->modelAdo->paginateDetailed($this->model, 'LIKE', $limit, $page, $year);

		if ( ! $result['success'] ) {
			return $result;
		}

		$lines            = Helpers::getRequire(PATH_APP.'lib/indicador.config.php');
		$arrExecuteConfig = Helpers::arrayGet($lines, 'executeConfig.acuerdo_det');
		$arrFiltersName   = Helpers::arrayGet($lines, 'filters.acuerdo_det');

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
		require_once $repoFileName;

		$arrData = [];

		foreach ($result['data'] as $key => $row) {
			$arrFiltros   = [];
			$arrFiltros[] = 'id_pais:'.$row['id_pais'];
			$arrFiltros[] = 'id_posicion:'.$row['acuerdo_det_productos'];
			$params       = [
				'indicador_filtros'        => implode('||', $arrFiltros),
				'tipo_indicador_activador' => 'volumen',
			];

			$repo = new $repoClassName(
				$params,
				$arrFiltersName,
				$year,
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
			if ( ! $rsExecuted['success'] ) {
				return $rsExecuted;
			}
			$executedWeight = 0;
			if ( $rsExecuted['total'] > 0) {
				$rowExecuted = array_shift($rsExecuted['data']);
				//se divide por 1000 para convertir en Toneladas metricas
				$executedWeight = ( $rowExecuted['peso_neto'] / 1000 );
			}
			$rate = ($row['contingente_det_peso_neto'] == 0) ? 0 : ($executedWeight / $row['contingente_det_peso_neto'] ) * 100 ;

			$statusCtgCls = 'good_traffic';
			$statusCtgTxt = Lang::get('alerta.verde');
			$statusSvgCls = 'good_traffic';
			$statusSvgTxt = Lang::get('alerta.verde');
			if ($row['contingente_mcontingente'] == '1' && $row['contingente_det_peso_neto'] > 0) {

				$green  = (float)$row['alerta_contingente_verde'];
				$yellow = (float)$row['alerta_contingente_amarilla'];
				$red    = (float)$row['alerta_contingente_roja'];
				
				if ( $rate <= $yellow && $rate >= $green) {
					$statusCtgCls = 'average_traffic';
					$statusCtgTxt = Lang::get('alerta.amarilla');
				} elseif ( $rate > $yellow ) {
					$statusCtgCls = 'poor_traffic';
					$statusCtgTxt = Lang::get('alerta.roja');
				} else {
					$statusCtgCls = 'good_traffic';
					$statusCtgTxt = Lang::get('alerta.verde');
				}
				if ($row['contingente_msalvaguardia'] == '1') {
					$sobretasa = (float)$row['contingente_salvaguardia_sobretasa'];

					$green   = $red + ( $sobretasa * ( (float)$row['alerta_salvaguardia_verde'] / 100) );
					$yellow  = $red + ( $sobretasa * ( (float)$row['alerta_salvaguardia_amarilla'] / 100) );
					$red     = $red + ( $sobretasa * ( (float)$row['alerta_salvaguardia_roja'] / 100) );

					//var_dump($green, $yellow, $red, $rate);

					if ( $rate <= $yellow && $rate >= $green) {
						$statusSvgCls = 'average_traffic';
						$statusSvgTxt = Lang::get('alerta.amarilla');
					} elseif ( $rate > $yellow ) {
						$statusSvgCls = 'poor_traffic';
						$statusSvgTxt = Lang::get('alerta.roja');
					} else {
						$statusSvgCls = 'good_traffic';
						$statusSvgTxt = Lang::get('alerta.verde');
					}
				}
			}

			$arrData[] = array_merge(
				$row,
				[ 'peso_neto'     => $executedWeight ],
				[ 'ejecutado'     => $rate ],
				[ 'estado_ctg'    => $statusCtgCls ],
				[ 'estado_ctg_tt' => $statusCtgTxt ],
				[ 'estado_svg'    => $statusSvgCls ],
				[ 'estado_svg_tt' => $statusSvgTxt ]
			);

		}

		$result['data'] = $arrData;

		return $result;
	}

	public function listId($params)
	{
		extract($params);
		$acuerdo_det_id = (empty($acuerdo_det_id)) ? '' : $acuerdo_det_id ;

		$result = $this->findPrimaryKey($acuerdo_det_id);

		if ( ! $result['success'] ) {
			return $result;
		}
		$rowAcuerdo_det = array_shift($result['data']);

		$arrProducts  = explode(',', $rowAcuerdo_det['acuerdo_det_productos']);
		$posicionRepo = new PosicionRepo;
		$params = [
			'valuesqry' => true,
			'query'     => implode('|', $arrProducts)
		];

		$result = $posicionRepo->listAll($params);

		if ( ! $result['success'] ) {
			return $result;
		}

		$arrData     = [];

		foreach ($result['data'] as $key => $row) {
			if (in_array($row['id_posicion'], $arrProducts) ) {
				$arrData[] = $row;
			}
		}

		$result = [
			'success'      => true,
			'productsData' => $arrData,
			'data'         => [$rowAcuerdo_det]
		];

		return $result;
	}

	public function listTreebyParent($params)
	{
		extract($params);

		if (empty($acuerdo_id)) {
			return [
				'success' => false,
				'error'   => 'Incomplete data for this request.'
			];
		}

		$this->model->setAcuerdo_det_acuerdo_id($acuerdo_id);
		$result = $this->modelAdo->exactSearch($this->model);
		if (!$result['success']) {
			return $result;
		}

		$arrData = $result['data'];
		$arr     = [];
		foreach ($arrData as $key => $row) {

			$arr[] = [
				'id'   => $row['acuerdo_det_id'],
				'text' => $row['acuerdo_det_productos_desc'],
				'leaf' => true,
			];
			
		}
		$result = $arr;

		return $result;
	}

	public function publicSearch($params)
	{

		extract($params);

		$products  = ( !empty($products) && is_array($products) ) ? $products : [] ;
		$countries = ( !empty($countries) && is_array($countries) ) ? $countries : [] ;
		$trade     = ( empty($trade) ) ? 'impo' : $trade ;

		if (empty($products) || empty($countries)) {
			$result = [
				'success' => false,
				'error'   => 'Incomplete data for this request.'
			];
			return $result;
		}

		$this->acuerdoRepo = new AcuerdoRepo;

		$result = $this->acuerdoRepo->listByCountry( compact('countries', 'trade') );

		if (!$result['success']) {
			return $result;
		}

		$this->acuerdoRepo = new AcuerdoRepo;

		$rowAgreement = array_shift($result['data']);
		$acuerdo_id   = $rowAgreement['acuerdo_id'];
		$result       = $this->acuerdoRepo->listId( compact('acuerdo_id') );

		if (!$result['success']) {
			return $result;
		}
		$rowAgreement = array_shift($result['data']);

		//productos solo deberia venir uno
		$product = array_shift($products);
		$result  = $this->listByProduct(compact('product'));

		if (!$result['success']) {
			return $result;
		}

		$this->contingenteRepo  = new ContingenteRepo;
		$this->desgravacionRepo = new DesgravacionRepo;

		$rowAgreementDet = [];

		foreach ($result['data'] as $key => $row) {
			if ( $row['acuerdo_intercambio'] == $trade && $row['acuerdo_det_acuerdo_id'] == $acuerdo_id ) {

				$rsAgreementDet = $this->listId( [ 'acuerdo_det_id' => $row['acuerdo_det_id'] ] );

				if (!$rsAgreementDet['success']) {
					return $rsAgreementDet;
				}

				$acuerdo_id     = $row['acuerdo_det_acuerdo_id'];
				$acuerdo_det_id = $row[$this->primaryKey];
				$country        = ( $row['acuerdo_det_contingente_acumulado_pais'] == '1' ) ? '' : array_shift($countries) ;
				$rsQuota        = $this->contingenteRepo->listDetail( compact('acuerdo_id', 'acuerdo_det_id', 'country') );

				if (!$rsQuota['success']) {
					return $rsQuota;
				}

				$rsReduction = $this->desgravacionRepo->listDetail( compact('acuerdo_id', 'acuerdo_det_id', 'country') );

				if (!$rsQuota['success']) {
					return $rsQuota;
				}

				$arrDetail = [];
				if ( $rsQuota['rowContingente']['contingente_mcontingente'] == '1' ) {
					foreach ($rsQuota['arrContingente_det'] as $rowDet) {
						$year = $rowDet['contingente_det_anio_ini'];
						$arrDetail[ $year ] = [
							'year'  => $year,
							'quota' => $rowDet['contingente_det_peso_neto'],
							'duty'  => 0
						];
					}
				}
				if ( $rsReduction['rowDesgravacion']['desgravacion_mdesgravacion'] == '1' ) {
					foreach ($rsReduction['arrDesgravacion_det'] as $rowDet) {
						$year = $rowDet['desgravacion_det_anio_ini'];
						$rowQuota = Helpers::findKeyInArrayMulti(
							$arrDetail,
							'year',
							$year
						);
						if ($rowQuota !== false) {
							//si encuentra el registro en contingentes añade informacion de desgravacion
							$arrDetail[ $year ][ 'duty' ] = $rowDet['desgravacion_det_tasa'];
						} else {
							//si no encuentra el registro crea uno 
							$arrDetail[ $year ] = [
								'year'  => $year,
								'quota' => 0,
								'duty'  => $rowDet['desgravacion_det_tasa']
							];
						}
					}
				}

				//var_dump($rsReduction);
				
				$arrAgreementDet[] = array_merge( 
					$row,
					[
						'productsData'    => $rsAgreementDet['productsData'],
						'rowContingente'  => $rsQuota['rowContingente'],
						'rowDesgravacion' => $rsReduction['rowDesgravacion'],
						'arrDetail'       => $arrDetail, 
					]
				);

			}
		}
		if ( empty($arrAgreementDet) ) {
			return [
				'success' => false,
				'error'   => Lang::get('error.no_records_found')
			];
			return $result;
		}
		$return = [
			'success'         => true,
			'rowAgreement'    => $rowAgreement,
			'arrAgreementDet' => $arrAgreementDet,
			'total'           => count($arrAgreementDet),
		];
		return $return;
		//var_dump($rowAgreementDet, $rowAgreement);

		

		

		var_dump($result);
		//hasta aqui tiene toda la informacion para resumir todo la configuracion del acuerdo

		//var_dump($rowAgreementDet, $rowAgreement);



		$model = $this->getModel();
		$model->setAcuerdo_id( implode('", "', $arrAgreement) );

		$this->modelAdo->setColumns([
			'acuerdo_id',
			'paises_iata',
			'pais_iata',
		]);

		return $this->modelAdo->inSearch($model);

	}

}
