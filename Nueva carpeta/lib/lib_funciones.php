<?php
//ini_set("display_errors",true);
function asignar_reclutador($usuario_id){
	include_once(PATH_RAIZ.'ssgroup/lib/usuario/usuarioAdo.php');
	$usuarioAdo = new UsuarioAdo('ssgroup');
	$usuario    = new Usuario;
	$usuario->setUsuario_id($usuario_id);
	$rsUsuario = $usuarioAdo->listaId($usuario);
	
	include_once(PATH_RAIZ.'ssgroup/lib/reclutador_countries/reclutador_countriesAdo.php');
	$reclutador_countriesAdo = new Reclutador_countriesAdo('ssgroup');
	//print(" ==== ".$usuario_id);
	//exit();
	if($rsUsuario){
		if($rsUsuario[0]["usuario_reclutador_id"] == 0){
			$pais_residencia = $rsUsuario[0]["usuario_CountryId"];			
			$reclutador_countries    = new Reclutador_countries;
			$reclutador_countries->setReclutador_countries_CountryId($pais_residencia);
			$rsReclutador = $reclutador_countriesAdo->listaByCountry($reclutador_countries);
			if($rsReclutador){ //si el candidato es de un pais asignado a algun reclutador
				//toma el primero ya que el query lo organiza por numero de asignados y fecha de la ultima asignacion
				$reclutador_id = $rsReclutador[0]["reclutador_countries_usuario_id"]; 
				$reclutador_nombre = $rsReclutador[0]["reclutador_nombre"];
				$reclutador_email = $rsReclutador[0]["reclutador_email"];
			}
			else{
				$reclutador_nombre = "";
				$reclutador_email = EMAIL_ADMIN;
				$reclutador_id = ID_ADMIN;
			}
		}
		else{
			$reclutador_id = $rsUsuario[0]["usuario_reclutador_id"];
			$reclutador_nombre = $rsUsuario[0]["reclutador_nombre"];
			$reclutador_email = $rsUsuario[0]["reclutador_email"];
		}
		
		//actualiza la fecha de ultima asignacion
		$reclutador_countries    = new Reclutador_countries;
		$reclutador_countries->setReclutador_countries_usuario_id($reclutador_id);
		$reclutador_countries->setReclutador_countries_fultima_asig(date("Y-m-d H:i:s"));
		$reclutador_countriesAdo->actFultima($reclutador_countries);
		//actualiza el reclutador en el usuario
		$usuario    = new Usuario;
		$usuario->setUsuario_id($usuario_id);
		$usuario->setUsuario_reclutador_id($reclutador_id);
		$usuarioAdo->actualizar($usuario);
		//crea el registro de historial
		include(PATH_RAIZ.'ssgroup/lib/historial/historialAdo.php');
		$historialAdo = new HistorialAdo('ssgroup');
		$historial    = new Historial;
		$historial->setHistorial_usuario_id($usuario_id);
		$historial->setHistorial_acciones_id(1); //accion de asignacion de reclutador
		$historial->setHistorial_fecha(date("Y-m-d H:i:s"));
		$historialAdo->insertar($historial);
		//print "reclutador = ".$reclutador_nombre;
		$candidato_nombre = strtoupper(sanear_string($rsUsuario[0]["usuario_papellido"]." ".
						  $rsUsuario[0]["usuario_sapellido"]." ".
						  $rsUsuario[0]["usuario_pnombre"]." ".
						  $rsUsuario[0]["usuario_snombre"]));
						  
		$candidato_nombre = str_replace("  "," ",$candidato_nombre);
		
		$message = file_get_contents(PATH_RAIZ.'email_templates/tpl_asignacion_reclutador.html');
		$message = str_replace('%reclutador_nombre%', $reclutador_nombre, $message);
		$message = str_replace('%candidato_nombre%', $candidato_nombre, $message);
		$message = str_replace('%usuario_email%', $rsUsuario[0]["usuario_email"], $message);
		//print $message;
		//exit();
		$asunto    = "New applicant pending...";
		$fromName  = MAIL_FROMNAME;
		$fromEmail = MAIL_FROM;
		$to		   = $reclutador_email;
		//$to		   = "FAS0980@GMAIL.COM";
		email($message, $asunto, $fromName, $fromEmail, $to);
	}
}
function validar_permisos($menu_id, $url){
	include_once(PATH_RAIZ."lib/conexion/conexion.php");
	include_once(PATH_RAIZ.'ssgroup/lib/permisos/permisosAdo.php');
	$permisosAdo = new PermisosAdo('ssgroup');
	$permisos    = new Permisos;
	$permisos->setPermisos_opc_menu_id($menu_id);
	$result = $permisosAdo->valida_permiso($permisos, $url);
	return $result;
}
function validar_seccion_cv_todas($usuario_id){
	validar_seccion_cv($menu_id, $usuario_id);
}
function validar_seccion_cv($menu_id, $usuario_id){
	include_once(PATH_RAIZ."lib/conexion/conexion.php");
	$return = "vacio";
	if($usuario_id == ""){
		return "vacio";
	}
	switch ($menu_id){
		case _RESUME_FORM1:
			include_once(PATH_RAIZ.'ssgroup/lib/usuario/usuarioAdo.php');
			$usuarioAdo = new UsuarioAdo('ssgroup');
			$usuario    = new Usuario;
			$usuario->setUsuario_id($usuario_id);
			$result = $usuarioAdo->listaId($usuario);
			if(count($result) > 0 && $result[0]["usuario_identificacion_imagen"] != ""){
				$return = "completo";
			}
			elseif(count($result) > 0){
				$return = "incompleto";
			}
			else{
				$return = "vacio";
			}
		break;
		case _RESUME_FORM2:
			include_once(PATH_RAIZ.'ssgroup/lib/empleado/empleadoAdo.php');
			$empleadoAdo = new EmpleadoAdo('ssgroup');
			$empleado    = new Empleado;
			$empleado->setEmpleado_usuario_id($usuario_id);
			$result = $empleadoAdo->listaId($empleado);
			//print_r($result);
			if(count($result) > 0 &&
				$result[0]["empleado_lista_2_airports_id"] != 0 &&
				$result[0]["empleado_lista_2_5_airports_id"] != 0
			){
				$return = "completo";
			}
			elseif(count($result) > 0){
				$return = "incompleto";
			}
			else{
				$return = "vacio";
			}
		break;
		case _RESUME_FORM3:
			include(PATH_RAIZ.'ssgroup/lib/rel_navieras/rel_navierasAdo.php');
			$rel_navierasAdo = new Rel_navierasAdo('ssgroup');
			$rel_navieras    = new Rel_navieras;
			$rel_navieras->setRel_navieras_usuario_id($_SESSION["session_usuario_id"]);
			$rsRel_navieras = $rel_navierasAdo->lista($rel_navieras);
			
		
			include_once(PATH_RAIZ.'ssgroup/lib/trabajo/trabajoAdo.php');
			$trabajoAdo = new TrabajoAdo('ssgroup');
			$trabajo    = new Trabajo;
			$trabajo->setTrabajo_empleado_usuario_id($usuario_id);
			$rsTrabajo = $trabajoAdo->lista($trabajo);
			if($rsRel_navieras && count($rsTrabajo) >= NUMERO_EXP_LABORAL){
				$return = "completo";
			}
			elseif(count($rsTrabajo) > 0 || $rsRel_navieras){
				$return = "incompleto";
			}
			else{
				$return = "vacio";
			}
		break;
		case _RESUME_FORM4:
			include_once(PATH_RAIZ.'ssgroup/lib/estudios/estudiosAdo.php');
			$estudiosAdo = new EstudiosAdo('ssgroup');
			$estudios    = new Estudios;
			$estudios->setEstudios_empleado_usuario_id($usuario_id);
			$result = $estudiosAdo->lista($estudios);
			if(count($result) >= NUMERO_ESTUDIOS){
				$return = "completo";
			}
			elseif(count($result) > 0){
				$return = "incompleto";
			}
			else{
				$return = "vacio";
			}
		break;
		case _RESUME_FORM5:
			include_once(PATH_RAIZ.'ssgroup/lib/certificaciones/certificacionesAdo.php');
			$certificacionesAdo = new CertificacionesAdo('ssgroup');
			$certificaciones    = new Certificaciones;
			$certificaciones->setCertificaciones_empleado_usuario_id($usuario_id);
			$result = $certificacionesAdo->lista($certificaciones);
			if(count($result) > 1){
				$return = "completo";
			}
			elseif(count($result) > 0){
				$return = "incompleto";
			}
			else{
				$return = "vacio";
			}
		break;
		case _RESUME_FORM6:
			include_once(PATH_RAIZ.'ssgroup/lib/empleado_idioma/empleado_idiomaAdo.php');
			$empleado_idiomaAdo = new Empleado_idiomaAdo('ssgroup');
			$empleado_idioma    = new Empleado_idioma;
			$empleado_idioma->setEmpleado_idioma_empleado_usuario_id($usuario_id);
			$result = $empleado_idiomaAdo->lista($empleado_idioma);
			if(count($result) >= NUMERO_IDIOMAS){
				$return = "completo";
			}
			elseif(count($result) > 0){
				$return = "incompleto";
			}
			else{
				$return = "vacio";
			}
		break;
		case _RESUME_FORM7:
			include_once(PATH_RAIZ.'ssgroup/lib/empleado_habilidad/empleado_habilidadAdo.php');
			$empleado_habilidadAdo = new Empleado_habilidadAdo('ssgroup');
			$empleado_habilidad    = new Empleado_habilidad;
			$empleado_habilidad->setEmpleado_habilidad_empleado_usuario_id($usuario_id);
			$result = $empleado_habilidadAdo->lista($empleado_habilidad);
			if(count($result) > 0){
				$return = "completo";
			}
		break;
		case _RESUME_FORM8:
			include_once(PATH_RAIZ.'ssgroup/lib/empleado_cargos/empleado_cargosAdo.php');
			$empleado_cargosAdo = new Empleado_cargosAdo('ssgroup');
			$empleado_cargos    = new Empleado_cargos;			
			$empleado_cargos->setEmpleado_cargos_empleado_usuario_id($usuario_id);
			$result = $empleado_cargosAdo->lista($empleado_cargos);
			if(count($result) > 0){
				$return = "completo";
			}
		break;
		case _RESUME_FORM9:   //INFORMACION FAMILIAR Y REFERENCIAS PERSONALES
			include_once(PATH_RAIZ.'ssgroup/lib/empleado_relacionados/empleado_relacionadosAdo.php');
			$empleado_relacionadosAdo = new Empleado_relacionadosAdo('ssgroup');
			$empleado_relacionados    = new Empleado_relacionados;
			$empleado_relacionados->setEmpleado_relacionados_tipos_relacionados_id(
				TR_REF1_ID .",".
				TR_REF2_ID .",".
				TR_REF3_ID
			);
			$empleado_relacionados->setEmpleado_relacionados_empleado_usuario_id($usuario_id);
			$result = $empleado_relacionadosAdo->lista($empleado_relacionados);
			if(count($result) > 2){
				$return = "completo";
			}
			
		break;
		case _RESUME_FORM10: //policies and conditions
			include_once(PATH_RAIZ.'ssgroup/lib/empleado_relacionados/empleado_relacionadosAdo.php');
			$empleado_relacionadosAdo = new Empleado_relacionadosAdo('ssgroup');
			$empleado_relacionados    = new Empleado_relacionados;
			$empleado_relacionados->setEmpleado_relacionados_tipos_relacionados_id(
				TR_LIFEBEN1_ID .",".
				TR_LIFEBEN2_ID
			);
			$empleado_relacionados->setEmpleado_relacionados_empleado_usuario_id($usuario_id);
			$result = $empleado_relacionadosAdo->lista($empleado_relacionados);
			if(count($result) > 1){
				$return = "completo";
			}
			
		break;
		case _RESUME_FORM11:  //VISAS AND PASSPORT
			

			include(PATH_RAIZ.'ssgroup/lib/empleado_pasaporte/empleado_pasaporteAdo.php');
			$empleado_pasaporteAdo = new Empleado_pasaporteAdo('ssgroup');
			$empleado_pasaporte    = new Empleado_pasaporte;
			$empleado_pasaporte->setEmpleado_pasaporte_empleado_usuario_id($_SESSION['session_usuario_id']);
			$rsPasaporte = $empleado_pasaporteAdo->lista($empleado_pasaporte);	
		
			
			include_once(PATH_RAIZ.'ssgroup/lib/empleado_visa/empleado_visaAdo.php');
			$empleado_visaAdo = new Empleado_visaAdo('ssgroup');
			$empleado_visa    = new Empleado_visa;
			$empleado_visa->setEmpleado_visa_empleado_usuario_id($usuario_id);
			$rsVisa = $empleado_visaAdo->lista($empleado_visa);
			
			if($rsVisa && $rsPasaporte){
				$return = "completo";
			}
			elseif($rsVisa || $rsPasaporte){
				$return = "incompleto";
			}
			else{
				$return = "vacio";
			}
		break;
		case _RESUME_FORM12:  //pre screening notes
			include_once(PATH_RAIZ.'ssgroup/lib/usuario/usuarioAdo.php');
			$usuarioAdo = new UsuarioAdo('ssgroup');
			$usuario    = new Usuario;
			$usuario->setUsuario_id($usuario_id);
			$result_usuario = $usuarioAdo->listaId($usuario);
			include_once(PATH_RAIZ.'ssgroup/lib/pre_screening_notes/pre_screening_notesAdo.php');
			$pre_screening_notesAdo = new Pre_screening_notesAdo('ssgroup');
			$pre_screening_notes    = new Pre_screening_notes;
			$pre_screening_notes->setPre_screening_notes_empleado_usuario_id($usuario_id);
			$result = $pre_screening_notesAdo->listaId($pre_screening_notes);
			if(count($result) > 0 &&
			   count($result_usuario) > 0 &&
			   $result[0]["usuario_firma"] != ""
			){
				$return = "completo";
			}
			elseif(count($result) > 0){
				$return = "incompleto";
			}
			else{
				$return = "vacio";
			}
		break;
		
	}
	
	return $return;
}
function email($mensaje, $asunto, $fromName, $fromEmail, $to){ //$to es un array con los destinatarios
	require_once('PHPMailer/class.phpmailer.php');	
	$mail = new PHPMailer(true);	
	$mail->IsSMTP();
	
	try {
		//$mail->SMTPDebug  = 2;                     // enables SMTP debug information (for testing)
		$mail->SMTPAuth   = true;                  // enable SMTP authentication
		$mail->SMTPSecure = "http";
		$mail->Host       = MAIL_HOST;      // sets GMAIL as the SMTP server
		$mail->Port       = 80;                   // set the SMTP port for the GMAIL server
		$mail->Username   = "info@thesevenseasgroup.info";
		$mail->Password   = "Seven7Fab"; # Editar el password 
		$mail->SetFrom($fromEmail, $fromName);
		if(!is_array($to)){
			$mail->AddAddress($to);
		}
		else{
			foreach($to as $email => $nombre){
				$mail->AddAddress($email, $nombre);
			}
		}
		
		//$mail->AddReplyTo('name@yourdomain.com', 'Webmaster');
		$mail->Subject = $asunto;
		$mail->AltBody = 'To view the message, please use an HTML compatible email viewer!'; // optional - MsgHTML will create an alternate automatically
		$mail->MsgHTML($mensaje);
		$mail->Send();
		return 'OK';
	}
	catch (phpmailerException $e) {
		return $e->errorMessage(); //Pretty error messages from PHPMailer
	}
	catch (Exception $e) {
		return $e->getMessage(); //Boring error messages from anything else!
	}
}

function sanear_string($string){
	if(is_array($string)){
		$tmp = array();
		foreach($string as $key => $valor){
			$tmp[$key] = sanear_string($valor);
		}
		return $tmp;
	}
 
    $string = trim($string);
	
	
    $string = str_replace(
        array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
        array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
        $string
    );
 
    $string = str_replace(
        array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
        array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
        $string
    );
 
    $string = str_replace(
        array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
        array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
        $string
    );
 
    $string = str_replace(
        array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
        array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
        $string
    );
 
    $string = str_replace(
        array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
        array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
        $string
    );
 
    $string = str_replace(
        array('ñ', 'Ñ', 'ç', 'Ç'),
        array('n', 'N', 'c', 'C',),
        $string
    );
	
	//Esta parte se encarga de eliminar cualquier caracter extraño
    $string = str_replace(
        array("\\", "¨", "º", "°",/*"-",*/ "~",
             "#",  "|", "!", "\"",
             "·", "$", "%", "&", "/",
             "(", ")", "?", "'", "¡",
             "¿", "[", "^", "`", "]",
             "+", "}", "{", "¨", "´",
             ">", "<", ";", "U2022", "°", "•", "", 
             ""),
        '',
        $string
    );
	$string = strip_tags($string);
	//$string = htmlentities($string);
	$string = stripslashes($string);
	$string = filter_var($string,FILTER_SANITIZE_STRING);
 	//$string = utf8_encode($string);
    return $string;
}
function uploadImage($name, $fileName, $dir){
	$result = new stdClass();
	$file   = $_FILES[$name];
	$tamano = $file['size'];
	$info   = getimagesize($file['tmp_name']);
	$tmp	= $file['name'];
	$ext 			= substr(strrchr($file['name'], '.'), 1);
	$nombre_archivo = md5($name.$fileName).".".$ext;	
	$contentType = $info['mime'];
	if($tamano > 1000000){
		$result->reason		= "You can upload images with weights greater than 1Mb";
		$result->success    = false;
	}
	elseif($contentType =='image/jpeg' || $contentType =='image/gif' || $contentType =='image/png' ){
		if(isset($_FILES[$name]) && move_uploaded_file($file['tmp_name'], $dir.$nombre_archivo)){
			list($width, $height, $type, $attr) = getimagesize($dir.$file['name']);
			$result->newImage	= $nombre_archivo;
			$result->imageWidth	= $width;
			$result->imageHeight= $height;
			$result->reason		= $dir.$nombre_archivo;
			$result->success    = true;
		}else{
			$result->reason		= "Unable to Upload";
			$result->success    = false;	
		}		
	}
	else{
		$result->reason		= "Invalid File in ".$tmp.". Please only upload images files";
		$result->success    = false;	
	}	
	return $result;
}
function uploadPdf($name, $fileName, $dir){
	$result 	 = new stdClass();
	$file   	 = $_FILES[$name];
	$tamano  	 = $file['size'];
	$contentType = $file['type'];
	$tmp		 = $file['name'];
	$ext 			= substr(strrchr($file['name'], '.'), 1);
	$nombre_archivo = md5($name.$fileName).".".$ext;
	if($tamano > 1000000){
		$result->reason		= "You can upload files with weights greater than 1Mb";
		$result->success    = false;
	}
	elseif($contentType =='application/pdf'){
		if(isset($_FILES[$name]) && move_uploaded_file($file['tmp_name'], $dir.$nombre_archivo)){
			$tamano = filesize($dir.$file['name']);
			$result->newPdf		= $nombre_archivo;
			$result->pdfSize	= human_filesize($tamano);
			$result->reason		= "Uploaded";
			$result->success    = true;
		}else{
			$result->reason		= "Unable to Upload";
			$result->success    = false;	
		}		
	}
	else{
		$result->reason		= "Invalid File in ".$tmp.". Please only upload PDF files";
		$result->success    = false;	
	}	
	return $result;
}
function human_filesize($bytes, $decimals = 2) {
  $sz = 'BKMGTP';
  $factor = floor((strlen($bytes) - 1) / 3);
  return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor];
}

function zipFilesAndDownload($file_names,$archive_file_name,$file_path){
  //create the object
	$zip = new ZipArchive();
	//create the file and throw the error if unsuccessful
	if ($zip->open($file_path.$archive_file_name, ZIPARCHIVE::CREATE )!==TRUE) {
		return "cannot open <$archive_file_name>\n";
	}
	
	//add each files of $file_name array to archive
	foreach($file_names as $files){
		$zip->addFile($file_path.$files,$files);
	}
	$zip->close();
	
	foreach($file_names as $files){
		//print $file_path.$files ."\n";
		if(file_exists($file_path.$files)){
			unlink($file_path.$files);
		}
	}
	return false;
	//then send the headers to foce download the zip file
	/*header("Content-type: application/zip");
	header("Content-Disposition: attachment; filename=$archive_file_name");
	header("Pragma: no-cache");
	header("Expires: 0");
	readfile("$archive_file_name");
	//unlink($archive_file_name);
	exit;*/
}

function comprimir($buffer) { 
    /* remove comments */
    $buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);
    /* remove tabs, spaces, newlines, etc. */
    $buffer = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $buffer);
    return $buffer;
}
function enviar_referencia($key,$email,$idioma_id,$emp_nombre, $jefe_nombre){
	/*include_once(PATH_RAIZ.'ssgroup/lib/idioma/idiomaAdo.php');
	$idiomaAdo = new IdiomaAdo('ssgroup');
	$idioma    = new Idioma;
	$idioma->setIdioma_id($idioma_id);
	$rsIdioma = $idiomaAdo->lista($idioma);
	$idioma_iso = $rsIdioma[0]["idioma_iso"];*/
	$url_raiz = ($_SERVER['HTTP_HOST'] != "")?URL_RAIZ:"http://thesevenseasgroup.info/";
	$idioma_iso = $idioma_id == 172?"en":"es";
	
	$url_referencia = $url_raiz."work_reference/".$idioma_iso."/".$key."/";
	$link_referencia = str_replace("http://","",$url_referencia);
	
	$message = file_get_contents(PATH_RAIZ.'email_templates/tpl_referencia_laboral_eng.html');
	if($idioma_iso == "en"){
		$message = file_get_contents(PATH_RAIZ.'email_templates/tpl_referencia_laboral_eng.html');
		$asunto = "Employment Reference for ".$emp_nombre." ";
	}
	elseif($idioma_iso == "es"){
		$message = file_get_contents(PATH_RAIZ.'email_templates/tpl_referencia_laboral_esp.html');
		$asunto = "Referencia laboral para ".$emp_nombre." ";
	}
	$message = str_replace('%jefe_nombre%', $jefe_nombre, $message);
	$message = str_replace('%empleado_nombre%', $emp_nombre, $message);
	$message = str_replace('%url_referencia%', $url_referencia, $message);
	$message = str_replace('%link_referencia%', $link_referencia, $message);
	
	$fromName  = MAIL_FROMNAME;
	$fromEmail = MAIL_FROM;
	$to		   = $email;
	email($message, $asunto, $fromName, $fromEmail, $to);
}
?>