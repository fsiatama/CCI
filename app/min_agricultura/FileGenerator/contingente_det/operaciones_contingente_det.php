<?php
session_start();
include('../../lib/config.php');
include_once(PATH_APP."lib/idioma.php");
include_once(PATH_APP."lib/lib_funciones.php");
include_once(PATH_APP."lib/lib_sesion.php");
include_once(PATH_RAIZ."min_agricultura/lib/contingente_det/contingente_detAdo.php");
$contingente_detAdo = new Contingente_detAdo("min_agricultura");
$contingente_det    = new Contingente_det;
if(isset($accion)){
	switch($accion){
		case "act":
			$contingente_det->setContingente_det_id($contingente_det_id);
			$contingente_det->setContingente_det_anio_ini($contingente_det_anio_ini);
			$contingente_det->setContingente_det_anio_fin($contingente_det_anio_fin);
			$contingente_det->setContingente_det_peso_neto($contingente_det_peso_neto);
			$contingente_det->setContingente_det_contingente_id($contingente_det_contingente_id);
			$contingente_det->setContingente_det_contingente_acuerdo_det_id($contingente_det_contingente_acuerdo_det_id);
			$contingente_det->setContingente_det_contingente_acuerdo_det_acuerdo_id($contingente_det_contingente_acuerdo_det_acuerdo_id);
			$rs_contingente_det = $contingente_detAdo->actualizar($contingente_det);
			if($rs_contingente_det !== true){
				$success = false;
			}
			else{
				$success = true;
			}
			$respuesta = array(
				"success"=>$success,
				"errors"=>array("reason"=>$rs_contingente_det)
			);
			echo json_encode($respuesta);
			exit();
		break;
		case "del":
			$contingente_det->setContingente_det_contingente_acuerdo_det_acuerdo_id($contingente_det_contingente_acuerdo_det_acuerdo_id);
			$rs_contingente_det = $contingente_detAdo->borrar($contingente_det);
			if($rs_contingente_det !== true){
				$success = false;
			}
			else{
				$success = true;
			}
			$respuesta = array(
				"success"=>$success,
				"errors"=>array("reason"=>$rs_contingente_det)
			);
			echo json_encode($respuesta);
			exit();
		break;
		case "crea":
			$contingente_det->setContingente_det_id($contingente_det_id);
			$contingente_det->setContingente_det_anio_ini($contingente_det_anio_ini);
			$contingente_det->setContingente_det_anio_fin($contingente_det_anio_fin);
			$contingente_det->setContingente_det_peso_neto($contingente_det_peso_neto);
			$contingente_det->setContingente_det_contingente_id($contingente_det_contingente_id);
			$contingente_det->setContingente_det_contingente_acuerdo_det_id($contingente_det_contingente_acuerdo_det_id);
			$contingente_det->setContingente_det_contingente_acuerdo_det_acuerdo_id($contingente_det_contingente_acuerdo_det_acuerdo_id);
			$rs_contingente_det = $contingente_detAdo->insertar($contingente_det);
			if($rs_contingente_det["success"] !== true){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>"Error creando contingente_det", "error"=>$rs_contingente_det["error"])
				);
				echo json_encode($respuesta);
				exit();
			}
			$contingente_det_contingente_acuerdo_det_acuerdo_id = $rs_contingente_det["insert_id"];
			$respuesta = array(
				"success"=>true,
				"errors"=>array("reason"=>$contingente_det_contingente_acuerdo_det_acuerdo_id)
			);
			echo json_encode($respuesta);
			exit();
		break;
		case "lista":
			$arr = array();
			$contingente_det->setContingente_det_id($contingente_det_id);
			$contingente_det->setContingente_det_anio_ini($contingente_det_anio_ini);
			$contingente_det->setContingente_det_anio_fin($contingente_det_anio_fin);
			$contingente_det->setContingente_det_peso_neto($contingente_det_peso_neto);
			$contingente_det->setContingente_det_contingente_id($contingente_det_contingente_id);
			$contingente_det->setContingente_det_contingente_acuerdo_det_id($contingente_det_contingente_acuerdo_det_id);
			$contingente_det->setContingente_det_contingente_acuerdo_det_acuerdo_id($contingente_det_contingente_acuerdo_det_acuerdo_id);
			$rs_contingente_det = $contingente_detAdo->lista($contingente_det);
			if(!is_array($rs_contingente_det)){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>$rs_contingente_det)
				);
				echo json_encode($respuesta);
				exit();
			}
			foreach($rs_contingente_det["data"] as $key => $data){
				$arr[] = sanear_string($data);
			}
			$respuesta = array(
				"success"=>true,
				"total"=>$rs_contingente_det["total"],
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
			$rs_contingente_det = $contingente_detAdo->lista_filtro($query, $valuesqry, $limit);
			if(!is_array($rs_contingente_det)){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>$rs_contingente_det)
				);
				echo json_encode($respuesta);
				exit();
			}
			elseif($rs_contingente_det["total"] == 0){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>sanear_string(_NOSEENCONTRARONREGISTROS))
				);
				echo json_encode($respuesta);
				exit();
			}
			else{
				foreach($rs_contingente_det["data"] as $key => $data){
					$arr[] = sanear_string($data);
				}
				$respuesta = array(
					"success"=>true,
					"total"=>$rs_contingente_det["total"],
					"data"=>$arr
				);
				echo json_encode($respuesta);
				exit();
			}
		break;
	}
}
?>
