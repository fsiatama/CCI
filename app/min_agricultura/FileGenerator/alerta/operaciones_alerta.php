<?php
session_start();
include('../../lib/config.php');
include_once(PATH_APP."lib/idioma.php");
include_once(PATH_APP."lib/lib_funciones.php");
include_once(PATH_APP."lib/lib_sesion.php");
include_once(PATH_RAIZ."min_agricultura/lib/alerta/alertaAdo.php");
$alertaAdo = new AlertaAdo("min_agricultura");
$alerta    = new Alerta;
if(isset($accion)){
	switch($accion){
		case "act":
			$alerta->setAlerta_id($alerta_id);
			$alerta->setAlerta_contingente_verde($alerta_contingente_verde);
			$alerta->setAlerta_contingente_amarilla($alerta_contingente_amarilla);
			$alerta->setAlerta_contingente_roja($alerta_contingente_roja);
			$alerta->setAlerta_salvaguardia_verde($alerta_salvaguardia_verde);
			$alerta->setAlerta_salvaguardia_amarilla($alerta_salvaguardia_amarilla);
			$alerta->setAlerta_salvaguardia_roja($alerta_salvaguardia_roja);
			$alerta->setAlerta_emails($alerta_emails);
			$alerta->setAlerta_contingente_id($alerta_contingente_id);
			$alerta->setAlerta_contingente_acuerdo_det_id($alerta_contingente_acuerdo_det_id);
			$alerta->setAlerta_contingente_acuerdo_det_acuerdo_id($alerta_contingente_acuerdo_det_acuerdo_id);
			$alerta->setAlerta_disp1($alerta_disp1);
			$alerta->setAlerta_disp2($alerta_disp2);
			$alerta->setAlerta_disp3($alerta_disp3);
			$alerta->setAlerta_disp4($alerta_disp4);
			$alerta->setAlerta_disp5($alerta_disp5);
			$alerta->setAlerta_disp6($alerta_disp6);
			$rs_alerta = $alertaAdo->actualizar($alerta);
			if($rs_alerta !== true){
				$success = false;
			}
			else{
				$success = true;
			}
			$respuesta = array(
				"success"=>$success,
				"errors"=>array("reason"=>$rs_alerta)
			);
			echo json_encode($respuesta);
			exit();
		break;
		case "del":
			$alerta->setAlerta_contingente_acuerdo_det_acuerdo_id($alerta_contingente_acuerdo_det_acuerdo_id);
			$rs_alerta = $alertaAdo->borrar($alerta);
			if($rs_alerta !== true){
				$success = false;
			}
			else{
				$success = true;
			}
			$respuesta = array(
				"success"=>$success,
				"errors"=>array("reason"=>$rs_alerta)
			);
			echo json_encode($respuesta);
			exit();
		break;
		case "crea":
			$alerta->setAlerta_id($alerta_id);
			$alerta->setAlerta_contingente_verde($alerta_contingente_verde);
			$alerta->setAlerta_contingente_amarilla($alerta_contingente_amarilla);
			$alerta->setAlerta_contingente_roja($alerta_contingente_roja);
			$alerta->setAlerta_salvaguardia_verde($alerta_salvaguardia_verde);
			$alerta->setAlerta_salvaguardia_amarilla($alerta_salvaguardia_amarilla);
			$alerta->setAlerta_salvaguardia_roja($alerta_salvaguardia_roja);
			$alerta->setAlerta_emails($alerta_emails);
			$alerta->setAlerta_contingente_id($alerta_contingente_id);
			$alerta->setAlerta_contingente_acuerdo_det_id($alerta_contingente_acuerdo_det_id);
			$alerta->setAlerta_contingente_acuerdo_det_acuerdo_id($alerta_contingente_acuerdo_det_acuerdo_id);
			$alerta->setAlerta_disp1($alerta_disp1);
			$alerta->setAlerta_disp2($alerta_disp2);
			$alerta->setAlerta_disp3($alerta_disp3);
			$alerta->setAlerta_disp4($alerta_disp4);
			$alerta->setAlerta_disp5($alerta_disp5);
			$alerta->setAlerta_disp6($alerta_disp6);
			$rs_alerta = $alertaAdo->insertar($alerta);
			if($rs_alerta["success"] !== true){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>"Error creando alerta", "error"=>$rs_alerta["error"])
				);
				echo json_encode($respuesta);
				exit();
			}
			$alerta_contingente_acuerdo_det_acuerdo_id = $rs_alerta["insert_id"];
			$respuesta = array(
				"success"=>true,
				"errors"=>array("reason"=>$alerta_contingente_acuerdo_det_acuerdo_id)
			);
			echo json_encode($respuesta);
			exit();
		break;
		case "lista":
			$arr = array();
			$alerta->setAlerta_id($alerta_id);
			$alerta->setAlerta_contingente_verde($alerta_contingente_verde);
			$alerta->setAlerta_contingente_amarilla($alerta_contingente_amarilla);
			$alerta->setAlerta_contingente_roja($alerta_contingente_roja);
			$alerta->setAlerta_salvaguardia_verde($alerta_salvaguardia_verde);
			$alerta->setAlerta_salvaguardia_amarilla($alerta_salvaguardia_amarilla);
			$alerta->setAlerta_salvaguardia_roja($alerta_salvaguardia_roja);
			$alerta->setAlerta_emails($alerta_emails);
			$alerta->setAlerta_contingente_id($alerta_contingente_id);
			$alerta->setAlerta_contingente_acuerdo_det_id($alerta_contingente_acuerdo_det_id);
			$alerta->setAlerta_contingente_acuerdo_det_acuerdo_id($alerta_contingente_acuerdo_det_acuerdo_id);
			$alerta->setAlerta_disp1($alerta_disp1);
			$alerta->setAlerta_disp2($alerta_disp2);
			$alerta->setAlerta_disp3($alerta_disp3);
			$alerta->setAlerta_disp4($alerta_disp4);
			$alerta->setAlerta_disp5($alerta_disp5);
			$alerta->setAlerta_disp6($alerta_disp6);
			$rs_alerta = $alertaAdo->lista($alerta);
			if(!is_array($rs_alerta)){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>$rs_alerta)
				);
				echo json_encode($respuesta);
				exit();
			}
			foreach($rs_alerta["data"] as $key => $data){
				$arr[] = sanear_string($data);
			}
			$respuesta = array(
				"success"=>true,
				"total"=>$rs_alerta["total"],
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
			$rs_alerta = $alertaAdo->lista_filtro($query, $valuesqry, $limit);
			if(!is_array($rs_alerta)){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>$rs_alerta)
				);
				echo json_encode($respuesta);
				exit();
			}
			elseif($rs_alerta["total"] == 0){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>sanear_string(_NOSEENCONTRARONREGISTROS))
				);
				echo json_encode($respuesta);
				exit();
			}
			else{
				foreach($rs_alerta["data"] as $key => $data){
					$arr[] = sanear_string($data);
				}
				$respuesta = array(
					"success"=>true,
					"total"=>$rs_alerta["total"],
					"data"=>$arr
				);
				echo json_encode($respuesta);
				exit();
			}
		break;
	}
}
?>
