<?php
session_start();
include('../../lib/config.php');
include_once(PATH_APP."lib/idioma.php");
include_once(PATH_APP."lib/lib_funciones.php");
include_once(PATH_APP."lib/lib_sesion.php");
include_once(PATH_RAIZ."min_agricultura/lib/category_menu/category_menuAdo.php");
$category_menuAdo = new Category_menuAdo("min_agricultura");
$category_menu    = new Category_menu;
if(isset($accion)){
	switch($accion){
		case "act":
			$category_menu->setCategory_menu_id($category_menu_id);
			$category_menu->setCategory_menu_name($category_menu_name);
			$category_menu->setCategory_menu_order($category_menu_order);
			$rs_category_menu = $category_menuAdo->actualizar($category_menu);
			if($rs_category_menu !== true){
				$success = false;
			}
			else{
				$success = true;
			}
			$respuesta = array(
				"success"=>$success,
				"errors"=>array("reason"=>$rs_category_menu)
			);
			echo json_encode($respuesta);
			exit();
		break;
		case "del":
			$category_menu->setCategory_menu_id($category_menu_id);
			$rs_category_menu = $category_menuAdo->borrar($category_menu);
			if($rs_category_menu !== true){
				$success = false;
			}
			else{
				$success = true;
			}
			$respuesta = array(
				"success"=>$success,
				"errors"=>array("reason"=>$rs_category_menu)
			);
			echo json_encode($respuesta);
			exit();
		break;
		case "crea":
			$category_menu->setCategory_menu_id($category_menu_id);
			$category_menu->setCategory_menu_name($category_menu_name);
			$category_menu->setCategory_menu_order($category_menu_order);
			$rs_category_menu = $category_menuAdo->insertar($category_menu);
			if($rs_category_menu["success"] !== true){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>"Error creando category_menu", "error"=>$rs_category_menu["error"])
				);
				echo json_encode($respuesta);
				exit();
			}
			$category_menu_id = $rs_category_menu["insert_id"];
			$respuesta = array(
				"success"=>true,
				"errors"=>array("reason"=>$category_menu_id)
			);
			echo json_encode($respuesta);
			exit();
		break;
		case "lista":
			$arr = array();
			$category_menu->setCategory_menu_id($category_menu_id);
			$category_menu->setCategory_menu_name($category_menu_name);
			$category_menu->setCategory_menu_order($category_menu_order);
			$rs_category_menu = $category_menuAdo->lista($category_menu);
			if(!is_array($rs_category_menu)){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>$rs_category_menu)
				);
				echo json_encode($respuesta);
				exit();
			}
			foreach($rs_category_menu["datos"] as $key => $data){
				$arr[] = sanear_string($data);
			}
			$respuesta = array(
				"success"=>true,
				"total"=>$rs_category_menu["total"],
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
			$rs_category_menu = $category_menuAdo->lista_filtro($query, $valuesqry, $limit);
			if(!is_array($rs_category_menu)){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>$rs_category_menu)
				);
				echo json_encode($respuesta);
				exit();
			}
			elseif($rs_category_menu["total"] == 0){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>sanear_string(_NOSEENCONTRARONREGISTROS))
				);
				echo json_encode($respuesta);
				exit();
			}
			else{
				foreach($rs_category_menu["datos"] as $key => $data){
					$arr[] = sanear_string($data);
				}
				$respuesta = array(
					"success"=>true,
					"total"=>$rs_category_menu["total"],
					"datos"=>$arr
				);
				echo json_encode($respuesta);
				exit();
			}
		break;
	}
}
?>
