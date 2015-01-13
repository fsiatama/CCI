<?php
session_start();
include('../../lib/config.php');
include_once(PATH_APP."lib/idioma.php");
include_once(PATH_APP."lib/lib_funciones.php");
include_once(PATH_APP."lib/lib_sesion.php");
include_once(PATH_RAIZ."min_agricultura/lib/contingente/contingenteAdo.php");
$contingenteAdo = new ContingenteAdo("min_agricultura");
$contingente    = new Contingente;
if(isset($accion)){
	switch($accion){
		case "act":
			$contingente->setContingente_id($contingente_id);
			$contingente->setContingente_id_pais($contingente_id_pais);
			$contingente->setContingente_mcontingente($contingente_mcontingente);
			$contingente->setContingente_desc($contingente_desc);
			$contingente->setContingente_msalvaguardia($contingente_msalvaguardia);
			$contingente->setContingente_salvaguardia_sobretasa($contingente_salvaguardia_sobretasa);
			$contingente->setContingente_acuerdo_det_id($contingente_acuerdo_det_id);
			$contingente->setContingente_acuerdo_det_acuerdo_id($contingente_acuerdo_det_acuerdo_id);
			$rs_contingente = $contingenteAdo->actualizar($contingente);
			if($rs_contingente !== true){
				$success = false;
			}
			else{
				$success = true;
			}
			$respuesta = array(
				"success"=>$success,
				"errors"=>array("reason"=>$rs_contingente)
			);
			echo json_encode($respuesta);
			exit();
		break;
		case "del":
			$contingente->setContingente_acuerdo_det_acuerdo_id($contingente_acuerdo_det_acuerdo_id);
			$rs_contingente = $contingenteAdo->borrar($contingente);
			if($rs_contingente !== true){
				$success = false;
			}
			else{
				$success = true;
			}
			$respuesta = array(
				"success"=>$success,
				"errors"=>array("reason"=>$rs_contingente)
			);
			echo json_encode($respuesta);
			exit();
		break;
		case "crea":
			$contingente->setContingente_id($contingente_id);
			$contingente->setContingente_id_pais($contingente_id_pais);
			$contingente->setContingente_mcontingente($contingente_mcontingente);
			$contingente->setContingente_desc($contingente_desc);
			$contingente->setContingente_msalvaguardia($contingente_msalvaguardia);
			$contingente->setContingente_salvaguardia_sobretasa($contingente_salvaguardia_sobretasa);
			$contingente->setContingente_acuerdo_det_id($contingente_acuerdo_det_id);
			$contingente->setContingente_acuerdo_det_acuerdo_id($contingente_acuerdo_det_acuerdo_id);
			$rs_contingente = $contingenteAdo->insertar($contingente);
			if($rs_contingente["success"] !== true){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>"Error creando contingente", "error"=>$rs_contingente["error"])
				);
				echo json_encode($respuesta);
				exit();
			}
			$contingente_acuerdo_det_acuerdo_id = $rs_contingente["insert_id"];
			$respuesta = array(
				"success"=>true,
				"errors"=>array("reason"=>$contingente_acuerdo_det_acuerdo_id)
			);
			echo json_encode($respuesta);
			exit();
		break;
		case "lista":
			$arr = array();
			$contingente->setContingente_id($contingente_id);
			$contingente->setContingente_id_pais($contingente_id_pais);
			$contingente->setContingente_mcontingente($contingente_mcontingente);
			$contingente->setContingente_desc($contingente_desc);
			$contingente->setContingente_msalvaguardia($contingente_msalvaguardia);
			$contingente->setContingente_salvaguardia_sobretasa($contingente_salvaguardia_sobretasa);
			$contingente->setContingente_acuerdo_det_id($contingente_acuerdo_det_id);
			$contingente->setContingente_acuerdo_det_acuerdo_id($contingente_acuerdo_det_acuerdo_id);
			$rs_contingente = $contingenteAdo->lista($contingente);
			if(!is_array($rs_contingente)){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>$rs_contingente)
				);
				echo json_encode($respuesta);
				exit();
			}
			foreach($rs_contingente["data"] as $key => $data){
				$arr[] = sanear_string($data);
			}
			$respuesta = array(
				"success"=>true,
				"total"=>$rs_contingente["total"],
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
			$rs_contingente = $contingenteAdo->lista_filtro($query, $valuesqry, $limit);
			if(!is_array($rs_contingente)){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>$rs_contingente)
				);
				echo json_encode($respuesta);
				exit();
			}
			elseif($rs_contingente["total"] == 0){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>sanear_string(_NOSEENCONTRARONREGISTROS))
				);
				echo json_encode($respuesta);
				exit();
			}
			else{
				foreach($rs_contingente["data"] as $key => $data){
					$arr[] = sanear_string($data);
				}
				$respuesta = array(
					"success"=>true,
					"total"=>$rs_contingente["total"],
					"data"=>$arr
				);
				echo json_encode($respuesta);
				exit();
			}
		break;
	}
}
?>
