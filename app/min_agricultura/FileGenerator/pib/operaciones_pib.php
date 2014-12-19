<?php
session_start();
include('../../lib/config.php');
include_once(PATH_APP."lib/idioma.php");
include_once(PATH_APP."lib/lib_funciones.php");
include_once(PATH_APP."lib/lib_sesion.php");
include_once(PATH_RAIZ."min_agricultura/lib/pib/pibAdo.php");
$pibAdo = new PibAdo("min_agricultura");
$pib    = new Pib;
if(isset($accion)){
	switch($accion){
		case "act":
			$pib->setPib_id($pib_id);
			$pib->setPib_anio($pib_anio);
			$pib->setPib_periodo($pib_periodo);
			$pib->setPib_agricultura($pib_agricultura);
			$pib->setPib_nacional($pib_nacional);
			$pib->setPib_finsert($pib_finsert);
			$pib->setPib_uinsert($pib_uinsert);
			$pib->setPib_fupdate($pib_fupdate);
			$pib->setPib_uupdate($pib_uupdate);
			$rs_pib = $pibAdo->actualizar($pib);
			if($rs_pib !== true){
				$success = false;
			}
			else{
				$success = true;
			}
			$respuesta = array(
				"success"=>$success,
				"errors"=>array("reason"=>$rs_pib)
			);
			echo json_encode($respuesta);
			exit();
		break;
		case "del":
			$pib->setPib_id($pib_id);
			$rs_pib = $pibAdo->borrar($pib);
			if($rs_pib !== true){
				$success = false;
			}
			else{
				$success = true;
			}
			$respuesta = array(
				"success"=>$success,
				"errors"=>array("reason"=>$rs_pib)
			);
			echo json_encode($respuesta);
			exit();
		break;
		case "crea":
			$pib->setPib_id($pib_id);
			$pib->setPib_anio($pib_anio);
			$pib->setPib_periodo($pib_periodo);
			$pib->setPib_agricultura($pib_agricultura);
			$pib->setPib_nacional($pib_nacional);
			$pib->setPib_finsert($pib_finsert);
			$pib->setPib_uinsert($pib_uinsert);
			$pib->setPib_fupdate($pib_fupdate);
			$pib->setPib_uupdate($pib_uupdate);
			$rs_pib = $pibAdo->insertar($pib);
			if($rs_pib["success"] !== true){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>"Error creando pib", "error"=>$rs_pib["error"])
				);
				echo json_encode($respuesta);
				exit();
			}
			$pib_id = $rs_pib["insert_id"];
			$respuesta = array(
				"success"=>true,
				"errors"=>array("reason"=>$pib_id)
			);
			echo json_encode($respuesta);
			exit();
		break;
		case "lista":
			$arr = array();
			$pib->setPib_id($pib_id);
			$pib->setPib_anio($pib_anio);
			$pib->setPib_periodo($pib_periodo);
			$pib->setPib_agricultura($pib_agricultura);
			$pib->setPib_nacional($pib_nacional);
			$pib->setPib_finsert($pib_finsert);
			$pib->setPib_uinsert($pib_uinsert);
			$pib->setPib_fupdate($pib_fupdate);
			$pib->setPib_uupdate($pib_uupdate);
			$rs_pib = $pibAdo->lista($pib);
			if(!is_array($rs_pib)){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>$rs_pib)
				);
				echo json_encode($respuesta);
				exit();
			}
			foreach($rs_pib["data"] as $key => $data){
				$arr[] = sanear_string($data);
			}
			$respuesta = array(
				"success"=>true,
				"total"=>$rs_pib["total"],
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
			$rs_pib = $pibAdo->lista_filtro($query, $valuesqry, $limit);
			if(!is_array($rs_pib)){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>$rs_pib)
				);
				echo json_encode($respuesta);
				exit();
			}
			elseif($rs_pib["total"] == 0){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>sanear_string(_NOSEENCONTRARONREGISTROS))
				);
				echo json_encode($respuesta);
				exit();
			}
			else{
				foreach($rs_pib["data"] as $key => $data){
					$arr[] = sanear_string($data);
				}
				$respuesta = array(
					"success"=>true,
					"total"=>$rs_pib["total"],
					"data"=>$arr
				);
				echo json_encode($respuesta);
				exit();
			}
		break;
	}
}
?>
