<?php
session_start();
include('../../lib/config.php');
include_once(PATH_APP."lib/idioma.php");
include_once(PATH_APP."lib/lib_funciones.php");
include_once(PATH_APP."lib/lib_sesion.php");
include_once(PATH_RAIZ."min_agricultura/lib/tipo_indicador/tipo_indicadorAdo.php");
$tipo_indicadorAdo = new Tipo_indicadorAdo("min_agricultura");
$tipo_indicador    = new Tipo_indicador;
if(isset($accion)){
	switch($accion){
		case "act":
			$tipo_indicador->setTipo_indicador_id($tipo_indicador_id);
			$tipo_indicador->setTipo_indicador_nombre($tipo_indicador_nombre);
			$tipo_indicador->setTipo_indicador_abrev($tipo_indicador_abrev);
			$tipo_indicador->setTipo_indicador_activador($tipo_indicador_activador);
			$tipo_indicador->setTipo_indicador_calculo($tipo_indicador_calculo);
			$tipo_indicador->setTipo_indicador_definicion($tipo_indicador_definicion);
			$rs_tipo_indicador = $tipo_indicadorAdo->actualizar($tipo_indicador);
			if($rs_tipo_indicador !== true){
				$success = false;
			}
			else{
				$success = true;
			}
			$respuesta = array(
				"success"=>$success,
				"errors"=>array("reason"=>$rs_tipo_indicador)
			);
			echo json_encode($respuesta);
			exit();
		break;
		case "del":
			$tipo_indicador->setTipo_indicador_id($tipo_indicador_id);
			$rs_tipo_indicador = $tipo_indicadorAdo->borrar($tipo_indicador);
			if($rs_tipo_indicador !== true){
				$success = false;
			}
			else{
				$success = true;
			}
			$respuesta = array(
				"success"=>$success,
				"errors"=>array("reason"=>$rs_tipo_indicador)
			);
			echo json_encode($respuesta);
			exit();
		break;
		case "crea":
			$tipo_indicador->setTipo_indicador_id($tipo_indicador_id);
			$tipo_indicador->setTipo_indicador_nombre($tipo_indicador_nombre);
			$tipo_indicador->setTipo_indicador_abrev($tipo_indicador_abrev);
			$tipo_indicador->setTipo_indicador_activador($tipo_indicador_activador);
			$tipo_indicador->setTipo_indicador_calculo($tipo_indicador_calculo);
			$tipo_indicador->setTipo_indicador_definicion($tipo_indicador_definicion);
			$rs_tipo_indicador = $tipo_indicadorAdo->insertar($tipo_indicador);
			if($rs_tipo_indicador["success"] !== true){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>"Error creando tipo_indicador", "error"=>$rs_tipo_indicador["error"])
				);
				echo json_encode($respuesta);
				exit();
			}
			$tipo_indicador_id = $rs_tipo_indicador["insert_id"];
			$respuesta = array(
				"success"=>true,
				"errors"=>array("reason"=>$tipo_indicador_id)
			);
			echo json_encode($respuesta);
			exit();
		break;
		case "lista":
			$arr = array();
			$tipo_indicador->setTipo_indicador_id($tipo_indicador_id);
			$tipo_indicador->setTipo_indicador_nombre($tipo_indicador_nombre);
			$tipo_indicador->setTipo_indicador_abrev($tipo_indicador_abrev);
			$tipo_indicador->setTipo_indicador_activador($tipo_indicador_activador);
			$tipo_indicador->setTipo_indicador_calculo($tipo_indicador_calculo);
			$tipo_indicador->setTipo_indicador_definicion($tipo_indicador_definicion);
			$rs_tipo_indicador = $tipo_indicadorAdo->lista($tipo_indicador);
			if(!is_array($rs_tipo_indicador)){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>$rs_tipo_indicador)
				);
				echo json_encode($respuesta);
				exit();
			}
			foreach($rs_tipo_indicador["data"] as $key => $data){
				$arr[] = sanear_string($data);
			}
			$respuesta = array(
				"success"=>true,
				"total"=>$rs_tipo_indicador["total"],
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
			$rs_tipo_indicador = $tipo_indicadorAdo->lista_filtro($query, $valuesqry, $limit);
			if(!is_array($rs_tipo_indicador)){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>$rs_tipo_indicador)
				);
				echo json_encode($respuesta);
				exit();
			}
			elseif($rs_tipo_indicador["total"] == 0){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>sanear_string(_NOSEENCONTRARONREGISTROS))
				);
				echo json_encode($respuesta);
				exit();
			}
			else{
				foreach($rs_tipo_indicador["data"] as $key => $data){
					$arr[] = sanear_string($data);
				}
				$respuesta = array(
					"success"=>true,
					"total"=>$rs_tipo_indicador["total"],
					"data"=>$arr
				);
				echo json_encode($respuesta);
				exit();
			}
		break;
	}
}
?>
