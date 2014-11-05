<?php
/**
*@author Fabian Siatama
*clase base para la coneccion a la base de datos
*/

include(PATH_RAIZ.'adodb5/adodb.inc.php');

class Conexion
{
	protected $conn;

    public function __construct($bd)
	{
		$ADODB_CACHE_DIR = PATH_RAIZ.'cache';
		define('ADODB_FORCE_VALUE',3); //PARA TOMAR LOS VALUES CON CADENA 'null' como NULL de mysql
		$this->conn = ADONewConnection('mysqli');

    	//print_r($bd);
		require PATH_RAIZ.'lib/config.php';
    	//$this->conn->Connect($bd['server'], $bd['login'], $bd['password'], $bd['bd']);
    	$this->conn->Connect($coneccion[$bd]['server'], $coneccion[$bd]['login'], $coneccion[$bd]['password'], $coneccion[$bd]['bd']);

		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
	}
	public function getConnection()
	{
		return $this->conn;
	}

}

?>
