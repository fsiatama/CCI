<?php
session_start();
include('../../lib/config.php');
include_once(PATH_APP."lib/idioma.php");
include_once(PATH_APP."lib/lib_funciones.php");
include_once(PATH_APP."lib/lib_sesion.php");
include_once(PATH_RAIZ."min_agricultura/lib/session/sessionAdo.php");
$sessionAdo = new SessionAdo("min_agricultura");
$session    = new Session;
if(isset($accion)){
	switch($accion){
		case "act":
			$session->setSession_user_id($session_user_id);
			$session->setSession_php_id($session_php_id);
			$session->setSession_date($session_date);
			$session->setSession_active($session_active);
			$rs_session = $sessionAdo->actualizar($session);
			if($rs_session !== true){
				$success = false;
			}
			else{
				$success = true;
			}
			$respuesta = array(
				"success"=>$success,
				"errors"=>array("reason"=>$rs_session)
			);
			echo json_encode($respuesta);
			exit();
		break;
		case "del":
			$session->setSession_user_id($session_user_id);
			$rs_session = $sessionAdo->borrar($session);
			if($rs_session !== true){
				$success = false;
			}
			else{
				$success = true;
			}
			$respuesta = array(
				"success"=>$success,
				"errors"=>array("reason"=>$rs_session)
			);
			echo json_encode($respuesta);
			exit();
		break;
		case "crea":
			$session->setSession_user_id($session_user_id);
			$session->setSession_php_id($session_php_id);
			$session->setSession_date($session_date);
			$session->setSession_active($session_active);
			$rs_session = $sessionAdo->insertar($session);
			if($rs_session["success"] !== true){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>"Error creando session", "error"=>$rs_session["error"])
				);
				echo json_encode($respuesta);
				exit();
			}
			$session_user_id = $rs_session["insert_id"];
			$respuesta = array(
				"success"=>true,
				"errors"=>array("reason"=>$session_user_id)
			);
			echo json_encode($respuesta);
			exit();
		break;
		case "lista":
			$arr = array();
			$session->setSession_user_id($session_user_id);
			$session->setSession_php_id($session_php_id);
			$session->setSession_date($session_date);
			$session->setSession_active($session_active);
			$rs_session = $sessionAdo->lista($session);
			if(!is_array($rs_session)){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>$rs_session)
				);
				echo json_encode($respuesta);
				exit();
			}
			foreach($rs_session["datos"] as $key => $data){
				$arr[] = sanear_string($data);
			}
			$respuesta = array(
				"success"=>true,
				"total"=>$rs_session["total"],
				"datos"=>$arr
			);
			echo json_encode($respuesta);
			exit();
		break;
		case "lista_filtro":
			$arr = array();
			$start = (isset($start))?$start:0;
			$limit = (isset($limit))?$limit:MAXREGEXCEL;
			$page = ($start==0)?1:($start/$limit)+1;
			$limit = $page . ", " . $limit;
			$rs_session = $sessionAdo->lista_filtro($query, $valuesqry, $limit);
			if(!is_array($rs_session)){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>$rs_session)
				);
				echo json_encode($respuesta);
				exit();
			}
			elseif($rs_session["total"] == 0){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>sanear_string(_NOSEENCONTRARONREGISTROS))
				);
				echo json_encode($respuesta);
				exit();
			}
			else{
				foreach($rs_session["datos"] as $key => $data){
					$arr[] = sanear_string($data);
				}
				$respuesta = array(
					"success"=>true,
					"total"=>$rs_session["total"],
					"datos"=>$arr
				);
				echo json_encode($respuesta);
				exit();
			}
		break;
	}
}
?>
