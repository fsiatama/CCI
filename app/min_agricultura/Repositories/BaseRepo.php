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
	
	public function find($id)
	{
		return $this->model->find($id);
	}
}