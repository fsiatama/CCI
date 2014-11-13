<?php
session_start();
include('../../lib/config.php');
include_once(PATH_APP."lib/idioma.php");
include_once(PATH_APP."lib/lib_funciones.php");
include_once(PATH_APP."lib/lib_sesion.php");
include_once(PATH_RAIZ."min_agricultura/lib/permissions/permissionsAdo.php");
$permissionsAdo = new PermissionsAdo("min_agricultura");
$permissions    = new Permissions;
if(isset($accion)){
	switch($accion){
		case "act":
			$permissions->setPermissions_id($permissions_id);
			$permissions->setPermissions_profile_id($permissions_profile_id);
			$permissions->setPermissions_menu_id($permissions_menu_id);
			$permissions->setPermissions_list($permissions_list);
			$permissions->setPermissions_modify($permissions_modify);
			$permissions->setPermissions_create($permissions_create);
			$permissions->setPermissions_delete($permissions_delete);
			$permissions->setPermissions_export($permissions_export);
			$rs_permissions = $permissionsAdo->actualizar($permissions);
			if($rs_permissions !== true){
				$success = false;
			}
			else{
				$success = true;
			}
			$respuesta = array(
				"success"=>$success,
				"errors"=>array("reason"=>$rs_permissions)
			);
			echo json_encode($respuesta);
			exit();
		break;
		case "del":
			$permissions->setPermissions_id($permissions_id);
			$rs_permissions = $permissionsAdo->borrar($permissions);
			if($rs_permissions !== true){
				$success = false;
			}
			else{
				$success = true;
			}
			$respuesta = array(
				"success"=>$success,
				"errors"=>array("reason"=>$rs_permissions)
			);
			echo json_encode($respuesta);
			exit();
		break;
		case "crea":
			$permissions->setPermissions_id($permissions_id);
			$permissions->setPermissions_profile_id($permissions_profile_id);
			$permissions->setPermissions_menu_id($permissions_menu_id);
			$permissions->setPermissions_list($permissions_list);
			$permissions->setPermissions_modify($permissions_modify);
			$permissions->setPermissions_create($permissions_create);
			$permissions->setPermissions_delete($permissions_delete);
			$permissions->setPermissions_export($permissions_export);
			$rs_permissions = $permissionsAdo->insertar($permissions);
			if($rs_permissions["success"] !== true){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>"Error creando permissions", "error"=>$rs_permissions["error"])
				);
				echo json_encode($respuesta);
				exit();
			}
			$permissions_id = $rs_permissions["insert_id"];
			$respuesta = array(
				"success"=>true,
				"errors"=>array("reason"=>$permissions_id)
			);
			echo json_encode($respuesta);
			exit();
		break;
		case "lista":
			$arr = array();
			$permissions->setPermissions_id($permissions_id);
			$permissions->setPermissions_profile_id($permissions_profile_id);
			$permissions->setPermissions_menu_id($permissions_menu_id);
			$permissions->setPermissions_list($permissions_list);
			$permissions->setPermissions_modify($permissions_modify);
			$permissions->setPermissions_create($permissions_create);
			$permissions->setPermissions_delete($permissions_delete);
			$permissions->setPermissions_export($permissions_export);
			$rs_permissions = $permissionsAdo->lista($permissions);
			if(!is_array($rs_permissions)){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>$rs_permissions)
				);
				echo json_encode($respuesta);
				exit();
			}
			foreach($rs_permissions["datos"] as $key => $data){
				$arr[] = sanear_string($data);
			}
			$respuesta = array(
				"success"=>true,
				"total"=>$rs_permissions["total"],
				"datos"=>$arr
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
			$rs_permissions = $permissionsAdo->lista_filtro($query, $valuesqry, $limit);
			if(!is_array($rs_permissions)){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>$rs_permissions)
				);
				echo json_encode($respuesta);
				exit();
			}
			elseif($rs_permissions["total"] == 0){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>sanear_string(_NOSEENCONTRARONREGISTROS))
				);
				echo json_encode($respuesta);
				exit();
			}
			else{
				foreach($rs_permissions["datos"] as $key => $data){
					$arr[] = sanear_string($data);
				}
				$respuesta = array(
					"success"=>true,
					"total"=>$rs_permissions["total"],
					"datos"=>$arr
				);
				echo json_encode($respuesta);
				exit();
			}
		break;
	}
}
?>
