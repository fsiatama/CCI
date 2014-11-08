<?php
session_start();
include('../../lib/config.php');
include_once(PATH_APP."lib/idioma.php");
include_once(PATH_APP."lib/lib_funciones.php");
include_once(PATH_APP."lib/lib_sesion.php");
include_once(PATH_RAIZ."min_agricultura/lib/user/userAdo.php");
$userAdo = new UserAdo("min_agricultura");
$user    = new User;
if(isset($accion)){
	switch($accion){
		case "act":
			$user->setUser_id($user_id);
			$user->setUser_full_name($user_full_name);
			$user->setUser_email($user_email);
			$user->setUser_password($user_password);
			$user->setUser_uinsert($user_uinsert);
			$user->setUser_finsert($user_finsert);
			$user->setUser_fupdate($user_fupdate);
			$rs_user = $userAdo->actualizar($user);
			if($rs_user !== true){
				$success = false;
			}
			else{
				$success = true;
			}
			$respuesta = array(
				"success"=>$success,
				"errors"=>array("reason"=>$rs_user)
			);
			echo json_encode($respuesta);
			exit();
		break;
		case "del":
			$user->setUser_id($user_id);
			$rs_user = $userAdo->borrar($user);
			if($rs_user !== true){
				$success = false;
			}
			else{
				$success = true;
			}
			$respuesta = array(
				"success"=>$success,
				"errors"=>array("reason"=>$rs_user)
			);
			echo json_encode($respuesta);
			exit();
		break;
		case "crea":
			$user->setUser_id($user_id);
			$user->setUser_full_name($user_full_name);
			$user->setUser_email($user_email);
			$user->setUser_password($user_password);
			$user->setUser_uinsert($user_uinsert);
			$user->setUser_finsert($user_finsert);
			$user->setUser_fupdate($user_fupdate);
			$rs_user = $userAdo->insertar($user);
			if($rs_user["success"] !== true){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>"Error creando user", "error"=>$rs_user["error"])
				);
				echo json_encode($respuesta);
				exit();
			}
			$user_id = $rs_user["insert_id"];
			$respuesta = array(
				"success"=>true,
				"errors"=>array("reason"=>$user_id)
			);
			echo json_encode($respuesta);
			exit();
		break;
		case "lista":
			$arr = array();
			$user->setUser_id($user_id);
			$user->setUser_full_name($user_full_name);
			$user->setUser_email($user_email);
			$user->setUser_password($user_password);
			$user->setUser_uinsert($user_uinsert);
			$user->setUser_finsert($user_finsert);
			$user->setUser_fupdate($user_fupdate);
			$rs_user = $userAdo->lista($user);
			if(!is_array($rs_user)){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>$rs_user)
				);
				echo json_encode($respuesta);
				exit();
			}
			foreach($rs_user["datos"] as $key => $data){
				$arr[] = sanear_string($data);
			}
			$respuesta = array(
				"success"=>true,
				"total"=>$rs_user["total"],
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
			$rs_user = $userAdo->lista_filtro($query, $valuesqry, $limit);
			if(!is_array($rs_user)){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>$rs_user)
				);
				echo json_encode($respuesta);
				exit();
			}
			elseif($rs_user["total"] == 0){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>sanear_string(_NOSEENCONTRARONREGISTROS))
				);
				echo json_encode($respuesta);
				exit();
			}
			else{
				foreach($rs_user["datos"] as $key => $data){
					$arr[] = sanear_string($data);
				}
				$respuesta = array(
					"success"=>true,
					"total"=>$rs_user["total"],
					"datos"=>$arr
				);
				echo json_encode($respuesta);
				exit();
			}
		break;
	}
}
?>
