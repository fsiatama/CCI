<?php
//Trae la sesión que esté asignada
session_start();
//ini_set("display_errors", 1);
//Variables de configuración del sistema
include_once("../../lib/config.php");
include_once(PATH_APP."lib/lib_sesion.php");
include_once(PATH_APP."lib/lib_sphinx.php");
include_once(PATH_APP."lib/lib_funciones.php");

//Incluye el diccionario
include_once (PATH_APP."lib/idioma.php");

//trae la base de datos del pais seleccionado
include_once(PATH_RAIZ.'sicex_r/lib/pais/paisAdo.php');
$paisAdo = new PaisAdo('sicex_r');
$pais    = new Pais;
$pais->setPais_id($paisId);
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
		case 'lista':
			//print_r($filtrosIntercambioSisduan[$intercambio]);
			foreach($filtrosIntercambioSisduan[$intercambio] as $key => $filtro){
				//print $filtro['filtro'] ."==". $filtroId;
				if($filtro['filtro'] == $filtroId){		
					$filtro_tabla  = trim($filtro['tabla']);
				}
			}
		break;
		case 'lista_directorio':
			foreach($filtrosDirectorio as $key => $filtro){
				//print $filtro['filtro'] ."==". $filtroId;
				if($filtro['filtro'] == $filtroId){		
					$filtro_tabla  = trim($filtro['tabla']);
				}
			}
		break;
	}
}

//print $filtro_tabla;
if($filtro_tabla == ""){
	echo '{success: false, msg:"No esta definida la tabla del filtro"}';
	exit();
}

//id de los filtros con traduccion, es decir que cambian con el idioma selecionado por el usuario
//por ejemplo el filtro de la tabla arancel trae la descripcion en ingles y en español
$filtros_con_traduccion = array(412 /*,411*/ );

$logica = (in_array($filtroId,$filtros_con_traduccion) && $_SESSION['session_idioma_id'] != 1 )?true:false;

if($valuesqry){ //si viene una lista de valores para seleccionar
	$query = explode("|",$query);
}
else{ //se trata de una busqueda textual o completa del usuario
	if(isset($_SESSION['usuario_tpl']) && $_SESSION['usuario_tpl'] != ""){
		$arr_reporte_tpl = buscar_reporte_usuario_tpl($_SESSION['usuario_tpl'],2,$paisId,$intercambio);
		if($arr_reporte_tpl != false){
			$_filtros = convierteArreglo($arr_reporte_tpl["reportes_filtros"]);
			if (array_key_exists($filtroId, $_filtros)){
				$arr_query = explode(",",$_filtros[$filtroId]);
				$arr_query[] = $query;
				$query = $arr_query;
				//print_r($arr_query);
			}
		}
	}
}
//print_r($objAdo);
$arr_selected = false;
if($selected != ""){
	$arr_selected = explode(",",$selected);
}
$arr = array();
foreach($arr_selected as $key => $data){
	$arr[] = "'".$data."'";
}
$arr_selected = $arr;
$start = (isset($start))?$start:0;
$limit = (isset($limit))?$limit:MAXREGEXCEL;
$page = ($start==0)?1:($start/$limit)+1;
$limit = $page . ", " . $limit;

$objAdo = Factory::fabrica($filtro_tabla, $db);
$result = $objAdo->lista_filtro($query, $valuesqry, $limit, $arr_selected);
//print_r($result);
$arr = array();
if($result){
	/*if($result["total"] == 0){
		echo '{success: false, msg:"'.sanear_string(_NOSEENCONTRARONREGISTROS).'"}';
		exit();
	}*/
	
	foreach($result["datos"] as $key => $data){	
		if($logica){
			$descripcion = resaltar(strtoupper($query),sanear_string($data[2]));
			$descripcion_ori = sanear_string($data[2]);
		}
		else{
			$descripcion = resaltar(strtoupper($query),sanear_string($data[1]));
			$descripcion_ori = sanear_string($data[1]);
		}
		$arr[] = array(
			'valor_id'=>$data[0]
			,'valor_desc'=>$descripcion
			,'valor_desc_ori'=>$descripcion_ori
		);
	}
	$data = json_encode($arr);
	$total = $result["total"];
	print('{"total":"'.$total.'", "datos":'.$data.'}');
	exit();
}
else{
	echo '{success: false, msg:"'.sanear_string(_NOSEENCONTRARONREGISTROS).'"}';
	exit();
}


class Factory{
    // El método de fábrica parametrizada
    public static function fabrica($tipo, $db){
        if (include_once PATH_RAIZ.'sisduan/lib/'.$tipo.'/'.$tipo.'Ado.php'){
			$nombreclase = ucfirst($tipo)."Ado";
			return new $nombreclase($db);
        } else {
            throw new Exception('Driver no encontrado');
        }
    }
}

