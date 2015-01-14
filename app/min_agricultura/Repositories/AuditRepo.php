<?php

require PATH_MODELS.'Entities/Audit.php';
require PATH_MODELS.'Ado/AuditAdo.php';
require_once ('BaseRepo.php');

class AuditRepo extends BaseRepo {

	public function getModel()
	{
		return new Audit;
	}
	
	public function getModelAdo()
	{
		return new AuditAdo;
	}

	public function getPrimaryKey()
	{
		return 'audit_id';
	}

	public function validateModify($params)
	{
		extract($params);
		$result = $this->findPrimaryKey($audit_id);

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

	public function create($params)
	{
		$result = $this->setData($params, 'create');
		if (!$result['success']) {
			return $result;
		}

		$result = $this->modelAdo->create($this->model);
		if ($result['success']) {
			return ['success' => true];
		}

		return $result;
	}

	public function setData($params, $action)
	{
		//extract($params);

		$arrDebug  = debug_backtrace();
		$arrScript = [];
		$arrMethod = [];
		$levelFive = false;

		foreach ($arrDebug as $key => $row) {
			//por el nivel de abstraccion las clases que intervienen entan en la 
			//posicion 3 y 4
			if ($key == 3) {
				$arrScript[] = $row['file'];
				$arrMethod[] = $row['class'] . ' => ' . $row['function'];
			}
			if ($key == 4) {
				$arrMethod[] = $row['class'] . ' => ' . $row['function'];
				if (!empty($row['file'])) {
					//si en el nivel 4 de abstraccion existe un archivo, debe recojer el metodo del nivel 5
					$arrScript[] = $row['file'];
					$levelFive = true;
				}
			}
			if ($key == 5 && $levelFive) {
				$arrMethod[] = $row['class'] . ' => ' . $row['function'];
			}
		}

		$audit_script     = implode('||', $arrScript);
		$audit_method     = implode('||', $arrMethod);
		$audit_parameters = '';

		foreach ($params as $key => $value) {
			if (is_array($value)) {
				$value = implode(',', $value);
			} else {
				$value = (empty($value)) ? '' : addslashes($value) ;
			}
			
			$audit_parameters .= $key . ' = ' . $value . '\n';
		}


		//var_dump( $audit_script,  $audit_method, $audit_parameters);

		if (
			empty($audit_script) ||
			empty($audit_method) ||
			empty($audit_parameters)
		) {
			$result = [
				'success' => false,
				'error'   => 'Incomplete data for this request.'
			];
			return $result;
		}
		$this->model->setAudit_script($audit_script);
		$this->model->setAudit_method($audit_method);
		$this->model->setAudit_parameters($audit_parameters);
		$this->model->setAudit_uinsert($_SESSION['user_id']);
		$this->model->setAudit_finsert(Helpers::getDateTimeNow());
		
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
			$this->model->setAudit_id(implode('", "', $query));
			$this->model->setAudit_table(implode('", "', $query));
			$this->model->setAudit_script(implode('", "', $query));
			$this->model->setAudit_method(implode('", "', $query));
			$this->model->setAudit_parameters(implode('", "', $query));
			$this->model->setAudit_uinsert(implode('", "', $query));
			$this->model->setAudit_finsert(implode('", "', $query));

			return $this->modelAdo->inSearch($this->model);
		}
		else {
			$this->model->setAudit_id($query);
			$this->model->setAudit_table($query);
			$this->model->setAudit_script($query);
			$this->model->setAudit_method($query);
			$this->model->setAudit_parameters($query);
			$this->model->setAudit_uinsert($query);
			$this->model->setAudit_finsert($query);

			return $this->modelAdo->paginate($this->model, 'LIKE', $limit, $page);
		}

	}

}
