<?php

set_time_limit(0);
ini_set('memory_limit', '128M');

session_start();
//error_reporting(E_ALL);
include ("../../lib/config.php");

include (PATH_APP."lib/lib_sesion.php");
include (PATH_APP."lib/idioma.php");
include (PATH_APP."lib/lib_filtros.php");
include (PATH_APP."lib/lib_funciones.php");
include (PATH_RAIZ."lib/excel.php");

/*--------------------------------------------------trae la informacion del reporte --------------------------------------------------------------------*/
include_once(PATH_RAIZ.'sicex_r/lib/reportes/reportesAdo.php');
$reportesAdo = new ReportesAdo('sicex_r');
$reportes    = new Reportes;
$reportes->setReportes_id($reporte);
$reportes->setReportes_uinsert($_SESSION['session_usuario_id']);
$reportes->setReportes_isleaf("1");
$rsReportes = $reportesAdo->lista($reportes); //como busco por ID del reporte, debe devolver solo un registro

$intercambio = $rsReportes[0]["reportes_intercambio"];
$pais_id	 = $rsReportes[0]["reportes_pais_id"];
$producto	 = $rsReportes[0]["reportes_producto_id"];
$campos		 = $rsReportes[0]['reportes_campos'];
$filtros     = $rsReportes[0]['reportes_filtros'];

//print_r($campos);
/*--------------------------------------------------fin la informacion del reporte --------------------------------------------------------------------*/

/*--------------------------------------------------trae la informacion del pais --------------------------------------------------------------------*/
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

$_camposIntercambio = $camposIntercambioSisduan[$intercambio];

/*-------------------------------------------------- fin trae la informacion del pais --------------------------------------------------------------------*/

/*-------------------------------------------------- busca el reporte del usuario_tpl, si existe --------------------------------------------------------------------*/

if(isset($_SESSION['usuario_tpl']) && $_SESSION['usuario_tpl'] != ""){ 
	$reportes = new Reportes;
	$reportes->setReportes_producto_id($producto);
	$reportes->setReportes_uinsert($_SESSION['usuario_tpl']);
	$reportes->setReportes_pais_id($pais_id);
	$reportes->setReportes_intercambio($intercambio);
	$rsReportesTpl = $reportesAdo->lista($reportes);
	
	if($rsReportesTpl){
		//elimina los campos no permitidos del reporte
		$_arr = explode("||",$campos);
		$_arr_tpl = explode("||",$rsReportesTpl[0]['reportes_campos']);
		$_arr2 = array_intersect($_arr,$_arr_tpl);
		$campos = implode("||",$_arr2);
		
		$_arr2 = array();
		$_arr_tpl = explode("||",$rsReportesTpl[0]['reportes_filtros']);
		foreach($_arr_tpl as $key => $data){
			$filtro_tpl = explode(':', $data);
			//descarta los filtros de fecha
			if($filtro_tpl[0] != FILTRO_ANIO && $filtro_tpl[0] != FILTRO_PERIODODESDE && $filtro_tpl[0] != FILTRO_PERIODOHASTA){
				$_arr2[$filtro_tpl[0]] = $filtro_tpl[1];
			}
		}	
		$filtros_tpl = filtrosAArray($_arr2);
		
		
	}
}
/*-------------------------------------------------- fin busca el reporte del usuario_tpl, si existe --------------------------------------------------------------------*/

/*-------------------------------------------------- extrae la informacion de los campos para la consulta --------------------------------------------------------------------*/
//provisional por cambio en el nombre de los campos**************
$_camposIntercambio = $camposIntercambioSisduan[$intercambio];	//*
$tmp_campos = array();											//*
foreach($_camposIntercambio as $key => $data){					//*
	$tmp_campos[]=$data["campo"].":".$data["nombre"];			//*
}																//*
$campos = implode("||",$tmp_campos);							//*
//*****************************************************************
$_arrCampos = convierteArreglo($campos);
$origCampo  = array();
$arrCampos  = array();
$arrTitulos = array();
$arrTipos   = array();
	
foreach($_arrCampos as $campo => $titulo) {
	$origCampo = campoDatos($_camposIntercambio, $campo);
	$arrTitulos[] = $titulo;
	
	/*if($origCampo['alias'] == ''){
		$alias = str_replace("decl.","",$campo);
		$alias = str_replace("dir.","",$alias);
		$alias = str_replace("aran.","",$alias);
		$alias = str_replace("dirprove.","",$alias);
	}
	else{
		$alias = $origCampo['alias'];
	}
	
	$arrCampos[] = $campo . " AS " . $alias;*/
	
	if($origCampo['alias'] == ''){
		//$arrCampos[] = $campo;
		$alias = str_replace("decl.","",$campo);
	}
	else{
		$alias = $origCampo['alias'];
	}
	if(isset($sort) && $sort == $alias){
		$sort = $origCampo['key'] != ""?$origCampo['key']:$alias;
	}
	if(isset($groupBy) && $groupBy == $alias){
		$sort = $origCampo['key'] != ""?$origCampo['key']:$alias;
	}
	
	$arrCampos[] = $campo . " AS " . $alias;
	
}
/*-------------------------------------------------- extrae la informacion de los filtros y la tabla para la consulta --------------------------------------------------------------------*/
$_filtros = convierteArreglo($filtros);
$filtrosArr = filtrosAArray($_filtros);
$filtrosSql = filtros(false, $filtrosArr);

foreach($_filtros as $key => $data){
	if($key == FILTRO_ANIO){
		$anio = $data;
	}
}
$tabla       = ($intercambio == 0) ? "declaraimp" : "declaraexp";
$tabla      .= $anio. " AS decl";

/*-------------------------------------------------- fin extrae la informacion de los filtros para la consulta --------------------------------------------------------------------*/

include_once(PATH_RAIZ.'sisduan/lib/declaraciones/declaracionesAdo.php');
$declaracionesAdo = new DeclaracionesAdo($db);


if(isset($accion)){
	switch ($accion){
		case 'lista':
			$arr = array();
			$start = (isset($start))?$start:0;
			$limit = (isset($limit))?$limit:30;
			
			$page = ($start==0)?1:($start/$limit)+1;
			
			$limit = $page . ", " . $limit;
			
			
			//busca el campo por el cual debe ordenar, si existe un parametro de ordenacion			
			$orderby = (isset($sort))?$sort." ".$dir:"";
			//$groupBy = (isset($groupBy))?$groupBy." ".$groupDir:"";
			//print_r($arrCampos);
			$result   = $declaracionesAdo->lista($db, $intercambio, $tabla, $arrCampos, $filtrosSql, $limit, $orderby);
			
			//print_r($result);
			
			foreach($result["datos"] as $key => $data){
				//print_r($data);
				$arr[] = filtro_grid($data);
			}
			
			
			if(isset($formato)){
				$head   = json_decode(stripslashes($fields));
				$total = _TOTALREGISTROS . ": ". $total;
				$result = $reporte."_".time();
				$archivo = CreaExcel($arr, $formato, $head, $total, $result);
				echo '{success: true, msg:'.json_encode($archivo).'}';
		   	}
		   	else{
				$data = json_encode($arr); 
				print('{"total":"'.$result["total"].'", "datos":'.$data.'}');
			}
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
