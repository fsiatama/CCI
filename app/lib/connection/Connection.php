<?php
/**
*@author Fabian Siatama
*clase base para la coneccion a una base de datos
*/

include(PATH_RAIZ.'adodb5/adodb.inc.php');

class Connection
{
	protected $connection;
	private $driver;
	private $host;
	private $database;
	private $username;
	private $password;

    public function __construct($database)
	{
		$ADODB_CACHE_DIR = PATH_RAIZ.'cache';
		define('ADODB_FORCE_VALUE',3);//PARA TOMAR LOS VALUES CON CADENA 'null' como NULL de mysql
		
		$this->setConnection($database);
		
		$this->connection = ADONewConnection($this->driver);
    	$this->connection->Connect(
    		$this->host,
    		$this->username,
    		$this->password,
    		$this->database
    	);

		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
	}
	public function setConnection($database)
	{
		require PATH_RAIZ.'lib/config.php';

		if (empty($connections[$database])) {
			$database = $connections['default'];
		}

		$this->driver   = $connections[$database]['driver'];
		$this->host     = $connections[$database]['host'];
		$this->database = $connections[$database]['database'];
		$this->username = $connections[$database]['username'];
		$this->password = $connections[$database]['password'];
	}

}

?>
