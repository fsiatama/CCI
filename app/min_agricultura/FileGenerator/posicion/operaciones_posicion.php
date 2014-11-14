<?php
session_start();
include('../../lib/config.php');
include_once(PATH_APP."lib/idioma.php");
include_once(PATH_APP."lib/lib_funciones.php");
include_once(PATH_APP."lib/lib_sesion.php");
include_once(PATH_RAIZ."min_agricultura/lib/posicion/posicionAdo.php");
$posicionAdo = new PosicionAdo("min_agricultura");
$posicion    = new Posicion;
if(isset($accion)){
	switch($accion){
		case "act":
			$posicion->setPosicion_id($posicion_id);
			$posicion->setPosicion($posicion);
			$rs_posicion = $posicionAdo->actualizar($posicion);
			if($rs_posicion !== true){
				$success = false;
			}
			else{
				$success = true;
			}
			$respuesta = array(
				"success"=>$success,
				"errors"=>array("reason"=>$rs_posicion)
			);
			echo json_encode($respuesta);
			exit();
		break;
		case "del":
			$posicion->setPosicion_id($posicion_id);
			$rs_posicion = $posicionAdo->borrar($posicion);
			if($rs_posicion !== true){
				$success = false;
			}
			else{
				$success = true;
			}
			$respuesta = array(
				"success"=>$success,
				"errors"=>array("reason"=>$rs_posicion)
			);
			echo json_encode($respuesta);
			exit();
		break;
		case "crea":
			$posicion->setPosicion_id($posicion_id);
			$posicion->setPosicion($posicion);
			$rs_posicion = $posicionAdo->insertar($posicion);
			if($rs_posicion["success"] !== true){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>"Error creando posicion", "error"=>$rs_posicion["error"])
				);
				echo json_encode($respuesta);
				exit();
			}
			$posicion_id = $rs_posicion["insert_id"];
			$respuesta = array(
				"success"=>true,
				"errors"=>array("reason"=>$posicion_id)
			);
			echo json_encode($respuesta);
			exit();
		break;
		case "lista":
			$arr = array();
			$posicion->setPosicion_id($posicion_id);
			$posicion->setPosicion($posicion);
			$rs_posicion = $posicionAdo->lista($posicion);
			if(!is_array($rs_posicion)){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>$rs_posicion)
				);
				echo json_encode($respuesta);
				exit();
			}
			foreach($rs_posicion["datos"] as $key => $data){
				$arr[] = sanear_string($data);
			}
			$respuesta = array(
				"success"=>true,
				"total"=>$rs_posicion["total"],
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
			$rs_posicion = $posicionAdo->lista_filtro($query, $valuesqry, $limit);
			if(!is_array($rs_posicion)){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>$rs_posicion)
				);
				echo json_encode($respuesta);
				exit();
			}
			elseif($rs_posicion["total"] == 0){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>sanear_string(_NOSEENCONTRARONREGISTROS))
				);
				echo json_encode($respuesta);
				exit();
			}
			else{
				foreach($rs_posicion["datos"] as $key => $data){
					$arr[] = sanear_string($data);
				}
				$respuesta = array(
					"success"=>true,
					"total"=>$rs_posicion["total"],
					"datos"=>$arr
				);
				echo json_encode($respuesta);
				exit();
			}
		break;
	}
}
?>
