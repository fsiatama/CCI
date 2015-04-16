<?php
session_start();
include('../../lib/config.php');
include_once(PATH_APP."lib/idioma.php");
include_once(PATH_APP."lib/lib_funciones.php");
include_once(PATH_APP."lib/lib_sesion.php");
include_once(PATH_RAIZ."min_agricultura/lib/declaraimp/declaraimpAdo.php");
$declaraimpAdo = new DeclaraimpAdo("min_agricultura");
$declaraimp    = new Declaraimp;
if(isset($accion)){
	switch($accion){
		case "act":
			$declaraimp->setId($id);
			$declaraimp->setAnio($anio);
			$declaraimp->setPeriodo($periodo);
			$declaraimp->setFecha($fecha);
			$declaraimp->setId_empresa($id_empresa);
			$declaraimp->setId_paisorigen($id_paisorigen);
			$declaraimp->setId_paiscompra($id_paiscompra);
			$declaraimp->setId_paisprocedencia($id_paisprocedencia);
			$declaraimp->setId_deptorigen($id_deptorigen);
			$declaraimp->setId_capitulo($id_capitulo);
			$declaraimp->setId_partida($id_partida);
			$declaraimp->setId_subpartida($id_subpartida);
			$declaraimp->setId_posicion($id_posicion);
			$declaraimp->setId_ciiu($id_ciiu);
			$declaraimp->setValorcif($valorcif);
			$declaraimp->setValorfob($valorfob);
			$declaraimp->setPeso_neto($peso_neto);
			$declaraimp->setArancel_pagado($arancel_pagado);
			$declaraimp->setValorarancel($valorarancel);
			$rs_declaraimp = $declaraimpAdo->actualizar($declaraimp);
			if($rs_declaraimp !== true){
				$success = false;
			}
			else{
				$success = true;
			}
			$respuesta = array(
				"success"=>$success,
				"errors"=>array("reason"=>$rs_declaraimp)
			);
			echo json_encode($respuesta);
			exit();
		break;
		case "del":
			$declaraimp->setId($id);
			$rs_declaraimp = $declaraimpAdo->borrar($declaraimp);
			if($rs_declaraimp !== true){
				$success = false;
			}
			else{
				$success = true;
			}
			$respuesta = array(
				"success"=>$success,
				"errors"=>array("reason"=>$rs_declaraimp)
			);
			echo json_encode($respuesta);
			exit();
		break;
		case "crea":
			$declaraimp->setId($id);
			$declaraimp->setAnio($anio);
			$declaraimp->setPeriodo($periodo);
			$declaraimp->setFecha($fecha);
			$declaraimp->setId_empresa($id_empresa);
			$declaraimp->setId_paisorigen($id_paisorigen);
			$declaraimp->setId_paiscompra($id_paiscompra);
			$declaraimp->setId_paisprocedencia($id_paisprocedencia);
			$declaraimp->setId_deptorigen($id_deptorigen);
			$declaraimp->setId_capitulo($id_capitulo);
			$declaraimp->setId_partida($id_partida);
			$declaraimp->setId_subpartida($id_subpartida);
			$declaraimp->setId_posicion($id_posicion);
			$declaraimp->setId_ciiu($id_ciiu);
			$declaraimp->setValorcif($valorcif);
			$declaraimp->setValorfob($valorfob);
			$declaraimp->setPeso_neto($peso_neto);
			$declaraimp->setArancel_pagado($arancel_pagado);
			$declaraimp->setValorarancel($valorarancel);
			$rs_declaraimp = $declaraimpAdo->insertar($declaraimp);
			if($rs_declaraimp["success"] !== true){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>"Error creando declaraimp", "error"=>$rs_declaraimp["error"])
				);
				echo json_encode($respuesta);
				exit();
			}
			$id = $rs_declaraimp["insert_id"];
			$respuesta = array(
				"success"=>true,
				"errors"=>array("reason"=>$id)
			);
			echo json_encode($respuesta);
			exit();
		break;
		case "lista":
			$arr = array();
			$declaraimp->setId($id);
			$declaraimp->setAnio($anio);
			$declaraimp->setPeriodo($periodo);
			$declaraimp->setFecha($fecha);
			$declaraimp->setId_empresa($id_empresa);
			$declaraimp->setId_paisorigen($id_paisorigen);
			$declaraimp->setId_paiscompra($id_paiscompra);
			$declaraimp->setId_paisprocedencia($id_paisprocedencia);
			$declaraimp->setId_deptorigen($id_deptorigen);
			$declaraimp->setId_capitulo($id_capitulo);
			$declaraimp->setId_partida($id_partida);
			$declaraimp->setId_subpartida($id_subpartida);
			$declaraimp->setId_posicion($id_posicion);
			$declaraimp->setId_ciiu($id_ciiu);
			$declaraimp->setValorcif($valorcif);
			$declaraimp->setValorfob($valorfob);
			$declaraimp->setPeso_neto($peso_neto);
			$declaraimp->setArancel_pagado($arancel_pagado);
			$declaraimp->setValorarancel($valorarancel);
			$rs_declaraimp = $declaraimpAdo->lista($declaraimp);
			if(!is_array($rs_declaraimp)){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>$rs_declaraimp)
				);
				echo json_encode($respuesta);
				exit();
			}
			foreach($rs_declaraimp["data"] as $key => $data){
				$arr[] = sanear_string($data);
			}
			$respuesta = array(
				"success"=>true,
				"total"=>$rs_declaraimp["total"],
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
			$rs_declaraimp = $declaraimpAdo->lista_filtro($query, $valuesqry, $limit);
			if(!is_array($rs_declaraimp)){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>$rs_declaraimp)
				);
				echo json_encode($respuesta);
				exit();
			}
			elseif($rs_declaraimp["total"] == 0){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>sanear_string(_NOSEENCONTRARONREGISTROS))
				);
				echo json_encode($respuesta);
				exit();
			}
			else{
				foreach($rs_declaraimp["data"] as $key => $data){
					$arr[] = sanear_string($data);
				}
				$respuesta = array(
					"success"=>true,
					"total"=>$rs_declaraimp["total"],
					"data"=>$arr
				);
				echo json_encode($respuesta);
				exit();
			}
		break;
	}
}
?>
