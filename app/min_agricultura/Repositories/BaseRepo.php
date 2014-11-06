<?php

require PATH_APP.'lib/connection/Connection.php';

abstract class BaseRepo extends Connection {
	
	protected $model;

	public function __construct()
	{
		parent::__construct('min_agricultura');
		$this->model = $this->getModel();
	}
	abstract public function getModel();
	
	public function find($id)
	{
		return $this->model->find($id);
	}
}