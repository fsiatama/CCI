<?php
//error_reporting(E_ALL);
//ini_set("display_errors", true);
set_time_limit(0);
ini_set('memory_limit', '2048M');

session_start();

include ("../../lib/config.php");
include (PATH_APP."lib/idioma.php");
include (PATH_APP."lib/lib_sesion.php");
include (PATH_APP."lib/lib_sphinx.php");
include (PATH_APP."lib/lib_funciones.php");
include (PATH_RAIZ."lib/php_excel.php"); //en windows no funciona


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
/*****************************************************************************************************************************************************/

include_once(PATH_RAIZ.'sisduan/lib/declaraciones/declaracionesAdo.php');

$filasArr     = array();
$filasArr[] = "id";
$filasArr[] = "decl.ano AS ano";

$filtros_adicionales = json_decode(stripslashes($filtros_adicionales), true);
$filtros_impo = array();
$filtros_expo = array();
foreach($filtros_adicionales as $key => $data){
	foreach($data as $subkey => $subdata){
		if($subkey == FILTRO_PAISORIGEN){
			$filtros_impo[FILTRO_PAISORIGEN] = $subdata;
			$filtros_expo[FILTRO_PAISDESTINO] = $subdata;
		}
		else{
			$filtros_impo[$subkey] = $subdata;
			$filtros_expo[$subkey] = $subdata;
		}
	}
}

/*****************************************consulta Impo************************************************************************************************/

$filtros_sql = filtrosAArray($filtros_impo, $db);
$filtrosSql  = filtros($filtros_sql);

$totalesArr = array();
$origCampo = campoDatos($camposIntercambioSisduan[0], "valorfob");
if($origCampo === false){
	$origCampo = campoDatos($camposIntercambioSisduan[0], "valorcif");
	if($origCampo === false){
		$respuesta = array(
			"success"=>false,
			"errors"=>array("reason"=>"No existe campo de totales para el pais en impo")
		);
		echo json_encode($respuesta);
		exit();		
	}
}

$totalesArr[] = array("campo"=>$origCampo["campo"], "alias"=>"valor_impo", "nombre"=>sanear_string($origCampo["nombre"]), "tipo"=>$origCampo["tipo"]);

$declaracionesAdo = new DeclaracionesAdo($db);
$tabla = "acumulado_impo AS decl";
$rs_impo = $declaracionesAdo->pivot($db, 0, $tabla, implode(",",$filasArr), array(), $totalesArr, $filtrosSql, "", "", false, "");
if(!$rs_impo || $rs_impo["total"] == 0){
	echo '{success: false, msg:"'.sanear_string(_NOSEENCONTRARONREGISTROS).' Impo"}';
	exit();
}

/*****************************************consulta Expo************************************************************************************************/

$filtros_sql = filtrosAArray($filtros_expo, $db);
$filtrosSql  = filtros($filtros_sql);

$totalesArr = array();
$origCampo = campoDatos($camposIntercambioSisduan[1], "valorfob");
if($origCampo === false){
	$origCampo = campoDatos($camposIntercambioSisduan[1], "valorcif");
	if($origCampo === false){
		$respuesta = array(
			"success"=>false,
			"errors"=>array("reason"=>"No existe campo de totales para el pais en expo")
		);
		echo json_encode($respuesta);
		exit();
	}
}

$totalesArr[] = array("campo"=>$origCampo["campo"], "alias"=>"valor_expo", "nombre"=>sanear_string($origCampo["nombre"]), "tipo"=>$origCampo["tipo"]);

$totalesGraficoArr[] = array("campo"=>$origCampo["campo"], "alias"=>"valor_balanza", "nombre"=>sanear_string($origCampo["nombre"]), "tipo"=>$origCampo["tipo"]);

$declaracionesAdo = new DeclaracionesAdo($db);
$tabla = "acumulado_expo AS decl";
$rs_expo = $declaracionesAdo->pivot($db, 1, $tabla, implode(",",$filasArr), array(), $totalesArr, $filtrosSql, "", "", false, "");
if(!$rs_expo || $rs_expo["total"] == 0){
	echo '{success: false, msg:"'.sanear_string(_NOSEENCONTRARONREGISTROS).' Expo"}';
	exit();
}

/*****************************************************************************************************************************************************/

$anos_impo = array();
$anos_expo = array();
$arr = array();
$arr_grafico = array();
foreach($rs_expo["datos"] as $i => $data_expo){
	$anos_expo[] = $data_expo["ano"];
	foreach($rs_impo["datos"] as $j => $data_impo){
		$anos_impo[] = $data_impo["ano"];
		if($data_expo["ano"] == $data_impo["ano"]){
			$arr[] = array("ano"=>$data_expo["ano"], "valor_expo"=>$data_expo["valor_expo"], "valor_impo"=>$data_impo["valor_impo"], "valor_balanza"=>($data_expo["valor_expo"] - $data_impo["valor_impo"]));
			$arr_grafico[] = array("ano"=>$data_expo["ano"], "valor_balanza"=>($data_expo["valor_expo"] - $data_impo["valor_impo"]));
		}
	}
}

$columns = array();
$columns[] = array("type"=>"string", "col"=>"ano");
$columns[] = array("type"=>"real", "col"=>"valor_expo");
$columns[] = array("type"=>"real", "col"=>"valor_impo");
$columns[] = array("type"=>"rate", "col"=>"valor_balanza");


//print_r($rs_expo["columns"]);
//print_r($arr);
$sort = (isset($sort))?$sort:"ano";
$dir = (isset($dir))?$dir:"ASC";


if($dir == "ASC"){
	usort($arr, function($a, $b){
		global $sort;
		if($a[$sort]==$b[$sort]) return 0;
		return $a[$sort] > $b[$sort]?1:-1;
	});
}
else{
	usort($arr, function($a, $b){
		global $sort;
		if($a[$sort]==$b[$sort]) return 0;
		return $a[$sort] < $b[$sort]?1:-1;
	});
}
usort($arr_grafico, function($a, $b) {
	if($a["ano"]==$b["ano"]) return 0;
	return $a["ano"] > $b["ano"]?1:-1;
});
$json_grafico = json_graficos($arr_grafico, "ano", array("ano"), $totalesGraficoArr, AREA);

if(isset($formato)){
	$head   = json_decode(stripslashes($fields), true);
	$total = _TOTALREGISTROS . ": ". count($arr);
	$nombreXls = "Balanza_".time();
	$archivo = CreaExcel($arr, $formato, $head, $total, $nombreXls, $columns);
	echo '{success: true, msg:'.json_encode($archivo).'}';
}
else{
	$data = json_encode($arr);	
	print('{
		"total":"'.count($arr).'"
		,"datos":'.$data.'
		,"json_grafico":'.$json_grafico.'
		,"success":true
	}');
}
exit();

?>
