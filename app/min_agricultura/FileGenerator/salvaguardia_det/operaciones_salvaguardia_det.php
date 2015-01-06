<?php
session_start();
include('../../lib/config.php');
include_once(PATH_APP."lib/idioma.php");
include_once(PATH_APP."lib/lib_funciones.php");
include_once(PATH_APP."lib/lib_sesion.php");
include_once(PATH_RAIZ."min_agricultura/lib/salvaguardia_det/salvaguardia_detAdo.php");
$salvaguardia_detAdo = new Salvaguardia_detAdo("min_agricultura");
$salvaguardia_det    = new Salvaguardia_det;
if(isset($accion)){
	switch($accion){
		case "act":
			$salvaguardia_det->setSalvaguardia_det_id($salvaguardia_det_id);
			$salvaguardia_det->setSalvaguardia_det_anio_ini($salvaguardia_det_anio_ini);
			$salvaguardia_det->setSalvaguardia_det_anio_fin($salvaguardia_det_anio_fin);
			$salvaguardia_det->setSalvaguardia_det_peso_neto($salvaguardia_det_peso_neto);
			$salvaguardia_det->setSalvaguardia_det_salvaguardia_id($salvaguardia_det_salvaguardia_id);
			$salvaguardia_det->setSalvaguardia_det_salvaguardia_contingente_id($salvaguardia_det_salvaguardia_contingente_id);
			$salvaguardia_det->setSalvaguardia_det_salvaguardia_contingente_acuerdo_det_id($salvaguardia_det_salvaguardia_contingente_acuerdo_det_id);
			$salvaguardia_det->setSalvaguardia_det_salvaguardia_contingente_acuerdo_det_acuerdo_id($salvaguardia_det_salvaguardia_contingente_acuerdo_det_acuerdo_id);
			$rs_salvaguardia_det = $salvaguardia_detAdo->actualizar($salvaguardia_det);
			if($rs_salvaguardia_det !== true){
				$success = false;
			}
			else{
				$success = true;
			}
			$respuesta = array(
				"success"=>$success,
				"errors"=>array("reason"=>$rs_salvaguardia_det)
			);
			echo json_encode($respuesta);
			exit();
		break;
		case "del":
			$salvaguardia_det->setSalvaguardia_det_salvaguardia_contingente_acuerdo_det_acuerdo_id($salvaguardia_det_salvaguardia_contingente_acuerdo_det_acuerdo_id);
			$rs_salvaguardia_det = $salvaguardia_detAdo->borrar($salvaguardia_det);
			if($rs_salvaguardia_det !== true){
				$success = false;
			}
			else{
				$success = true;
			}
			$respuesta = array(
				"success"=>$success,
				"errors"=>array("reason"=>$rs_salvaguardia_det)
			);
			echo json_encode($respuesta);
			exit();
		break;
		case "crea":
			$salvaguardia_det->setSalvaguardia_det_id($salvaguardia_det_id);
			$salvaguardia_det->setSalvaguardia_det_anio_ini($salvaguardia_det_anio_ini);
			$salvaguardia_det->setSalvaguardia_det_anio_fin($salvaguardia_det_anio_fin);
			$salvaguardia_det->setSalvaguardia_det_peso_neto($salvaguardia_det_peso_neto);
			$salvaguardia_det->setSalvaguardia_det_salvaguardia_id($salvaguardia_det_salvaguardia_id);
			$salvaguardia_det->setSalvaguardia_det_salvaguardia_contingente_id($salvaguardia_det_salvaguardia_contingente_id);
			$salvaguardia_det->setSalvaguardia_det_salvaguardia_contingente_acuerdo_det_id($salvaguardia_det_salvaguardia_contingente_acuerdo_det_id);
			$salvaguardia_det->setSalvaguardia_det_salvaguardia_contingente_acuerdo_det_acuerdo_id($salvaguardia_det_salvaguardia_contingente_acuerdo_det_acuerdo_id);
			$rs_salvaguardia_det = $salvaguardia_detAdo->insertar($salvaguardia_det);
			if($rs_salvaguardia_det["success"] !== true){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>"Error creando salvaguardia_det", "error"=>$rs_salvaguardia_det["error"])
				);
				echo json_encode($respuesta);
				exit();
			}
			$salvaguardia_det_salvaguardia_contingente_acuerdo_det_acuerdo_id = $rs_salvaguardia_det["insert_id"];
			$respuesta = array(
				"success"=>true,
				"errors"=>array("reason"=>$salvaguardia_det_salvaguardia_contingente_acuerdo_det_acuerdo_id)
			);
			echo json_encode($respuesta);
			exit();
		break;
		case "lista":
			$arr = array();
			$salvaguardia_det->setSalvaguardia_det_id($salvaguardia_det_id);
			$salvaguardia_det->setSalvaguardia_det_anio_ini($salvaguardia_det_anio_ini);
			$salvaguardia_det->setSalvaguardia_det_anio_fin($salvaguardia_det_anio_fin);
			$salvaguardia_det->setSalvaguardia_det_peso_neto($salvaguardia_det_peso_neto);
			$salvaguardia_det->setSalvaguardia_det_salvaguardia_id($salvaguardia_det_salvaguardia_id);
			$salvaguardia_det->setSalvaguardia_det_salvaguardia_contingente_id($salvaguardia_det_salvaguardia_contingente_id);
			$salvaguardia_det->setSalvaguardia_det_salvaguardia_contingente_acuerdo_det_id($salvaguardia_det_salvaguardia_contingente_acuerdo_det_id);
			$salvaguardia_det->setSalvaguardia_det_salvaguardia_contingente_acuerdo_det_acuerdo_id($salvaguardia_det_salvaguardia_contingente_acuerdo_det_acuerdo_id);
			$rs_salvaguardia_det = $salvaguardia_detAdo->lista($salvaguardia_det);
			if(!is_array($rs_salvaguardia_det)){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>$rs_salvaguardia_det)
				);
				echo json_encode($respuesta);
				exit();
			}
			foreach($rs_salvaguardia_det["data"] as $key => $data){
				$arr[] = sanear_string($data);
			}
			$respuesta = array(
				"success"=>true,
				"total"=>$rs_salvaguardia_det["total"],
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
			$rs_salvaguardia_det = $salvaguardia_detAdo->lista_filtro($query, $valuesqry, $limit);
			if(!is_array($rs_salvaguardia_det)){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>$rs_salvaguardia_det)
				);
				echo json_encode($respuesta);
				exit();
			}
			elseif($rs_salvaguardia_det["total"] == 0){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>sanear_string(_NOSEENCONTRARONREGISTROS))
				);
				echo json_encode($respuesta);
				exit();
			}
			else{
				foreach($rs_salvaguardia_det["data"] as $key => $data){
					$arr[] = sanear_string($data);
				}
				$respuesta = array(
					"success"=>true,
					"total"=>$rs_salvaguardia_det["total"],
					"data"=>$arr
				);
				echo json_encode($respuesta);
				exit();
			}
		break;
	}
}
?>
