<?php

require PATH_MODELS.'Entities/Contingente.php';
require PATH_MODELS.'Ado/ContingenteAdo.php';
require_once PATH_MODELS.'Repositories/Contingente_detRepo.php';
require_once PATH_MODELS.'Repositories/AlertaRepo.php';
require_once ('BaseRepo.php');

class ContingenteRepo extends BaseRepo {

	private $contingente_detRepo;

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

		$alertaRepo = new AlertaRepo;

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
				'alerta_contingente_verde' => '60',
				'alerta_contingente_amarilla' => '90',
				'alerta_contingente_roja' => '100',
				'alerta_salvaguardia_verde' => '70',
				'alerta_salvaguardia_amarilla' => '90',
				'alerta_salvaguardia_roja' => '100',
				'alerta_emails' => '',
				'alerta_contingente_id' => $result['insertId'],
				'alerta_contingente_acuerdo_det_id' => $acuerdo_det_id,
				'alerta_contingente_acuerdo_det_acuerdo_id' => $acuerdo_det_acuerdo_id,
			];

			$result = $alertaRepo->create($params);
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
					'alerta_contingente_verde' => '60',
					'alerta_contingente_amarilla' => '90',
					'alerta_contingente_roja' => '100',
					'alerta_salvaguardia_verde' => '70',
					'alerta_salvaguardia_amarilla' => '90',
					'alerta_salvaguardia_roja' => '100',
					'alerta_emails' => '',
					'alerta_contingente_id' => $result['insertId'],
					'alerta_contingente_acuerdo_det_id' => $acuerdo_det_id,
					'alerta_contingente_acuerdo_det_acuerdo_id' => $acuerdo_det_acuerdo_id,
				];

				$result = $alertaRepo->create($params);
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
		//busca todos los contingentes hijos por acuerdo_det_id
		$this->model->setContingente_acuerdo_det_id($acuerdo_det_id);
		$this->model->setContingente_acuerdo_det_acuerdo_id($acuerdo_det_acuerdo_id);
		$result = $this->modelAdo->exactSearch($this->model);
		if (!$result['success']) {
			return $result;
		}

		//realiza el borrado de cada contingente y sus hijos en contingente_det
		foreach ($result['data'] as $key => $row) {
			//implementar borrado de contingente_det


			$this->model = $this->getModel();
			$primaryKey  = $row[$this->primaryKey];

			$result = $this->findPrimaryKey($primaryKey);
			if ($result['success']) {
				$result = $this->modelAdo->delete($this->model);
			}
		}

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
			'alerta_id',
			'alerta_contingente_verde',
			'alerta_contingente_amarilla',
			'alerta_contingente_roja',
			'alerta_salvaguardia_verde',
			'alerta_salvaguardia_amarilla',
			'alerta_salvaguardia_roja',
			'alerta_emails',
		]);
		$result = $this->modelAdo->paginate($this->model, 'LIKE', $limit, $page);

		if (!$result['success']) {
			return $result;
		}

		$arrData = [];

		foreach ($result['data'] as $key => $row) {
			$pais = (empty($row['acuerdo_mercado_id'])) ? $row['pais'] : $row['mercado_nombre'] ;
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

}
