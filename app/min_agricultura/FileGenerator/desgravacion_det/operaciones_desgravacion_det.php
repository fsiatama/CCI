<?php
session_start();
include('../../lib/config.php');
include_once(PATH_APP."lib/idioma.php");
include_once(PATH_APP."lib/lib_funciones.php");
include_once(PATH_APP."lib/lib_sesion.php");
include_once(PATH_RAIZ."min_agricultura/lib/desgravacion_det/desgravacion_detAdo.php");
$desgravacion_detAdo = new Desgravacion_detAdo("min_agricultura");
$desgravacion_det    = new Desgravacion_det;
if(isset($accion)){
	switch($accion){
		case "act":
			$desgravacion_det->setDesgravacion_det_id($desgravacion_det_id);
			$desgravacion_det->setDesgravacion_det_anio_ini($desgravacion_det_anio_ini);
			$desgravacion_det->setDesgravacion_det_anio_fin($desgravacion_det_anio_fin);
			$desgravacion_det->setDesgravacion_det_tasa($desgravacion_det_tasa);
			$desgravacion_det->setDesgravacion_det_tipo_operacion($desgravacion_det_tipo_operacion);
			$desgravacion_det->setDesgravacion_det_desgravacion_id($desgravacion_det_desgravacion_id);
			$desgravacion_det->setDesgravacion_det_desgravacion_acuerdo_det_id($desgravacion_det_desgravacion_acuerdo_det_id);
			$desgravacion_det->setDesgravacion_det_desgravacion_acuerdo_det_acuerdo_id($desgravacion_det_desgravacion_acuerdo_det_acuerdo_id);
			$rs_desgravacion_det = $desgravacion_detAdo->actualizar($desgravacion_det);
			if($rs_desgravacion_det !== true){
				$success = false;
			}
			else{
				$success = true;
			}
			$respuesta = array(
				"success"=>$success,
				"errors"=>array("reason"=>$rs_desgravacion_det)
			);
			echo json_encode($respuesta);
			exit();
		break;
		case "del":
			$desgravacion_det->setDesgravacion_det_desgravacion_acuerdo_det_acuerdo_id($desgravacion_det_desgravacion_acuerdo_det_acuerdo_id);
			$rs_desgravacion_det = $desgravacion_detAdo->borrar($desgravacion_det);
			if($rs_desgravacion_det !== true){
				$success = false;
			}
			else{
				$success = true;
			}
			$respuesta = array(
				"success"=>$success,
				"errors"=>array("reason"=>$rs_desgravacion_det)
			);
			echo json_encode($respuesta);
			exit();
		break;
		case "crea":
			$desgravacion_det->setDesgravacion_det_id($desgravacion_det_id);
			$desgravacion_det->setDesgravacion_det_anio_ini($desgravacion_det_anio_ini);
			$desgravacion_det->setDesgravacion_det_anio_fin($desgravacion_det_anio_fin);
			$desgravacion_det->setDesgravacion_det_tasa($desgravacion_det_tasa);
			$desgravacion_det->setDesgravacion_det_tipo_operacion($desgravacion_det_tipo_operacion);
			$desgravacion_det->setDesgravacion_det_desgravacion_id($desgravacion_det_desgravacion_id);
			$desgravacion_det->setDesgravacion_det_desgravacion_acuerdo_det_id($desgravacion_det_desgravacion_acuerdo_det_id);
			$desgravacion_det->setDesgravacion_det_desgravacion_acuerdo_det_acuerdo_id($desgravacion_det_desgravacion_acuerdo_det_acuerdo_id);
			$rs_desgravacion_det = $desgravacion_detAdo->insertar($desgravacion_det);
			if($rs_desgravacion_det["success"] !== true){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>"Error creando desgravacion_det", "error"=>$rs_desgravacion_det["error"])
				);
				echo json_encode($respuesta);
				exit();
			}
			$desgravacion_det_desgravacion_acuerdo_det_acuerdo_id = $rs_desgravacion_det["insert_id"];
			$respuesta = array(
				"success"=>true,
				"errors"=>array("reason"=>$desgravacion_det_desgravacion_acuerdo_det_acuerdo_id)
			);
			echo json_encode($respuesta);
			exit();
		break;
		case "lista":
			$arr = array();
			$desgravacion_det->setDesgravacion_det_id($desgravacion_det_id);
			$desgravacion_det->setDesgravacion_det_anio_ini($desgravacion_det_anio_ini);
			$desgravacion_det->setDesgravacion_det_anio_fin($desgravacion_det_anio_fin);
			$desgravacion_det->setDesgravacion_det_tasa($desgravacion_det_tasa);
			$desgravacion_det->setDesgravacion_det_tipo_operacion($desgravacion_det_tipo_operacion);
			$desgravacion_det->setDesgravacion_det_desgravacion_id($desgravacion_det_desgravacion_id);
			$desgravacion_det->setDesgravacion_det_desgravacion_acuerdo_det_id($desgravacion_det_desgravacion_acuerdo_det_id);
			$desgravacion_det->setDesgravacion_det_desgravacion_acuerdo_det_acuerdo_id($desgravacion_det_desgravacion_acuerdo_det_acuerdo_id);
			$rs_desgravacion_det = $desgravacion_detAdo->lista($desgravacion_det);
			if(!is_array($rs_desgravacion_det)){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>$rs_desgravacion_det)
				);
				echo json_encode($respuesta);
				exit();
			}
			foreach($rs_desgravacion_det["data"] as $key => $data){
				$arr[] = sanear_string($data);
			}
			$respuesta = array(
				"success"=>true,
				"total"=>$rs_desgravacion_det["total"],
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
			$rs_desgravacion_det = $desgravacion_detAdo->lista_filtro($query, $valuesqry, $limit);
			if(!is_array($rs_desgravacion_det)){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>$rs_desgravacion_det)
				);
				echo json_encode($respuesta);
				exit();
			}
			elseif($rs_desgravacion_det["total"] == 0){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>sanear_string(_NOSEENCONTRARONREGISTROS))
				);
				echo json_encode($respuesta);
				exit();
			}
			else{
				foreach($rs_desgravacion_det["data"] as $key => $data){
					$arr[] = sanear_string($data);
				}
				$respuesta = array(
					"success"=>true,
					"total"=>$rs_desgravacion_det["total"],
					"data"=>$arr
				);
				echo json_encode($respuesta);
				exit();
			}
		break;
	}
}
?>
