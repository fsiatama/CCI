<?php
session_start();
include('../../lib/config.php');
include_once(PATH_APP."lib/idioma.php");
include_once(PATH_APP."lib/lib_funciones.php");
include_once(PATH_APP."lib/lib_sesion.php");
include_once(PATH_RAIZ."min_agricultura/lib/desgravacion/desgravacionAdo.php");
$desgravacionAdo = new DesgravacionAdo("min_agricultura");
$desgravacion    = new Desgravacion;
if(isset($accion)){
	switch($accion){
		case "act":
			$desgravacion->setDesgravacion_id($desgravacion_id);
			$desgravacion->setDesgravacion_id_pais($desgravacion_id_pais);
			$desgravacion->setDesgravacion_mdesgravacion($desgravacion_mdesgravacion);
			$desgravacion->setDesgravacion_desc($desgravacion_desc);
			$desgravacion->setDesgravacion_acuerdo_det_id($desgravacion_acuerdo_det_id);
			$desgravacion->setDesgravacion_acuerdo_det_acuerdo_id($desgravacion_acuerdo_det_acuerdo_id);
			$rs_desgravacion = $desgravacionAdo->actualizar($desgravacion);
			if($rs_desgravacion !== true){
				$success = false;
			}
			else{
				$success = true;
			}
			$respuesta = array(
				"success"=>$success,
				"errors"=>array("reason"=>$rs_desgravacion)
			);
			echo json_encode($respuesta);
			exit();
		break;
		case "del":
			$desgravacion->setDesgravacion_acuerdo_det_acuerdo_id($desgravacion_acuerdo_det_acuerdo_id);
			$rs_desgravacion = $desgravacionAdo->borrar($desgravacion);
			if($rs_desgravacion !== true){
				$success = false;
			}
			else{
				$success = true;
			}
			$respuesta = array(
				"success"=>$success,
				"errors"=>array("reason"=>$rs_desgravacion)
			);
			echo json_encode($respuesta);
			exit();
		break;
		case "crea":
			$desgravacion->setDesgravacion_id($desgravacion_id);
			$desgravacion->setDesgravacion_id_pais($desgravacion_id_pais);
			$desgravacion->setDesgravacion_mdesgravacion($desgravacion_mdesgravacion);
			$desgravacion->setDesgravacion_desc($desgravacion_desc);
			$desgravacion->setDesgravacion_acuerdo_det_id($desgravacion_acuerdo_det_id);
			$desgravacion->setDesgravacion_acuerdo_det_acuerdo_id($desgravacion_acuerdo_det_acuerdo_id);
			$rs_desgravacion = $desgravacionAdo->insertar($desgravacion);
			if($rs_desgravacion["success"] !== true){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>"Error creando desgravacion", "error"=>$rs_desgravacion["error"])
				);
				echo json_encode($respuesta);
				exit();
			}
			$desgravacion_acuerdo_det_acuerdo_id = $rs_desgravacion["insert_id"];
			$respuesta = array(
				"success"=>true,
				"errors"=>array("reason"=>$desgravacion_acuerdo_det_acuerdo_id)
			);
			echo json_encode($respuesta);
			exit();
		break;
		case "lista":
			$arr = array();
			$desgravacion->setDesgravacion_id($desgravacion_id);
			$desgravacion->setDesgravacion_id_pais($desgravacion_id_pais);
			$desgravacion->setDesgravacion_mdesgravacion($desgravacion_mdesgravacion);
			$desgravacion->setDesgravacion_desc($desgravacion_desc);
			$desgravacion->setDesgravacion_acuerdo_det_id($desgravacion_acuerdo_det_id);
			$desgravacion->setDesgravacion_acuerdo_det_acuerdo_id($desgravacion_acuerdo_det_acuerdo_id);
			$rs_desgravacion = $desgravacionAdo->lista($desgravacion);
			if(!is_array($rs_desgravacion)){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>$rs_desgravacion)
				);
				echo json_encode($respuesta);
				exit();
			}
			foreach($rs_desgravacion["data"] as $key => $data){
				$arr[] = sanear_string($data);
			}
			$respuesta = array(
				"success"=>true,
				"total"=>$rs_desgravacion["total"],
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
			$rs_desgravacion = $desgravacionAdo->lista_filtro($query, $valuesqry, $limit);
			if(!is_array($rs_desgravacion)){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>$rs_desgravacion)
				);
				echo json_encode($respuesta);
				exit();
			}
			elseif($rs_desgravacion["total"] == 0){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>sanear_string(_NOSEENCONTRARONREGISTROS))
				);
				echo json_encode($respuesta);
				exit();
			}
			else{
				foreach($rs_desgravacion["data"] as $key => $data){
					$arr[] = sanear_string($data);
				}
				$respuesta = array(
					"success"=>true,
					"total"=>$rs_desgravacion["total"],
					"data"=>$arr
				);
				echo json_encode($respuesta);
				exit();
			}
		break;
	}
}
?>
