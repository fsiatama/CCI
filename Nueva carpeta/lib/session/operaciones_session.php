<?php
session_start();
include ('../../../lib/config.php');
include_once (PATH_RAIZ.'lib/conexion/conexion.php');
include(PATH_RAIZ.'ssgroup/lib/session/sessionAdo.php');
$sessionAdo = new SessionAdo('ssgroup');
$session    = new Session;
if(isset($accion)){
	switch ($accion){
		case 'act':
			$session->setSession_id($_POST['id']);
			$sessionAdo->actualizar($session);
		break;
		case 'del':
			$session->setSession_id($_POST['id']);
			$sessionAdo->borrar($session);
		break;
		case 'crea':
			$session->setSession_usuario_id($session_usuario_id);
			$session->setSession_php_id($session_php_id);
			$session->setSession_date($session_date);
			$session->setSession_activa($session_activa);
			$sessionAdo->insertar($session);
		break;
		case 'lista':
			$arr = array();
			$session->setSession_usuario_id($session_usuario_id);
			$session->setSession_php_id($session_php_id);
			$session->setSession_date($session_date);
			$session->setSession_activa($session_activa);
			$result = $sessionAdo->lista($session);
			foreach($result as $key => $data){
				$arr[] = filtro_grid($data);
			}
			if(isset($formato)){
				$head   = json_decode(stripslashes($campos));
				$total = 'Total Reg: '. $total;
				$result = 'file_'.time();
				$archivo = CreaExcel($arr, $formato, $head, $total, $result);
				echo '{success: true, msg:'.json_encode($archivo).'}';
				exit();
			}
			$data = json_encode($arr); 
			print('{"total":"'.count($result).'", "datos":'.$data.'}');
		break;
	}
}
function filtro_grid($contenido){
  $contenido = str_replace('¡','', $contenido);
  $contenido = str_replace('á','a', $contenido);
  $contenido = str_replace('é','e', $contenido);
  $contenido = str_replace('í','i', $contenido);
  $contenido = str_replace('ó','o', $contenido);
  $contenido = str_replace('ú','u', $contenido);
  $contenido = str_replace('ñ','n', $contenido);
  $contenido = str_replace('Á','A', $contenido);
  $contenido = str_replace('É','E', $contenido);
  $contenido = str_replace('Í','I', $contenido);
  $contenido = str_replace('Ó','O', $contenido);
  $contenido = str_replace('Ú','U', $contenido);
  $contenido = str_replace('Ñ','N', $contenido);
  return $contenido;
}
?>
