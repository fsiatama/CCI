<?php
session_start();
include('../../lib/config.php');
include_once(PATH_APP."lib/idioma.php");
include_once(PATH_APP."lib/lib_funciones.php");
include_once(PATH_APP."lib/lib_sesion.php");
include_once(PATH_RAIZ."min_agricultura/lib/indicator/indicatorAdo.php");
$indicatorAdo = new IndicatorAdo("min_agricultura");
$indicator    = new Indicator;
if(isset($accion)){
	switch($accion){
		case "act":
			$indicator->setIndicator_id($indicator_id);
			$indicator->setIndicator_name($indicator_name);
			$rs_indicator = $indicatorAdo->actualizar($indicator);
			if($rs_indicator !== true){
				$success = false;
			}
			else{
				$success = true;
			}
			$respuesta = array(
				"success"=>$success,
				"errors"=>array("reason"=>$rs_indicator)
			);
			echo json_encode($respuesta);
			exit();
		break;
		case "del":
			$indicator->setIndicator_id($indicator_id);
			$rs_indicator = $indicatorAdo->borrar($indicator);
			if($rs_indicator !== true){
				$success = false;
			}
			else{
				$success = true;
			}
			$respuesta = array(
				"success"=>$success,
				"errors"=>array("reason"=>$rs_indicator)
			);
			echo json_encode($respuesta);
			exit();
		break;
		case "crea":
			$indicator->setIndicator_id($indicator_id);
			$indicator->setIndicator_name($indicator_name);
			$rs_indicator = $indicatorAdo->insertar($indicator);
			if($rs_indicator["success"] !== true){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>"Error creando indicator", "error"=>$rs_indicator["error"])
				);
				echo json_encode($respuesta);
				exit();
			}
			$indicator_id = $rs_indicator["insert_id"];
			$respuesta = array(
				"success"=>true,
				"errors"=>array("reason"=>$indicator_id)
			);
			echo json_encode($respuesta);
			exit();
		break;
		case "lista":
			$arr = array();
			$indicator->setIndicator_id($indicator_id);
			$indicator->setIndicator_name($indicator_name);
			$rs_indicator = $indicatorAdo->lista($indicator);
			if(!is_array($rs_indicator)){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>$rs_indicator)
				);
				echo json_encode($respuesta);
				exit();
			}
			foreach($rs_indicator["datos"] as $key => $data){
				$arr[] = sanear_string($data);
			}
			$respuesta = array(
				"success"=>true,
				"total"=>$rs_indicator["total"],
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
			$rs_indicator = $indicatorAdo->lista_filtro($query, $valuesqry, $limit);
			if(!is_array($rs_indicator)){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>$rs_indicator)
				);
				echo json_encode($respuesta);
				exit();
			}
			elseif($rs_indicator["total"] == 0){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>sanear_string(_NOSEENCONTRARONREGISTROS))
				);
				echo json_encode($respuesta);
				exit();
			}
			else{
				foreach($rs_indicator["datos"] as $key => $data){
					$arr[] = sanear_string($data);
				}
				$respuesta = array(
					"success"=>true,
					"total"=>$rs_indicator["total"],
					"datos"=>$arr
				);
				echo json_encode($respuesta);
				exit();
			}
		break;
	}
}
?>
