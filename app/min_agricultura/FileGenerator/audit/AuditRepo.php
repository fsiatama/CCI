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

	public function setData($params, $action)
	{
		extract($params);

		if ($action == 'modify') {
			$result = $this->findPrimaryKey($audit_id);

			if (!$result['success']) {
				$result = [
					'success'  => false,
					'closeTab' => true,
					'tab'      => 'tab-'.$module,
					'error'    => $result['error']
				];
				return $result;
			}
		}

		if (
			empty($audit_id) ||
			empty($audit_table) ||
			empty($audit_script) ||
			empty($audit_method) ||
			empty($audit_parameters) ||
			empty($audit_uinsert) ||
			empty($audit_finsert)
		) {
			$result = [
				'success' => false,
				'error'   => 'Incomplete data for this request.'
			];
			return $result;
		}
		$this->model->setAudit_id($audit_id);
		$this->model->setAudit_table($audit_table);
		$this->model->setAudit_script($audit_script);
		$this->model->setAudit_method($audit_method);
		$this->model->setAudit_parameters($audit_parameters);

		if ($action == 'create') {
			$this->model->setAudit_uinsert($_SESSION['user_id']);
			$this->model->setAudit_finsert(Helpers::getDateTimeNow());
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
