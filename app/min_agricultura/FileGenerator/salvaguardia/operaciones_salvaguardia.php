<?php
session_start();
include('../../lib/config.php');
include_once(PATH_APP."lib/idioma.php");
include_once(PATH_APP."lib/lib_funciones.php");
include_once(PATH_APP."lib/lib_sesion.php");
include_once(PATH_RAIZ."min_agricultura/lib/salvaguardia/salvaguardiaAdo.php");
$salvaguardiaAdo = new SalvaguardiaAdo("min_agricultura");
$salvaguardia    = new Salvaguardia;
if(isset($accion)){
	switch($accion){
		case "act":
			$salvaguardia->setSalvaguardia_id($salvaguardia_id);
			$salvaguardia->setSalvaguardia_msalvaguardia($salvaguardia_msalvaguardia);
			$salvaguardia->setSalvaguardia_contingente_id($salvaguardia_contingente_id);
			$salvaguardia->setSalvaguardia_contingente_acuerdo_det_id($salvaguardia_contingente_acuerdo_det_id);
			$salvaguardia->setSalvaguardia_contingente_acuerdo_det_acuerdo_id($salvaguardia_contingente_acuerdo_det_acuerdo_id);
			$rs_salvaguardia = $salvaguardiaAdo->actualizar($salvaguardia);
			if($rs_salvaguardia !== true){
				$success = false;
			}
			else{
				$success = true;
			}
			$respuesta = array(
				"success"=>$success,
				"errors"=>array("reason"=>$rs_salvaguardia)
			);
			echo json_encode($respuesta);
			exit();
		break;
		case "del":
			$salvaguardia->setSalvaguardia_contingente_acuerdo_det_acuerdo_id($salvaguardia_contingente_acuerdo_det_acuerdo_id);
			$rs_salvaguardia = $salvaguardiaAdo->borrar($salvaguardia);
			if($rs_salvaguardia !== true){
				$success = false;
			}
			else{
				$success = true;
			}
			$respuesta = array(
				"success"=>$success,
				"errors"=>array("reason"=>$rs_salvaguardia)
			);
			echo json_encode($respuesta);
			exit();
		break;
		case "crea":
			$salvaguardia->setSalvaguardia_id($salvaguardia_id);
			$salvaguardia->setSalvaguardia_msalvaguardia($salvaguardia_msalvaguardia);
			$salvaguardia->setSalvaguardia_contingente_id($salvaguardia_contingente_id);
			$salvaguardia->setSalvaguardia_contingente_acuerdo_det_id($salvaguardia_contingente_acuerdo_det_id);
			$salvaguardia->setSalvaguardia_contingente_acuerdo_det_acuerdo_id($salvaguardia_contingente_acuerdo_det_acuerdo_id);
			$rs_salvaguardia = $salvaguardiaAdo->insertar($salvaguardia);
			if($rs_salvaguardia["success"] !== true){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>"Error creando salvaguardia", "error"=>$rs_salvaguardia["error"])
				);
				echo json_encode($respuesta);
				exit();
			}
			$salvaguardia_contingente_acuerdo_det_acuerdo_id = $rs_salvaguardia["insert_id"];
			$respuesta = array(
				"success"=>true,
				"errors"=>array("reason"=>$salvaguardia_contingente_acuerdo_det_acuerdo_id)
			);
			echo json_encode($respuesta);
			exit();
		break;
		case "lista":
			$arr = array();
			$salvaguardia->setSalvaguardia_id($salvaguardia_id);
			$salvaguardia->setSalvaguardia_msalvaguardia($salvaguardia_msalvaguardia);
			$salvaguardia->setSalvaguardia_contingente_id($salvaguardia_contingente_id);
			$salvaguardia->setSalvaguardia_contingente_acuerdo_det_id($salvaguardia_contingente_acuerdo_det_id);
			$salvaguardia->setSalvaguardia_contingente_acuerdo_det_acuerdo_id($salvaguardia_contingente_acuerdo_det_acuerdo_id);
			$rs_salvaguardia = $salvaguardiaAdo->lista($salvaguardia);
			if(!is_array($rs_salvaguardia)){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>$rs_salvaguardia)
				);
				echo json_encode($respuesta);
				exit();
			}
			foreach($rs_salvaguardia["data"] as $key => $data){
				$arr[] = sanear_string($data);
			}
			$respuesta = array(
				"success"=>true,
				"total"=>$rs_salvaguardia["total"],
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
			$rs_salvaguardia = $salvaguardiaAdo->lista_filtro($query, $valuesqry, $limit);
			if(!is_array($rs_salvaguardia)){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>$rs_salvaguardia)
				);
				echo json_encode($respuesta);
				exit();
			}
			elseif($rs_salvaguardia["total"] == 0){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>sanear_string(_NOSEENCONTRARONREGISTROS))
				);
				echo json_encode($respuesta);
				exit();
			}
			else{
				foreach($rs_salvaguardia["data"] as $key => $data){
					$arr[] = sanear_string($data);
				}
				$respuesta = array(
					"success"=>true,
					"total"=>$rs_salvaguardia["total"],
					"data"=>$arr
				);
				echo json_encode($respuesta);
				exit();
			}
		break;
	}
}
?>
