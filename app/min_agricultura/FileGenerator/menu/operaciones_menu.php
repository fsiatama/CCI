<?php
session_start();
include('../../lib/config.php');
include_once(PATH_APP."lib/idioma.php");
include_once(PATH_APP."lib/lib_funciones.php");
include_once(PATH_APP."lib/lib_sesion.php");
include_once(PATH_RAIZ."min_agricultura/lib/menu/menuAdo.php");
$menuAdo = new MenuAdo("min_agricultura");
$menu    = new Menu;
if(isset($accion)){
	switch($accion){
		case "act":
			$menu->setMenu_id($menu_id);
			$menu->setMenu_name($menu_name);
			$menu->setMenu_category_menu_id($menu_category_menu_id);
			$menu->setMenu_url($menu_url);
			$menu->setMenu_order($menu_order);
			$menu->setMenu_hidden($menu_hidden);
			$rs_menu = $menuAdo->actualizar($menu);
			if($rs_menu !== true){
				$success = false;
			}
			else{
				$success = true;
			}
			$respuesta = array(
				"success"=>$success,
				"errors"=>array("reason"=>$rs_menu)
			);
			echo json_encode($respuesta);
			exit();
		break;
		case "del":
			$menu->setMenu_id($menu_id);
			$rs_menu = $menuAdo->borrar($menu);
			if($rs_menu !== true){
				$success = false;
			}
			else{
				$success = true;
			}
			$respuesta = array(
				"success"=>$success,
				"errors"=>array("reason"=>$rs_menu)
			);
			echo json_encode($respuesta);
			exit();
		break;
		case "crea":
			$menu->setMenu_id($menu_id);
			$menu->setMenu_name($menu_name);
			$menu->setMenu_category_menu_id($menu_category_menu_id);
			$menu->setMenu_url($menu_url);
			$menu->setMenu_order($menu_order);
			$menu->setMenu_hidden($menu_hidden);
			$rs_menu = $menuAdo->insertar($menu);
			if($rs_menu["success"] !== true){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>"Error creando menu", "error"=>$rs_menu["error"])
				);
				echo json_encode($respuesta);
				exit();
			}
			$menu_id = $rs_menu["insert_id"];
			$respuesta = array(
				"success"=>true,
				"errors"=>array("reason"=>$menu_id)
			);
			echo json_encode($respuesta);
			exit();
		break;
		case "lista":
			$arr = array();
			$menu->setMenu_id($menu_id);
			$menu->setMenu_name($menu_name);
			$menu->setMenu_category_menu_id($menu_category_menu_id);
			$menu->setMenu_url($menu_url);
			$menu->setMenu_order($menu_order);
			$menu->setMenu_hidden($menu_hidden);
			$rs_menu = $menuAdo->lista($menu);
			if(!is_array($rs_menu)){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>$rs_menu)
				);
				echo json_encode($respuesta);
				exit();
			}
			foreach($rs_menu["datos"] as $key => $data){
				$arr[] = sanear_string($data);
			}
			$respuesta = array(
				"success"=>true,
				"total"=>$rs_menu["total"],
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
			$rs_menu = $menuAdo->lista_filtro($query, $valuesqry, $limit);
			if(!is_array($rs_menu)){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>$rs_menu)
				);
				echo json_encode($respuesta);
				exit();
			}
			elseif($rs_menu["total"] == 0){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>sanear_string(_NOSEENCONTRARONREGISTROS))
				);
				echo json_encode($respuesta);
				exit();
			}
			else{
				foreach($rs_menu["datos"] as $key => $data){
					$arr[] = sanear_string($data);
				}
				$respuesta = array(
					"success"=>true,
					"total"=>$rs_menu["total"],
					"datos"=>$arr
				);
				echo json_encode($respuesta);
				exit();
			}
		break;
	}
}
?>
