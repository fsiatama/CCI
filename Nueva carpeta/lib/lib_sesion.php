<?php
session_start();
//Librerías de consulta a BD
include_once (PATH_RAIZ."lib/conexion/conexion.php");


include_once(PATH_RAIZ.'ssgroup/lib/session/sessionAdo.php');
$sessionAdo = new SessionAdo('ssgroup');
$session    = new Session;
$session->setSession_usuario_id($_SESSION['session_usuario_id']);
$session->setSession_php_id(session_id());
$session->setSession_activa("1");
$rsSession = $sessionAdo->lista($session);
if(empty($rsSession) || $rsSession[0]['session_php_id'] != session_id()){
	echo "{success: false, errors: { reason: 'Your session has expired'}}";
	exit();
}

?>