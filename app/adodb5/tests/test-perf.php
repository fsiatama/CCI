<?php

include ("../../lib/config.php");
include_once(PATH_RAIZ.'lib/conexion/conexion.php');

error_reporting(E_ALL);
session_start();
$bd = "sisduancol";
$conn = &ADONewConnection('mysql');
$conn->Connect($coneccion[$bd]['server'], $coneccion[$bd]['login'], $coneccion[$bd]['password'], $coneccion[$bd]['bd']);
$perf =& NewPerfMonitor($conn);
$perf->UI($pollsecs=5);
 
?>
