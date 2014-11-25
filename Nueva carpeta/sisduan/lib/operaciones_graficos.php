<?php
session_start();
include ("../../lib/config.php");

include (PATH_APP."lib/lib_sesion.php");
include (PATH_APP."lib/lib_sphinx.php");
include (PATH_APP."lib/lib_funciones.php");

//Incluye el diccionario
include (PATH_APP."lib/idioma.php");

/*$datos = json_decode($datos,true);
$filas = json_decode($filas,true);

$arr_datos = array();

foreach($datos as $key => $data){
	print_r
}*/

//print_r(json_decode($columnas,true));

print '
{
	"success":true,
    "data": '.$datos.'
}
';

exit;


//error_reporting(E_ALL);


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
		foreach($camposIntercambioSisduan[$intercambio] as $key => $campo){
			//if($filtro['opcional'] == 1){
				$arr[] = array(
					'campos_id'=>$campo['campo']
					,'campos_order'=>$key
					,'campos_nombre'=>utf8_encode(traducir($campo['nombre']))
				);
			//}
		}
		
		$data = json_encode($arr); 
		print('{"total":"'.count($arr).'", "datos":'.$data.'}');
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
