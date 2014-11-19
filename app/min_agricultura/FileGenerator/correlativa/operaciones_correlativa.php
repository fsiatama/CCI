<?php
session_start();
include('../../lib/config.php');
include_once(PATH_APP."lib/idioma.php");
include_once(PATH_APP."lib/lib_funciones.php");
include_once(PATH_APP."lib/lib_sesion.php");
include_once(PATH_RAIZ."min_agricultura/lib/correlativa/correlativaAdo.php");
$correlativaAdo = new CorrelativaAdo("min_agricultura");
$correlativa    = new Correlativa;
if(isset($accion)){
	switch($accion){
		case "act":
			$correlativa->setCorrelativa_id($correlativa_id);
			$correlativa->setCorrelativa_fvigente($correlativa_fvigente);
			$correlativa->setCorrelativa_decreto($correlativa_decreto);
			$correlativa->setCorrelativa_observacion($correlativa_observacion);
			$correlativa->setCorrelativa_origen($correlativa_origen);
			$correlativa->setCorrelativa_destino($correlativa_destino);
			$correlativa->setCorrelativa_uinsert($correlativa_uinsert);
			$correlativa->setCorrelativa_finsert($correlativa_finsert);
			$correlativa->setCorrelativa_uupdate($correlativa_uupdate);
			$correlativa->setCorrelativa_fupdate($correlativa_fupdate);
			$rs_correlativa = $correlativaAdo->actualizar($correlativa);
			if($rs_correlativa !== true){
				$success = false;
			}
			else{
				$success = true;
			}
			$respuesta = array(
				"success"=>$success,
				"errors"=>array("reason"=>$rs_correlativa)
			);
			echo json_encode($respuesta);
			exit();
		break;
		case "del":
			$correlativa->setCorrelativa_id($correlativa_id);
			$rs_correlativa = $correlativaAdo->borrar($correlativa);
			if($rs_correlativa !== true){
				$success = false;
			}
			else{
				$success = true;
			}
			$respuesta = array(
				"success"=>$success,
				"errors"=>array("reason"=>$rs_correlativa)
			);
			echo json_encode($respuesta);
			exit();
		break;
		case "crea":
			$correlativa->setCorrelativa_id($correlativa_id);
			$correlativa->setCorrelativa_fvigente($correlativa_fvigente);
			$correlativa->setCorrelativa_decreto($correlativa_decreto);
			$correlativa->setCorrelativa_observacion($correlativa_observacion);
			$correlativa->setCorrelativa_origen($correlativa_origen);
			$correlativa->setCorrelativa_destino($correlativa_destino);
			$correlativa->setCorrelativa_uinsert($correlativa_uinsert);
			$correlativa->setCorrelativa_finsert($correlativa_finsert);
			$correlativa->setCorrelativa_uupdate($correlativa_uupdate);
			$correlativa->setCorrelativa_fupdate($correlativa_fupdate);
			$rs_correlativa = $correlativaAdo->insertar($correlativa);
			if($rs_correlativa["success"] !== true){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>"Error creando correlativa", "error"=>$rs_correlativa["error"])
				);
				echo json_encode($respuesta);
				exit();
			}
			$correlativa_id = $rs_correlativa["insert_id"];
			$respuesta = array(
				"success"=>true,
				"errors"=>array("reason"=>$correlativa_id)
			);
			echo json_encode($respuesta);
			exit();
		break;
		case "lista":
			$arr = array();
			$correlativa->setCorrelativa_id($correlativa_id);
			$correlativa->setCorrelativa_fvigente($correlativa_fvigente);
			$correlativa->setCorrelativa_decreto($correlativa_decreto);
			$correlativa->setCorrelativa_observacion($correlativa_observacion);
			$correlativa->setCorrelativa_origen($correlativa_origen);
			$correlativa->setCorrelativa_destino($correlativa_destino);
			$correlativa->setCorrelativa_uinsert($correlativa_uinsert);
			$correlativa->setCorrelativa_finsert($correlativa_finsert);
			$correlativa->setCorrelativa_uupdate($correlativa_uupdate);
			$correlativa->setCorrelativa_fupdate($correlativa_fupdate);
			$rs_correlativa = $correlativaAdo->lista($correlativa);
			if(!is_array($rs_correlativa)){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>$rs_correlativa)
				);
				echo json_encode($respuesta);
				exit();
			}
			foreach($rs_correlativa["data"] as $key => $data){
				$arr[] = sanear_string($data);
			}
			$respuesta = array(
				"success"=>true,
				"total"=>$rs_correlativa["total"],
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
			$rs_correlativa = $correlativaAdo->lista_filtro($query, $valuesqry, $limit);
			if(!is_array($rs_correlativa)){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>$rs_correlativa)
				);
				echo json_encode($respuesta);
				exit();
			}
			elseif($rs_correlativa["total"] == 0){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>sanear_string(_NOSEENCONTRARONREGISTROS))
				);
				echo json_encode($respuesta);
				exit();
			}
			else{
				foreach($rs_correlativa["data"] as $key => $data){
					$arr[] = sanear_string($data);
				}
				$respuesta = array(
					"success"=>true,
					"total"=>$rs_correlativa["total"],
					"data"=>$arr
				);
				echo json_encode($respuesta);
				exit();
			}
		break;
	}
}
?>
