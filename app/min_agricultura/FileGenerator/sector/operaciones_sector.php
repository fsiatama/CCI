<?php
session_start();
include('../../lib/config.php');
include_once(PATH_APP."lib/idioma.php");
include_once(PATH_APP."lib/lib_funciones.php");
include_once(PATH_APP."lib/lib_sesion.php");
include_once(PATH_RAIZ."min_agricultura/lib/sector/sectorAdo.php");
$sectorAdo = new SectorAdo("min_agricultura");
$sector    = new Sector;
if(isset($accion)){
	switch($accion){
		case "act":
			$sector->setSector_id($sector_id);
			$sector->setSector_nombre($sector_nombre);
			$sector->setSector_productos($sector_productos);
			$sector->setSector_uinsert($sector_uinsert);
			$sector->setSector_finsert($sector_finsert);
			$sector->setSector_uupdate($sector_uupdate);
			$sector->setSector_fupdate($sector_fupdate);
			$rs_sector = $sectorAdo->actualizar($sector);
			if($rs_sector !== true){
				$success = false;
			}
			else{
				$success = true;
			}
			$respuesta = array(
				"success"=>$success,
				"errors"=>array("reason"=>$rs_sector)
			);
			echo json_encode($respuesta);
			exit();
		break;
		case "del":
			$sector->setSector_id($sector_id);
			$rs_sector = $sectorAdo->borrar($sector);
			if($rs_sector !== true){
				$success = false;
			}
			else{
				$success = true;
			}
			$respuesta = array(
				"success"=>$success,
				"errors"=>array("reason"=>$rs_sector)
			);
			echo json_encode($respuesta);
			exit();
		break;
		case "crea":
			$sector->setSector_id($sector_id);
			$sector->setSector_nombre($sector_nombre);
			$sector->setSector_productos($sector_productos);
			$sector->setSector_uinsert($sector_uinsert);
			$sector->setSector_finsert($sector_finsert);
			$sector->setSector_uupdate($sector_uupdate);
			$sector->setSector_fupdate($sector_fupdate);
			$rs_sector = $sectorAdo->insertar($sector);
			if($rs_sector["success"] !== true){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>"Error creando sector", "error"=>$rs_sector["error"])
				);
				echo json_encode($respuesta);
				exit();
			}
			$sector_id = $rs_sector["insert_id"];
			$respuesta = array(
				"success"=>true,
				"errors"=>array("reason"=>$sector_id)
			);
			echo json_encode($respuesta);
			exit();
		break;
		case "lista":
			$arr = array();
			$sector->setSector_id($sector_id);
			$sector->setSector_nombre($sector_nombre);
			$sector->setSector_productos($sector_productos);
			$sector->setSector_uinsert($sector_uinsert);
			$sector->setSector_finsert($sector_finsert);
			$sector->setSector_uupdate($sector_uupdate);
			$sector->setSector_fupdate($sector_fupdate);
			$rs_sector = $sectorAdo->lista($sector);
			if(!is_array($rs_sector)){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>$rs_sector)
				);
				echo json_encode($respuesta);
				exit();
			}
			foreach($rs_sector["data"] as $key => $data){
				$arr[] = sanear_string($data);
			}
			$respuesta = array(
				"success"=>true,
				"total"=>$rs_sector["total"],
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
			$rs_sector = $sectorAdo->lista_filtro($query, $valuesqry, $limit);
			if(!is_array($rs_sector)){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>$rs_sector)
				);
				echo json_encode($respuesta);
				exit();
			}
			elseif($rs_sector["total"] == 0){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>sanear_string(_NOSEENCONTRARONREGISTROS))
				);
				echo json_encode($respuesta);
				exit();
			}
			else{
				foreach($rs_sector["data"] as $key => $data){
					$arr[] = sanear_string($data);
				}
				$respuesta = array(
					"success"=>true,
					"total"=>$rs_sector["total"],
					"data"=>$arr
				);
				echo json_encode($respuesta);
				exit();
			}
		break;
	}
}
?>
