<?php
session_start();
include('../../lib/config.php');
include_once(PATH_APP."lib/idioma.php");
include_once(PATH_APP."lib/lib_funciones.php");
include_once(PATH_APP."lib/lib_sesion.php");
include_once(PATH_RAIZ."min_agricultura/lib/empresa/empresaAdo.php");
$empresaAdo = new EmpresaAdo("min_agricultura");
$empresa    = new Empresa;
if(isset($accion)){
	switch($accion){
		case "act":
			$empresa->setId_empresa($id_empresa);
			$empresa->setDigito_cheq($digito_cheq);
			$empresa->setEmpresa($empresa);
			$empresa->setRepresentante($representante);
			$empresa->setId_departamentos($id_departamentos);
			$empresa->setDepartamentos($departamentos);
			$empresa->setId_ciudad($id_ciudad);
			$empresa->setCiudad($ciudad);
			$empresa->setDireccion($direccion);
			$empresa->setTelefono($telefono);
			$empresa->setTelefono2($telefono2);
			$empresa->setTelefono3($telefono3);
			$empresa->setFax($fax);
			$empresa->setFax2($fax2);
			$empresa->setFax3($fax3);
			$empresa->setEmail($email);
			$empresa->setClase($clase);
			$empresa->setUap($uap);
			$empresa->setAltex($altex);
			$empresa->setWeb($web);
			$empresa->setContacto1($contacto1);
			$empresa->setId_tipo_empresa($id_tipo_empresa);
			$rs_empresa = $empresaAdo->actualizar($empresa);
			if($rs_empresa !== true){
				$success = false;
			}
			else{
				$success = true;
			}
			$respuesta = array(
				"success"=>$success,
				"errors"=>array("reason"=>$rs_empresa)
			);
			echo json_encode($respuesta);
			exit();
		break;
		case "del":
			$empresa->setId_empresa($id_empresa);
			$rs_empresa = $empresaAdo->borrar($empresa);
			if($rs_empresa !== true){
				$success = false;
			}
			else{
				$success = true;
			}
			$respuesta = array(
				"success"=>$success,
				"errors"=>array("reason"=>$rs_empresa)
			);
			echo json_encode($respuesta);
			exit();
		break;
		case "crea":
			$empresa->setId_empresa($id_empresa);
			$empresa->setDigito_cheq($digito_cheq);
			$empresa->setEmpresa($empresa);
			$empresa->setRepresentante($representante);
			$empresa->setId_departamentos($id_departamentos);
			$empresa->setDepartamentos($departamentos);
			$empresa->setId_ciudad($id_ciudad);
			$empresa->setCiudad($ciudad);
			$empresa->setDireccion($direccion);
			$empresa->setTelefono($telefono);
			$empresa->setTelefono2($telefono2);
			$empresa->setTelefono3($telefono3);
			$empresa->setFax($fax);
			$empresa->setFax2($fax2);
			$empresa->setFax3($fax3);
			$empresa->setEmail($email);
			$empresa->setClase($clase);
			$empresa->setUap($uap);
			$empresa->setAltex($altex);
			$empresa->setWeb($web);
			$empresa->setContacto1($contacto1);
			$empresa->setId_tipo_empresa($id_tipo_empresa);
			$rs_empresa = $empresaAdo->insertar($empresa);
			if($rs_empresa["success"] !== true){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>"Error creando empresa", "error"=>$rs_empresa["error"])
				);
				echo json_encode($respuesta);
				exit();
			}
			$id_empresa = $rs_empresa["insert_id"];
			$respuesta = array(
				"success"=>true,
				"errors"=>array("reason"=>$id_empresa)
			);
			echo json_encode($respuesta);
			exit();
		break;
		case "lista":
			$arr = array();
			$empresa->setId_empresa($id_empresa);
			$empresa->setDigito_cheq($digito_cheq);
			$empresa->setEmpresa($empresa);
			$empresa->setRepresentante($representante);
			$empresa->setId_departamentos($id_departamentos);
			$empresa->setDepartamentos($departamentos);
			$empresa->setId_ciudad($id_ciudad);
			$empresa->setCiudad($ciudad);
			$empresa->setDireccion($direccion);
			$empresa->setTelefono($telefono);
			$empresa->setTelefono2($telefono2);
			$empresa->setTelefono3($telefono3);
			$empresa->setFax($fax);
			$empresa->setFax2($fax2);
			$empresa->setFax3($fax3);
			$empresa->setEmail($email);
			$empresa->setClase($clase);
			$empresa->setUap($uap);
			$empresa->setAltex($altex);
			$empresa->setWeb($web);
			$empresa->setContacto1($contacto1);
			$empresa->setId_tipo_empresa($id_tipo_empresa);
			$rs_empresa = $empresaAdo->lista($empresa);
			if(!is_array($rs_empresa)){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>$rs_empresa)
				);
				echo json_encode($respuesta);
				exit();
			}
			foreach($rs_empresa["data"] as $key => $data){
				$arr[] = sanear_string($data);
			}
			$respuesta = array(
				"success"=>true,
				"total"=>$rs_empresa["total"],
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
			$rs_empresa = $empresaAdo->lista_filtro($query, $valuesqry, $limit);
			if(!is_array($rs_empresa)){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>$rs_empresa)
				);
				echo json_encode($respuesta);
				exit();
			}
			elseif($rs_empresa["total"] == 0){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>sanear_string(_NOSEENCONTRARONREGISTROS))
				);
				echo json_encode($respuesta);
				exit();
			}
			else{
				foreach($rs_empresa["data"] as $key => $data){
					$arr[] = sanear_string($data);
				}
				$respuesta = array(
					"success"=>true,
					"total"=>$rs_empresa["total"],
					"data"=>$arr
				);
				echo json_encode($respuesta);
				exit();
			}
		break;
	}
}
?>
