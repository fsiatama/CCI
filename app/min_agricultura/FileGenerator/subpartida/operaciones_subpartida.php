<?php
session_start();
include('../../lib/config.php');
include_once(PATH_APP."lib/idioma.php");
include_once(PATH_APP."lib/lib_funciones.php");
include_once(PATH_APP."lib/lib_sesion.php");
include_once(PATH_RAIZ."min_agricultura/lib/subpartida/subpartidaAdo.php");
$subpartidaAdo = new SubpartidaAdo("min_agricultura");
$subpartida    = new Subpartida;
if(isset($accion)){
	switch($accion){
		case "act":
			$subpartida->setId_subpartida($id_subpartida);
			$subpartida->setSubpartida($subpartida);
			$subpartida->setId_capitulo($id_capitulo);
			$subpartida->setId_partida($id_partida);
			$rs_subpartida = $subpartidaAdo->actualizar($subpartida);
			if($rs_subpartida !== true){
				$success = false;
			}
			else{
				$success = true;
			}
			$respuesta = array(
				"success"=>$success,
				"errors"=>array("reason"=>$rs_subpartida)
			);
			echo json_encode($respuesta);
			exit();
		break;
		case "del":
			$subpartida->setId_subpartida($id_subpartida);
			$rs_subpartida = $subpartidaAdo->borrar($subpartida);
			if($rs_subpartida !== true){
				$success = false;
			}
			else{
				$success = true;
			}
			$respuesta = array(
				"success"=>$success,
				"errors"=>array("reason"=>$rs_subpartida)
			);
			echo json_encode($respuesta);
			exit();
		break;
		case "crea":
			$subpartida->setId_subpartida($id_subpartida);
			$subpartida->setSubpartida($subpartida);
			$subpartida->setId_capitulo($id_capitulo);
			$subpartida->setId_partida($id_partida);
			$rs_subpartida = $subpartidaAdo->insertar($subpartida);
			if($rs_subpartida["success"] !== true){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>"Error creando subpartida", "error"=>$rs_subpartida["error"])
				);
				echo json_encode($respuesta);
				exit();
			}
			$id_subpartida = $rs_subpartida["insert_id"];
			$respuesta = array(
				"success"=>true,
				"errors"=>array("reason"=>$id_subpartida)
			);
			echo json_encode($respuesta);
			exit();
		break;
		case "lista":
			$arr = array();
			$subpartida->setId_subpartida($id_subpartida);
			$subpartida->setSubpartida($subpartida);
			$subpartida->setId_capitulo($id_capitulo);
			$subpartida->setId_partida($id_partida);
			$rs_subpartida = $subpartidaAdo->lista($subpartida);
			if(!is_array($rs_subpartida)){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>$rs_subpartida)
				);
				echo json_encode($respuesta);
				exit();
			}
			foreach($rs_subpartida["data"] as $key => $data){
				$arr[] = sanear_string($data);
			}
			$respuesta = array(
				"success"=>true,
				"total"=>$rs_subpartida["total"],
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
			$rs_subpartida = $subpartidaAdo->lista_filtro($query, $valuesqry, $limit);
			if(!is_array($rs_subpartida)){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>$rs_subpartida)
				);
				echo json_encode($respuesta);
				exit();
			}
			elseif($rs_subpartida["total"] == 0){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>sanear_string(_NOSEENCONTRARONREGISTROS))
				);
				echo json_encode($respuesta);
				exit();
			}
			else{
				foreach($rs_subpartida["data"] as $key => $data){
					$arr[] = sanear_string($data);
				}
				$respuesta = array(
					"success"=>true,
					"total"=>$rs_subpartida["total"],
					"data"=>$arr
				);
				echo json_encode($respuesta);
				exit();
			}
		break;
	}
}
?>
