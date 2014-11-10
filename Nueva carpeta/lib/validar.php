<?php
//Variables de configuración del sistema
include ("./config.php");
//Librerías de consulta a BD
include (PATH_RAIZ."lib/conexion/conexion.php");
include (PATH_RAIZ."lib/lib_funciones.php");
//print_r($_REQUEST);
if($ac == FORGOT) {
	$arrUsuario = array();
	//Datos del usuario
	include(PATH_RAIZ.'ssgroup/lib/usuario/usuarioAdo.php');
	$usuarioAdo = new UsuarioAdo('ssgroup');
	$usuario    = new Usuario;
	
	$usuario->setUsuario_email($username);
	//$usuario->setUsuario_activo('1');
	$rsUsuario = $usuarioAdo->lista($usuario);
	//print_r($rsUsuario);
	
	foreach($rsUsuario as $key => $data){
		$arrUsuario[] = ($data);
	}
	if(empty($arrUsuario)){
		echo "{success: false, errors: { reason: 'Please ensure that you have a valid email address attached to your account.' }}";
		exit();
	}
	else{
		if($arrUsuario[0]["usuario_activo"] == "1"){
			$nombre = $arrUsuario[0]["usuario_pnombre"]. " " . $arrUsuario[0]["usuario_papellido"];
			$newpass = generaPass();
			$result = MailPass($username,$nombre,$newpass);
			
			if(!$result){
				echo "{success: true, errors:{reason: 'Error sending email!' }}";
			}
			else{
				$usuario = new Usuario;
				$usuario->setUsuario_id($arrUsuario[0]["usuario_id"]);
				$usuario->setUsuario_password(md5($newpass));
				
				$result = $usuarioAdo->actualizar($usuario);
				if($result != ''){
					echo '{reason: "'.sanear_string($result).'", success:false}';
				}
				else{
					echo "{success: true, errors:{reason: 'An email has been sent to ".$username." which is the registered address for your account. It includes information of your new password.' }}";
				}
			}
			exit();
		}
		else{
			echo "{success: false, errors:{reason: 'Your account is inactive.' }}";
			exit();
		}
	}
}
//Acción de Login
if($ac == LOGIN) {
	$arrUsuario = array();
    //Datos del usuario
	include(PATH_RAIZ.'ssgroup/lib/usuario/usuarioAdo.php');
	$usuarioAdo = new UsuarioAdo('ssgroup');
	$usuario    = new Usuario;

	$usuario->setUsuario_email($username);
	$usuario->setUsuario_password($password);
	$arrUsuario = array();
	$rsUsuario = $usuarioAdo->login($usuario);
	//print_r($rsUsuario);
	foreach($rsUsuario as $key => $data){
		$arrUsuario[] = filtro_grid($data);
	}
	if(empty($arrUsuario)){
		echo "{success: false, errors: { reason: 'Your email address and password did not match. Please try again.'}}";
		exit();
	}
	else{
        session_start();
		include_once(PATH_RAIZ.'ssgroup/lib/session/sessionAdo.php');
		$sessionAdo = new SessionAdo('ssgroup');
		$session    = new Session;
		$session->setSession_usuario_id($arrUsuario[0]["usuario_id"]);
		$rsSession = $sessionAdo->lista($session);
		if(!empty($rsSession)){
			/*if($rsSession[0]["session_activa"] == "1" && $rsSession[0]["session_php_id"] != session_id()){
				//pedir confirmacion para ingresar
			}
			else{*/
				$session->setSession_activa("1");
				$session->setSession_php_id(session_id());
				$session->setSession_date(date("Y-m-d H:i:s"));
				$sessionAdo->actualizar($session);
			//}
		}
		else{
			$session->setSession_activa("1");
			$session->setSession_php_id(session_id());
			$session->setSession_date(date("Y-m-d H:i:s"));
			$sessionAdo->insertar($session);
		}
		include_once(PATH_RAIZ.'ssgroup/lib/empleado/empleadoAdo.php');
		$empleadoAdo = new EmpleadoAdo('ssgroup');
		$empleado    = new Empleado;
		$empleado->setEmpleado_usuario_id($arrUsuario[0]["usuario_id"]);
		$rsEmpleado = $empleadoAdo->listaId($empleado);
		if($rsEmpleado){
			$_SESSION['session_estado_id'] = $rsEmpleado[0]['empleado_estado_id'];
		}
		else{
			$_SESSION['session_estado_id'] = 1;
		}
		
        $_SESSION['session_usuario_id'] = $arrUsuario[0]['usuario_id'];
        $_SESSION['session_nombre'] 	= $arrUsuario[0]['usuario_pnombre'];
        $_SESSION['session_apellidos'] 	= $arrUsuario[0]['usuario_papellido'];
        $_SESSION['session_email'] 		= $arrUsuario[0]['usuario_email'];
		$_SESSION['session_root'] 		= $arrUsuario[0]['usuario_root'];
		$_SESSION['session_genero']		= $arrUsuario[0]['usuario_genero'];
		$_SESSION['session_perfil']		= $arrUsuario[0]['usuario_perfil_id'];
		$_SESSION['start'] 				= time();

		echo "{success: true}";
		exit();
    }
}
if($ac == REGISTRO) {
	require_once('recaptcha/recaptchalib.php');
	$privatekey = "6Lei2NUSAAAAAMcBjPK0REc_AVCekpBrImSRWLan";
	$resp = recaptcha_check_answer ($privatekey,
								$_SERVER["REMOTE_ADDR"],
								$_POST["recaptcha_challenge_field"],
								$_POST["recaptcha_response_field"]);

	if (!$resp->is_valid) {
		//die ("The reCAPTCHA wasn't entered correctly. Go back and try it again." ."(reCAPTCHA said: " . $resp->error . ")");
		echo "{success: false, errors: { reason: '".$resp->error."' }}";
		exit();
	} else {
	  
		include(PATH_RAIZ.'ssgroup/lib/usuario/usuarioAdo.php');
		$usuarioAdo = new UsuarioAdo('ssgroup');
		$usuario    = new Usuario;
		
		$usuario->setUsuario_id("");
		$usuario->setUsuario_tipos_identificacion_id($tipos_identificacion);
		$usuario->setUsuario_documento_ident(trim(sanear_string($regIdentificacion)));
		$result = $usuarioAdo->existeIdentification($usuario);
		if($result){
			echo '{success: false, errors: { reason: "This Identification is already registered" }}';
			exit();
		}
		
		$usuario    = new Usuario;		
		$usuario->setUsuario_id("");
		$usuario->setUsuario_email(strtoupper($regEmail));
		$result = $usuarioAdo->existeEmail($usuario);
		if($result){
			echo '{success: false, errors: { reason: "This email address is already registered" }}';
			exit();
		}
		$key  	  = md5($regEmail.$regPassword);
		
		$regPnombre = sanear_string($regPnombre);
		$regSnombre = sanear_string($regSnombre);
		$regPapellido = sanear_string($regPapellido);
		$regSapellido = sanear_string($regSapellido);
		
		$usuario->setUsuario_pnombre(strtoupper($regPnombre));
		$usuario->setUsuario_snombre(strtoupper($regSnombre));
		$usuario->setUsuario_papellido(strtoupper($regPapellido));
		$usuario->setUsuario_sapellido(strtoupper($regSapellido));
		$usuario->setUsuario_perfil_id(1);
		
		$usuario->setUsuario_finsert(date("Y-m-d H:i:s"));
		$usuario->setUsuario_uinsert('0');
		$usuario->setUsuario_email(strtoupper($regEmail));
		$usuario->setUsuario_password($regPassword);
		$usuario->setUsuario_root('0');
		$usuario->setUsuario_activo('0');
		$usuario->setUsuario_CityId(strtoupper($city));
		$usuario->setUsuario_CountryId($country);
		
		$usuario->setUsuario_CountryId2($country2);
		$usuario->setUsuario_CityId2(strtoupper($region));
		$usuario->setUsuario_SkypeId(strtoupper($skypename));
		$usuario->setUsuario_tipos_identificacion_id($tipos_identificacion);
		$usuario->setUsuario_documento_ident(trim(sanear_string($regIdentificacion)));
		$usuario->setUsuario_genero($regGenero);
		$usuario->setUsuario_fnacimiento($regFnacimiento);
		$usuario->setUsuario_activationKey($key);
		$usuario->setUsuario_reclutador_id($reclutador);
		$result = $usuarioAdo->insertar($usuario);
		if($result != ''){
			echo '{success: false, errors: { reason: "'.sanear_string($result).'" }}';
		}
		else{
			$url_activacion = URL_RAIZ."activate/".$key."/";
			$link_activacion = str_replace("http://","",$url_activacion);
			
			$message = file_get_contents(PATH_RAIZ.'email_templates/tpl_bienvenida_nuevo_candidato.html');
			
			$message = str_replace('%usuario_nombre%', ucfirst(strtolower($regPnombre)), $message);
			$message = str_replace('%url_activacion%', $url_activacion, $message);
			$message = str_replace('%link_activacion%', $link_activacion, $message);
			$message = str_replace('%url%', URL_RAIZ, $message);
			
			$asunto    = "Account Details for ".ucfirst(strtolower($regPnombre))." at The Seven Seas Group";
			$fromName  = MAIL_FROMNAME;
			$fromEmail = MAIL_FROM;
			$to		   = $regEmail;
			email($message, $asunto, $fromName, $fromEmail, $to);
			echo '{success:true}';
		}
	}
}

//Acción Logout, borra toda la información de la sesión
if($ac == LOGOUT) {
    //Destruye la sesion si existe
    session_start();
    //Guarda la sesión al usuario
	if ($_SESSION['session_usuario_id'] <> ''){
		include_once(PATH_RAIZ.'ssgroup/lib/session/sessionAdo.php');
		$sessionAdo = new SessionAdo('ssgroup');
		$session    = new Session;
		$session->setSession_usuario_id($_SESSION['session_usuario_id']);
		$session->setSession_php_id(session_id());
		$session->setSession_activa("0");
		$rsSession = $sessionAdo->logout($session);
	}
    // Destruye todas las variables de la sesión
    $_SESSION = array();
    // Finalmente, destruye la sesión
    session_destroy();
    if($_SESSION['session_usuario_id'] == "") {
        $mensaje = "The session expired, please login again.";
    } else {
        $mensaje = "Successfully closed session.";
    }
	$data = utf8_encode($mensaje);
	echo "{success: true, 'msg':'".$data."'}";
}

//En caso de no venir la acción ($ac) se redirecciona a la página de ingreso (ingreso.php)
if($ac == "") {
    echo "{success: false, errors: { reason: 'Parameters Incomplete, contact the administrator.' }}";
    exit();
}


function MailPass($email,$nombre,$newpass){	
	$asunto = "your new password at the Seven Seas Group";
	$message = file_get_contents(PATH_RAIZ.'email_templates/tpl_recordar_password.html');
	$message = str_replace('%usuario_nombre%', $nombre, $message);
	$message = str_replace('%email%', $email, $message);
	$message = str_replace('%password%', $newpass, $message);
	
	$fromName  = MAIL_FROMNAME;
	$fromEmail = MAIL_FROM;
			
	$to = $email;
	if(!email($message, $asunto, $fromName, $fromEmail, $to)) {
	  return false;
	} else {
	  return true;
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
function generaPass(){
	//Se define una cadena de caractares. Te recomiendo que uses esta.
	$cadena = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
	//Obtenemos la longitud de la cadena de caracteres
	$longitudCadena=strlen($cadena);
	//Se define la variable que va a contener la contraseña
	$pass = "";
	//Se define la longitud de la contraseña, en mi caso 10, pero puedes poner la longitud que quieras
	$longitudPass=10;
	//Creamos la contraseña
	for($i=1 ; $i<=$longitudPass ; $i++){
		//Definimos numero aleatorio entre 0 y la longitud de la cadena de caracteres-1
		$pos=rand(0,$longitudCadena-1);
		//Vamos formando la contraseña en cada iteraccion del bucle, añadiendo a la cadena $pass la letra correspondiente a la posicion $pos en la cadena de caracteres definida.
		$pass .= substr($cadena,$pos,1);
	}
	return $pass;
}

?>