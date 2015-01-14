<?php
require_once PATH_MODELS.'Repositories/AuditRepo.php';

abstract class BaseRepo {
	
	protected $model;
	protected $modelAdo;
	protected $primaryKey;

	public function __construct()
	{
		$this->model      = $this->getModel();
		$this->modelAdo   = $this->getModelAdo();
		$this->primaryKey = $this->getPrimaryKey();
	}
	abstract public function getModel();
	abstract public function getModelAdo();
	abstract public function getPrimaryKey();
	abstract public function setData($params, $action);

	protected function getAuditRepo()
	{
		return new AuditRepo;
	}
	
	public function getColumnMethodName($metod, $columnName)
	{
		return strtolower($metod).ucfirst(strtolower($columnName));
	}

	public function findPrimaryKey($primaryKey)
	{
		if (empty($primaryKey)) {
			$result = array(
				'success' => false,
				'error'   => 'Incomplete data for this request.'
			);
			return $result;
		}
		$methodName = $this->getColumnMethodName('set', $this->primaryKey);
		if (method_exists($this->model, $methodName)) {
			call_user_func_array([$this->model, $methodName], compact('primaryKey'));
		}
		//$this->model->setCorrelativa_id($primaryKey);
		$result = $this->modelAdo->exactSearch($this->model);

		if ($result['total'] != 1) {
			$result = array(
				'success' => false,
				'error'   => 'Many or none records matching with this search.'
			);
			return $result;
		}
		return $result;
	}

	public function create($params)
	{
		//extract($params);
		
		$result = $this->setData($params, 'create');
		if (!$result['success']) {
			return $result;
		}

		//insertar registro de auditoria
		$result = $this->createAudit($params);
		if (!$result['success']) {
			return $result;
		}

		$result = $this->modelAdo->create($this->model);
		if ($result['success']) {
			return ['success' => true];
		}

		return $result;
	}

	public function modify($params)
	{
		$result = $this->setData($params, 'modify');
		if (!$result['success']) {
			return $result;
		}

		//insertar registro de auditoria
		$result = $this->createAudit($params);
		if (!$result['success']) {
			return $result;
		}

		$result = $this->modelAdo->update($this->model);
		if ($result['success']) {
			return ['success' => true];
		}
		return $result;
	}

	public function delete($params)
	{
		$primaryKey = $params[$this->primaryKey];

		//insertar registro de auditoria
		$result = $this->createAudit($params);
		if (!$result['success']) {
			return $result;
		}
		
		$result = $this->findPrimaryKey($primaryKey);

		if ($result['success']) {
			$result = $this->modelAdo->delete($this->model);
		}

		return $result;
	}

	protected function createAudit($params)
	{
		if (empty($params)) {
			return ['success' => true];
		}

		$auditRepo = $this->getAuditRepo();

		$result = $auditRepo->create($params);

		return $result;
	}
}