<?php
session_start();


include ("../../lib/config.php");
include_once(PATH_APP."lib/idioma.php");
include_once(PATH_APP."lib/lib_sesion.php");
include_once(PATH_APP."lib/lib_funciones.php");


if(isset($_SESSION["estadisticas"][$pais_id])){
	echo $_SESSION["estadisticas"][$pais_id];
	exit();
}



$idm = "en";
if($_GET['cambiaidioma'] == "ES"){
	$idm = "es";
}

include_once(PATH_RAIZ.'sicex_r/lib/pais/paisAdo.php');
$paisAdo = new PaisAdo('sicex_r');
$pais    = new Pais;
$pais->setPais_id($pais_id);
$paisDs  = $paisAdo->lista($pais);
$pais_iso = $paisDs[0]["pais_uupdate"];

$year_fin = date("Y") - 1;
$year_ini = $year_fin - 5;

$url_balanza = "http://api.worldbank.org/".$idm."/countries/".$pais_iso."/indicators/NE.RSB.GNFS.ZS?date=".$year_ini.":".$year_fin."&format=json";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url_balanza);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
$resultado_balanza = json_decode(curl_exec($ch), true);

$tit_balanza = "";
$arr_balanza = array();
$totalesGraficoArr = array();
//print_r($resultado_balanza);
foreach($resultado_balanza as $key => $data){	
	if(is_array($data)){
		foreach($data as $i => $row){
			if(is_array($row)){
				$tit_balanza = $row["indicator"]["value"];
				if($row["value"]){
					$arr_balanza[] = array("date"=>$row["date"], "value"=>$row["value"]);
				}
			}
		}
		//print_r($data);
	}
}
$arr_balanza = array_reverse($arr_balanza);
$totalesGraficoArr[] = array("campo"=>"value", "alias"=>"value", "nombre"=>sanear_string($tit_balanza), "tipo"=>"n");

$json_grafico = json_graficos($arr_balanza, "date", array("date"), $totalesGraficoArr, AREA);

$result = array(
	array("titulo"=>$tit_balanza),
	array("datos"=>$arr_balanza)
);
$estadisticas = '{
	"titulo":"'.($tit_balanza).'"
	,"json_grafico":'.$json_grafico.'
	,"datos":'.json_encode($arr_balanza).
'}';

$_SESSION["estadisticas"][$pais_id] = $estadisticas;
echo $estadisticas;
//print "'".json_encode($result)."'";
exit();

?>
