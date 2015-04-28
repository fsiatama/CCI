<?php
session_start();
include('../../lib/config.php');
include_once(PATH_APP."lib/idioma.php");
include_once(PATH_APP."lib/lib_funciones.php");
include_once(PATH_APP."lib/lib_sesion.php");
include_once(PATH_RAIZ."min_agricultura/lib/sobordoimp/sobordoimpAdo.php");
$sobordoimpAdo = new SobordoimpAdo("min_agricultura");
$sobordoimp    = new Sobordoimp;
if(isset($accion)){
	switch($accion){
		case "act":
			$sobordoimp->setId($id);
			$sobordoimp->setAnio($anio);
			$sobordoimp->setPeriodo($periodo);
			$sobordoimp->setFecha($fecha);
			$sobordoimp->setId_paisprocedencia($id_paisprocedencia);
			$sobordoimp->setId_capitulo($id_capitulo);
			$sobordoimp->setId_partida($id_partida);
			$sobordoimp->setId_subpartida($id_subpartida);
			$sobordoimp->setPeso_neto($peso_neto);
			$rs_sobordoimp = $sobordoimpAdo->actualizar($sobordoimp);
			if($rs_sobordoimp !== true){
				$success = false;
			}
			else{
				$success = true;
			}
			$respuesta = array(
				"success"=>$success,
				"errors"=>array("reason"=>$rs_sobordoimp)
			);
			echo json_encode($respuesta);
			exit();
		break;
		case "del":
			$sobordoimp->setId($id);
			$rs_sobordoimp = $sobordoimpAdo->borrar($sobordoimp);
			if($rs_sobordoimp !== true){
				$success = false;
			}
			else{
				$success = true;
			}
			$respuesta = array(
				"success"=>$success,
				"errors"=>array("reason"=>$rs_sobordoimp)
			);
			echo json_encode($respuesta);
			exit();
		break;
		case "crea":
			$sobordoimp->setId($id);
			$sobordoimp->setAnio($anio);
			$sobordoimp->setPeriodo($periodo);
			$sobordoimp->setFecha($fecha);
			$sobordoimp->setId_paisprocedencia($id_paisprocedencia);
			$sobordoimp->setId_capitulo($id_capitulo);
			$sobordoimp->setId_partida($id_partida);
			$sobordoimp->setId_subpartida($id_subpartida);
			$sobordoimp->setPeso_neto($peso_neto);
			$rs_sobordoimp = $sobordoimpAdo->insertar($sobordoimp);
			if($rs_sobordoimp["success"] !== true){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>"Error creando sobordoimp", "error"=>$rs_sobordoimp["error"])
				);
				echo json_encode($respuesta);
				exit();
			}
			$id = $rs_sobordoimp["insert_id"];
			$respuesta = array(
				"success"=>true,
				"errors"=>array("reason"=>$id)
			);
			echo json_encode($respuesta);
			exit();
		break;
		case "lista":
			$arr = array();
			$sobordoimp->setId($id);
			$sobordoimp->setAnio($anio);
			$sobordoimp->setPeriodo($periodo);
			$sobordoimp->setFecha($fecha);
			$sobordoimp->setId_paisprocedencia($id_paisprocedencia);
			$sobordoimp->setId_capitulo($id_capitulo);
			$sobordoimp->setId_partida($id_partida);
			$sobordoimp->setId_subpartida($id_subpartida);
			$sobordoimp->setPeso_neto($peso_neto);
			$rs_sobordoimp = $sobordoimpAdo->lista($sobordoimp);
			if(!is_array($rs_sobordoimp)){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>$rs_sobordoimp)
				);
				echo json_encode($respuesta);
				exit();
			}
			foreach($rs_sobordoimp["data"] as $key => $data){
				$arr[] = sanear_string($data);
			}
			$respuesta = array(
				"success"=>true,
				"total"=>$rs_sobordoimp["total"],
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
			$rs_sobordoimp = $sobordoimpAdo->lista_filtro($query, $valuesqry, $limit);
			if(!is_array($rs_sobordoimp)){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>$rs_sobordoimp)
				);
				echo json_encode($respuesta);
				exit();
			}
			elseif($rs_sobordoimp["total"] == 0){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>sanear_string(_NOSEENCONTRARONREGISTROS))
				);
				echo json_encode($respuesta);
				exit();
			}
			else{
				foreach($rs_sobordoimp["data"] as $key => $data){
					$arr[] = sanear_string($data);
				}
				$respuesta = array(
					"success"=>true,
					"total"=>$rs_sobordoimp["total"],
					"data"=>$arr
				);
				echo json_encode($respuesta);
				exit();
			}
		break;
	}
}
?>
