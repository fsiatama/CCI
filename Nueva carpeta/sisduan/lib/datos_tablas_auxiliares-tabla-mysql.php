<?php
//Trae la sesión que esté asignada
session_start();

//Variables de configuración del sistema
include ("../../lib/config.php");
include ("../../lib/lib_sesion.php");
//Incluye el diccionario
include (PATH_APP."lib/idioma.php");

//trae la base de datos del pais seleccionado
include_once(PATH_RAIZ.'sicex_r/lib/pais/paisAdo.php');
$paisAdo = new PaisAdo('sicex_r');
$pais    = new Pais;
$pais->setPais_id($paisId);
$paisDs  = $paisAdo->lista($pais);
$db = $paisDs[0]["pais_bd"];

//trae la informacion del filtro seleccionado
include_once(PATH_RAIZ.'sicex_r/lib/filtroreportes/filtroreportesAdo.php');
$filtroreportesAdo = new FiltroreportesAdo('sicex_r');
$filtroreportes    = new Filtroreportes;
$filtroreportes->setFiltroreportes_id($filtro);
$filtroDs = $filtroreportesAdo->listaId($filtroreportes);

$filtro_anter  = trim($filtroDs[0]["filtroreportes_filtro_ant"]);
$filtro_tabla  = trim($filtroDs[0]["filtroreportes_tabla"]);
$filtro_campo  = trim($filtroDs[0]["filtroreportes_campo"]);
$filtro_nombre = trim($filtroDs[0]["filtroreportes_nombre"]);

$objAdo = Factory::fabrica($filtro_tabla, $db);

//id de los filtros con traduccion, es decir que cambian con el idioma selecionado por el usuario
//por ejemplo el filtro de la tabla arancel trae la descripcion en ingles y en español
$filtros_con_traduccion = array(2);

$logica = (in_array($filtro,$filtros_con_traduccion) && $_GET['cambiaidioma'] <> "ES" )?true:false;

//print_r($objAdo);
$result = $objAdo->lista_filtro($query);

foreach($result as $key => $data){
	if($logica){
		$arr[] = array(
			'valor_id'=>$data[0]
			,'valor_desc'=>utf8_encode($data[2])
		);
	}
	else{
		$arr[] = array(
			'valor_id'=>$data[0]
			,'valor_desc'=>utf8_encode($data[1])
		);
	}
}
$data = json_encode($arr); 
print('{"total":"'.count($result).'", "datos":'.$data.'}');


class Factory{
    // El método de fábrica parametrizada
    public static function fabrica($tipo, $parametros){
        if (include_once PATH_RAIZ.'sisduan/lib/'.$tipo.'/'.$tipo.'Ado.php'){
			$nombreclase = ucfirst($tipo)."Ado";
			return new $nombreclase($parametros);
        } else {
            throw new Exception('Driver no encontrado');
        }
    }
}

