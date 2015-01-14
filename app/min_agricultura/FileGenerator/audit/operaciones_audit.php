<?php
session_start();
include('../../lib/config.php');
include_once(PATH_APP."lib/idioma.php");
include_once(PATH_APP."lib/lib_funciones.php");
include_once(PATH_APP."lib/lib_sesion.php");
include_once(PATH_RAIZ."min_agricultura/lib/audit/auditAdo.php");
$auditAdo = new AuditAdo("min_agricultura");
$audit    = new Audit;
if(isset($accion)){
	switch($accion){
		case "act":
			$audit->setAudit_id($audit_id);
			$audit->setAudit_table($audit_table);
			$audit->setAudit_script($audit_script);
			$audit->setAudit_method($audit_method);
			$audit->setAudit_parameters($audit_parameters);
			$audit->setAudit_uinsert($audit_uinsert);
			$audit->setAudit_finsert($audit_finsert);
			$rs_audit = $auditAdo->actualizar($audit);
			if($rs_audit !== true){
				$success = false;
			}
			else{
				$success = true;
			}
			$respuesta = array(
				"success"=>$success,
				"errors"=>array("reason"=>$rs_audit)
			);
			echo json_encode($respuesta);
			exit();
		break;
		case "del":
			$audit->setAudit_id($audit_id);
			$rs_audit = $auditAdo->borrar($audit);
			if($rs_audit !== true){
				$success = false;
			}
			else{
				$success = true;
			}
			$respuesta = array(
				"success"=>$success,
				"errors"=>array("reason"=>$rs_audit)
			);
			echo json_encode($respuesta);
			exit();
		break;
		case "crea":
			$audit->setAudit_id($audit_id);
			$audit->setAudit_table($audit_table);
			$audit->setAudit_script($audit_script);
			$audit->setAudit_method($audit_method);
			$audit->setAudit_parameters($audit_parameters);
			$audit->setAudit_uinsert($audit_uinsert);
			$audit->setAudit_finsert($audit_finsert);
			$rs_audit = $auditAdo->insertar($audit);
			if($rs_audit["success"] !== true){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>"Error creando audit", "error"=>$rs_audit["error"])
				);
				echo json_encode($respuesta);
				exit();
			}
			$audit_id = $rs_audit["insert_id"];
			$respuesta = array(
				"success"=>true,
				"errors"=>array("reason"=>$audit_id)
			);
			echo json_encode($respuesta);
			exit();
		break;
		case "lista":
			$arr = array();
			$audit->setAudit_id($audit_id);
			$audit->setAudit_table($audit_table);
			$audit->setAudit_script($audit_script);
			$audit->setAudit_method($audit_method);
			$audit->setAudit_parameters($audit_parameters);
			$audit->setAudit_uinsert($audit_uinsert);
			$audit->setAudit_finsert($audit_finsert);
			$rs_audit = $auditAdo->lista($audit);
			if(!is_array($rs_audit)){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>$rs_audit)
				);
				echo json_encode($respuesta);
				exit();
			}
			foreach($rs_audit["data"] as $key => $data){
				$arr[] = sanear_string($data);
			}
			$respuesta = array(
				"success"=>true,
				"total"=>$rs_audit["total"],
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
			$rs_audit = $auditAdo->lista_filtro($query, $valuesqry, $limit);
			if(!is_array($rs_audit)){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>$rs_audit)
				);
				echo json_encode($respuesta);
				exit();
			}
			elseif($rs_audit["total"] == 0){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>sanear_string(_NOSEENCONTRARONREGISTROS))
				);
				echo json_encode($respuesta);
				exit();
			}
			else{
				foreach($rs_audit["data"] as $key => $data){
					$arr[] = sanear_string($data);
				}
				$respuesta = array(
					"success"=>true,
					"total"=>$rs_audit["total"],
					"data"=>$arr
				);
				echo json_encode($respuesta);
				exit();
			}
		break;
	}
}
?>
