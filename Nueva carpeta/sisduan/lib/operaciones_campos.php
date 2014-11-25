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
			"errors"=>array("reason"=>"No existe configurtación para el pais")
		);
		echo json_encode($respuesta);
		exit();
	}
}
else{
	$respuesta = array(
		"success"=>false,
		"errors"=>array("reason"=>"No tiene asignado país de consulta")
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
