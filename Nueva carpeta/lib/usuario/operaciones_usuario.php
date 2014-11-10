<?php
session_start();
include_once ('../../../lib/config.php');
include_once (PATH_RAIZ.'lib/excel.php');
include_once (PATH_RAIZ.'lib/lib_funciones.php');
include_once (PATH_RAIZ.'lib/conexion/conexion.php');
include_once (PATH_RAIZ.'lib/lib_funciones.php');
include(PATH_RAIZ.'ssgroup/lib/usuario/usuarioAdo.php');
$usuarioAdo = new UsuarioAdo('ssgroup');
$usuario    = new Usuario;

if(isset($accion)){
	if( $accion != 'existeIdentification' && 
		$accion != 'existeEmail' && 
		$accion != 'existeSkypeId'&& 
		$accion != 'lista_reclutadores_combo'
	){
		include_once (PATH_RAIZ.'lib/lib_sesion.php');
	}
	switch ($accion){
		case 'actForm':
			$usuario_firma = "";
			$usuario_identificacion_imagen = "";
			$usuario->setUsuario_id($_SESSION["session_usuario_id"]);
			$rsUsuario = $usuarioAdo->listaId($usuario);
			
			$usuario->setUsuario_tipos_identificacion_id($usuario_tipos_identificacion_id);
			$usuario->setUsuario_documento_ident(trim(sanear_string($regIdentificacion)));
			$result = $usuarioAdo->existeIdentification($usuario);
			if($result){
				echo '{success: false, errors: { reason: "This Identification is already registered" }}';
				exit();
			}
			
			/*if(($_FILES["usuario_firma"]['tmp_name'] != "")){
				//si esta actualizando,debe borrar el archivo anterior
				$archivo = "";
				foreach($rsUsuario as $key => $data){
					$archivo = $data["usuario_firma"];
				}
				//print $archivo;
				if(file_exists(PATH_FIRMAS.$archivo) && $archivo != ""){
					unlink(PATH_FIRMAS.$archivo);
				}
				$result = uploadImage("usuario_firma", $_SESSION['session_usuario_id'].time(), PATH_FIRMAS);
				if(!$result->success){
					echo json_encode($result);
					exit();
				}
				$usuario_firma = trim($result->newImage);
			}*/
			
			if(($_FILES["usuario_identificacion_imagen"]['tmp_name'] != "")){
				//si esta actualizando,debe borrar el archivo anterior
				$archivo = "";
				foreach($rsUsuario as $key => $data){
					$archivo = $data["usuario_identificacion_imagen"];
				}
				if(file_exists(PATH_IDENTIDICACIONES.$archivo && $archivo != "")){
					unlink(PATH_IDENTIDICACIONES.$archivo);
				}				
				$result = uploadPdf(
					"usuario_identificacion_imagen",
					$_SESSION['session_usuario_id'].time(),
					PATH_IDENTIDICACIONES
				);
				if(!$result->success){
					echo json_encode($result);
					exit();
				}
				$usuario_identificacion_imagen = trim($result->newPdf);
			}
		
			$usuario->setUsuario_id($_SESSION['session_usuario_id']);
			$usuario->setUsuario_pnombre(strtoupper(sanear_string($regPnombre)));
			$usuario->setUsuario_snombre(strtoupper(sanear_string($regSnombre)));
			$usuario->setUsuario_papellido(strtoupper(sanear_string($regPapellido)));
			$usuario->setUsuario_sapellido(strtoupper(sanear_string($regSapellido)));
			$usuario->setUsuario_email(strtoupper(($regEmail)));
			if(isset($regPassword)){
				$usuario->setUsuario_password($regPassword);
			}
			$usuario->setUsuario_CityId(strtoupper(sanear_string($usuario_CityId)));
			$usuario->setUsuario_CountryId($usuario_CountryId);
			
			$usuario->setUsuario_CountryId2($usuario_CountryId2);
			$usuario->setUsuario_CityId2(strtoupper(sanear_string($usuario_CityId2)));
			$usuario->setUsuario_SkypeId(strtoupper(sanear_string($skypename)));
			$usuario->setUsuario_tipos_identificacion_id($usuario_tipos_identificacion_id);
			$usuario->setUsuario_documento_ident(trim(sanear_string($regIdentificacion)));
			$usuario->setUsuario_genero($regGenero);
			$usuario->setUsuario_fnacimiento($regFnacimiento);
			$usuario->setUsuario_identificacion_imagen($usuario_identificacion_imagen);
			//$usuario->setUsuario_firma($usuario_firma);
			$result = $usuarioAdo->actualizar($usuario);
			if($result != ''){
				echo '{reason: "'.sanear_string($result).'", success:false}';
			}
			else{
				echo '{success:true}';
			}
			exit();
		break;
		case 'actActivar':
			$usuario->setUsuario_id($id);
			$usuario->setUsuario_activo($estado);
			$result = $usuarioAdo->actualizar2($usuario);
			if($result != ''){
				echo '{reason: "'.sanear_string($result).'", success:false}';
			}
			else{
				echo '{success:true}';
			}
		break;
		case 'actPerfil':
			$usuario->setUsuario_id($candidato_id);
			$usuario->setUsuario_perfil_id($perfil_id);
			$result = $usuarioAdo->actualizar2($usuario);
			if($result != ''){
				echo '{reason: "'.sanear_string($result).'", success:false}';
			}
			else{
				echo '{success:true}';
			}
		break;
		case 'actFechaFormatos':
			$usuario->setUsuario_id($candidato_id);
			$usuario->setUsuario_fecha_formatos1($usuario_fecha_formatos1);
			$result = $usuarioAdo->actualizar2($usuario);
			if($result != ''){
				echo '{reason: "'.sanear_string($result).'", success:false}';
			}
			else{
				echo '{success:true}';
			}
		break;
		case 'actReclutador':
			$usuario->setUsuario_id($candidato_id);
			$rsUsuario = $usuarioAdo->listaId($usuario);
			$arr_usuario  = $rsUsuario[0];
			$nombre = $arr_usuario["usuario_papellido"]." ".
			$arr_usuario["usuario_sapellido"]." ".
			$arr_usuario["usuario_pnombre"]." ".
			$arr_usuario["usuario_snombre"];
			$nombre = str_replace("  "," ",$nombre);
			
			$usuario    = new Usuario;
			$usuario->setUsuario_id($reclutador_id);
			$rsReclutador = $usuarioAdo->listaId($usuario);
			$reclutador_nombre = $rsReclutador[0]["usuario_pnombre"]." ".$rsReclutador[0]["usuario_papellido"];
			
			$usuario    = new Usuario;
			$usuario->setUsuario_id($candidato_id);
			$usuario->setUsuario_reclutador_id($reclutador_id);
			
			$result = $usuarioAdo->actualizar2($usuario);
			if($result != ''){
				echo '{reason: "'.sanear_string($result).'", success:false}';
			}
			else{
				if($rsUsuario[0]["usuario_reclutador_id"] == $reclutador_id){
					echo '{success:true}';
					exit();
				}
				
				
				$message = file_get_contents(PATH_RAIZ.'email_templates/tpl_asignacion_reclutador.html');
				$message = str_replace('%reclutador_nombre%', $reclutador_nombre, $message);
				$message = str_replace('%candidato_nombre%', $nombre, $message);
				$message = str_replace('%usuario_email%', $arr_usuario["usuario_email"], $message);
				//envia email al nuevo reclutador
				
				//exit();
				$asunto    = "New applicant pending...";
				$fromName  = MAIL_FROMNAME;
				$fromEmail = MAIL_FROM;
				$to		   = $rsRecluatador[0]["usuario_email"];
				//$to		   = "FAS0980@GMAIL.COM";
				email($mensaje, $asunto, $fromName, $fromEmail, $to);
				
				$message = "";
				$message = file_get_contents(PATH_RAIZ.'email_templates/tpl_reasignacion_reclutador.html');
				$message = str_replace('%reclutador_nombre%', $arr_usuario["reclutador_nombre"], $message);
				$message = str_replace('%candidato_nombre%', $nombre, $message);
				$message = str_replace('%usuario_email%', $arr_usuario["usuario_email"], $message);
				//envia email al anterior reclutador
				$asunto    = "reassigned candidate";
				$fromName  = MAIL_FROMNAME;
				$fromEmail = MAIL_FROM;
				$to		   = $arr_usuario["reclutador_email"];
				//$to		   = "FAS0980@GMAIL.COM";
				email($mensaje, $asunto, $fromName, $fromEmail, $to);
				
				echo '{success:true}';
				exit();
			}
		break;
		case 'skypename':
			$url = "https://login.skype.com/json/validator";
	        $cUrl = curl_init();
			curl_setopt($cUrl, CURLOPT_URL, $url);
			curl_setopt($cUrl, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($cUrl, CURLOPT_POSTFIELDS, "new_username=".$value);
			$status_code = trim(curl_exec($cUrl));
			print(($status_code));
			curl_close($cUrl);
	
	    break;
		case 'del':
			$usuario->setUsuario_id($id);
			$usuarioAdo->borrar($usuario);
			if($result != ''){
				echo '{reason: "'.sanear_string($result).'", success:false}';
			}
			else{
				echo '{success:true}';
			}
		break;
		case 'crea':
			$usuario->setUsuario_id($usuario_id);
			$usuario->setUsuario_pnombre($usuario_pnombre);
			$usuario->setUsuario_snombre($usuario_snombre);
			$usuario->setUsuario_papellido($usuario_papellido);
			$usuario->setUsuario_sapellido($usuario_sapellido);
			$usuario->setUsuario_email($usuario_email);
			$usuario->setUsuario_password($usuario_password);
			$usuario->setUsuario_root($usuario_root);
			$usuario->setUsuario_activo($usuario_activo);
			$usuario->setUsuario_perfil_id($usuario_perfil_id);
			$usuario->setUsuario_finsert($usuario_finsert);
			$usuario->setUsuario_uinsert($usuario_uinsert);
			$usuario->setUsuario_CityId($usuario_CityId);
			$usuario->setUsuario_CountryId($usuario_CountryId);
			$usuario->setUsuario_CountryId2($usuario_CountryId2);
			$usuario->setUsuario_CityId2($usuario_CityId2);
			$usuario->setUsuario_SkypeId($usuario_SkypeId);
			$usuario->setUsuario_tipos_identificacion_id($usuario_tipos_identificacion_id);
			$usuario->setUsuario_documento_ident($usuario_documento_ident);
			$usuario->setUsuario_genero($usuario_genero);
			$usuario->setUsuario_fnacimiento($usuario_fnacimiento);
			$usuario->setUsuario_activationKey($usuario_activationKey);
			$usuario->setUsuario_reclutador_id($usuario_reclutador_id);
			$usuario->setUsuario_identificacion_imagen($usuario_identificacion_imagen);
			$usuario->setUsuario_firma($usuario_firma);
			$usuario->setUsuario_fecha_formatos1($usuario_fecha_formatos1);
			$usuario->setUsuario_campo_disponible2($usuario_campo_disponible2);
			$usuario->setUsuario_campo_disponible3($usuario_campo_disponible3);
			$usuario->setUsuario_campo_disponible4($usuario_campo_disponible4);
			$usuario->setUsuario_campo_disponible5($usuario_campo_disponible5);
			$result = $usuarioAdo->insertar($usuario);
			if($result != ''){
				echo '{reason: "'.sanear_string($result).'", success:false}';
			}
			else{
				echo '{success:true}';
			}
		break;
		case 'lista_grilla':
			$arr = array();
			$start  = isset($start)  ? $start  :  0;
			$count  = isset($limit)  ? $limit  : 20;
			$sort   = isset($sort)   ? $sort   : '';
			$dir    = isset($dir)    ? $dir    : 'ASC';
			$query  = isset($query)  ? $query  : null;
			
			$filtro = json_decode(stripslashes($fields));
			
			$result = $usuarioAdo->lista_grilla($start, $count, $sort, $dir, $query, $filtro);
			
			if(is_array($result["data"])){
				foreach($result["data"] as $key => $data){	
					$arr[] = filtro_grid($data);
				}
				if(isset($formato)){
					$head   = json_decode(stripslashes($campos));					
					$total = "Total Reg: ". $total;
					$result = "file_".time();					
					$archivo = CreaExcel($arr, $formato, $head, $total, $result);					
					echo '{success: true, msg:'.json_encode($archivo).'}';
					exit();
				}
			}
			$data = json_encode($arr); 
			print('{"total":"'.$result["total"].'", "datos":'.$data.'}');
		break;
		case 'lista':
			$arr = array();
			$usuario->setUsuario_id($usuario_id);
			$usuario->setUsuario_pnombre($usuario_pnombre);
			$usuario->setUsuario_snombre($usuario_snombre);
			$usuario->setUsuario_papellido($usuario_papellido);
			$usuario->setUsuario_sapellido($usuario_sapellido);
			$usuario->setUsuario_email($usuario_email);
			$usuario->setUsuario_password($usuario_password);
			$usuario->setUsuario_root($usuario_root);
			$usuario->setUsuario_activo($usuario_activo);
			$usuario->setUsuario_perfil_id($usuario_perfil_id);
			$usuario->setUsuario_finsert($usuario_finsert);
			$usuario->setUsuario_uinsert($usuario_uinsert);
			$usuario->setUsuario_CityId($usuario_CityId);
			$usuario->setUsuario_CountryId($usuario_CountryId);
			$usuario->setUsuario_CountryId2($usuario_CountryId2);
			$usuario->setUsuario_CityId2($usuario_CityId2);
			$usuario->setUsuario_SkypeId($usuario_SkypeId);
			$usuario->setUsuario_tipos_identificacion_id($usuario_tipos_identificacion_id);
			$usuario->setUsuario_documento_ident($usuario_documento_ident);
			$usuario->setUsuario_genero($usuario_genero);
			$usuario->setUsuario_fnacimiento($usuario_fnacimiento);
			$usuario->setUsuario_activationKey($usuario_activationKey);
			$usuario->setUsuario_reclutador_id($usuario_reclutador_id);
			$usuario->setUsuario_identificacion_imagen($usuario_identificacion_imagen);
			$usuario->setUsuario_firma($usuario_firma);
			$usuario->setUsuario_fecha_formatos1($usuario_fecha_formatos1);
			$usuario->setUsuario_campo_disponible2($usuario_campo_disponible2);
			$usuario->setUsuario_campo_disponible3($usuario_campo_disponible3);
			$usuario->setUsuario_campo_disponible4($usuario_campo_disponible4);
			$usuario->setUsuario_campo_disponible5($usuario_campo_disponible5);
			$result = $usuarioAdo->lista($usuario);
			foreach($result as $key => $data){
				$arr[] = filtro_grid($data);
			}
			if(isset($formato)){
				$head   = json_decode(stripslashes($campos));
				$total = 'Total Reg: '. $total;
				$result = 'file_'.time();
				$archivo = CreaExcel($arr, $formato, $head, $total, $result);
				echo '{success: true, msg:'.json_encode($archivo).'}';
				exit();
			}
			$data = json_encode($arr); 
			print('{"total":"'.count($result).'", "datos":'.$data.'}');
		break;
		case 'listaId':
			$arr = array();
			$usuario->setUsuario_id($_SESSION["session_usuario_id"]);
			$result = $usuarioAdo->listaId($usuario);
			foreach($result as $key => $data){
				$arr[] = filtro_grid($data);
			}
			if(isset($formato)){
				$head   = json_decode(stripslashes($campos));
				$total = 'Total Reg: '. $total;
				$result = 'file_'.time();
				$archivo = CreaExcel($arr, $formato, $head, $total, $result);
				echo '{success: true, msg:'.json_encode($archivo).'}';
				exit();
			}
			$data = json_encode($arr); 
			print('{"total":"'.count($result).'", "datos":'.$data.'}');
		break;
		case 'listaFechaFormatos':
			$arr = array();
			$usuario->setUsuario_id($id);
			$result = $usuarioAdo->listaId($usuario);
			/*foreach($result as $key => $data){
				if ($key == "usuario_fecha_formatos1") {
					$arr[] = filtro_grid($data);
				}
			}*/
			$fecha = $result[0]["usuario_fecha_formatos1"];
			if ($fecha == "0000-00-00") {
				$arr[] = array("usuario_fecha_formatos1"=>"");
			}
			else{
				$arr[] = array("usuario_fecha_formatos1"=>$fecha);
			}
			$respuesta = array(
				"datos"=>$arr
				,"total"=>count($result)
			);
			echo json_encode($respuesta);
			exit();
		break;
		case 'lista_reclutadores_combo':
			$arr = array();
			$usuario->setUsuario_perfil_id(PERFIL_RECLUTADOR.",".PERFIL_ADMIN);
			$usuario->setUsuario_activo(1);
			$result = $usuarioAdo->listaCombo($usuario);
			foreach($result as $key => $data){
				$arr[] = filtro_grid($data);
			}
			$data = json_encode($arr); 
			print('{"total":"'.count($result).'", "datos":'.$data.'}');
		break;
		case 'lista_reclutadores_admin_combo':
			$arr = array();
			$usuario->setUsuario_perfil_id(PERFIL_RECLUTADOR.",".PERFIL_ADMIN);
			$result = $usuarioAdo->listaCombo($usuario);
			foreach($result as $key => $data){
				$arr[] = filtro_grid($data);
			}
			$data = json_encode($arr); 
			print('{"total":"'.count($result).'", "datos":'.$data.'}');
		break;
		case 'lista_reclutadores_countries':
			$arr = array();
			$start  = isset($start)  ? $start  :  0;
			$count  = isset($limit)  ? $limit  : 20;
			$sort   = isset($sort)   ? $sort   : '';
			$dir    = isset($dir)    ? $dir    : 'ASC';
			$query  = isset($query)  ? $query  : null;
			
			$filtro = json_decode(stripslashes($fields));
			
			$result = $usuarioAdo->lista_reclutadores_countries($start, $count, $sort, $dir, $query, $filtro);
			//print_r($result);
			if(is_array($result["data"])){
				foreach($result["data"] as $key => $data){	
					$arr[] = filtro_grid($data);
				}
				if(isset($formato)){
					$head   = json_decode(stripslashes($campos));					
					$total = "Total Reg: ". $total;
					$result = "file_".time();					
					$archivo = CreaExcel($arr, $formato, $head, $total, $result);					
					echo '{success: true, msg:'.json_encode($archivo).'}';
					exit();
				}
			}
			$data = json_encode($arr); 
			print('{"total":"'.$result["total"].'", "datos":'.$data.'}');
			
		break;
		case 'existeEmail':
			$arr = array();
			$usuario->setUsuario_id($id);
			$usuario->setUsuario_email(trim($value));
			$result = $usuarioAdo->existeEmail($usuario);
			$success = ($result) ? 'false': 'true';
			echo '{reason: "This email address is already registered", success:true, valid: '.$success.'}';
	    break;
		case 'existeIdentification':
			$arr = array();
			$usuario->setUsuario_id($_SESSION["session_usuario_id"]);
			$usuario->setUsuario_tipos_identificacion_id($tipo);
			$usuario->setUsuario_documento_ident(trim(sanear_string($value)));
			$result = $usuarioAdo->existeIdentification($usuario);
			$success = ($result) ? 'false': 'true';
			echo '{reason: "This Identification is already registered", success:true, valid: '.$success.'}';
	    break;
		case 'existeSkypeId':
			$arr = array();
			$usuario->setUsuario_id($id);
			$usuario->setUsuario_SkypeId(trim($value));
			$result = $usuarioAdo->existeSkypeId($usuario);
			$success = ($result) ? 'false': 'true';
			echo '{reason: "This Skype Id is already registered", success:true, valid: '.$success.'}';
	    break;
		case "lista_filtro_empleados":
			$arr = array();
			$start = (isset($start))?$start:0;
			$limit = (isset($limit))?$limit:30;
			$page = ($start==0)?1:($start/$limit)+1;
			$limit = $page . ", " . $limit;
			$rs_usuario = $usuarioAdo->lista_filtro_empleados($query, $valuesqry, $limit);
			if(!is_array($rs_usuario)){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>$rs_usuario)
				);
				echo json_encode($respuesta);
				exit();
			}
			else{
				foreach($rs_usuario["datos"] as $key => $data){
					$arr[] = sanear_string($data);
				}
				$respuesta = array(
					"success"=>true,
					"total"=>$rs_usuario["total"],
					"datos"=>$arr
				);
				echo json_encode($respuesta);
				exit();
			}
		break;
  }
}
function filtro_grid($contenido){
  $contenido = str_replace('¡','', $contenido);
  $contenido = str_replace('á','a', $contenido);
  $contenido = str_replace('é','e', $contenido);
  $contenido = str_replace('í','i', $contenido);
  $contenido = str_replace('ó','o', $contenido);
  $contenido = str_replace('ú','u', $contenido);
  $contenido = str_replace('ñ','n', $contenido);
  $contenido = str_replace('Á','A', $contenido);
  $contenido = str_replace('É','E', $contenido);
  $contenido = str_replace('Í','I', $contenido);
  $contenido = str_replace('Ó','O', $contenido);
  $contenido = str_replace('Ú','U', $contenido);
  $contenido = str_replace('Ñ','N', $contenido);
  return $contenido;
}
?>
