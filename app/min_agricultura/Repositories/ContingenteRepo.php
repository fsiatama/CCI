<?php

require PATH_MODELS.'Entities/Contingente.php';
require PATH_MODELS.'Ado/ContingenteAdo.php';
require_once PATH_MODELS.'Repositories/Contingente_detRepo.php';
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

		$arrData = [];

		foreach ($result['data'] as $key => $row) {
			$pais = ($row['acuerdo_det_contingente_acumulado_pais'] == '0') ? $row['pais'] : $row['mercado_nombre'] ;
			$arrData[] = [
				'contingente_id'                     => $row['contingente_id'],
				'contingente_id_pais'                => $row['contingente_id_pais'],
				'pais'                               => $pais,
				'contingente_mcontingente'           => $row['contingente_mcontingente'],
				'contingente_mcontingente_title'     => $row['contingente_mcontingente_title'],
				'contingente_desc'                   => $row['contingente_desc'],
				'contingente_msalvaguardia'          => $row['contingente_msalvaguardia'],
				'contingente_msalvaguardia_title'    => $row['contingente_msalvaguardia_title'],
				'contingente_salvaguardia_sobretasa' => $row['contingente_salvaguardia_sobretasa'],
				'contingente_acuerdo_det_id'         => $row['contingente_acuerdo_det_id'],
				'contingente_acuerdo_det_acuerdo_id' => $row['contingente_acuerdo_det_acuerdo_id'],
				'alerta_id'                          => $row['alerta_id'],
				'alerta_contingente_verde'           => $row['alerta_contingente_verde'],
				'alerta_contingente_amarilla'        => $row['alerta_contingente_amarilla'],
				'alerta_contingente_roja'            => $row['alerta_contingente_roja'],
				'alerta_salvaguardia_verde'          => $row['alerta_salvaguardia_verde'],
				'alerta_salvaguardia_amarilla'       => $row['alerta_salvaguardia_amarilla'],
				'alerta_salvaguardia_roja'           => $row['alerta_salvaguardia_roja'],
				'alerta_emails'                      => $row['alerta_emails'],
			];
		}

		$result['data'] = $arrData;

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
			empty($acuerdo_det_id)
		) {
			$result = [
				'success' => false,
				'error'   => 'Incomplete data for this request. contingenteRepo  execute'
			];
			return $result;
		}
		$acuerdo_detRepo = new Acuerdo_detRepo;
		$result = $acuerdo_detRepo->listId($params);

		if (!$result['success']) {
			return $result;
		}
		$rowAcuerdo_det = array_shift($result['data']);

		$acuerdoRepo = new AcuerdoRepo;
		$result      = $acuerdoRepo->listId(compact('acuerdo_id'));
		if (!$result['success']) {
			return $result;
		}
		$rowAcuerdo     = array_shift($result['data']);
		$countryData    = $result['country_data'];
		$arrCountriesId = array_column($countryData, 'id_pais');

		
		$lines            = Helpers::getRequire(PATH_APP.'lib/indicador.config.php');
		$arrExecuteConfig = Helpers::arrayGet($lines, 'executeConfig.contingente');
		$arrFiltersName   = Helpers::arrayGet($lines, 'filters.contingente');

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

		$arrFiltros   = [];
		$arrFiltros[] = 'id_pais:'.implode(',', $arrCountriesId);
		$arrFiltros[] = 'id_posicion:'.$rowAcuerdo_det['acuerdo_det_productos'];
		//se envia como informacion adicional la llave de contingente en arrFiltros para buscar el detalle, no funciona como filtro en declaraciones ni sobordos
		$arrFiltros[] = 'acuerdo_det_contingente_acumulado_pais:'.$rowAcuerdo_det['acuerdo_det_contingente_acumulado_pais'];

		$params = [
			'indicador_filtros'        => implode('||', $arrFiltros),
			'tipo_indicador_activador' => 'volumen',
		];

		require $repoFileName;

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

		return $result;

		$arrExecutedData   = $result['data'];
		$arrCumulativeData = $result['cumulativeData'];
		var_dump($result);



		//busca todos los contingentes hijos por acuerdo_det_id
		$this->model->setContingente_acuerdo_det_id($acuerdo_det_id);
		$this->model->setContingente_acuerdo_det_acuerdo_id($acuerdo_id);
		$result = $this->modelAdo->exactSearch($this->model);
		if (!$result['success']) {
			return $result;
		}

		$this->contingente_detRepo = new Contingente_detRepo;
		
		$arrData = [];

		foreach ($result['data'] as $key => $row) {
			$quotaWeight = 0;
			if ($row['contingente_mcontingente'] == '1') {
				$params = [
					'contingente_id'                     => $row['contingente_id'],
					'contingente_acuerdo_det_id'         => $row['contingente_acuerdo_det_id'],
					'contingente_acuerdo_det_acuerdo_id' => $row['contingente_acuerdo_det_acuerdo_id'],
					'year'                               => $year,
				];
				$result = $this->contingente_detRepo->listId($params);
				if (!$result['success']) {
					return $result;
				}
				if (empty($result['data'])) {
					//si no hay datos, significa un error debido a que si maneja contingente pero no existen hijos en contingente_det
					return [
						'success' => false,
						'error'   => 'Not found Quota detail configuration'
					];
				}
				//esta consulta deberia arrojar un solo registro
				$rowQuota    = array_shift($result['data']);
				$quotaWeight = $rowQuota['contingente_det_peso_neto'];
			}

			/*$totalWeight = 0;
			$id_pais_ant = '';

			foreach ($arrExecutedData as $data) {
				if (empty($data['id_pais']) || $data['id_pais'] == $row['contingente_id_pais']) {

					$id_pais = ($rowAcuerdo_det['acuerdo_det_contingente_acumulado_pais'] == '1') ? $rowAcuerdo['acuerdo_mercado_id'] : $data['id_pais'] ;
					$pais    = ($rowAcuerdo_det['acuerdo_det_contingente_acumulado_pais'] == '1') ? $rowAcuerdo['mercado_nombre'] : $data['pais'] ;

					if ($id_pais_ant != $id_pais) {
						$totalWeight = 0;
						$id_pais_ant = $id_pais;
					}
					$totalWeight += (float)$data['peso_neto'];
					$arrData[] = [
						'id'                  => $data['id'],
						'periodo'             => $data['periodo'],
						'pais'                => $pais,
						'id_pais'             => $id_pais,
						'peso_neto'           => $data['peso_neto'],
						'peso_neto_acumulado' => $totalWeight,
						'contingente'         => $quotaWeight,
					];
				}
				/*if ($rowAcuerdo_det['acuerdo_det_contingente_acumulado_pais'] == '1') {
					$arrData[] = [
						'id'          => $data['id'],
						'periodo'     => $data['periodo'],
						'pais'        => $rowAcuerdo['mercado_nombre'],
						'id_pais'     => $rowAcuerdo['acuerdo_mercado_id'],
						'peso_neto'   => $data['peso_neto'],
						'contingente' => $quotaWeight,
					];
				} elseif ($data['id_pais'] == $row['contingente_id_pais']) {
				}
			}*/
		}

		var_dump($arrData);







		if ($format !== false && !empty($fields) && $result['total'] > 0) {
			$arrDescription   = [];
			$arrDescription['title'] = $rowAcuerdo_det['acuerdo_nombre'];
			$arrDescription[Lang::get('acuerdo_det.table_name')] = $rowAcuerdo_det['acuerdo_det_productos_desc'];

			$excel = new Excel (
				$result,
				$format,
				$fields,
				$rowAcuerdo_det['acuerdo_nombre'],
				$arrDescription
			);
			$result = $excel->write();
		}
			

		return $result;

	}

}
