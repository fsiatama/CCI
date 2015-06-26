<?php
session_start();
include('../../lib/config.php');
include_once(PATH_APP."lib/idioma.php");
include_once(PATH_APP."lib/lib_funciones.php");
include_once(PATH_APP."lib/lib_sesion.php");
include_once(PATH_RAIZ."min_agricultura/lib/acuerdo_info/acuerdo_infoAdo.php");
$acuerdo_infoAdo = new Acuerdo_infoAdo("min_agricultura");
$acuerdo_info    = new Acuerdo_info;
if(isset($accion)){
	switch($accion){
		case "act":
			$acuerdo_info->setAcuerdo_id($acuerdo_id);
			$acuerdo_info->setAcuerdo_nombre($acuerdo_nombre);
			$acuerdo_info->setAcuerdo_descripcion($acuerdo_descripcion);
			$acuerdo_info->setAcuerdo_fvigente($acuerdo_fvigente);
			$acuerdo_info->setAcuerdo_ffirma($acuerdo_ffirma);
			$acuerdo_info->setAcuerdo_ley($acuerdo_ley);
			$acuerdo_info->setAcuerdo_decreto($acuerdo_decreto);
			$acuerdo_info->setAcuerdo_url($acuerdo_url);
			$acuerdo_info->setAcuerdo_estado($acuerdo_estado);
			$acuerdo_info->setAcuerdo_uinsert($acuerdo_uinsert);
			$acuerdo_info->setAcuerdo_finsert($acuerdo_finsert);
			$acuerdo_info->setAcuerdo_uupdate($acuerdo_uupdate);
			$acuerdo_info->setAcuerdo_fupdate($acuerdo_fupdate);
			$acuerdo_info->setAcuerdo_mercado_id($acuerdo_mercado_id);
			$acuerdo_info->setAcuerdo_id_pais($acuerdo_id_pais);
			$rs_acuerdo_info = $acuerdo_infoAdo->actualizar($acuerdo_info);
			if($rs_acuerdo_info !== true){
				$success = false;
			}
			else{
				$success = true;
			}
			$respuesta = array(
				"success"=>$success,
				"errors"=>array("reason"=>$rs_acuerdo_info)
			);
			echo json_encode($respuesta);
			exit();
		break;
		case "del":
			$acuerdo_info->setAcuerdo_id($acuerdo_id);
			$rs_acuerdo_info = $acuerdo_infoAdo->borrar($acuerdo_info);
			if($rs_acuerdo_info !== true){
				$success = false;
			}
			else{
				$success = true;
			}
			$respuesta = array(
				"success"=>$success,
				"errors"=>array("reason"=>$rs_acuerdo_info)
			);
			echo json_encode($respuesta);
			exit();
		break;
		case "crea":
			$acuerdo_info->setAcuerdo_id($acuerdo_id);
			$acuerdo_info->setAcuerdo_nombre($acuerdo_nombre);
			$acuerdo_info->setAcuerdo_descripcion($acuerdo_descripcion);
			$acuerdo_info->setAcuerdo_fvigente($acuerdo_fvigente);
			$acuerdo_info->setAcuerdo_ffirma($acuerdo_ffirma);
			$acuerdo_info->setAcuerdo_ley($acuerdo_ley);
			$acuerdo_info->setAcuerdo_decreto($acuerdo_decreto);
			$acuerdo_info->setAcuerdo_url($acuerdo_url);
			$acuerdo_info->setAcuerdo_estado($acuerdo_estado);
			$acuerdo_info->setAcuerdo_uinsert($acuerdo_uinsert);
			$acuerdo_info->setAcuerdo_finsert($acuerdo_finsert);
			$acuerdo_info->setAcuerdo_uupdate($acuerdo_uupdate);
			$acuerdo_info->setAcuerdo_fupdate($acuerdo_fupdate);
			$acuerdo_info->setAcuerdo_mercado_id($acuerdo_mercado_id);
			$acuerdo_info->setAcuerdo_id_pais($acuerdo_id_pais);
			$rs_acuerdo_info = $acuerdo_infoAdo->insertar($acuerdo_info);
			if($rs_acuerdo_info["success"] !== true){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>"Error creando acuerdo_info", "error"=>$rs_acuerdo_info["error"])
				);
				echo json_encode($respuesta);
				exit();
			}
			$acuerdo_id = $rs_acuerdo_info["insert_id"];
			$respuesta = array(
				"success"=>true,
				"errors"=>array("reason"=>$acuerdo_id)
			);
			echo json_encode($respuesta);
			exit();
		break;
		case "lista":
			$arr = array();
			$acuerdo_info->setAcuerdo_id($acuerdo_id);
			$acuerdo_info->setAcuerdo_nombre($acuerdo_nombre);
			$acuerdo_info->setAcuerdo_descripcion($acuerdo_descripcion);
			$acuerdo_info->setAcuerdo_fvigente($acuerdo_fvigente);
			$acuerdo_info->setAcuerdo_ffirma($acuerdo_ffirma);
			$acuerdo_info->setAcuerdo_ley($acuerdo_ley);
			$acuerdo_info->setAcuerdo_decreto($acuerdo_decreto);
			$acuerdo_info->setAcuerdo_url($acuerdo_url);
			$acuerdo_info->setAcuerdo_estado($acuerdo_estado);
			$acuerdo_info->setAcuerdo_uinsert($acuerdo_uinsert);
			$acuerdo_info->setAcuerdo_finsert($acuerdo_finsert);
			$acuerdo_info->setAcuerdo_uupdate($acuerdo_uupdate);
			$acuerdo_info->setAcuerdo_fupdate($acuerdo_fupdate);
			$acuerdo_info->setAcuerdo_mercado_id($acuerdo_mercado_id);
			$acuerdo_info->setAcuerdo_id_pais($acuerdo_id_pais);
			$rs_acuerdo_info = $acuerdo_infoAdo->lista($acuerdo_info);
			if(!is_array($rs_acuerdo_info)){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>$rs_acuerdo_info)
				);
				echo json_encode($respuesta);
				exit();
			}
			foreach($rs_acuerdo_info["data"] as $key => $data){
				$arr[] = sanear_string($data);
			}
			$respuesta = array(
				"success"=>true,
				"total"=>$rs_acuerdo_info["total"],
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
			$rs_acuerdo_info = $acuerdo_infoAdo->lista_filtro($query, $valuesqry, $limit);
			if(!is_array($rs_acuerdo_info)){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>$rs_acuerdo_info)
				);
				echo json_encode($respuesta);
				exit();
			}
			elseif($rs_acuerdo_info["total"] == 0){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>sanear_string(_NOSEENCONTRARONREGISTROS))
				);
				echo json_encode($respuesta);
				exit();
			}
			else{
				foreach($rs_acuerdo_info["data"] as $key => $data){
					$arr[] = sanear_string($data);
				}
				$respuesta = array(
					"success"=>true,
					"total"=>$rs_acuerdo_info["total"],
					"data"=>$arr
				);
				echo json_encode($respuesta);
				exit();
			}
		break;
	}
}
?>
