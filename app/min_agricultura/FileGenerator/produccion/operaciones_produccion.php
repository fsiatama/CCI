<?php
session_start();
include('../../lib/config.php');
include_once(PATH_APP."lib/idioma.php");
include_once(PATH_APP."lib/lib_funciones.php");
include_once(PATH_APP."lib/lib_sesion.php");
include_once(PATH_RAIZ."min_agricultura/lib/produccion/produccionAdo.php");
$produccionAdo = new ProduccionAdo("min_agricultura");
$produccion    = new Produccion;
if(isset($accion)){
	switch($accion){
		case "act":
			$produccion->setProduccion_id($produccion_id);
			$produccion->setProduccion_sector_id($produccion_sector_id);
			$produccion->setProduccion_anio($produccion_anio);
			$produccion->setProduccion_peso_neto($produccion_peso_neto);
			$produccion->setProduccion_finsert($produccion_finsert);
			$produccion->setProduccion_uinsert($produccion_uinsert);
			$produccion->setProduccion_fupdate($produccion_fupdate);
			$produccion->setProduccion_uupdate($produccion_uupdate);
			$rs_produccion = $produccionAdo->actualizar($produccion);
			if($rs_produccion !== true){
				$success = false;
			}
			else{
				$success = true;
			}
			$respuesta = array(
				"success"=>$success,
				"errors"=>array("reason"=>$rs_produccion)
			);
			echo json_encode($respuesta);
			exit();
		break;
		case "del":
			$produccion->setProduccion_id($produccion_id);
			$rs_produccion = $produccionAdo->borrar($produccion);
			if($rs_produccion !== true){
				$success = false;
			}
			else{
				$success = true;
			}
			$respuesta = array(
				"success"=>$success,
				"errors"=>array("reason"=>$rs_produccion)
			);
			echo json_encode($respuesta);
			exit();
		break;
		case "crea":
			$produccion->setProduccion_id($produccion_id);
			$produccion->setProduccion_sector_id($produccion_sector_id);
			$produccion->setProduccion_anio($produccion_anio);
			$produccion->setProduccion_peso_neto($produccion_peso_neto);
			$produccion->setProduccion_finsert($produccion_finsert);
			$produccion->setProduccion_uinsert($produccion_uinsert);
			$produccion->setProduccion_fupdate($produccion_fupdate);
			$produccion->setProduccion_uupdate($produccion_uupdate);
			$rs_produccion = $produccionAdo->insertar($produccion);
			if($rs_produccion["success"] !== true){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>"Error creando produccion", "error"=>$rs_produccion["error"])
				);
				echo json_encode($respuesta);
				exit();
			}
			$produccion_id = $rs_produccion["insert_id"];
			$respuesta = array(
				"success"=>true,
				"errors"=>array("reason"=>$produccion_id)
			);
			echo json_encode($respuesta);
			exit();
		break;
		case "lista":
			$arr = array();
			$produccion->setProduccion_id($produccion_id);
			$produccion->setProduccion_sector_id($produccion_sector_id);
			$produccion->setProduccion_anio($produccion_anio);
			$produccion->setProduccion_peso_neto($produccion_peso_neto);
			$produccion->setProduccion_finsert($produccion_finsert);
			$produccion->setProduccion_uinsert($produccion_uinsert);
			$produccion->setProduccion_fupdate($produccion_fupdate);
			$produccion->setProduccion_uupdate($produccion_uupdate);
			$rs_produccion = $produccionAdo->lista($produccion);
			if(!is_array($rs_produccion)){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>$rs_produccion)
				);
				echo json_encode($respuesta);
				exit();
			}
			foreach($rs_produccion["data"] as $key => $data){
				$arr[] = sanear_string($data);
			}
			$respuesta = array(
				"success"=>true,
				"total"=>$rs_produccion["total"],
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
			$rs_produccion = $produccionAdo->lista_filtro($query, $valuesqry, $limit);
			if(!is_array($rs_produccion)){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>$rs_produccion)
				);
				echo json_encode($respuesta);
				exit();
			}
			elseif($rs_produccion["total"] == 0){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>sanear_string(_NOSEENCONTRARONREGISTROS))
				);
				echo json_encode($respuesta);
				exit();
			}
			else{
				foreach($rs_produccion["data"] as $key => $data){
					$arr[] = sanear_string($data);
				}
				$respuesta = array(
					"success"=>true,
					"total"=>$rs_produccion["total"],
					"data"=>$arr
				);
				echo json_encode($respuesta);
				exit();
			}
		break;
	}
}
?>
