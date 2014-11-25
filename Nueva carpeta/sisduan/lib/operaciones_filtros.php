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
			"errors"=>array("reason"=>"No existe configuracion para el pais")
		);
		echo json_encode($respuesta);
		exit();
	}
}
else{
	$respuesta = array(
		"success"=>false,
		"errors"=>array("reason"=>"No tiene asignado pais de consulta")
	);
	echo json_encode($respuesta);
	exit();
}

if(isset($accion)){
	switch ($accion){
		case 'lista':
			$arr = array();		
			foreach($filtrosIntercambioSisduan[$intercambio] as $key => $filtro){
				if($filtro['opcional'] == 1){
					if($acumulado == "1"){
						if($filtro['enacumulado'] == 1){
							$arr[] = array(
								'filtros_id'=>$filtro['filtro']
								,'filtros_order'=>$key
								,'filtros_nombre'=>utf8_encode(traducir($filtro['nombre']))
							);
						}
					}
					else{
						$arr[] = array(
							'filtros_id'=>$filtro['filtro']
							,'filtros_order'=>$key
							,'filtros_nombre'=>utf8_encode(traducir($filtro['nombre']))
						);
					}
				}
			}
			
			$data = json_encode($arr); 
			print('{"total":"'.count($arr).'", "datos":'.$data.'}');
			exit();
		break;
		case 'lista_directorio':
			$arr = array();
			if(!isset($camposDirectorio) || !isset($filtrosDirectorio)){
				$respuesta = array(
					"success"=>false,
					"errors"=>array("reason"=>"No existe configurtaci�n para el directorio del pais")
				);
				echo json_encode($respuesta);
				exit();
			}
			foreach($filtrosDirectorio as $key => $filtro){
				$arr[] = array(
					'filtros_id'=>$filtro['filtro']
					,'filtros_order'=>$key
					,'filtros_nombre'=>utf8_encode(traducir($filtro['nombre']))
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
