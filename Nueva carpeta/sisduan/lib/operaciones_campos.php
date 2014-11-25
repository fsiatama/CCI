<?php
session_start();
//error_reporting(E_ALL);
include ("../../lib/config.php");

include (PATH_APP."lib/lib_sesion.php");
include (PATH_APP."lib/lib_sphinx.php");
include (PATH_APP."lib/lib_funciones.php");

//Incluye el diccionario
include (PATH_APP."lib/idioma.php");

include_once(PATH_RAIZ.'sicex_r/lib/pais/paisAdo.php');
$paisAdo = new PaisAdo('sicex_r');
$pais    = new Pais;
$pais->setPais_id($pais_id);
$paisDs  = $paisAdo->lista($pais);
$db = strtolower($paisDs[0]["pais_bd"]);

if($db != ""){	
	if(file_exists(PATH_RAIZ."lib/".$db.".php")){
		include (PATH_RAIZ."lib/".$db.".php");
	}
	else{
		$respuesta = array(
			"success"=>false,
			"errors"=>array("reason"=>"No existe configurtaci�n para el pais")
		);
		echo json_encode($respuesta);
		exit();
	}
}
else{
	$respuesta = array(
		"success"=>false,
		"errors"=>array("reason"=>"No tiene asignado pa�s de consulta")
	);
	echo json_encode($respuesta);
	exit();
}

if(isset($accion)){
	switch ($accion){
		case 'listaDisponibles':
			$arr = array();
			$_camposIntercambio = $camposIntercambioSisduan[$intercambio];
			if(!empty($_SESSION['usuario_tpl'])){	
				$_camposIntercambio = orig_campos_reporte_usuario_tpl($_SESSION['usuario_tpl'],2,$pais_id,$intercambio,$_camposIntercambio);
			}
			foreach($_camposIntercambio as $key => $campo){
				if($acumulado == "1"){
					if($campo['enacumulado'] == 1){
						$arr[] = array(
							'campos_id'=>$campo['campo']
							,'campos_order'=>$key
							,'campos_nombre'=>utf8_encode(traducir($campo['nombre']))
						);
					}
				}
				else{
					$arr[] = array(
						'campos_id'=>$campo['campo']
						,'campos_order'=>$key
						,'campos_nombre'=>utf8_encode(traducir($campo['nombre']))
					);
				}
			}
			
			$data = json_encode($arr); 
			print('{"total":"'.count($arr).'", "datos":'.$data.'}');
			exit();
		break;
		case 'lista_directorio':
			$arr = array();		
			foreach($camposDirectorio as $key => $campo){
				$arr[] = array(
					'campos_id'=>$campo['campo']
					,'campos_order'=>$key
					,'campos_nombre'=>utf8_encode(traducir($campo['nombre']))
				);
			}			
			$data = json_encode($arr); 
			print('{"total":"'.count($arr).'", "datos":'.$data.'}');
			exit();
		break;
	}
}
function filtro_grid($contenido){
  $contenido = str_replace('�','', $contenido);
  $contenido = str_replace('�','a', $contenido);
  $contenido = str_replace('�','e', $contenido);
  $contenido = str_replace('�','i', $contenido);
  $contenido = str_replace('�','o', $contenido);
  $contenido = str_replace('�','u', $contenido);
  $contenido = str_replace('�','n', $contenido);
  $contenido = str_replace('�','A', $contenido);
  $contenido = str_replace('�','E', $contenido);
  $contenido = str_replace('�','I', $contenido);
  $contenido = str_replace('�','O', $contenido);
  $contenido = str_replace('�','U', $contenido);
  $contenido = str_replace('�','N', $contenido);
  return $contenido;
}
?>
