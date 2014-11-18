<?php

abstract class BaseRepo {
	
	protected $model;
	protected $modelAdo;

	public function __construct()
	{
		$this->model    = $this->getModel();
		$this->modelAdo = $this->getModelAdo();
	}
	abstract public function getModel();
	abstract public function getModelAdo();
	
	public function getColumnMethodName($metod, $columnName)
	{
		return strtolower($metod).ucfirst(strtolower($columnName));
	}
}