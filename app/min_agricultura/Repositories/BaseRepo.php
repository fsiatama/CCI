<?php

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
}