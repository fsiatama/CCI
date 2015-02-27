<?php
session_start();
include('../../lib/config.php');
include_once(PATH_APP."lib/idioma.php");
include_once(PATH_APP."lib/lib_funciones.php");
include_once(PATH_APP."lib/lib_sesion.php");
include_once(PATH_RAIZ."min_agricultura/lib/acuerdo/acuerdoAdo.php");
$acuerdoAdo = new AcuerdoAdo("min_agricultura");
$acuerdo    = new Acuerdo;
if(isset($accion)){
	switch($accion){
		case "act":
			$acuerdo->setAcuerdo_id($acuerdo_id);
			$acuerdo->setAcuerdo_nombre($acuerdo_nombre);
			$acuerdo->setAcuerdo_descripcion($acuerdo_descripcion);
			$acuerdo->setAcuerdo_intercambio($acuerdo_intercambio);
			$acuerdo->setAcuerdo_fvigente($acuerdo_fvigente);
			$acuerdo->setAcuerdo_ffirma($acuerdo_ffirma);
			$acuerdo->setAcuerdo_ley($acuerdo_ley);
			$acuerdo->setAcuerdo_decreto($acuerdo_decreto);
			$acuerdo->setAcuerdo_url($acuerdo_url);
			$acuerdo->setAcuerdo_tipo_acuerdo($acuerdo_tipo_acuerdo);
			$acuerdo->setAcuerdo_uinsert($acuerdo_uinsert);
			$acuerdo->setAcuerdo_finsert($acuerdo_finsert);
			$acuerdo->setAcuerdo_uupdate($acuerdo_uupdate);
			$acuerdo->setAcuerdo_fupdate($acuerdo_fupdate);
			$acuerdo->setAcuerdo_mercado_id($acuerdo_mercado_id);
			$acuerdo->setAcuerdo_id_pais($acuerdo_id_pais);
			$rs_acuerdo = $acuerdoAdo->actualizar($acuerdo);
			if($rs_acuerdo !== true){
				$success = false;
			}
			else{
				$success = true;
			}
			$respuesta = array(
				"success"=>$success,
				"errors"=>array("reason"=>$rs_acuerdo)
			);
			echo json_encode($respuesta);
			exit();
		break;
		case "del":
			$acuerdo->setAcuerdo_id($acuerdo_id);
			$rs_acuerdo = $acuerdoAdo->borrar($acuerdo);
			if($rs_acuerdo !== true){
				$success = false;
			}
			else{
				$success = true;
			}
			$respuesta = array(
				"success"=>$success,
				"errors"=>array("reason"=>$rs_acuerdo)
			);
			echo json_encode($respuesta);
			exit();
		break;
		case "crea":
			$acuerdo->setAcuerdo_id($acuerdo_id);
			$acuerdo->setAcuerdo_nombre($acuerdo_nombre);
			$acuerdo->setAcuerdo_descripcion($acuerdo_descripcion);
			$acuerdo->setAcuerdo_intercambio($acuerdo_intercambio);
			$acuerdo->setAcuerdo_fvigente($acuerdo_fvigente);
			$acuerdo->setAcuerdo_ffirma($acuerdo_ffirma);
			$acuerdo->setAcuerdo_ley($acuerdo_ley);
			$acuerdo->setAcuerdo_decreto($acuerdo_decreto);
			$acuerdo->setAcuerdo_url($acuerdo_url);
			$acuerdo->setAcuerdo_tipo_acuerdo($acuerdo_tipo_acuerdo);
			$acuerdo->setAcuerdo_uinsert($acuerdo_uinsert);
			$acuerdo->setAcuerdo_finsert($acuerdo_finsert);
			$acuerdo->setAcuerdo_uupdate($acuerdo_uupdate);
			$acuerdo->setAcuerdo_fupdate($acuerdo_fupdate);
			$acuerdo->setAcuerdo_mercado_id($acuerdo_mercado_id);
			$acuerdo->setAcuerdo_id_pais($acuerdo_id_pais);
			$rs_acuerdo = $acuerdoAdo->insertar($acuerdo);
			if($rs_acuerdo["success"] !== true){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>"Error creando acuerdo", "error"=>$rs_acuerdo["error"])
				);
				echo json_encode($respuesta);
				exit();
			}
			$acuerdo_id = $rs_acuerdo["insert_id"];
			$respuesta = array(
				"success"=>true,
				"errors"=>array("reason"=>$acuerdo_id)
			);
			echo json_encode($respuesta);
			exit();
		break;
		case "lista":
			$arr = array();
			$acuerdo->setAcuerdo_id($acuerdo_id);
			$acuerdo->setAcuerdo_nombre($acuerdo_nombre);
			$acuerdo->setAcuerdo_descripcion($acuerdo_descripcion);
			$acuerdo->setAcuerdo_intercambio($acuerdo_intercambio);
			$acuerdo->setAcuerdo_fvigente($acuerdo_fvigente);
			$acuerdo->setAcuerdo_ffirma($acuerdo_ffirma);
			$acuerdo->setAcuerdo_ley($acuerdo_ley);
			$acuerdo->setAcuerdo_decreto($acuerdo_decreto);
			$acuerdo->setAcuerdo_url($acuerdo_url);
			$acuerdo->setAcuerdo_tipo_acuerdo($acuerdo_tipo_acuerdo);
			$acuerdo->setAcuerdo_uinsert($acuerdo_uinsert);
			$acuerdo->setAcuerdo_finsert($acuerdo_finsert);
			$acuerdo->setAcuerdo_uupdate($acuerdo_uupdate);
			$acuerdo->setAcuerdo_fupdate($acuerdo_fupdate);
			$acuerdo->setAcuerdo_mercado_id($acuerdo_mercado_id);
			$acuerdo->setAcuerdo_id_pais($acuerdo_id_pais);
			$rs_acuerdo = $acuerdoAdo->lista($acuerdo);
			if(!is_array($rs_acuerdo)){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>$rs_acuerdo)
				);
				echo json_encode($respuesta);
				exit();
			}
			foreach($rs_acuerdo["data"] as $key => $data){
				$arr[] = sanear_string($data);
			}
			$respuesta = array(
				"success"=>true,
				"total"=>$rs_acuerdo["total"],
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
			$rs_acuerdo = $acuerdoAdo->lista_filtro($query, $valuesqry, $limit);
			if(!is_array($rs_acuerdo)){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>$rs_acuerdo)
				);
				echo json_encode($respuesta);
				exit();
			}
			elseif($rs_acuerdo["total"] == 0){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>sanear_string(_NOSEENCONTRARONREGISTROS))
				);
				echo json_encode($respuesta);
				exit();
			}
			else{
				foreach($rs_acuerdo["data"] as $key => $data){
					$arr[] = sanear_string($data);
				}
				$respuesta = array(
					"success"=>true,
					"total"=>$rs_acuerdo["total"],
					"data"=>$arr
				);
				echo json_encode($respuesta);
				exit();
			}
		break;
	}
}
?>
