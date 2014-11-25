<?php
session_start();
include('../../lib/config.php');
include_once(PATH_APP."lib/idioma.php");
include_once(PATH_APP."lib/lib_funciones.php");
include_once(PATH_APP."lib/lib_sesion.php");
include_once(PATH_RAIZ."min_agricultura/lib/indicador/indicadorAdo.php");
$indicadorAdo = new IndicadorAdo("min_agricultura");
$indicador    = new Indicador;
if(isset($accion)){
	switch($accion){
		case "act":
			$indicador->setIndicador_id($indicador_id);
			$indicador->setIndicador_nombre($indicador_nombre);
			$indicador->setIndicador_tipo_indicador_id($indicador_tipo_indicador_id);
			$indicador->setIndicador_campos($indicador_campos);
			$indicador->setIndicador_filtros($indicador_filtros);
			$indicador->setIndicador_leaf($indicador_leaf);
			$indicador->setIndicador_parent($indicador_parent);
			$indicador->setIndicador_uinsert($indicador_uinsert);
			$indicador->setIndicador_finsert($indicador_finsert);
			$indicador->setIndicador_fupdate($indicador_fupdate);
			$rs_indicador = $indicadorAdo->actualizar($indicador);
			if($rs_indicador !== true){
				$success = false;
			}
			else{
				$success = true;
			}
			$respuesta = array(
				"success"=>$success,
				"errors"=>array("reason"=>$rs_indicador)
			);
			echo json_encode($respuesta);
			exit();
		break;
		case "del":
			$indicador->setIndicador_id($indicador_id);
			$rs_indicador = $indicadorAdo->borrar($indicador);
			if($rs_indicador !== true){
				$success = false;
			}
			else{
				$success = true;
			}
			$respuesta = array(
				"success"=>$success,
				"errors"=>array("reason"=>$rs_indicador)
			);
			echo json_encode($respuesta);
			exit();
		break;
		case "crea":
			$indicador->setIndicador_id($indicador_id);
			$indicador->setIndicador_nombre($indicador_nombre);
			$indicador->setIndicador_tipo_indicador_id($indicador_tipo_indicador_id);
			$indicador->setIndicador_campos($indicador_campos);
			$indicador->setIndicador_filtros($indicador_filtros);
			$indicador->setIndicador_leaf($indicador_leaf);
			$indicador->setIndicador_parent($indicador_parent);
			$indicador->setIndicador_uinsert($indicador_uinsert);
			$indicador->setIndicador_finsert($indicador_finsert);
			$indicador->setIndicador_fupdate($indicador_fupdate);
			$rs_indicador = $indicadorAdo->insertar($indicador);
			if($rs_indicador["success"] !== true){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>"Error creando indicador", "error"=>$rs_indicador["error"])
				);
				echo json_encode($respuesta);
				exit();
			}
			$indicador_id = $rs_indicador["insert_id"];
			$respuesta = array(
				"success"=>true,
				"errors"=>array("reason"=>$indicador_id)
			);
			echo json_encode($respuesta);
			exit();
		break;
		case "lista":
			$arr = array();
			$indicador->setIndicador_id($indicador_id);
			$indicador->setIndicador_nombre($indicador_nombre);
			$indicador->setIndicador_tipo_indicador_id($indicador_tipo_indicador_id);
			$indicador->setIndicador_campos($indicador_campos);
			$indicador->setIndicador_filtros($indicador_filtros);
			$indicador->setIndicador_leaf($indicador_leaf);
			$indicador->setIndicador_parent($indicador_parent);
			$indicador->setIndicador_uinsert($indicador_uinsert);
			$indicador->setIndicador_finsert($indicador_finsert);
			$indicador->setIndicador_fupdate($indicador_fupdate);
			$rs_indicador = $indicadorAdo->lista($indicador);
			if(!is_array($rs_indicador)){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>$rs_indicador)
				);
				echo json_encode($respuesta);
				exit();
			}
			foreach($rs_indicador["data"] as $key => $data){
				$arr[] = sanear_string($data);
			}
			$respuesta = array(
				"success"=>true,
				"total"=>$rs_indicador["total"],
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
			$rs_indicador = $indicadorAdo->lista_filtro($query, $valuesqry, $limit);
			if(!is_array($rs_indicador)){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>$rs_indicador)
				);
				echo json_encode($respuesta);
				exit();
			}
			elseif($rs_indicador["total"] == 0){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>sanear_string(_NOSEENCONTRARONREGISTROS))
				);
				echo json_encode($respuesta);
				exit();
			}
			else{
				foreach($rs_indicador["data"] as $key => $data){
					$arr[] = sanear_string($data);
				}
				$respuesta = array(
					"success"=>true,
					"total"=>$rs_indicador["total"],
					"data"=>$arr
				);
				echo json_encode($respuesta);
				exit();
			}
		break;
	}
}
?>
