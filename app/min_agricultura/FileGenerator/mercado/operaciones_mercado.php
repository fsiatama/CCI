<?php
session_start();
include('../../lib/config.php');
include_once(PATH_APP."lib/idioma.php");
include_once(PATH_APP."lib/lib_funciones.php");
include_once(PATH_APP."lib/lib_sesion.php");
include_once(PATH_RAIZ."min_agricultura/lib/mercado/mercadoAdo.php");
$mercadoAdo = new MercadoAdo("min_agricultura");
$mercado    = new Mercado;
if(isset($accion)){
	switch($accion){
		case "act":
			$mercado->setMercado_id($mercado_id);
			$mercado->setMercado_nombre($mercado_nombre);
			$mercado->setMercado_paises($mercado_paises);
			$mercado->setMercado_bandera($mercado_bandera);
			$mercado->setMercado_uinsert($mercado_uinsert);
			$mercado->setMercado_finsert($mercado_finsert);
			$mercado->setMercado_uupdate($mercado_uupdate);
			$mercado->setMercado_fupdate($mercado_fupdate);
			$rs_mercado = $mercadoAdo->actualizar($mercado);
			if($rs_mercado !== true){
				$success = false;
			}
			else{
				$success = true;
			}
			$respuesta = array(
				"success"=>$success,
				"errors"=>array("reason"=>$rs_mercado)
			);
			echo json_encode($respuesta);
			exit();
		break;
		case "del":
			$mercado->setMercado_id($mercado_id);
			$rs_mercado = $mercadoAdo->borrar($mercado);
			if($rs_mercado !== true){
				$success = false;
			}
			else{
				$success = true;
			}
			$respuesta = array(
				"success"=>$success,
				"errors"=>array("reason"=>$rs_mercado)
			);
			echo json_encode($respuesta);
			exit();
		break;
		case "crea":
			$mercado->setMercado_id($mercado_id);
			$mercado->setMercado_nombre($mercado_nombre);
			$mercado->setMercado_paises($mercado_paises);
			$mercado->setMercado_bandera($mercado_bandera);
			$mercado->setMercado_uinsert($mercado_uinsert);
			$mercado->setMercado_finsert($mercado_finsert);
			$mercado->setMercado_uupdate($mercado_uupdate);
			$mercado->setMercado_fupdate($mercado_fupdate);
			$rs_mercado = $mercadoAdo->insertar($mercado);
			if($rs_mercado["success"] !== true){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>"Error creando mercado", "error"=>$rs_mercado["error"])
				);
				echo json_encode($respuesta);
				exit();
			}
			$mercado_id = $rs_mercado["insert_id"];
			$respuesta = array(
				"success"=>true,
				"errors"=>array("reason"=>$mercado_id)
			);
			echo json_encode($respuesta);
			exit();
		break;
		case "lista":
			$arr = array();
			$mercado->setMercado_id($mercado_id);
			$mercado->setMercado_nombre($mercado_nombre);
			$mercado->setMercado_paises($mercado_paises);
			$mercado->setMercado_bandera($mercado_bandera);
			$mercado->setMercado_uinsert($mercado_uinsert);
			$mercado->setMercado_finsert($mercado_finsert);
			$mercado->setMercado_uupdate($mercado_uupdate);
			$mercado->setMercado_fupdate($mercado_fupdate);
			$rs_mercado = $mercadoAdo->lista($mercado);
			if(!is_array($rs_mercado)){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>$rs_mercado)
				);
				echo json_encode($respuesta);
				exit();
			}
			foreach($rs_mercado["data"] as $key => $data){
				$arr[] = sanear_string($data);
			}
			$respuesta = array(
				"success"=>true,
				"total"=>$rs_mercado["total"],
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
			$rs_mercado = $mercadoAdo->lista_filtro($query, $valuesqry, $limit);
			if(!is_array($rs_mercado)){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>$rs_mercado)
				);
				echo json_encode($respuesta);
				exit();
			}
			elseif($rs_mercado["total"] == 0){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>sanear_string(_NOSEENCONTRARONREGISTROS))
				);
				echo json_encode($respuesta);
				exit();
			}
			else{
				foreach($rs_mercado["data"] as $key => $data){
					$arr[] = sanear_string($data);
				}
				$respuesta = array(
					"success"=>true,
					"total"=>$rs_mercado["total"],
					"data"=>$arr
				);
				echo json_encode($respuesta);
				exit();
			}
		break;
	}
}
?>
