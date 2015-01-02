<?php
session_start();
include('../../lib/config.php');
include_once(PATH_APP."lib/idioma.php");
include_once(PATH_APP."lib/lib_funciones.php");
include_once(PATH_APP."lib/lib_sesion.php");
include_once(PATH_RAIZ."min_agricultura/lib/comtrade_country/comtrade_countryAdo.php");
$comtrade_countryAdo = new Comtrade_countryAdo("min_agricultura");
$comtrade_country    = new Comtrade_country;
if(isset($accion)){
	switch($accion){
		case "act":
			$comtrade_country->setId_country($id_country);
			$comtrade_country->setCountry($country);
			$rs_comtrade_country = $comtrade_countryAdo->actualizar($comtrade_country);
			if($rs_comtrade_country !== true){
				$success = false;
			}
			else{
				$success = true;
			}
			$respuesta = array(
				"success"=>$success,
				"errors"=>array("reason"=>$rs_comtrade_country)
			);
			echo json_encode($respuesta);
			exit();
		break;
		case "del":
			$comtrade_country->setId_country($id_country);
			$rs_comtrade_country = $comtrade_countryAdo->borrar($comtrade_country);
			if($rs_comtrade_country !== true){
				$success = false;
			}
			else{
				$success = true;
			}
			$respuesta = array(
				"success"=>$success,
				"errors"=>array("reason"=>$rs_comtrade_country)
			);
			echo json_encode($respuesta);
			exit();
		break;
		case "crea":
			$comtrade_country->setId_country($id_country);
			$comtrade_country->setCountry($country);
			$rs_comtrade_country = $comtrade_countryAdo->insertar($comtrade_country);
			if($rs_comtrade_country["success"] !== true){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>"Error creando comtrade_country", "error"=>$rs_comtrade_country["error"])
				);
				echo json_encode($respuesta);
				exit();
			}
			$id_country = $rs_comtrade_country["insert_id"];
			$respuesta = array(
				"success"=>true,
				"errors"=>array("reason"=>$id_country)
			);
			echo json_encode($respuesta);
			exit();
		break;
		case "lista":
			$arr = array();
			$comtrade_country->setId_country($id_country);
			$comtrade_country->setCountry($country);
			$rs_comtrade_country = $comtrade_countryAdo->lista($comtrade_country);
			if(!is_array($rs_comtrade_country)){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>$rs_comtrade_country)
				);
				echo json_encode($respuesta);
				exit();
			}
			foreach($rs_comtrade_country["data"] as $key => $data){
				$arr[] = sanear_string($data);
			}
			$respuesta = array(
				"success"=>true,
				"total"=>$rs_comtrade_country["total"],
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
			$rs_comtrade_country = $comtrade_countryAdo->lista_filtro($query, $valuesqry, $limit);
			if(!is_array($rs_comtrade_country)){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>$rs_comtrade_country)
				);
				echo json_encode($respuesta);
				exit();
			}
			elseif($rs_comtrade_country["total"] == 0){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>sanear_string(_NOSEENCONTRARONREGISTROS))
				);
				echo json_encode($respuesta);
				exit();
			}
			else{
				foreach($rs_comtrade_country["data"] as $key => $data){
					$arr[] = sanear_string($data);
				}
				$respuesta = array(
					"success"=>true,
					"total"=>$rs_comtrade_country["total"],
					"data"=>$arr
				);
				echo json_encode($respuesta);
				exit();
			}
		break;
	}
}
?>
