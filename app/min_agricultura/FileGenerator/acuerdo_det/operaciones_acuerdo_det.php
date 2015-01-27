<?php
session_start();
include('../../lib/config.php');
include_once(PATH_APP."lib/idioma.php");
include_once(PATH_APP."lib/lib_funciones.php");
include_once(PATH_APP."lib/lib_sesion.php");
include_once(PATH_RAIZ."min_agricultura/lib/acuerdo_det/acuerdo_detAdo.php");
$acuerdo_detAdo = new Acuerdo_detAdo("min_agricultura");
$acuerdo_det    = new Acuerdo_det;
if(isset($accion)){
	switch($accion){
		case "act":
			$acuerdo_det->setAcuerdo_det_id($acuerdo_det_id);
			$acuerdo_det->setAcuerdo_det_arancel_base($acuerdo_det_arancel_base);
			$acuerdo_det->setAcuerdo_det_productos($acuerdo_det_productos);
			$acuerdo_det->setAcuerdo_det_productos_desc($acuerdo_det_productos_desc);
			$acuerdo_det->setAcuerdo_det_administracion($acuerdo_det_administracion);
			$acuerdo_det->setAcuerdo_det_administrador($acuerdo_det_administrador);
			$acuerdo_det->setAcuerdo_det_nperiodos($acuerdo_det_nperiodos);
			$acuerdo_det->setAcuerdo_det_acuerdo_id($acuerdo_det_acuerdo_id);
			$acuerdo_det->setAcuerdo_det_contingente_acumulado_pais($acuerdo_det_contingente_acumulado_pais);
			$acuerdo_det->setAcuerdo_det_desgravacion_igual_pais($acuerdo_det_desgravacion_igual_pais);
			$rs_acuerdo_det = $acuerdo_detAdo->actualizar($acuerdo_det);
			if($rs_acuerdo_det !== true){
				$success = false;
			}
			else{
				$success = true;
			}
			$respuesta = array(
				"success"=>$success,
				"errors"=>array("reason"=>$rs_acuerdo_det)
			);
			echo json_encode($respuesta);
			exit();
		break;
		case "del":
			$acuerdo_det->setAcuerdo_det_acuerdo_id($acuerdo_det_acuerdo_id);
			$rs_acuerdo_det = $acuerdo_detAdo->borrar($acuerdo_det);
			if($rs_acuerdo_det !== true){
				$success = false;
			}
			else{
				$success = true;
			}
			$respuesta = array(
				"success"=>$success,
				"errors"=>array("reason"=>$rs_acuerdo_det)
			);
			echo json_encode($respuesta);
			exit();
		break;
		case "crea":
			$acuerdo_det->setAcuerdo_det_id($acuerdo_det_id);
			$acuerdo_det->setAcuerdo_det_arancel_base($acuerdo_det_arancel_base);
			$acuerdo_det->setAcuerdo_det_productos($acuerdo_det_productos);
			$acuerdo_det->setAcuerdo_det_productos_desc($acuerdo_det_productos_desc);
			$acuerdo_det->setAcuerdo_det_administracion($acuerdo_det_administracion);
			$acuerdo_det->setAcuerdo_det_administrador($acuerdo_det_administrador);
			$acuerdo_det->setAcuerdo_det_nperiodos($acuerdo_det_nperiodos);
			$acuerdo_det->setAcuerdo_det_acuerdo_id($acuerdo_det_acuerdo_id);
			$acuerdo_det->setAcuerdo_det_contingente_acumulado_pais($acuerdo_det_contingente_acumulado_pais);
			$acuerdo_det->setAcuerdo_det_desgravacion_igual_pais($acuerdo_det_desgravacion_igual_pais);
			$rs_acuerdo_det = $acuerdo_detAdo->insertar($acuerdo_det);
			if($rs_acuerdo_det["success"] !== true){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>"Error creando acuerdo_det", "error"=>$rs_acuerdo_det["error"])
				);
				echo json_encode($respuesta);
				exit();
			}
			$acuerdo_det_acuerdo_id = $rs_acuerdo_det["insert_id"];
			$respuesta = array(
				"success"=>true,
				"errors"=>array("reason"=>$acuerdo_det_acuerdo_id)
			);
			echo json_encode($respuesta);
			exit();
		break;
		case "lista":
			$arr = array();
			$acuerdo_det->setAcuerdo_det_id($acuerdo_det_id);
			$acuerdo_det->setAcuerdo_det_arancel_base($acuerdo_det_arancel_base);
			$acuerdo_det->setAcuerdo_det_productos($acuerdo_det_productos);
			$acuerdo_det->setAcuerdo_det_productos_desc($acuerdo_det_productos_desc);
			$acuerdo_det->setAcuerdo_det_administracion($acuerdo_det_administracion);
			$acuerdo_det->setAcuerdo_det_administrador($acuerdo_det_administrador);
			$acuerdo_det->setAcuerdo_det_nperiodos($acuerdo_det_nperiodos);
			$acuerdo_det->setAcuerdo_det_acuerdo_id($acuerdo_det_acuerdo_id);
			$acuerdo_det->setAcuerdo_det_contingente_acumulado_pais($acuerdo_det_contingente_acumulado_pais);
			$acuerdo_det->setAcuerdo_det_desgravacion_igual_pais($acuerdo_det_desgravacion_igual_pais);
			$rs_acuerdo_det = $acuerdo_detAdo->lista($acuerdo_det);
			if(!is_array($rs_acuerdo_det)){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>$rs_acuerdo_det)
				);
				echo json_encode($respuesta);
				exit();
			}
			foreach($rs_acuerdo_det["data"] as $key => $data){
				$arr[] = sanear_string($data);
			}
			$respuesta = array(
				"success"=>true,
				"total"=>$rs_acuerdo_det["total"],
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
			$rs_acuerdo_det = $acuerdo_detAdo->lista_filtro($query, $valuesqry, $limit);
			if(!is_array($rs_acuerdo_det)){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>$rs_acuerdo_det)
				);
				echo json_encode($respuesta);
				exit();
			}
			elseif($rs_acuerdo_det["total"] == 0){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>sanear_string(_NOSEENCONTRARONREGISTROS))
				);
				echo json_encode($respuesta);
				exit();
			}
			else{
				foreach($rs_acuerdo_det["data"] as $key => $data){
					$arr[] = sanear_string($data);
				}
				$respuesta = array(
					"success"=>true,
					"total"=>$rs_acuerdo_det["total"],
					"data"=>$arr
				);
				echo json_encode($respuesta);
				exit();
			}
		break;
	}
}
?>