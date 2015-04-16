<?php
session_start();
include('../../lib/config.php');
include_once(PATH_APP."lib/idioma.php");
include_once(PATH_APP."lib/lib_funciones.php");
include_once(PATH_APP."lib/lib_sesion.php");
include_once(PATH_RAIZ."min_agricultura/lib/declaraexp/declaraexpAdo.php");
$declaraexpAdo = new DeclaraexpAdo("min_agricultura");
$declaraexp    = new Declaraexp;
if(isset($accion)){
	switch($accion){
		case "act":
			$declaraexp->setId($id);
			$declaraexp->setAnio($anio);
			$declaraexp->setPeriodo($periodo);
			$declaraexp->setFecha($fecha);
			$declaraexp->setId_empresa($id_empresa);
			$declaraexp->setId_paisdestino($id_paisdestino);
			$declaraexp->setId_deptorigen($id_deptorigen);
			$declaraexp->setId_capitulo($id_capitulo);
			$declaraexp->setId_partida($id_partida);
			$declaraexp->setId_subpartida($id_subpartida);
			$declaraexp->setId_posicion($id_posicion);
			$declaraexp->setId_ciiu($id_ciiu);
			$declaraexp->setValorfob($valorfob);
			$declaraexp->setValorcif($valorcif);
			$declaraexp->setValor_pesos($valor_pesos);
			$declaraexp->setPeso_neto($peso_neto);
			$rs_declaraexp = $declaraexpAdo->actualizar($declaraexp);
			if($rs_declaraexp !== true){
				$success = false;
			}
			else{
				$success = true;
			}
			$respuesta = array(
				"success"=>$success,
				"errors"=>array("reason"=>$rs_declaraexp)
			);
			echo json_encode($respuesta);
			exit();
		break;
		case "del":
			$declaraexp->setId($id);
			$rs_declaraexp = $declaraexpAdo->borrar($declaraexp);
			if($rs_declaraexp !== true){
				$success = false;
			}
			else{
				$success = true;
			}
			$respuesta = array(
				"success"=>$success,
				"errors"=>array("reason"=>$rs_declaraexp)
			);
			echo json_encode($respuesta);
			exit();
		break;
		case "crea":
			$declaraexp->setId($id);
			$declaraexp->setAnio($anio);
			$declaraexp->setPeriodo($periodo);
			$declaraexp->setFecha($fecha);
			$declaraexp->setId_empresa($id_empresa);
			$declaraexp->setId_paisdestino($id_paisdestino);
			$declaraexp->setId_deptorigen($id_deptorigen);
			$declaraexp->setId_capitulo($id_capitulo);
			$declaraexp->setId_partida($id_partida);
			$declaraexp->setId_subpartida($id_subpartida);
			$declaraexp->setId_posicion($id_posicion);
			$declaraexp->setId_ciiu($id_ciiu);
			$declaraexp->setValorfob($valorfob);
			$declaraexp->setValorcif($valorcif);
			$declaraexp->setValor_pesos($valor_pesos);
			$declaraexp->setPeso_neto($peso_neto);
			$rs_declaraexp = $declaraexpAdo->insertar($declaraexp);
			if($rs_declaraexp["success"] !== true){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>"Error creando declaraexp", "error"=>$rs_declaraexp["error"])
				);
				echo json_encode($respuesta);
				exit();
			}
			$id = $rs_declaraexp["insert_id"];
			$respuesta = array(
				"success"=>true,
				"errors"=>array("reason"=>$id)
			);
			echo json_encode($respuesta);
			exit();
		break;
		case "lista":
			$arr = array();
			$declaraexp->setId($id);
			$declaraexp->setAnio($anio);
			$declaraexp->setPeriodo($periodo);
			$declaraexp->setFecha($fecha);
			$declaraexp->setId_empresa($id_empresa);
			$declaraexp->setId_paisdestino($id_paisdestino);
			$declaraexp->setId_deptorigen($id_deptorigen);
			$declaraexp->setId_capitulo($id_capitulo);
			$declaraexp->setId_partida($id_partida);
			$declaraexp->setId_subpartida($id_subpartida);
			$declaraexp->setId_posicion($id_posicion);
			$declaraexp->setId_ciiu($id_ciiu);
			$declaraexp->setValorfob($valorfob);
			$declaraexp->setValorcif($valorcif);
			$declaraexp->setValor_pesos($valor_pesos);
			$declaraexp->setPeso_neto($peso_neto);
			$rs_declaraexp = $declaraexpAdo->lista($declaraexp);
			if(!is_array($rs_declaraexp)){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>$rs_declaraexp)
				);
				echo json_encode($respuesta);
				exit();
			}
			foreach($rs_declaraexp["data"] as $key => $data){
				$arr[] = sanear_string($data);
			}
			$respuesta = array(
				"success"=>true,
				"total"=>$rs_declaraexp["total"],
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
			$rs_declaraexp = $declaraexpAdo->lista_filtro($query, $valuesqry, $limit);
			if(!is_array($rs_declaraexp)){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>$rs_declaraexp)
				);
				echo json_encode($respuesta);
				exit();
			}
			elseif($rs_declaraexp["total"] == 0){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>sanear_string(_NOSEENCONTRARONREGISTROS))
				);
				echo json_encode($respuesta);
				exit();
			}
			else{
				foreach($rs_declaraexp["data"] as $key => $data){
					$arr[] = sanear_string($data);
				}
				$respuesta = array(
					"success"=>true,
					"total"=>$rs_declaraexp["total"],
					"data"=>$arr
				);
				echo json_encode($respuesta);
				exit();
			}
		break;
	}
}
?>
