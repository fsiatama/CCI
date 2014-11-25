<?php
/*error_reporting(E_ALL);
ini_set("display_errors", true);*/
set_time_limit(0);
ini_set('memory_limit', '2048M');

session_start();

include ("../../lib/config.php");
include_once(PATH_APP."lib/idioma.php");
include_once(PATH_APP."lib/lib_sesion.php");
include_once(PATH_APP."lib/lib_sphinx.php");
include_once(PATH_APP."lib/lib_funciones.php");
include_once(PATH_RAIZ."lib/php_excel.php"); //en windows no funciona
$periodo = json_decode($periodo, true);
/*--------------------------------------------------trae la informacion del reporte --------------------------------------------------------------------*/
include_once(PATH_RAIZ.'sicex_r/lib/reportes/reportesAdo.php');
$reportesAdo = new ReportesAdo('sicex_r');
$reportes    = new Reportes;
$reportes->setReportes_id($reporte);
//$reportes->setReportes_uinsert($_SESSION['session_usuario_id']);
$reportes->setReportes_isleaf("1");
$rsReportes = $reportesAdo->lista($reportes); //como busco por ID del reporte, debe devolver solo un registro

$intercambio = $rsReportes[0]["reportes_intercambio"];
$pais_id	 = $rsReportes[0]["reportes_pais_id"];
$producto	 = $rsReportes[0]["reportes_producto_id"];
$campos_rep	 = $rsReportes[0]['reportes_campos'];
$filtros     = $rsReportes[0]['reportes_filtros'];
$acumulado   = $rsReportes[0]['reportes_acumulado'];
$detalle     = $rsReportes[0]['reportes_detalle'];
$reportes_nombre = $rsReportes[0]['reportes_nombre'];

include_once(PATH_RAIZ.'sicex_r/lib/infotabla/infotablaAdo.php');
$infotablaAdo = new InfotablaAdo('sicex_r');
$infotabla    = new Infotabla;
$infotabla->setInfotabla_producto_id($producto);
$infotabla->setInfotabla_intercambio($intercambio);
$infotabla->setInfotabla_pais_id($pais_id);
$rs_infotabla = $infotablaAdo->lista_periodo($infotabla);
$arr_periodo_consulta = $rs_infotabla["datos"];
if(isset($filtros_adicionales) && $filtros_adicionales != ""){
	if(!isset($_SESSION["filtros_adicionales"][$reporte]) || ($_SESSION["filtros_adicionales"][$reporte] != $filtros_adicionales)){
		$_SESSION["filtros_adicionales"][$reporte] = $filtros_adicionales;
		unset($_SESSION["reporte_detallado"][$reporte]);
		unset($_SESSION["reporte_pivot"][$reporte]);
	}
	$arr_filtros_adi = explode(",",$filtros_adicionales);
	$filtros .= "||".implode("||",$arr_filtros_adi);
}
elseif(isset($_SESSION["filtros_adicionales"][$reporte])){
	unset($_SESSION["reporte_detallado"][$reporte]);
	unset($_SESSION["reporte_pivot"][$reporte]);
}
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
		include_once(PATH_RAIZ."lib/".$db.".php");
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

/*-------------------------------------------------- fin trae la informacion del pais --------------------------------------------------------------------*/

/*-------------------------------------------------- extrae la informacion de los campos para la consulta --------------------------------------------------------------------*/
$_camposIntercambio = $camposIntercambioSisduan[$intercambio];
$tmp_campos = array();
$tablas_alias = array();
foreach($tablasAuxAcum[$intercambio] as $tabla){
	$arr = explode(" AS ",$tabla["tabla"]);
	if(count($arr) > 1){
		$tablas_alias[] = $arr[1];
	}
	else{
		$tablas_alias[] = $arr[0];
	}
}

foreach($_camposIntercambio as $key => $data){
	if($acumulado == "1"){
		if($data['enacumulado'] == 1){
			$arr = explode(".",$data["campo"]);
			if(in_array($arr[0],$tablas_alias)){
				$tmp_campos[] = $data["campo"].":".$data["nombre"];
			}
			else{
				$tmp_campos[] = "decl.".$arr[1].":".$data["nombre"];
			}
		}
	}
	else{
		$tmp_campos[]=$data["campo"].":".$data["nombre"];
	}
}
$campos = implode("||",$tmp_campos);
//si el reporte es acumulado y no existe el parametro filas, quiere decir que la peticion viene del boton de descarga directa
//por lo tanto se configura la tabla dinamica con la configuracion almacenada en el reporte
$directo_excel = false;
if($acumulado == "1" && !isset($filas)){
	$arr_filas    = ($rsReportes[0]["reportes_filas"] == "")?array():explode("||",$rsReportes[0]["reportes_filas"]);
	$arr_columnas = ($rsReportes[0]["reportes_columnas"] == "")?array():explode("||",$rsReportes[0]["reportes_columnas"]);
	$arr_totales  = ($rsReportes[0]["reportes_totales"] == "")?array():explode("||",$rsReportes[0]["reportes_totales"]);
	$arr_tmp = array();
	$arr_fields = array(); //para los campos del excel
	foreach($arr_filas as $key => $data){
		$origCampo = campoDatos($_camposIntercambio, $data);
		if($origCampo){
			if($origCampo['alias'] == ''){
				$aux = explode(".",$data);
				$prefijo = $aux[0];
				$sufijo  = $aux[1];
				$alias = $sufijo;
			}
			else{
				$alias = $origCampo['alias'];
			}
			$arr_tmp[] = array("id"=>$data, "alias"=>$alias, "key"=>$origCampo["key"]);
			$arr_fields[$alias] = utf8_encode($origCampo["nombre"]);
		}
	}
	if(empty($arr_tmp)){
		$respuesta = array(
			"success"=>false,
			"errors"=>array("reason"=>"No existe configurtación para el pais")
		);
		echo json_encode($respuesta);
		exit();
	}
	$filas = json_encode($arr_tmp);
	$directo_excel = true;
	
	$arr_tmp = array();
	foreach($arr_totales as $key => $data){
		$origCampo = campoDatos($_camposIntercambio, $data);
		if($origCampo){
			if($origCampo['alias'] == ''){
				$aux = explode(".",$data);
				$prefijo = $aux[0];
				$sufijo  = $aux[1];
				$alias = $sufijo;
			}
			else{
				$alias = $origCampo['alias'];
			}
			$arr_tmp[] = array("id"=>$data, "alias"=>$alias);
			$arr_fields[$alias] = utf8_encode($origCampo["nombre"]);
		}
	}
	$totales = json_encode($arr_tmp);
	
	$arr_tmp = array();
	foreach($arr_columnas as $key => $data){
		$origCampo = campoDatos($_camposIntercambio, $data);
		if($origCampo){
			if($origCampo['alias'] == ''){
				$aux = explode(".",$data);
				$prefijo = $aux[0];
				$sufijo  = $aux[1];
				$alias = $sufijo;
			}
			else{
				$alias = $origCampo['alias'];
			}
			$arr_tmp[] = array("id"=>$data, "alias"=>$alias);
		}
	}
	$columnas = json_encode($arr_tmp);
}
unset($rsReportes);//liberar memoria
//******************************************************************************************************/


/*-------------------------------------------------- busca el reporte del usuario_tpl, si existe --------------------------------------------------------------------*/
$filtros_tpl = "";
if(isset($_SESSION['usuario_tpl']) && $_SESSION['usuario_tpl'] != ""){
	$arr_reporte_tpl = buscar_reporte_usuario_tpl($_SESSION['usuario_tpl'],$producto,$pais_id,$intercambio);
	if($arr_reporte_tpl != false){
		//elimina los campos no permitidos del reporte
		$_arr = explode("||",$campos);
		$_arr_tpl = explode("||",$arr_reporte_tpl['reportes_campos']);
		$_arr_tmp = array();
		$_arr_tmp2 = array();
		foreach($_arr as $key => $data){
			$arr_tmp_campo = explode(":",$data);
			$_arr_tmp[] = $arr_tmp_campo[0]; //solo el nombre de la base de datos
			$_arr_tmp2[$arr_tmp_campo[0]] = $arr_tmp_campo[1];
		}
		$_arr = $_arr_tmp;
		
		$_arr_tmp = array();
		foreach($_arr_tpl as $key => $data){
			$arr_tmp_campo = explode(":",$data);
			$_arr_tmp[] = $arr_tmp_campo[0]; //solo el nombre de la base de datos
		}
		$_arr_tpl = $_arr_tmp;
		
		$_arr_tmp = array_intersect($_arr,$_arr_tpl);
		$_arr2 = array();
		foreach($_arr_tmp as $key => $data){
			$_arr2[] = $data.":".$_arr_tmp2[$data];
		}
		$campos = implode("||",$_arr2);
		
		$_arr2 = array();
		$_arr_tpl = explode("||",$arr_reporte_tpl['reportes_filtros']);
		foreach($_arr_tpl as $key => $data){
			$filtro_tpl = explode(':', $data);
			//descarta los filtros de fecha
			if($filtro_tpl[0] != FILTRO_ANIO && $filtro_tpl[0] != FILTRO_PERIODODESDE && $filtro_tpl[0] != FILTRO_PERIODOHASTA){
				$_arr2[] = $filtro_tpl[0].":".$filtro_tpl[1];
			}
		}
		if(!empty($_arr2)){
			$filtros_tpl = implode("||",$_arr2);
		}
	}
}
/*-------------------------------------------------- fin busca el reporte del usuario_tpl, si existe --------------------------------------------------------------------*/

$_arrCampos = convierteArreglo($campos);
$origCampo  = array();
$arrCampos  = array();
$arrTipos   = array();
$campos_totales = $agruparIntercambioSisduan[$intercambio][_TOTALES];

//print_r($campos_totales);

foreach($_arrCampos as $campo => $titulo) {
	$origCampo = campoDatos($_camposIntercambio, $campo);
	
	//asigna un alias para todos los campos
	if($origCampo['alias'] == ''){
		$aux = explode(".",$campo);
		$prefijo = $aux[0];
		$sufijo  = $aux[1];
		$alias = $sufijo;
	}
	else{
		$alias = $origCampo['alias'];
	}
	
	//asigna el ordenamiento
	/*if(isset($sort) && $sort == $alias){
		$sort = $origCampo['key'] != ""?$origCampo['key']:$alias;
	}*/
	
	//si el usuario esta agrupando, aqui se asigna el campo por el cual debe agrupar
	if(isset($groupBy) && $groupBy == $alias){
		//print "group = ". $groupBy;
		$groupByKey = $origCampo['key'] != ""?$origCampo['key']:$alias;
	}
	
	//si existe agrupamiento, debe sumar los campos de totales
	if($groupBy != ""){
		if(in_array($campo, $campos_totales)){
			$arrCampos[] = "SUM(".$campo.")". " AS " . $alias;
		}
		elseif($groupBy == $alias){
			$arrCampos[] = $campo . " AS " . $alias;
		}
		else{
			$arrCampos[] = "NULL AS " . $alias;
		}
	}
	else{
		$arrCampos[] = $campo. " AS " . $alias;
	}
}
/*-------------------------------------------------- extrae la informacion de los filtros y la tabla para la consulta --------------------------------------------------------------------*/
$_filtros = convierteArreglo($filtros);
//print_r($_filtros);
//exit();
$filtrosSql = array();


$query_ori = $query; //texto de busqueda original para resaltar
//si el parametro fields y query estan definidos debe realizar la busqueda textual
if(isset($campos_full_text) && isset($query) && strlen($query) > 2){
	$campos_busqueda = json_decode(stripslashes($campos_full_text));
	$campos_full_text = implode(",",$campos_busqueda);
	$query  = sanear_string($query);
	
	if(!isset($_SESSION["query"][$reporte]) || ($_SESSION["query"][$reporte] != $query)){
		$_SESSION["query"][$reporte] = $query;
		unset($_SESSION["reporte_detallado"][$reporte]);
		unset($_SESSION["reporte_pivot"][$reporte]);
	}
	//$query  = "@(".$fields.") \"^".$query."*\" | (^".$query."*) | \"^".$query."$ \" | \"".$query."\" | (".$query.") | (".$query."*)";
	//$_filtros["600"] = array("fields"=>$fields, "valor"=>$query);
}
elseif(isset($_SESSION["query"][$reporte])){
	unset($_SESSION["reporte_detallado"][$reporte]);
	unset($_SESSION["reporte_pivot"][$reporte]);
}
$des = "";
if($periodo["periodoPersonalizado"] != PERIODOPERSONALIZADO && !empty($periodo["periodoPersonalizado"])){
	$des = _PERIODO_MODIFICADO.":";	
	switch($periodo["periodoPersonalizado"]){
		case ULTIMO_ANO:
			$des .= ""._ULTIMO_ANO."";
		break;
		case ULTIMO_SEMESTRE:
			$des .= ""._ULTIMO_SEMESTRE."";
		break;
		case ULTIMO_TRIMESTRE:
			$des .= ""._ULTIMO_TRIMESTRE."";
		break;
		case ULTIMO_BIMESTRE:
			$des .= ""._ULTIMO_BIMESTRE."";
		break;
		case ULTIMO_MES:
			$des .= ""._ULTIMO_MES."";
		break;
		case ULTIMO_QUINCENA:
			$des .= ""._ULTIMO_QUINCENA."";
		break;
		case ULTIMO_SEMANA:
			$des .= ""._ULTIMO_SEMANA."";
		break;
	}
	$des .= "->";
	$_filtros[FILTRO_PERIODODESDE] = $periodo["periodoPersonalizado"];
	$_filtros[FILTRO_PERIODOHASTA] = $periodo["periodoPersonalizado"];
	$fechaMax = strtotime($arr_periodo_consulta[0]["fechaMax"]);
	$anio = date("Y", $fechaMax);
}
elseif(!empty($periodo["anio"]) && !empty($periodo["perini"]) && !empty($periodo["perfin"])){
	$des = _PERIODO_MODIFICADO.":".$periodo["anio"]."-".traducir($_periodo[$periodo["perini"]])."-".traducir($_periodo[$periodo["perfin"]])."->";
	$_filtros[FILTRO_ANIO] = $periodo["anio"];
	$_filtros[FILTRO_PERIODODESDE] = $periodo["perini"];
	$_filtros[FILTRO_PERIODOHASTA] = $periodo["perfin"];
}
$filtrosArr = array();
foreach($_filtros as $key => $data){
	$_filtro = filtroDatos($filtrosIntercambioSisduan[$intercambio], $key);
	if($_filtro !== false){
		$filtrosArr[] = array("campo"=> $_filtro["campo"], "alias"=> $_filtro["alias"], "filtro"=> $_filtro["filtro"], "valor"=>$data, "tipo"=>$_filtro["sphinx_attr"]);
	}
	if($key == FILTRO_ANIO){
		$anio = $data;
	}
	elseif($key == FILTRO_PERIODODESDE){
		$periodoini = $data;
	}
	elseif($key == FILTRO_PERIODOHASTA){
		$periodofin = $data;
	}
}
if($periodoini >= ULTIMO_ANO || $periodofin >= ULTIMO_ANO){ //no son periodos fijos, si no variables (ultimos seis meses, tres meses, dos meses, etc.)
	$fechaMax = strtotime($arr_periodo_consulta[0]["fechaMax"]);
	$anio = date("Y", $fechaMax);
	$_filtros[FILTRO_ANIO] = $anio;
}
if($filtros_tpl != ""){
	$_filtros = convierteArreglo($filtros_tpl);
	foreach($_filtros as $key => $data){
		$_filtro = filtroDatos($filtrosIntercambioSisduan[$intercambio], $key);
		if($_filtro !== false){
			$filtrosArr[] = array("campo"=> $_filtro["campo"], "alias"=> $_filtro["alias"], "filtro"=> $_filtro["filtro"], "valor"=>$data, "tipo"=>$_filtro["sphinx_attr"]);
		}
	}
}
//print_r($filtrosArr);
//exit();
//$filtrosArr = filtrosAArray($_filtros, $db);

if($db == "sisduancri_new"){
	$dbindex = "aduanascri_new";
}
else{
	$dbindex = str_replace("sisduan","aduanas_",$db);
	$dbindex = str_replace("_new","",$dbindex);
}
if($acumulado == "1"){
	$tabla  = ($intercambio == 0) ? "acumulado_impo" : "acumulado_expo";
}
else{
	$tabla  = ($intercambio == 0) ? "declaraimp" : "declaraexp";
	$tabla .= $anio;
}

$index  = "index_".$dbindex."_".$tabla;
$tabla .= " AS decl";
$query = isset($query)?$query:"";

/*-------------------------------------------------- fin extrae la informacion de los filtros para la consulta --------------------------------------------------------------------*/
include_once(PATH_RAIZ.'sisduan/lib/declaraciones/declaracionesAdo.php');
//busca las tablas auxiliares de cada pais
$tablas   = array();
$tablas[] = $tabla;
$join     = array();
if($acumulado == "1"){
	foreach($tablasAuxAcum[$intercambio] as $key => $data){
		$tablas[] = $data["tabla"];
		$join[]	  = $data["join"];
	}
}
else{
	foreach($tablasAux[$intercambio] as $key => $data){
		$tablas[] = $data["tabla"];
		$join[]	  = $data["join"];
	}
}

$detalle = ($detalle != "")?$detalle:$filtros;

if(isset($accion)){
	switch ($accion){
		case 'lista':
			$tiempo_inicio = microtime_float();
			if(!isset($_SESSION["reporte_detallado"][$reporte])){
				$result_sphinx = consulta_sphinx($filtrosArr, $index, $query, $acumulado, $db, 0 ,$producto, $campos_full_text, $arr_periodo_consulta);
				$_SESSION["reporte_detallado"][$reporte] = $result_sphinx;
			}
			else{
				$result_sphinx = $_SESSION["reporte_detallado"][$reporte];
			}
			
			$tiempo_fin = microtime_float();
			$tiempo_sph = $tiempo_fin - $tiempo_inicio;
			//print_r($result_sphinx);
			if($result_sphinx["error"]){
				unset($_SESSION["reporte_detallado"][$reporte]);
				$respuesta = array(
					"success"=>false
					,"msg"=>utf8_encode($result_sphinx["msg"])
				);
				echo json_encode($respuesta);
				exit();
			}
			$filtrosSql = filtros($result_sphinx["filtros_sql"]);
		
			$arr = array();
			$start = (isset($start))?$start:0;
			$limit = (isset($limit))?$limit:MAXREGEXCEL;
			$orderby = "";
			
			$page = ($start==0)?1:($start/$limit)+1;
			
			$limit = $page . ", " . $limit;
			
			//si esta agrupando, en este caso NO se puede ordenar dado que se utiliza el modificador "WITH ROLLUP"
			/*if(isset($groupByKey)){
				$groupBy = (isset($groupByKey))?$groupByKey." ".$groupDir:"";
			}
			else{
				//busca el campo por el cual debe ordenar, si existe un parametro de ordenacion			
				$orderby = (isset($sort))?$sort." ".$dir:"";
			}*/
			$groupBy = (isset($groupByKey))?$groupByKey." ".$groupDir:"";
			$orderby = (isset($sort))?$sort." ".$dir:"";
			
			/*if($result_sphinx["total"] > MAXREGEXCEL){
				$formatted = sprintf(_EXCEDELIMITE, MAXREGEXCEL, $result_sphinx["total"]);
				echo '{success: false, msg:"'.sanear_string($formatted).'"}';
				exit();
			}*/
			if(isset($formato)){
				//2 = en proceso, 1 = pendiente para descargar
				//Cambia el estado a no pendiente en el registro del reporte
				$rs = actReportePendiente($reporte, 2, 0, date("Y-m-d H:i:s"));
			}
			$tiempo_inicio = microtime_float();
			$declaracionesAdo = new DeclaracionesAdo($db);
			$result = $declaracionesAdo->lista($db, $intercambio, implode(", ",$tablas), $arrCampos, $filtrosSql, $limit, $orderby, $groupBy, false, $result_sphinx["total"], $join);
			
			unset($filtrosSql);
			unset($result_sphinx);
			unset($arrCampos);
			unset($join);
			unset($tablas);
			
			if(!is_array($result) || empty($result)){
				if(isset($formato)){
					//2 = en proceso, 1 = pendiente para descargar, 3 = Cancelado por error
       				//Cambia el estado a no pendiente en el registro del reporte
					$rs = actReportePendiente($reporte, 3, 0,"");
				}
				$respuesta = array(
					"success"=>false,
					"msg"=>sanear_string($result)
				);
				echo json_encode($respuesta);
				exit();
			}
			
			if($result["total"] == 0){
				if(isset($formato)){
					//2 = en proceso, 1 = pendiente para descargar, 3 = Cancelado 
       				//Cambia el estado a no pendiente en el registro del reporte
					$rs = actReportePendiente($reporte, 3, 0,"");
				}
				$respuesta = array(
					"success"=>false,
					"msg"=>sanear_string(_NOSEENCONTRARONREGISTROS)
				);
				echo json_encode($respuesta);
				exit();
			}
			//print_r($result);
			foreach($result["datos"] as $key => $data){
				if(isset($formato)){
					$arr[] = $data;
				}
				else{
					//$aux = filter_var_array($data,FILTER_SANITIZE_STRING);
					$arr[] = sanear_string($data);
				}
			}
			$tiempo_fin = microtime_float();
			$tiempo = $tiempo_fin - $tiempo_inicio;
			if(isset($formato)){
				if($_SESSION["download"] != "1"){
					$respuesta = array(
						"success"=>false,
						"msg"=>sanear_string(_ACCIONNOPERMITIDA)
					);
					echo json_encode($respuesta);
					exit();
				}
				
				if(isset($fields)){
					$head = json_decode(stripslashes($fields), true);
					//print_r($head);
				}
				else{
					$tmp_campos = convierteArreglo($campos_rep);
					$head = array();
					foreach($tmp_campos as $campo => $titulo) {
						$origCampo = campoDatos($_camposIntercambio, $campo);
						//asigna un alias para todos los campos
						if($origCampo['alias'] == ''){
							$aux = explode(".",$campo);
							$prefijo = $aux[0];
							$sufijo  = $aux[1];
							$alias = $sufijo;
						}
						else{
							$alias = $origCampo['alias'];
						}
						$head[$alias] = sanear_string($origCampo['nombre']);
					}
					//print_r($head);
				}
				
				//2 = en proceso, 1 = pendiente para descargar
				//Cambia el estado a no pendiente en el registro del reporte
				$rs = actReportePendiente($reporte, 1, $result["total"], date("Y-m-d H:i:s"));
				
				//formatea las columnas en el excel
				$arr_columns_nuevo = array();
				foreach($result["columns"] as $key => $data){
					$data_type = $data["type"];
					$data_col = $data["col"];
					$origCampo = campoDatos($_camposIntercambio, $data["col"]);
					if($origCampo){
						if($origCampo["tipo"] == "s"){
							$data_type = "C";
						}
					}					
					$arr_columns_nuevo[] = array("type"=>$data_type, "col"=>$data_col);				
				}
				$result["columns"] = $arr_columns_nuevo;
				
				monitoreo($producto, EXCEL, $_REQUEST, $detalle, $result["total"]);
				$total     = _TOTALREGISTROS . ": ". $result["total"];
				$reportes_nombre = filter_var($reportes_nombre, FILTER_SANITIZE_SPECIAL_CHARS);
				$detalle = $des.$detalle;
				$descripcion_arr = array_descripcion_reporte($reportes_nombre,$detalle);
				//$reportes_nombre = urlencode(filter_var($reportes_nombre, FILTER_SANITIZE_URL));
				$nombreXls = $reporte; //$reportes_nombre;
				$tiempo_inicio = microtime_float();
				$archivo = CreaExcel($arr, $formato, $head, $total, $nombreXls, $result["columns"], $descripcion_arr);
				
				$tiempo_fin = microtime_float();
				$tiempo_excel = $tiempo_fin - $tiempo_inicio;
			
				$respuesta = array(
					"success"=>true,
					"msg"=>$archivo,
					"tiempo"=>$tiempo,
					"tiempo_excel"=>$tiempo_excel,
					"tiempo_sph"=>$tiempo_sph
				);
				echo json_encode($respuesta);
				exit();
				//echo '{success: true, msg:'.json_encode($archivo).', tiempo:"'.$tiempo.'", tiempo_sph:"'.$tiempo_sph.'"}';
		   	}
		   	else{
				monitoreo($producto, LISTAR, $_REQUEST, $detalle, $result["total"]);
				$data = ($arr);
				$respuesta = array(
					"success"=>true,
					"total"=>$result["total"],
					"datos"=>$data,
					"time"=>$tiempo,
					"tiempo_sph"=>$tiempo_sph
				);
				echo json_encode($respuesta);
				exit();
				//print('{"total":"'.$result["total"].'", "datos":'.$data.', time:"'.$tiempo.'", tiempo_sph:"'.$tiempo_sph.'"}');
			}
			exit();
		break;
		case 'pivot':			
			$arr = array();
			$start = (isset($start))?$start:0;
			$limit = (isset($limit))?$limit:30;
			$orderby = "";
			
			$page = ($start==0)?1:($start/$limit)+1;
			
			$limit = $page . ", " . $limit;
			
			if($sorters){
				$sorters = json_decode(stripslashes($sorters), true);
				$sortersArr = array();
				foreach($sorters as $key => $campo){
					//print_r($campo);
					$sortersArr[] = $campo["index"] . " " . $campo["dir"];
				}
			}
			
			$orderby = (isset($sorters))?implode(",",$sortersArr):"";
			$filas = json_decode(stripslashes($filas), true);
		
			$filasArr     = array();
			$filasArr[]   = "id";
			$filasAlias   = array();
			$filasAlias[] = "id";
			$filasFiltro  = array();
			foreach($filas as $key => $campo){
				if($campo["key"] != ""){
					$is_filtro = campoDatos($filtrosIntercambioSisduan[$intercambio], $campo["key"]);
					if($is_filtro !== false){
						$filasFiltro[] = array("filtro" => $is_filtro["filtro"], "campo" => $campo["alias"], "alias" => $is_filtro["alias"], "filtro_campo" => $is_filtro["campo"]);
						$filasArr[] = $campo["key"] . " AS filtroid_" . $campo["alias"];
						$filasAlias[] = "filtroid_" . $campo["alias"];
					}
				}
				$is_filtro = campoDatos($filtrosIntercambioSisduan[$intercambio], $campo["id"]);
				if($is_filtro !== false){
					$filasFiltro[] = array("filtro" => $is_filtro["filtro"], "campo" => $campo["alias"], "alias" => $is_filtro["alias"], "filtro_campo" => $is_filtro["campo"]);
					$filasArr[] = $campo["id"] . " AS filtroid_" . $campo["alias"];
					$filasAlias[] = "filtroid_" . $campo["alias"];
				}
				
				$filasArr[] = $campo["id"] . " AS " . $campo["alias"];
				$filasAlias[] = $campo["alias"];
			}
			$filas = implode(",",$filasArr);
			if($multiano == 1){
				if(!isset($_SESSION["reporte_pivot_multianio"][$reporte])){
					unset($_SESSION["reporte_pivot"][$reporte]);
					$_SESSION["reporte_pivot_multianio"][$reporte] = $multiano;
				}
				$columnas = array();
				$columnas[] = array("id"=>"decl.ano", "alias"=>"ano");
			}
			else{
				if(isset($_SESSION["reporte_pivot_multianio"][$reporte])){
					unset($_SESSION["reporte_pivot"][$reporte]);
					unset($_SESSION["reporte_pivot_multianio"][$reporte]);
				}
				$columnas = json_decode(stripslashes($columnas), true);
			}
			//print_r($columnas);
			$columnasArr = array();
			foreach($columnas as $key => $campo){
				$columnasArr[] = $campo["id"];
			}
			if(count($columnasArr) < 2 ){
				$columnas = implode(",",$columnasArr);
			}
			else{
				$columnas = $columnasArr;
			}
			
			$totales = json_decode(stripslashes($totales), true);			
			$totalesArr = array();
			foreach($totales as $key => $campo){
				
				$origCampo = campoDatos($_camposIntercambio, $campo["id"]);
				$totalesArr[] = array("campo"=>$campo["id"], "alias"=>sanear_string_full($campo["alias"]), "nombre"=>sanear_string_full($origCampo["nombre"]), "tipo"=>$origCampo["tipo"]);
				
			}
			
			if(!isset($_SESSION["reporte_pivot"][$reporte])){
				$result_sphinx = consulta_sphinx($filtrosArr, $index, $query, $acumulado, $db, $multiano, $producto, false, $arr_periodo_consulta);
				$_SESSION["reporte_pivot"][$reporte] = $result_sphinx;
			}
			else{
				$result_sphinx = $_SESSION["reporte_pivot"][$reporte];
			}
			if($result_sphinx["error"]){
				$respuesta = array(
					"success"=>false,
					"msg"=>utf8_encode($result_sphinx["msg"])
				);
				echo json_encode($respuesta);
				exit();
			}
			$filtrosSql = filtros($result_sphinx["filtros_sql"]);
			
			
			if(isset($formato)){
				//2 = en proceso, 1 = pendiente para descargar
				//Cambia el estado a no pendiente en el registro del reporte
				$rs = actReportePendiente($reporte, 2, 0, date("Y-m-d H:i:s"));
			}
			$declaracionesAdo = new DeclaracionesAdo($db);
			$result   = $declaracionesAdo->pivot($db, $intercambio, implode(", ",$tablas), $filas, $columnas, $totalesArr, $filtrosSql, $limit, $orderby, false, $join);
			if(!is_array($result)){
				if(isset($formato)){
					//2 = en proceso, 1 = pendiente para descargar, 3 = Cancelado 
       				//Cambia el estado a no pendiente en el registro del reporte
					$rs = actReportePendiente($reporte, 3, 0,"");
				}
				$respuesta = array(
					"success"=>false,
					"msg"=>sanear_string($result)
				);
				echo json_encode($respuesta);
				exit();
			}
			if($result["total"] > MAXREGEXCEL){
				if(isset($formato)){
					//2 = en proceso, 1 = pendiente para descargar, 3 = Cancelado 
       				//Cambia el estado a no pendiente en el registro del reporte
					$rs = actReportePendiente($reporte, 3, 0,"");
				}
				$formatted = sprintf(_EXCEDELIMITE, MAXREGEXCEL, $result["total"]);
				$respuesta = array(
					"success"=>false,
					"msg"=>sanear_string($formatted)
				);
				echo json_encode($respuesta);
				exit();
			}
			
			$arr_totales = $totalesArr;
			$totalesArr = array();
			$demasArr   = array();
			$concat_filas_arr = array();
			$asigno_eje_x = false;
			//sumar los valores totales para poder calcular la participacion
			foreach($result["datos"] as $index => $data){
				$i = 0;
				foreach($data as $key => $valor){
					if(!is_numeric($key)){ //como es un array asociativo solo tiene en cuenta los indices no numericos...ya que las filas se repiten con la key = nombre de la columna y la key = autoincrement
						$i++;
						if(!in_array($key, $filasAlias)){
							$totalesArr[$key] += $valor;
							if($index >= $mostrar){
								$demasArr[$key] += $valor;
							}
						}
						else{
							if($i == count($filasAlias)){
								if($index > $mostrar){
									$demasArr[$key]   = "LOS DEMAS";
								}
								$totalesArr[$key] = "TOTAL";
							}
							else{
								if($index > $mostrar){
									$demasArr[$key]   = "*******************************";
								}
								$totalesArr[$key] = "*******************************";
							}
						}
						
						if(isset($grafico) && $grafico == "true"){ //si la consulta es para grafico se debe concatenar las filas para mostrarlas en el eje x
							if(in_array($key, $filasAlias) && $key != 'id'  && substr($key,0,9) != "filtroid_"){
								if(!$asigno_eje_x){
									$eje_x = $key;
									$asigno_eje_x = true;
								}
								$concat_filas_arr[$index] .= $valor . " ";
								//print_r($result["datos"][$index]["id_posicion"]);
							}
							//print_r($filasAlias);
							//print_r($filasArr);
							
						}						
					}
				}				
			}
			if(isset($grafico) && $grafico == "true"){ //si la consulta es para grafico se debe concatenar las filas para mostrarlas en el eje x
				foreach($result["datos"] as $index => $data){
					$result["datos"][$index][$eje_x] = $concat_filas_arr[$index];
				}
			}
			
			//inserta las columnas con los valores de participacion calculados
			foreach($result["datos"] as $index => $data){
				if($index < $mostrar){
					foreach($data as $key => $valor){
						if(!in_array($key, $filasAlias) && !is_numeric($key)){
							$participacion = round(($valor / $totalesArr[$key]) * 100 , 2);
							$data = array_merge($data, array("porc_".$key => $participacion));
							$totalesArr["porc_".$key] = 100;
						}
					}
					$arr[] = sanear_string($data);
				}
			}
			if(!empty($demasArr)){
				//calcula la participacion de la fila de los demas
				foreach($totalesArr as $key => $valor){
					if($valor > 0){
						$participacion = round(($demasArr[$key] / $valor) * 100 , 2);
					}
					else{
						$participacion = 0;
					}
					//print $key . " = ".$participacion."\n";
					$demasArr["porc_".$key] = $participacion;
				}
				//adiciona la linea de los demas
				$arr[] = $demasArr;
			}
			if(!isset($grafico) || $grafico == "false"){ //si la consulta es para grafico no debe mostrar la linea de totales
				$arr[] = $totalesArr;
			}
			else{
				if(isset($fields)){
					$head = json_decode(stripslashes($fields), true);
					$tmp = array();
					foreach($arr as $index => $data){
						$tmp[] = array_intersect_key($data, $head);
					}
					$arr = $tmp;
				}
				print json_graficos($arr, $eje_x, $filasAlias, $arr_totales, $tipo_grafico);
				exit();
			}
			//genera el columnmodel 
			$colmodel = array();
			//$campos_store = array();
			$index = 0;
			$arr_columns_nuevo = array();
			foreach($result["columns"] as $key => $data){
				$data_type = $data["type"];
				$data_col = $data["col"];
				$filtro = "";
				$origCampo = campoDatos($_camposIntercambio, $data["col"]);
				$hidden = 'false';
				
				if($data['type'] != 'rate'){
					$index++; //indice del campo por el cual se va a ordenar
				}
				if($origCampo){
					if($data['type'] == 'C'){
						$renderer = "";
						$is_filtro = campoDatos($filasFiltro, $data["col"]);
						if($is_filtro !== false){
							$renderer = "link";
							$filtro = $is_filtro["filtro"];
						}
						$tipo = 'string';
						$align = 'left';
						//$renderer = "link";
						//print $origCampo["key"];
					}
					elseif(in_array($data["col"], $filasAlias)){
						$tipo = 'string';
						if($origCampo["tipo"] == "n"){
							$align = 'right';
							$renderer = "numberFormat";
						}
						else{
							$align = 'left';
							$renderer = "";
							$data_type = "string";
						}
						//print $data["col"].".... tipo_bd = ".$data['type']."...... tipo_conf = ".$origCampo["tipo"]."\n";
					}
					else{
						$tipo = 'float';
						$align = 'right';
						$renderer = "numberFormat";
					}
					if($data['type'] == 'rate'){
						$name   = "porc_".$origCampo["alias"];
						$hidden = 'true';
						$header = "% ".($origCampo["nombre"]);
					}
					else{
						$name   = $origCampo["alias"];
						$header = ($origCampo["nombre"]);
					}
				}
				elseif(substr($data["col"],0,9) == "filtroid_"){
					$tipo = 'string';
					$renderer = "";
					$align = 'left';
					$name   = sanear_string_full($data["col"]);
					$header = ($data["col"]);
				}
				else{
					//print($data["col"]." = ".$data['type']."\n");
					$tipo = 'float';
					$align = 'right';
					$renderer = "numberFormat";
					if($data['type'] == 'rate'){
						$name   = "porc_".sanear_string_full($data["col"]);
						$hidden = 'true';
						$header = "% ".($data["col"]);
					}
					else{
						$name   = sanear_string_full($data["col"]);
						$header = ($data["col"]);
					}
					if($directo_excel && $data["col"] != "id" && $data['type'] != 'rate'){ //cuando es directo a excel, debe agregar los campos calculados en el head del excel
						$arr_fields[$name] = utf8_encode($header);
					}
				}
				
				$header = utf8_encode($header);
				
				//$campos_store[] = array("name"=>$name);
				$colmodel[] = array(
					"name"=>$name
					,"header"=>$header
					,"align"=>$align
					,"type"=>$tipo
					,"hidden"=>$hidden
					,"renderer"=>$renderer
					,"index"=>$index
					,"filtro"=>$filtro
				);
				
				$arr_columns_nuevo[] = array("type"=>$data_type, "col"=>$data_col);				
			}
			$result["columns"] = $arr_columns_nuevo;
			//print_r($colmodel);
			//foreach($_arrCampos as $campo => $titulo) {
				
			if($result["total"] == 0){
				if(isset($formato)){
					//2 = en proceso, 1 = pendiente para descargar, 3 = Cancelado 
       				//Cambia el estado a no pendiente en el registro del reporte
					$rs = actReportePendiente($reporte, 3, 0,"");
				}
				$respuesta = array(
					"success"=>false,
					"msg"=>sanear_string(_NOSEENCONTRARONREGISTROS)
				);
				echo json_encode($respuesta);
				exit();
			}
			if(isset($formato)){
				if($_SESSION["download"] != "1"){
					$respuesta = array(
						"success"=>false,
						"msg"=>sanear_string(_ACCIONNOPERMITIDA)
					);
					echo json_encode($respuesta);
					exit();
				}
				//2 = en proceso, 1 = pendiente para descargar
				//Cambia el estado a no pendiente en el registro del reporte
				$rs = actReportePendiente($reporte, 1, $result["total"], date("Y-m-d H:i:s"));
				
				if($directo_excel){ //si es directo a excel, el parametro fields no viene por eso se debe configurar
					$head = ($arr_fields);
				}
				else{
					$head   = json_decode(stripslashes($fields), true);
				}
				
				monitoreo($producto, EXCEL, $_REQUEST, $detalle, $result["total"]);
				$total = _TOTALREGISTROS . ": ". $result["total"];
				
				$reportes_nombre = filter_var($reportes_nombre, FILTER_SANITIZE_SPECIAL_CHARS);
				$detalle = $des.$detalle;
				$descripcion_arr = array_descripcion_reporte($reportes_nombre,$detalle);
				//$reportes_nombre = urlencode(filter_var($reportes_nombre, FILTER_SANITIZE_URL));
				$nombreXls = $reporte; //$reportes_nombre;
				$tiempo_inicio = microtime_float();
				$archivo = CreaExcel($arr, $formato, $head, $total, $nombreXls, $result["columns"], $descripcion_arr);
				
				$tiempo_fin = microtime_float();
				$tiempo_excel = $tiempo_fin - $tiempo_inicio;
				$respuesta = array(
					"success"=>true,
					"msg"=>$archivo,
					"tiempo"=>$tiempo,
					"tiempo_excel"=>$tiempo_excel,
					"tiempo_sph"=>$tiempo_sph
				);
				echo json_encode($respuesta);
				exit();
		   	}
		   	else{
				$data = ($arr);
				
				monitoreo($producto, LISTAR, $_REQUEST, $detalle, $result["total"]);
				
				$respuesta = array(
					"success"=>true,
					"total"=>$result["total"],
					"datos"=>$data,
					"metaData"=>array(
						"fields"=>($colmodel),
						"root"=>"datos",
						"totalProperty"=>"total",
						"successProperty"=>"success",
						"series"=>($result["series"])
					)
				);
				echo json_encode_jsfunc($respuesta);
				exit();
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
