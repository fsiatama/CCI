<?php
$ADODB_CACHE_DIR = PATH_RAIZ.'cache';
define('ADODB_FORCE_VALUE',3); //PARA TOMAR LOS VALUES CON CADENA 'null' como NULL de mysql
include(PATH_RAIZ.'adodb5/adodb.inc.php');	   # Carga el codigo comun de ADOdb
class Conexion{
    var $conn;
    function Conexion($bd){
		include(PATH_RAIZ.'lib/config.php');
		$this->conn = &ADONewConnection('mysqli'); 
    	$this->conn->PConnect($coneccion[$bd]['server'], $coneccion[$bd]['login'], $coneccion[$bd]['password'], $coneccion[$bd]['bd']);

		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC; 
    }
}

?>
