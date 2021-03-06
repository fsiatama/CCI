<?php

require PATH_MODELS.'Entities/Contingente.php';
require PATH_MODELS.'Ado/ContingenteAdo.php';
require_once PATH_MODELS.'Repositories/Contingente_detRepo.php';
require_once PATH_MODELS.'Repositories/Desgravacion_detRepo.php';
//require_once PATH_MODELS.'Repositories/AcuerdoRepo.php';
require_once PATH_MODELS.'Repositories/AlertaRepo.php';
require_once ('BaseRepo.php');

class ContingenteRepo extends BaseRepo {

	private $contingente_detRepo;
	private $alertaRepo;

	public function getModel()
	{
		return new Contingente;
	}
	
	public function getModelAdo()
	{
		return new ContingenteAdo;
	}

	public function getPrimaryKey()
	{
		return 'contingente_id';
	}

	public function validateModify($params)
	{
		extract($params);
		$result = $this->findPrimaryKey($contingente_id);

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

	public function createByAgreement($params)
	{
		extract($params);
		$acuerdoRepo = new AcuerdoRepo;
		$acuerdo_id  = $acuerdo_det_acuerdo_id;
		//verifica que exista el acuerdo y trae los datos incluido un array con los datos de los paises del acuerdo
		$result = $acuerdoRepo->listId(compact('acuerdo_id'));
		if (!$result['success']) {
			return $result;
		}
		$rowAcuerdo   = array_shift($result['data']);
		$country_data = $result['country_data'];

		$countryAccumulated = ($acuerdo_det_contingente_acumulado_pais == '1') ? true : false ;

		$this->alertaRepo = new AlertaRepo;

		if ($countryAccumulated) {
			$params = [
				'contingente_id_pais'                => $rowAcuerdo['acuerdo_mercado_id'],
				'contingente_mcontingente'           => '0',
				'contingente_desc'                   => '',
				'contingente_msalvaguardia'          => '0',
				'contingente_salvaguardia_sobretasa' => 0,
				'contingente_acuerdo_det_id'         => $acuerdo_det_id,
				'contingente_acuerdo_det_acuerdo_id' => $acuerdo_det_acuerdo_id,
			];
			$result = $this->create($params);
			if (!$result['success']) {
				return $result;
			}

			//crear registro de alerta
			$params = [
				'alerta_contingente_verde' => '0',
				'alerta_contingente_amarilla' => '0',
				'alerta_contingente_roja' => '0',
				'alerta_salvaguardia_verde' => '0',
				'alerta_salvaguardia_amarilla' => '0',
				'alerta_salvaguardia_roja' => '0',
				'alerta_emails' => '',
				'alerta_contingente_id' => $result['insertId'],
				'alerta_contingente_acuerdo_det_id' => $acuerdo_det_id,
				'alerta_contingente_acuerdo_det_acuerdo_id' => $acuerdo_det_acuerdo_id,
			];

			$result = $this->alertaRepo->create($params);
			if (!$result['success']) {
				return $result;
			}
			
		} else {
			foreach ($country_data as $key => $row) {
				$this->model = $this->getModel();
				$params = [
					'contingente_id_pais'                => $row['id_pais'],
					'contingente_mcontingente'           => '0',
					'contingente_desc'                   => '',
					'contingente_msalvaguardia'          => '0',
					'contingente_salvaguardia_sobretasa' => 0,
					'contingente_acuerdo_det_id'         => $acuerdo_det_id,
					'contingente_acuerdo_det_acuerdo_id' => $acuerdo_det_acuerdo_id,
				];
				$result = $this->create($params);
				if (!$result['success']) {
					return $result;
				}
				//crear registro de alerta
				$params = [
					'alerta_contingente_verde' => '0',
					'alerta_contingente_amarilla' => '0',
					'alerta_contingente_roja' => '0',
					'alerta_salvaguardia_verde' => '0',
					'alerta_salvaguardia_amarilla' => '0',
					'alerta_salvaguardia_roja' => '0',
					'alerta_emails' => '',
					'alerta_contingente_id' => $result['insertId'],
					'alerta_contingente_acuerdo_det_id' => $acuerdo_det_id,
					'alerta_contingente_acuerdo_det_acuerdo_id' => $acuerdo_det_acuerdo_id,
				];

				$result = $this->alertaRepo->create($params);
				if (!$result['success']) {
					return $result;
				}
			}
		}
		return $result;
	}

	public function deleteByParent($params)
	{
		extract($params);
		if (
			empty($acuerdo_det_acuerdo_id) ||
			empty($acuerdo_det_id)
		) {
			$result = [
				'success' => false,
				'error'   => 'Incomplete data for this request. contingenteRepo  deleteByParent'
			];
			return $result;
		}
		$this->model = $this->getModel();
		//busca todos los contingentes hijos por acuerdo_det_id
		$this->model->setContingente_acuerdo_det_id($acuerdo_det_id);
		$this->model->setContingente_acuerdo_det_acuerdo_id($acuerdo_det_acuerdo_id);
		$result = $this->modelAdo->exactSearch($this->model);
		if (!$result['success']) {
			return $result;
		}

		$this->alertaRepo = new AlertaRepo;
		$this->contingente_detRepo = new Contingente_detRepo;

		$arrData = $result['data'];

		//var_dump('contingentes a borrar',$result['data']);

		//realiza el borrado de cada contingente y sus hijos en contingente_det
		foreach ($arrData as $key => $row) {
			
			//borrado de contingente_det
			$result = $this->deleteQuotas(
				$row['contingente_id'],
				$row['contingente_acuerdo_det_id'],
				$row['contingente_acuerdo_det_acuerdo_id']
			);
			if (!$result['success']) {
				return $result;
			}

			//implementar borrado de alerta
			$result = $this->deleteAlerts(
				$row['contingente_id'],
				$row['contingente_acuerdo_det_id'],
				$row['contingente_acuerdo_det_acuerdo_id']
			);
			if (!$result['success']) {
				return $result;
			}

			$this->model = $this->getModel();
			$result = $this->delete($row);
			if (!$result['success']) {
				return $result;
			}
		}

		return $result;
	}

	private function deleteAlerts($contingente_id, $contingente_acuerdo_det_id, $contingente_acuerdo_det_acuerdo_id)
	{
		$result = $this->alertaRepo->deleteByParent(
			compact(
				'contingente_id',
				'contingente_acuerdo_det_id',
				'contingente_acuerdo_det_acuerdo_id'
			)
		);
		return $result;
	}

	private function deleteQuotas($contingente_id, $contingente_acuerdo_det_id, $contingente_acuerdo_det_acuerdo_id)
	{
		$result = $this->contingente_detRepo->deleteByParent(
			compact(
				'contingente_id',
				'contingente_acuerdo_det_id',
				'contingente_acuerdo_det_acuerdo_id'
			)
		);
		return $result;
	}

	private function createQuotas($contingente_id, $contingente_acuerdo_det_id, $contingente_acuerdo_det_acuerdo_id)
	{
		$result = $this->contingente_detRepo->createByAgreementDet(
			compact(
				'contingente_id',
				'contingente_acuerdo_det_id',
				'contingente_acuerdo_det_acuerdo_id'
			)
		);
		return $result;
	}

	public function setData($params, $action)
	{
		extract($params);

		if ($action == 'modify') {
			$this->contingente_detRepo = new Contingente_detRepo;

			$result = $this->findPrimaryKey($contingente_id);

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
			if ($contingente_mcontingente != $row['contingente_mcontingente']) {
				$result = $this->deleteQuotas(
					$contingente_id,
					$contingente_acuerdo_det_id,
					$contingente_acuerdo_det_acuerdo_id
				);
				if (!$result['success']) {
					return $result;
				}

				if ($contingente_mcontingente === '1') {
					$result = $this->createQuotas(
						$contingente_id,
						$contingente_acuerdo_det_id,
						$contingente_acuerdo_det_acuerdo_id
					);
					if (!$result['success']) {
						return $result;
					}
				}
			}

			$alerta_contingente_verde     = ( empty($alerta_contingente_verde) ) ? '0' : $alerta_contingente_verde ;
			$alerta_contingente_amarilla  = ( empty($alerta_contingente_amarilla) ) ? '0' : $alerta_contingente_amarilla ;
			$alerta_contingente_roja      = ( empty($alerta_contingente_roja) ) ? '0' : $alerta_contingente_roja ;
			$alerta_salvaguardia_verde    = ( empty($alerta_salvaguardia_verde) ) ? '0' : $alerta_salvaguardia_verde ;
			$alerta_salvaguardia_amarilla = ( empty($alerta_salvaguardia_amarilla) ) ? '0' : $alerta_salvaguardia_amarilla ;
			$alerta_salvaguardia_roja     = ( empty($alerta_salvaguardia_roja) ) ? '0' : $alerta_salvaguardia_roja ;
			$alerta_emails                = ( empty($alerta_emails) ) ? '' : $alerta_emails ;

			$this->alertaRepo = new AlertaRepo;
			$params = [
				'alerta_contingente_verde'                  => $alerta_contingente_verde,
				'alerta_contingente_amarilla'               => $alerta_contingente_amarilla,
				'alerta_contingente_roja'                   => $alerta_contingente_roja,
				'alerta_salvaguardia_verde'                 => $alerta_salvaguardia_verde,
				'alerta_salvaguardia_amarilla'              => $alerta_salvaguardia_amarilla,
				'alerta_salvaguardia_roja'                  => $alerta_salvaguardia_roja,
				'alerta_emails'                             => $alerta_emails,
				'alerta_id'                                 => $alerta_id,
				'alerta_contingente_id'                     => $contingente_id,
				'alerta_contingente_acuerdo_det_id'         => $contingente_acuerdo_det_id,
				'alerta_contingente_acuerdo_det_acuerdo_id' => $contingente_acuerdo_det_acuerdo_id,
			];
			$result = $this->alertaRepo->modify($params);
			if (!$result['success']) {
				return $result;
			}
		}

		if (
			empty($contingente_id_pais) ||
			empty($contingente_acuerdo_det_id) ||
			empty($contingente_acuerdo_det_acuerdo_id)
		) {
			$result = [
				'success' => false,
				'error'   => 'Incomplete data for this request.'
			];
			return $result;
		}
		$contingente_msalvaguardia          = (empty($contingente_msalvaguardia)) ? '0' : $contingente_msalvaguardia ;
		$contingente_salvaguardia_sobretasa = (empty($contingente_salvaguardia_sobretasa)) ? '0' : $contingente_salvaguardia_sobretasa ;

		$this->model->setContingente_id_pais($contingente_id_pais);
		$this->model->setContingente_mcontingente($contingente_mcontingente);
		$this->model->setContingente_desc($contingente_desc);
		$this->model->setContingente_msalvaguardia($contingente_msalvaguardia);
		$this->model->setContingente_salvaguardia_sobretasa($contingente_salvaguardia_sobretasa);
		$this->model->setContingente_acuerdo_det_id($contingente_acuerdo_det_id);
		$this->model->setContingente_acuerdo_det_acuerdo_id($contingente_acuerdo_det_acuerdo_id);

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
			$this->model->setContingente_id(implode('", "', $query));
			$this->model->setContingente_id_pais(implode('", "', $query));
			$this->model->setContingente_mcontingente(implode('", "', $query));
			$this->model->setContingente_desc(implode('", "', $query));
			$this->model->setContingente_msalvaguardia(implode('", "', $query));
			$this->model->setContingente_salvaguardia_sobretasa(implode('", "', $query));
			$this->model->setContingente_acuerdo_det_id(implode('", "', $query));
			$this->model->setContingente_acuerdo_det_acuerdo_id(implode('", "', $query));

			return $this->modelAdo->inSearch($this->model);
		}
		else {
			$this->model->setContingente_id($query);
			$this->model->setContingente_id_pais($query);
			$this->model->setContingente_mcontingente($query);
			$this->model->setContingente_desc($query);
			$this->model->setContingente_msalvaguardia($query);
			$this->model->setContingente_salvaguardia_sobretasa($query);
			$this->model->setContingente_acuerdo_det_id($query);
			$this->model->setContingente_acuerdo_det_acuerdo_id($query);

			return $this->modelAdo->paginate($this->model, 'LIKE', $limit, $page);
		}

	}

	public function grid($params)
	{
		extract($params);
		/**/
		$start = ( isset($start) ) ? $start : 0;
		$limit = ( isset($limit) ) ? $limit : 30;
		$page  = ( $start==0 ) ? 1 : ( $start / $limit ) + 1;

		if (empty($contingente_acuerdo_det_id) || empty($contingente_acuerdo_det_acuerdo_id)) {
			$result = [
				'success' => false,
				'error'   => 'Incomplete data for this request.'
			];
			return $result;
		}
		$this->model->setContingente_acuerdo_det_id($contingente_acuerdo_det_id);
		$this->model->setContingente_acuerdo_det_acuerdo_id($contingente_acuerdo_det_acuerdo_id);

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
				$this->model->setContingente_id($query);
				$this->model->setContingente_id_pais($query);
				$this->model->setContingente_mcontingente($query);
				$this->model->setContingente_desc($query);
				$this->model->setContingente_acuerdo_det_id($query);
				$this->model->setContingente_acuerdo_det_acuerdo_id($query);
			}
			
		}

		$this->modelAdo->setColumns([
			'contingente_id',
			'contingente_id_pais',
			'contingente_mcontingente',
			'contingente_mcontingente_title',
			'contingente_desc',
			'contingente_msalvaguardia',
			'contingente_msalvaguardia_title',
			'contingente_salvaguardia_sobretasa',
			'contingente_acuerdo_det_id',
			'contingente_acuerdo_det_acuerdo_id',
			'acuerdo_mercado_id',
			'acuerdo_id_pais',
			'pais',
			'mercado_nombre',
			'acuerdo_det_contingente_acumulado_pais',
			'alerta_id',
			'alerta_contingente_verde',
			'alerta_contingente_amarilla',
			'alerta_contingente_roja',
			'alerta_salvaguardia_verde',
			'alerta_salvaguardia_amarilla',
			'alerta_salvaguardia_roja',
			'alerta_emails'
		]);
		$result = $this->modelAdo->paginate($this->model, 'LIKE', $limit, $page);

		if (!$result['success']) {
			return $result;
		}

		return $result;

	}

	public function execute($params)
	{
		extract($params);

		$year   = (empty($year)) ? '' : $year ;
		$period = (empty($period)) ? 12 : $period ;
		$format = (empty($format)) ? false : $format ;
		$fields = (empty($fields)) ? [] : json_decode(stripslashes($fields), true) ;
		$scope  = (empty($scope)) ? 1 : $scope ;

		if (
			empty($acuerdo_id) ||
			empty($acuerdo_det_id) ||
			empty($contingente_id) ||
			empty($desgravacion_id)
		) {
			$result = [
				'success' => false,
				'error'   => 'Incomplete data for this request. contingenteRepo  execute'
			];
			return $result;
		}
		$this->model->setContingente_acuerdo_det_id($acuerdo_det_id);
		$this->model->setContingente_acuerdo_det_acuerdo_id($acuerdo_id);
		$this->model->setContingente_id($contingente_id);

		$result = $this->modelAdo->exactSearch($this->model);
		if (!$result['success']) {
			return $result;
		}
		//la consulta solo deberia arrojar un registro
		$arrContingente = array_shift($result['data']);
		$trade          = $arrContingente['acuerdo_intercambio'];

		/************************************ busca el registro detalle de desgravacion ************************************/

		$desgravacion_detRepo = new Desgravacion_detRepo;
		$params = [
			'desgravacion_id'                     => $desgravacion_id,
			'desgravacion_acuerdo_det_id'         => $acuerdo_det_id,
			'desgravacion_acuerdo_det_acuerdo_id' => $acuerdo_id,
			'year'                               => $year,
		];
		$result = $desgravacion_detRepo->listId($params);
		if (!$result['success']) {
			return $result;
		}
		$arrDesgravacion_det        = array_shift( $result['data'] );
		$desgravacion_det_tasa_intra    = ( empty($arrDesgravacion_det) ) ? 0 : (float)$arrDesgravacion_det['desgravacion_det_tasa_intra'] ;
		$desgravacion_det_tasa_extra    = ( empty($arrDesgravacion_det) ) ? 0 : (float)$arrDesgravacion_det['desgravacion_det_tasa_extra'] ;
		$desgravacion_mdesgravacion = ( empty($arrDesgravacion_det) ) ? '0' : $arrDesgravacion_det['desgravacion_mdesgravacion'] ;

		/************************************ busca el registro detalle de contingente ************************************/

		$this->contingente_detRepo = new Contingente_detRepo;
		$params = [
			'contingente_id'                     => $arrContingente['contingente_id'],
			'contingente_acuerdo_det_id'         => $arrContingente['contingente_acuerdo_det_id'],
			'contingente_acuerdo_det_acuerdo_id' => $arrContingente['contingente_acuerdo_det_acuerdo_id'],
			'year'                               => $year,
		];
		$result = $this->contingente_detRepo->listId($params);
		if (!$result['success']) {
			return $result;
		}

		//la consulta solo deberia arrojar un registro
		$arrContingente_det = array_shift( $result['data'] );
		$quotaWeight        = ( empty( $arrContingente_det['contingente_det_peso_neto'] ) ) ? 0 : (float)$arrContingente_det['contingente_det_peso_neto'] ;
		$safeguardWeight    = 0;
		if ($arrContingente['contingente_msalvaguardia'] == '1') {
			$safeguard       = (float)$arrContingente['contingente_salvaguardia_sobretasa'];
			$safeguardWeight = $quotaWeight * ( ( $safeguard / 100 ) );
		}

		/**************************************************************************************************************/

		$lines            = Helpers::getRequire(PATH_APP.'lib/indicador.config.php');
		$methodName       = ( $source == 'bol' ) ? 'acuerdo_det_bol' : 'acuerdo_det';
		//var_dump($methodName, $source);
		$arrExecuteConfig = Helpers::arrayGet($lines, 'executeConfig.'. $methodName );
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
		require $repoFileName;

		$arrFiltros   = [];
		$arrFiltros[] = 'id_pais:'.$arrContingente['id_pais'];
		$arrFiltros[] = 'id_posicion:'.$arrContingente['acuerdo_det_productos'];
		$params       = [
			'indicador_filtros'        => implode('||', $arrFiltros),
			'tipo_indicador_activador' => 'volumen',
		];

		$repo = new $repoClassName(
			$params,
			$arrFiltersName,
			$year,
			$period,
			$scope
		);
		if (!method_exists($repo, $repoMethodName)) {
			return [
				'success' => false,
				'error'   => 'unavailable method '. $repoMethodName
			];
		}
		$result = call_user_func_array([$repo, $repoMethodName], []);

		if (!$result['success']) {
			return $result;
		}
		
		$arrFinal            = [];
		$gaugeChart          = [];
		$safeguardGaugeChart = false;

		if ($result['total'] == 0) {
			
			$arrFinal[] = [
				'id'               => $arrContingente['contingente_id'],
				'periodo'          => $year,
				'executedWeight'   => 0,
				'executedRate'     => 0,
				'cumulativeWeight' => 0,
				'cumulativeRate'   => 0,
			];

			$gaugeChart = $this->getGaugeData($arrContingente, 0, $quotaWeight, $arrContingente['pais']);

		} else {

			$arrDeclaraciones      = $result['data'];
			$arrData               = [];
			$cumulativeRate        = 0;
			$cumulativeWeight      = 0;
			$cumulativeRateExtra   = 0;
			$cumulativeWeightExtra = 0;

			foreach ($arrDeclaraciones as $data) {

				if (!isset($executedWeightIntra[$data['periodo']])) {
					$executedWeightIntra[$data['periodo']] = 0;
					$executedRateIntra[$data['periodo']]   = 0;
					$executedWeightExtra[$data['periodo']] = 0;
					$executedRateExtra[$data['periodo']]   = 0;
				}


				$weight     = ( (float)$data['peso_neto'] / 1000 );
				$tariffRate = ( isset($data['porcentaje_arancel']) ) ? (float)$data['porcentaje_arancel'] : 0;

				if ($trade == 'impo') {
					if ($desgravacion_mdesgravacion == '1') {
						# si maneja desgravacion el valor de declaraimp.porcentaje_arancel debe ser igual 
						# al de la desgravacion intracontingente, de lo contrario se medira como peso 
						# extracontingente
						if ( $desgravacion_det_tasa_intra == $tariffRate ) {
							$executedWeightIntra[$data['periodo']] += $weight;
						} else {
							$executedWeightExtra[$data['periodo']] += $weight;
						}
					}
				} else {
					# si es expo, se sumara todo como peso intra contingente ya que no se puede
					# determinar si pago o no el porcentaje adecuado de arancel
					$executedWeightIntra[$data['periodo']] += $weight;
				}

				$executedRateIntra[$data['periodo']] = ($quotaWeight == 0) ? 0 : ( $executedWeightIntra[$data['periodo']] / $quotaWeight ) * 100 ;
				$executedRateExtra[$data['periodo']] = ($quotaWeight == 0) ? 0 : ( $executedWeightExtra[$data['periodo']] / $quotaWeight ) * 100 ;

				$arrData[$data['periodo']] = [
					'id'                  => $arrContingente['contingente_id'],
					'periodo'             => $data['periodo'],
					'executedWeightIntra' => $executedWeightIntra[$data['periodo']],
					'executedRateIntra'   => $executedRateIntra[$data['periodo']],
					'executedWeightExtra' => $executedWeightExtra[$data['periodo']],
					'executedRateExtra'   => $executedRateExtra[$data['periodo']],
				];

			}

			foreach ($arrData as $key => $row) {

				$cumulativeWeight += $row['executedWeightIntra'];
				$cumulativeRate   += $row['executedRateIntra'];

				$cumulativeWeightExtra += $row['executedWeightExtra'];
				$cumulativeRateExtra   += $row['executedRateExtra'];

				$arrFinal[] = [
					'id'               => $row['id'],
					'periodo'          => $row['periodo'],
					'executedWeightIntra'   => $row['executedWeightIntra'],
					'executedRateIntra'     => $row['executedRateIntra'],
					'executedWeightExtra'   => $row['executedWeightExtra'],
					'executedRateExtra'     => $row['executedRateExtra'],
					'cumulativeWeight' => $cumulativeWeight,
					'cumulativeRate'   => $cumulativeRate,
				];
			}

			$gaugeChart = $this->getGaugeData($arrContingente, $cumulativeRate, $quotaWeight, $arrContingente['pais']);
			if ($arrContingente['contingente_msalvaguardia']) {
				$safeguardGaugeChart = $this->getGaugeData($arrContingente, $cumulativeRateExtra, $quotaWeight, $arrContingente['pais'], true);
			}
		}

		$desgravacion_det_tasa = ($desgravacion_mdesgravacion == '1') ? $desgravacion_det_tasa_intra : false ;


		$result = [
			'success'                 => true,
			'data'                    => $arrFinal,
			'gaugeChartData'          => $gaugeChart,
			'safeguardGaugeChartData' => $safeguardGaugeChart,
			'quotaWeight'             => $quotaWeight,
			'safeguardWeight'         => $safeguardWeight,
			'tariffRate'              => $desgravacion_det_tasa,
			'total'                   => count($arrFinal)
		];

		if ($format !== false && !empty($fields) && $result['total'] > 0) {
			$arrDescription   = [];
			$arrDescription['title'] = $arrContingente['acuerdo_nombre'];
			$arrDescription[Lang::get('acuerdo.partner_title')]  = Lang::get('acuerdo.partner_title') . ': ' . $arrContingente['pais'];
			$arrDescription[Lang::get('acuerdo_det.table_name')] = Lang::get('acuerdo_det.table_name') . ': ' . $arrContingente['acuerdo_det_productos_desc'];

			$excel = new Excel (
				$result,
				$format,
				$fields,
				$arrContingente['acuerdo_nombre'],
				$arrDescription
			);
			$result = $excel->write();
		}

		return $result;

	}

	public function listDetail($params)
	{
		extract($params);

		if (
			empty($acuerdo_id) ||
			empty($acuerdo_det_id)
		) {
			$result = [
				'success' => false,
				'error'   => 'Incomplete data for this request. contingenteRepo  execute'
			];
			return $result;
		}

		$this->model->setContingente_acuerdo_det_id($acuerdo_det_id);
		$this->model->setContingente_acuerdo_det_acuerdo_id($acuerdo_id);
		if ( !empty($country) ) {
			$this->model->setContingente_id_pais($country);
		}

		$result = $this->modelAdo->exactSearch($this->model);
		if (!$result['success']) {
			return $result;
		}
		//la consulta solo deberia arrojar un registro
		$rowContingente                     = array_shift($result['data']);
		$contingente_id                     = $rowContingente['contingente_id'];
		$contingente_acuerdo_det_id         = $rowContingente['contingente_acuerdo_det_id'];
		$contingente_acuerdo_det_acuerdo_id = $rowContingente['contingente_acuerdo_det_acuerdo_id'];

		$this->contingente_detRepo = new Contingente_detRepo;
		$result = $this->contingente_detRepo->listId( compact('contingente_id', 'contingente_acuerdo_det_id', 'contingente_acuerdo_det_acuerdo_id') );
		if (!$result['success']) {
			return $result;
		}

		$result = [
			'success' => true,
			'rowContingente' => $rowContingente,
			'arrContingente_det' => $result['data'],
		];

		return $result;
	}

	private function getGaugeData($row, $dial, $quotaWeight, $title, $safeguard = false)
	{
		//var_dump($row);
		$arr = [];
		if ($row['contingente_mcontingente'] == '1' && $quotaWeight > 0) {
			$upperLimit = 100;			

			$green  = (float)$row['alerta_contingente_verde'];
			$green  = ($green < 1) ? 60 : $green;
			
			$yellow = (float)$row['alerta_contingente_amarilla'];
			$yellow = ($yellow < $green) ? 90 : $yellow;
			
			$red    = (float)$row['alerta_contingente_roja'];
			$red    = ($red < $yellow) ? 100 : $red;

			$colors = [
				[
					'minValue' => '0',
					'maxValue' => $green,
					'code'     => '#6baa01',
				],[
					'minValue' => $green,
					'maxValue' => $yellow,
					'code'     => '#f8bd19',
				],[
					'minValue' => $yellow,
					'maxValue' => $red,
					'code'     => '#e44a00',
				]
			];

			$trendPoints = [
				[
					'startValue'   => 0,
					'displayValue' => ' ',
					'showValues'   => 0,
					'color'        => '#0075c2',
					'useMarker'    => '1',
				],[
					'startValue'   => $row['alerta_contingente_roja'],
					'displayValue' => ' ',
					'showValues'   => 0,
					'color'        => '#0075c2',
					'useMarker'    => '1',
				],[
					'startValue'     => 0,
					'endValue'       => $row['alerta_contingente_roja'],
					'displayValue'   => ' ',
					'alpha'          => 1,
					'markerTooltext' => Lang::get('contingente.contingente'),
					'displayValue'   => Lang::get('contingente.contingente')
					//'color'        => '#0075c2'
				],[
					'startValue'   => $row['alerta_contingente_roja'],
					//'dashed'     => 1,
					'displayValue' => ' ',
					'showValues'   => 0,
					'color'        => '#0075c2',
					'useMarker'    => '1',
				]
			];

			$colorsSalvag      = [];
			$trendPointsSalvag = [];
			if ($row['contingente_msalvaguardia'] == '1') {
				$sobretasa   = (float)$row['contingente_salvaguardia_sobretasa'];
				//$upperLimit += $sobretasa;
				
				$greenS  = (float)$row['alerta_salvaguardia_verde'];
				$greenS  = ($greenS < 1) ? 70 : $greenS;
				$greenS  = $sobretasa * ($greenS / 100);
				
				$yellowS = (float)$row['alerta_salvaguardia_amarilla'];
				$yellowS = ($yellowS < $greenS) ? 90 : $yellowS;
				$yellowS = $sobretasa * ($yellowS / 100);
				
				$redS    = (float)$row['alerta_salvaguardia_roja'];
				$redS    = ($redS < $yellowS) ? 100 : $redS;
				$redS    = $sobretasa * ($redS / 100);

				$colorsSalvag = [
					[
						'minValue' => 0,
						'maxValue' => ($greenS),
						'code'     => '#399e38',
					],[
						'minValue' => ($greenS),
						'maxValue' => ($yellowS),
						'code'     => '#e48739',
					],[
						'minValue' => ($yellowS),
						'maxValue' => ($redS),
						'code'     => '#b41527',
					]
				];

				$trendPointsSalvag = [
					[
						'startValue'   => 0,
						'displayValue' => ' ',
						'showValues'   => 0,
						'color'        => '#0075c2',
						'useMarker'    => '1',
					],[
						'startValue'   => $row['alerta_salvaguardia_roja'],
						'displayValue' => ' ',
						'showValues'   => 0,
						'color'        => '#0075c2',
						'useMarker'    => '1',
					],[
						'startValue'     => 0,
						'endValue'       => $row['alerta_salvaguardia_roja'],
						'displayValue'   => ' ',
						'alpha'          => 1,
						'markerTooltext' => Lang::get('contingente.salvaguardia'),
						'displayValue'   => Lang::get('contingente.salvaguardia')
					],[
						'startValue'   => $row['alerta_salvaguardia_roja'],
						//'dashed'     => 1,
						'displayValue' => ' ',
						'showValues'   => 0,
						'color'        => '#0075c2',
						'useMarker'    => '1',
					]
				];
			}

			$colors      = (! $safeguard) ? $colors : $colorsSalvag ;
			$trendPoints = (! $safeguard) ? $trendPoints : $trendPointsSalvag ;
			$title       = (! $safeguard) ? $title : ' ' ;
			//$trendPoints = array_merge($trendPoints, $trendPointsSalvag);

			$upperLimit = ($dial > $upperLimit) ? ($dial + 5) : $upperLimit ;
			$arr = [
				'chart' => [
					'theme'             => 'fint',
					'lowerLimit'        => '0',
					'upperLimit'        => $upperLimit,
					'caption'           => $title,
					'subcaption'        => '',
					'showValue'         => '1',
					'exportenabled'     => '1',
					'numberSuffix'      => '%',
					'valueFontSize'     => '11',
					'valueFontBold'     => '0',
					'captionPadding'    => '20',
					'majorTMColor'      => '333333',
					'majorTMAlpha'      => '100',
					'majorTMHeight'     => '10',
					'majorTMThickness'  => '2',
					'minorTMColor'      => '666666',
					'minorTMAlpha'      => '100',
					'minorTMHeight'     => '7',
					'minorTMThickness'  => '7',
					/*'tickMarkDistance'  => '10',
					'tickValueDistance' => '10',*/
					'majorTMNumber'     => '14',
				],
				'colorRange' => [
					'color' => $colors
				],
				'pointers' => [
					'pointer' => [
						['value' => $dial]
					]
				],
				'trendPoints' => [
					'point' => $trendPoints
				],
				'tickValues' => [
					['value' => $dial]
				]
			];
		}
		return $arr;
	}

}
