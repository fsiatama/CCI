<?php
session_start();
include('../../lib/config.php');
include_once(PATH_APP."lib/idioma.php");
include_once(PATH_APP."lib/lib_funciones.php");
include_once(PATH_APP."lib/lib_sesion.php");
include_once(PATH_RAIZ."min_agricultura/lib/sobordoexp/sobordoexpAdo.php");
$sobordoexpAdo = new SobordoexpAdo("min_agricultura");
$sobordoexp    = new Sobordoexp;
if(isset($accion)){
	switch($accion){
		case "act":
			$sobordoexp->setId($id);
			$sobordoexp->setAnio($anio);
			$sobordoexp->setPeriodo($periodo);
			$sobordoexp->setFecha($fecha);
			$sobordoexp->setId_paisdestino($id_paisdestino);
			$sobordoexp->setId_capitulo($id_capitulo);
			$sobordoexp->setId_partida($id_partida);
			$sobordoexp->setId_subpartida($id_subpartida);
			$sobordoexp->setPeso_neto($peso_neto);
			$rs_sobordoexp = $sobordoexpAdo->actualizar($sobordoexp);
			if($rs_sobordoexp !== true){
				$success = false;
			}
			else{
				$success = true;
			}
			$respuesta = array(
				"success"=>$success,
				"errors"=>array("reason"=>$rs_sobordoexp)
			);
			echo json_encode($respuesta);
			exit();
		break;
		case "del":
			$sobordoexp->setId($id);
			$rs_sobordoexp = $sobordoexpAdo->borrar($sobordoexp);
			if($rs_sobordoexp !== true){
				$success = false;
			}
			else{
				$success = true;
			}
			$respuesta = array(
				"success"=>$success,
				"errors"=>array("reason"=>$rs_sobordoexp)
			);
			echo json_encode($respuesta);
			exit();
		break;
		case "crea":
			$sobordoexp->setId($id);
			$sobordoexp->setAnio($anio);
			$sobordoexp->setPeriodo($periodo);
			$sobordoexp->setFecha($fecha);
			$sobordoexp->setId_paisdestino($id_paisdestino);
			$sobordoexp->setId_capitulo($id_capitulo);
			$sobordoexp->setId_partida($id_partida);
			$sobordoexp->setId_subpartida($id_subpartida);
			$sobordoexp->setPeso_neto($peso_neto);
			$rs_sobordoexp = $sobordoexpAdo->insertar($sobordoexp);
			if($rs_sobordoexp["success"] !== true){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>"Error creando sobordoexp", "error"=>$rs_sobordoexp["error"])
				);
				echo json_encode($respuesta);
				exit();
			}
			$id = $rs_sobordoexp["insert_id"];
			$respuesta = array(
				"success"=>true,
				"errors"=>array("reason"=>$id)
			);
			echo json_encode($respuesta);
			exit();
		break;
		case "lista":
			$arr = array();
			$sobordoexp->setId($id);
			$sobordoexp->setAnio($anio);
			$sobordoexp->setPeriodo($periodo);
			$sobordoexp->setFecha($fecha);
			$sobordoexp->setId_paisdestino($id_paisdestino);
			$sobordoexp->setId_capitulo($id_capitulo);
			$sobordoexp->setId_partida($id_partida);
			$sobordoexp->setId_subpartida($id_subpartida);
			$sobordoexp->setPeso_neto($peso_neto);
			$rs_sobordoexp = $sobordoexpAdo->lista($sobordoexp);
			if(!is_array($rs_sobordoexp)){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>$rs_sobordoexp)
				);
				echo json_encode($respuesta);
				exit();
			}
			foreach($rs_sobordoexp["data"] as $key => $data){
				$arr[] = sanear_string($data);
			}
			$respuesta = array(
				"success"=>true,
				"total"=>$rs_sobordoexp["total"],
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
			$rs_sobordoexp = $sobordoexpAdo->lista_filtro($query, $valuesqry, $limit);
			if(!is_array($rs_sobordoexp)){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>$rs_sobordoexp)
				);
				echo json_encode($respuesta);
				exit();
			}
			elseif($rs_sobordoexp["total"] == 0){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>sanear_string(_NOSEENCONTRARONREGISTROS))
				);
				echo json_encode($respuesta);
				exit();
			}
			else{
				foreach($rs_sobordoexp["data"] as $key => $data){
					$arr[] = sanear_string($data);
				}
				$respuesta = array(
					"success"=>true,
					"total"=>$rs_sobordoexp["total"],
					"data"=>$arr
				);
				echo json_encode($respuesta);
				exit();
			}
		break;
	}
}
?>
