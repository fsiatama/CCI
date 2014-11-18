<?php
session_start();
include('../../lib/config.php');
include_once(PATH_APP."lib/idioma.php");
include_once(PATH_APP."lib/lib_funciones.php");
include_once(PATH_APP."lib/lib_sesion.php");
include_once(PATH_RAIZ."min_agricultura/lib/profile/profileAdo.php");
$profileAdo = new ProfileAdo("min_agricultura");
$profile    = new Profile;
if(isset($accion)){
	switch($accion){
		case "act":
			$profile->setProfile_id($profile_id);
			$profile->setProfile_name($profile_name);
			$rs_profile = $profileAdo->actualizar($profile);
			if($rs_profile !== true){
				$success = false;
			}
			else{
				$success = true;
			}
			$respuesta = array(
				"success"=>$success,
				"errors"=>array("reason"=>$rs_profile)
			);
			echo json_encode($respuesta);
			exit();
		break;
		case "del":
			$profile->setProfile_id($profile_id);
			$rs_profile = $profileAdo->borrar($profile);
			if($rs_profile !== true){
				$success = false;
			}
			else{
				$success = true;
			}
			$respuesta = array(
				"success"=>$success,
				"errors"=>array("reason"=>$rs_profile)
			);
			echo json_encode($respuesta);
			exit();
		break;
		case "crea":
			$profile->setProfile_id($profile_id);
			$profile->setProfile_name($profile_name);
			$rs_profile = $profileAdo->insertar($profile);
			if($rs_profile["success"] !== true){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>"Error creando profile", "error"=>$rs_profile["error"])
				);
				echo json_encode($respuesta);
				exit();
			}
			$profile_id = $rs_profile["insert_id"];
			$respuesta = array(
				"success"=>true,
				"errors"=>array("reason"=>$profile_id)
			);
			echo json_encode($respuesta);
			exit();
		break;
		case "lista":
			$arr = array();
			$profile->setProfile_id($profile_id);
			$profile->setProfile_name($profile_name);
			$rs_profile = $profileAdo->lista($profile);
			if(!is_array($rs_profile)){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>$rs_profile)
				);
				echo json_encode($respuesta);
				exit();
			}
			foreach($rs_profile["data"] as $key => $data){
				$arr[] = sanear_string($data);
			}
			$respuesta = array(
				"success"=>true,
				"total"=>$rs_profile["total"],
				"data"=>$arr
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
			$rs_profile = $profileAdo->lista_filtro($query, $valuesqry, $limit);
			if(!is_array($rs_profile)){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>$rs_profile)
				);
				echo json_encode($respuesta);
				exit();
			}
			elseif($rs_profile["total"] == 0){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>sanear_string(_NOSEENCONTRARONREGISTROS))
				);
				echo json_encode($respuesta);
				exit();
			}
			else{
				foreach($rs_profile["data"] as $key => $data){
					$arr[] = sanear_string($data);
				}
				$respuesta = array(
					"success"=>true,
					"total"=>$rs_profile["total"],
					"data"=>$arr
				);
				echo json_encode($respuesta);
				exit();
			}
		break;
	}
}
?>
