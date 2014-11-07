<?php

require PATH_APP.'lib/connection/Connection.php';

abstract class BaseAdo extends Connection {
	
	public function __construct()
	{
		parent::__construct('min_agricultura');
	}

	abstract public function setData($model);
	
}