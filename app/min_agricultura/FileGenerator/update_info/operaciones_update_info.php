<?php
session_start();
include('../../lib/config.php');
include_once(PATH_APP."lib/idioma.php");
include_once(PATH_APP."lib/lib_funciones.php");
include_once(PATH_APP."lib/lib_sesion.php");
include_once(PATH_RAIZ."min_agricultura/lib/update_info/update_infoAdo.php");
$update_infoAdo = new Update_infoAdo("min_agricultura");
$update_info    = new Update_info;
if(isset($accion)){
	switch($accion){
		case "act":
			$update_info->setUpdate_info_id($update_info_id);
			$update_info->setUpdate_info_product($update_info_product);
			$update_info->setUpdate_info_trade($update_info_trade);
			$update_info->setUpdate_info_from($update_info_from);
			$update_info->setUpdate_info_to($update_info_to);
			$rs_update_info = $update_infoAdo->actualizar($update_info);
			if($rs_update_info !== true){
				$success = false;
			}
			else{
				$success = true;
			}
			$respuesta = array(
				"success"=>$success,
				"errors"=>array("reason"=>$rs_update_info)
			);
			echo json_encode($respuesta);
			exit();
		break;
		case "del":
			$update_info->setUpdate_info_id($update_info_id);
			$rs_update_info = $update_infoAdo->borrar($update_info);
			if($rs_update_info !== true){
				$success = false;
			}
			else{
				$success = true;
			}
			$respuesta = array(
				"success"=>$success,
				"errors"=>array("reason"=>$rs_update_info)
			);
			echo json_encode($respuesta);
			exit();
		break;
		case "crea":
			$update_info->setUpdate_info_id($update_info_id);
			$update_info->setUpdate_info_product($update_info_product);
			$update_info->setUpdate_info_trade($update_info_trade);
			$update_info->setUpdate_info_from($update_info_from);
			$update_info->setUpdate_info_to($update_info_to);
			$rs_update_info = $update_infoAdo->insertar($update_info);
			if($rs_update_info["success"] !== true){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>"Error creando update_info", "error"=>$rs_update_info["error"])
				);
				echo json_encode($respuesta);
				exit();
			}
			$update_info_id = $rs_update_info["insert_id"];
			$respuesta = array(
				"success"=>true,
				"errors"=>array("reason"=>$update_info_id)
			);
			echo json_encode($respuesta);
			exit();
		break;
		case "lista":
			$arr = array();
			$update_info->setUpdate_info_id($update_info_id);
			$update_info->setUpdate_info_product($update_info_product);
			$update_info->setUpdate_info_trade($update_info_trade);
			$update_info->setUpdate_info_from($update_info_from);
			$update_info->setUpdate_info_to($update_info_to);
			$rs_update_info = $update_infoAdo->lista($update_info);
			if(!is_array($rs_update_info)){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>$rs_update_info)
				);
				echo json_encode($respuesta);
				exit();
			}
			foreach($rs_update_info["data"] as $key => $data){
				$arr[] = sanear_string($data);
			}
			$respuesta = array(
				"success"=>true,
				"total"=>$rs_update_info["total"],
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
			$rs_update_info = $update_infoAdo->lista_filtro($query, $valuesqry, $limit);
			if(!is_array($rs_update_info)){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>$rs_update_info)
				);
				echo json_encode($respuesta);
				exit();
			}
			elseif($rs_update_info["total"] == 0){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>sanear_string(_NOSEENCONTRARONREGISTROS))
				);
				echo json_encode($respuesta);
				exit();
			}
			else{
				foreach($rs_update_info["data"] as $key => $data){
					$arr[] = sanear_string($data);
				}
				$respuesta = array(
					"success"=>true,
					"total"=>$rs_update_info["total"],
					"data"=>$arr
				);
				echo json_encode($respuesta);
				exit();
			}
		break;
	}
}
?>
