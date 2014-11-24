<?php
ini_set('display_errors', true);
error_reporting(E_ALL);

$base         = "min_agricultura"; //$_GET["db"];
$nombre_tabla = "pais"; //$_GET["tabla"];

if($base == "" || $nombre_tabla == ""){
	print "no hay datos";
	exit();
}
if(!file_exists($nombre_tabla)){
	mkdir($nombre_tabla);
}

include('../../adodb5/adodb.inc.php');	   # Carga el codigo comun de ADOdb


$conn = &ADONewConnection('mysqli');  # crea la conexion
$conn->PConnect('127.0.0.1','root','','information_schema');# se conecta a la base de datos agora
$sql = "SELECT * FROM COLUMNS WHERE TABLE_SCHEMA = '".$base."' AND (TABLE_NAME = '".$nombre_tabla."' )";
$rs = $conn->Execute($sql);
//print $rs->GetMenu('COLUMN_NAME','orden_compra_cab_id');
$result    = array();
$result2   = array();
$contenido = "";
$cabecera  = "";
$variables = "";

$cabecera  = "<?php\r\nclass ".ucfirst($nombre_tabla)." {\r\n\r\n";

$llave_primaria = false;
while (!$rs->EOF) {
	$contenido  .= "	public function set". ucfirst($rs->fields["COLUMN_NAME"])."(\$". $rs->fields["COLUMN_NAME"] ."){\r\n";
	$contenido  .= "		\$this->". $rs->fields["COLUMN_NAME"]." = \$". $rs->fields["COLUMN_NAME"].";\r\n";
	$contenido  .= "	}\r\n\r\n";
	$contenido  .= "	public function get". ucfirst($rs->fields["COLUMN_NAME"])."(){\r\n";
	$contenido  .= "		return \$this->". $rs->fields["COLUMN_NAME"].";\r\n";
	$contenido  .= "	}\r\n\r\n";
	$variables  .= "	private \$". $rs->fields["COLUMN_NAME"]. ";\r\n";
	$result[]    = $rs->fields["COLUMN_NAME"];
	$result2[]   = $rs->fields;
	if($rs->fields["COLUMN_KEY"] == "PRI"){
		$llave_primaria = $rs->fields["COLUMN_NAME"];
	}
	$rs->MoveNext();
}

if($llave_primaria === false){
	print "no existe llave primaria";
	exit();
}
$cabecera .= $variables."\r\n";
$cabecera .= $contenido;
$cabecera .= "}";
//printf($contenido);

$archivo = $nombre_tabla . "/" . ucfirst($nombre_tabla).".php";
if(!file_exists($archivo)){
	$fp = fopen($archivo,"w+");
	fwrite($fp, $cabecera);
	fclose($fp);
}
else {
	print $archivo . " ya existe, no se ha creado \r\n";
}

/******************************************************************************************************/
/*********************************Inicia creacion del Ado**********************************************/
/******************************************************************************************************/
$getters = "";
foreach($result as $key => $campos){
	$getters .= "		\$" . $campos . " = \$" . $nombre_tabla ."->get".ucfirst($campos)."();\r\n";
}

$contenido = "<?php

require_once ('BaseAdo.php');

class ".ucfirst($nombre_tabla)."Ado extends BaseAdo {

	protected function setTable()
	{
		\$this->table = '".$nombre_tabla."';
	}

	protected function setPrimaryKey()
	{
		\$this->primaryKey = '".$llave_primaria."';
	}

	protected function setData()
	{
		\$".$nombre_tabla." = \$this->getModel();

".$getters."
		\$this->data = compact(
			'".implode("',\r\n			'", $result)."'
		);
	}

	public function create(\$".$nombre_tabla.")
	{
		\$conn = \$this->getConnection();
		\$this->setModel(\$".$nombre_tabla.");
		\$this->setData();

		\$sql = '
			INSERT INTO ".$nombre_tabla." (
				".implode(",\r\n				", $result)."
			)
			VALUES (
				\"'.\$this->data['".implode("'].'\",\r\n				\"'.\$this->data['", $result)."'].'\"
			)
		';
		\$resultSet = \$conn->Execute(\$sql);
		\$result = \$this->buildResult(\$resultSet, \$conn->Insert_ID());

		return \$result;
	}

	public function buildSelect()
	{
		\$filter = array();
		\$operator = \$this->getOperator();
		\$joinOperator = ' AND ';
		foreach(\$this->data as \$key => \$data){
			if (\$data <> ''){
				if (\$operator == '=') {
					\$filter[] = \$key . ' ' . \$operator . ' \"' . \$data . '\"';
				}
				elseif (\$operator == 'IN') {
					\$filter[] = \$key . ' ' . \$operator . '(\"' . \$data . '\")';
				}
				else {
					\$filter[] = \$key . ' ' . \$operator . ' \"%' . \$data . '%\"';
					\$joinOperator = ' OR ';
				}
			}
		}

		\$sql = 'SELECT
			 ".implode(",\r\n			 ", $result)."
			FROM ".$nombre_tabla."
		';
		if(!empty(\$filter)){
			\$sql .= ' WHERE ('. implode( \$joinOperator, \$filter ).')';
		}

		return \$sql;
	}

}
";

$archivo = $nombre_tabla . "/" .ucfirst($nombre_tabla)."Ado.php";
if(!file_exists($archivo)){
	$fp = fopen($archivo,"w+");
	fwrite($fp, $contenido);
	fclose($fp);
}
else {
	print $archivo . " ya existe, no se ha creado ";
}

/******************************************************************************************************/
/*********************************Inicia creacion del Repo**********************************************/
/******************************************************************************************************/

$contenido = "<?php

require PATH_APP.'".$base."/Entities/".ucfirst($nombre_tabla).".php';
require PATH_APP.'".$base."/Ado/".ucfirst($nombre_tabla)."Ado.php';
require_once ('BaseRepo.php');

class ".ucfirst($nombre_tabla)."Repo extends BaseRepo {

	public function getModel()
	{
		return new ".ucfirst($nombre_tabla).";
	}
	
	public function getModelAdo()
	{
		return new ".ucfirst($nombre_tabla)."Ado;
	}

	public function getPrimaryKey()
	{
		return '".$llave_primaria."';
	}

	public function validateModify(\$params)
	{
		extract(\$params);
		\$result = \$this->findPrimaryKey(\$".$llave_primaria.");

		if (!\$result['success']) {
			\$result = [
				'success'  => false,
				'closeTab' => true,
				'tab'      => 'tab-'.\$module,
				'error'    => \$result['error']
			];
		}
		return \$result;
	}

	public function setData(\$params, \$action)
	{
		extract(\$params);

		if (\$action == 'modify') {
			\$result = \$this->findPrimaryKey(\$".$llave_primaria.");

			if (!\$result['success']) {
				\$result = [
					'success'  => false,
					'closeTab' => true,
					'tab'      => 'tab-'.\$module,
					'error'    => \$result['error']
				];
				return \$result;
			}
		}

		if (
			empty(\$".implode(") ||\r\n			empty(\$", $result).")
		) {
			\$result = array(
				'success' => false,
				'error'   => 'Incomplete data for this request.'
			);
			return \$result;
		}
";
foreach($result as $key => $campos){
	$contenido .= "			\$this->model->set".ucfirst($campos)."(\$" . $campos . ");\r\n";
}
$contenido .= "		

		if (\$action == 'create') {
		}
		elseif (\$action == 'modify') {
		}
		\$result = array('success' => true);
		return \$result;
	}

}	

";

$archivo = $nombre_tabla . "/" .ucfirst($nombre_tabla)."Repo.php";
if(!file_exists($archivo)){
	$fp = fopen($archivo,"w+");
	fwrite($fp, $contenido);
	fclose($fp);
}
else {
	print $archivo . " ya existe, no se ha creado ";
}

/******************************************************************************************************/
/*********************************Inicia creacion del Controller**********************************************/
/******************************************************************************************************/

$contenido = "<?php

require PATH_APP.'min_agricultura/Repositories/".ucfirst($nombre_tabla)."Repo.php';
require PATH_APP.'min_agricultura/Repositories/UserRepo.php';

class ".ucfirst($nombre_tabla)."Controller {
	
	protected \$".($nombre_tabla)."Repo;

	public function __construct()
	{
		\$this->".($nombre_tabla)."Repo = new ".ucfirst($nombre_tabla)."Repo;
		\$this->userRepo        = new UserRepo;
	}
	
	public function listAction(\$urlParams, \$postParams)
    {
        return \$this->".($nombre_tabla)."Repo->listAll(\$postParams);
    }

}
	

";

$archivo = $nombre_tabla . "/" .ucfirst($nombre_tabla)."Controller.php";
if(!file_exists($archivo)){
	$fp = fopen($archivo,"w+");
	fwrite($fp, $contenido);
	fclose($fp);
}
else {
	print $archivo . " ya existe, no se ha creado ";
}


$contenido = "";
$cabecera  = "";
$variables = "";

$contenido .= "<?php\r\n";
$contenido .= "session_start();\r\n";
$contenido .= "include('../../lib/config.php');\r\n";
$contenido .= "include_once(PATH_APP.\"lib/idioma.php\");\r\n";
$contenido .= "include_once(PATH_APP.\"lib/lib_funciones.php\");\r\n";
$contenido .= "include_once(PATH_APP.\"lib/lib_sesion.php\");\r\n";
$contenido .= "include_once(PATH_RAIZ.\"".$base."/lib/".$nombre_tabla."/".$nombre_tabla."Ado.php\");\r\n";

$contenido .= "\$".$nombre_tabla."Ado = new " . ucfirst($nombre_tabla) ."Ado(\"".$base."\");\r\n";
$contenido .= "\$".$nombre_tabla."    = new " . ucfirst($nombre_tabla) .";\r\n";


$contenido .= "if(isset(\$accion)){\r\n";
$contenido .= "	switch(\$accion){\r\n";
$contenido .= "		case \"act\":\r\n";
foreach($result as $key => $campos){
	$contenido .= "			\$" . $nombre_tabla ."->set".ucfirst($campos)."(\$" . $campos . ");\r\n";
}
$contenido .= "			\$rs_".$nombre_tabla." = \$".$nombre_tabla."Ado->actualizar(\$".$nombre_tabla.");\r\n";
$contenido .= "			if(\$rs_".$nombre_tabla." !== true){\r\n";
$contenido .= "				\$success = false;\r\n";
$contenido .= "			}\r\n";
$contenido .= "			else{\r\n";
$contenido .= "				\$success = true;\r\n";
$contenido .= "			}\r\n";
$contenido .= "			\$respuesta = array(\r\n";
$contenido .= "				\"success\"=>\$success,\r\n";
$contenido .= "				\"errors\"=>array(\"reason\"=>\$rs_".$nombre_tabla.")\r\n";
$contenido .= "			);\r\n";
$contenido .= "			echo json_encode(\$respuesta);\r\n";
$contenido .= "			exit();\r\n";
$contenido .= "		break;\r\n";
$contenido .= "		case \"del\":\r\n";
$contenido .= "			\$".$nombre_tabla."->set".ucfirst($llave_primaria)."(\$".$llave_primaria.");\r\n";
$contenido .= "			\$rs_".$nombre_tabla." = \$".$nombre_tabla."Ado->borrar(\$".$nombre_tabla.");\r\n";
$contenido .= "			if(\$rs_".$nombre_tabla." !== true){\r\n";
$contenido .= "				\$success = false;\r\n";
$contenido .= "			}\r\n";
$contenido .= "			else{\r\n";
$contenido .= "				\$success = true;\r\n";
$contenido .= "			}\r\n";
$contenido .= "			\$respuesta = array(\r\n";
$contenido .= "				\"success\"=>\$success,\r\n";
$contenido .= "				\"errors\"=>array(\"reason\"=>\$rs_".$nombre_tabla.")\r\n";
$contenido .= "			);\r\n";
$contenido .= "			echo json_encode(\$respuesta);\r\n";
$contenido .= "			exit();\r\n";
$contenido .= "		break;\r\n";
$contenido .= "		case \"crea\":\r\n";
foreach($result as $key => $campos){
	$contenido .= "			\$" . $nombre_tabla ."->set".ucfirst($campos)."(\$" . $campos . ");\r\n";
}
$contenido .= "			\$rs_".$nombre_tabla." = \$".$nombre_tabla."Ado->insertar(\$".$nombre_tabla.");\r\n";
$contenido .= "			if(\$rs_".$nombre_tabla."[\"success\"] !== true){\r\n";
$contenido .= "				\$respuesta = array(\r\n";
$contenido .= "					\"success\"=>false,\r\n";
$contenido .= "					\"errors\"=>array(\"reason\"=>\"Error creando ".$nombre_tabla."\", \"error\"=>\$rs_".$nombre_tabla."[\"error\"])\r\n";
$contenido .= "				);\r\n";
$contenido .= "				echo json_encode(\$respuesta);\r\n";
$contenido .= "				exit();\r\n";
$contenido .= "			}\r\n";
$contenido .= "			\$".$llave_primaria." = \$rs_".$nombre_tabla."[\"insert_id\"];\r\n";
$contenido .= "			\$respuesta = array(\r\n";
$contenido .= "				\"success\"=>true,\r\n";
$contenido .= "				\"errors\"=>array(\"reason\"=>\$".$llave_primaria.")\r\n";
$contenido .= "			);\r\n";
$contenido .= "			echo json_encode(\$respuesta);\r\n";
$contenido .= "			exit();\r\n";
$contenido .= "		break;\r\n";
$contenido .= "		case \"lista\":\r\n";
$contenido .= "			\$arr = array();\r\n";
foreach($result as $key => $campos){
	$contenido .= "			\$" . $nombre_tabla ."->set".ucfirst($campos)."(\$" . $campos . ");\r\n";
}
$contenido .= "			\$rs_".$nombre_tabla." = \$".$nombre_tabla."Ado->lista(\$".$nombre_tabla.");\r\n";
$contenido .= "			if(!is_array(\$rs_".$nombre_tabla.")){\r\n";
$contenido .= "				\$respuesta = array(\r\n";
$contenido .= "					\"success\"=>false,\r\n";
$contenido .= "					\"errors\"=>array(\"reason\"=>\$rs_".$nombre_tabla.")\r\n";
$contenido .= "				);\r\n";
$contenido .= "				echo json_encode(\$respuesta);\r\n";
$contenido .= "				exit();\r\n";
$contenido .= "			}\r\n";
$contenido .= "			foreach(\$rs_".$nombre_tabla."[\"data\"] as \$key => \$data){\r\n";
$contenido .= "				\$arr[] = sanear_string(\$data);\r\n";
$contenido .= "			}\r\n";

$contenido .= "			\$respuesta = array(\r\n";
$contenido .= "				\"success\"=>true,\r\n";
$contenido .= "				\"total\"=>\$rs_".$nombre_tabla."[\"total\"],\r\n";
$contenido .= "				\"data\"=>\$arr\r\n";
$contenido .= "			);\r\n";
$contenido .= "			echo json_encode(\$respuesta);\r\n";
$contenido .= "			exit();\r\n";
$contenido .= "		break;\r\n";

$contenido .= "		case \"lista_filtro\":\r\n";
$contenido .= "			\$arr = array();\r\n";

$contenido .= "			\$start = (isset(\$start))?\$start:0;\r\n";
$contenido .= "			\$limit = (isset(\$limit))?\$limit:MAXREGEXCEL;\r\n";
$contenido .= "			\$page = (\$start==0)?1:(\$start/\$limit)+1;\r\n";
$contenido .= "			\$limit = \$page . \", \" . \$limit;\r\n";

$contenido .= "			\$rs_".$nombre_tabla." = \$".$nombre_tabla."Ado->lista_filtro(\$query, \$valuesqry, \$limit);\r\n";
$contenido .= "			if(!is_array(\$rs_".$nombre_tabla.")){\r\n";
$contenido .= "				\$respuesta = array(\r\n";
$contenido .= "					\"success\"=>false,\r\n";
$contenido .= "					\"errors\"=>array(\"reason\"=>\$rs_".$nombre_tabla.")\r\n";
$contenido .= "				);\r\n";
$contenido .= "				echo json_encode(\$respuesta);\r\n";
$contenido .= "				exit();\r\n";
$contenido .= "			}\r\n";
$contenido .= "			elseif(\$rs_".$nombre_tabla."[\"total\"] == 0){\r\n";
$contenido .= "				\$respuesta = array(\r\n";
$contenido .= "					\"success\"=>false,\r\n";
$contenido .= "					\"errors\"=>array(\"reason\"=>sanear_string(_NOSEENCONTRARONREGISTROS))\r\n";
$contenido .= "				);\r\n";
$contenido .= "				echo json_encode(\$respuesta);\r\n";
$contenido .= "				exit();\r\n";
$contenido .= "			}\r\n";
$contenido .= "			else{\r\n";
$contenido .= "				foreach(\$rs_".$nombre_tabla."[\"data\"] as \$key => \$data){\r\n";
$contenido .= "					\$arr[] = sanear_string(\$data);\r\n";
$contenido .= "				}\r\n";
$contenido .= "				\$respuesta = array(\r\n";
$contenido .= "					\"success\"=>true,\r\n";
$contenido .= "					\"total\"=>\$rs_".$nombre_tabla."[\"total\"],\r\n";
$contenido .= "					\"data\"=>\$arr\r\n";
$contenido .= "				);\r\n";
$contenido .= "				echo json_encode(\$respuesta);\r\n";
$contenido .= "				exit();\r\n";
$contenido .= "			}\r\n";
$contenido .= "		break;\r\n";


$contenido .= "	}\r\n";
$contenido .= "}\r\n";
$contenido .= "?>\r\n";

$archivo = $nombre_tabla . "/" ."operaciones_".$nombre_tabla.".php";
if(!file_exists($archivo)){
	$fp = fopen($archivo,"w+");
	fwrite($fp, $contenido);
	fclose($fp);
}
else {
	print $archivo . " ya existe, no se ha creado ";
}


$contenido = "";
$cabecera  = "";
$variables = "";


$contenido .= "<?php\r\n";
$contenido .= "session_start();\r\n";
$contenido .= "include_once('../../lib/config.php');\r\n";
$contenido .= "?>\r\n";
$contenido .= "/*<script>*/\r\n";

$arr_str_fields  = array();
$arr_col_model   = array();
$arr_form_reader = array();
$arr_form_items  = array();

foreach($result2 as $key => $campos){
	$tipo    = "string";
	$alinear = "left";
	$format  = "";
	$column_xtype = "";
	$column_format = "";
	
	if($campos["DATA_TYPE"] == "varchar"  || $campos["DATA_TYPE"] == "text"){
		$tipo = "string";
		$alinear = "left";
		$xtype   = "textfield";
	}
	elseif($campos["DATA_TYPE"] == "int" || $campos["DATA_TYPE"] == "tinyint" || $campos["DATA_TYPE"] == "mediumint" || $campos["DATA_TYPE"] == "smallint" || $campos["DATA_TYPE"] == "bigint" || $campos["DATA_TYPE"] == "double"){
		$tipo = "float";
		$alinear = "right";
		$xtype   = "numberfield";
		$column_xtype = "xtype:'numbercolumn', ";
	}
	elseif($campos["DATA_TYPE"] == "date"){
		$tipo    = "string";
		$alinear = "left";
		$xtype   = "datefield";
		$format  = ", dateFormat:'Y-m-d'";
		$column_xtype = "xtype:'datecolumn', ";
		$column_format = ", format:'Y-m-d'";
	}
	elseif($campos["DATA_TYPE"] == "datetime"){
		$tipo    = "date";
		$alinear = "left";
		$xtype   = "datefield";
		$format  = ", dateFormat:'Y-m-d H:i:s'";
		$column_xtype = "xtype:'datecolumn', ";
		$column_format = ", format:'Y-m-d, g:i a'";
	}
	
	$arr_str_fields[]  = "{name:'".$campos["COLUMN_NAME"]."', type:'".$tipo."'".$format."}";;
	$arr_col_model[]   = "{".$column_xtype."header:'<?= Lang::get('".$nombre_tabla.".columns_title.".$campos["COLUMN_NAME"]."'); ?>', align:'".$alinear ."', hidden:false, dataIndex:'".$campos["COLUMN_NAME"]."'".$column_format."}";
	$arr_form_reader[] = "{name:'".$campos["COLUMN_NAME"]."', mapping:'".$campos["COLUMN_NAME"]."', type:'".$tipo."'}";
	$str  = "defaults:{anchor:'100%'}\r\n";
	$str .= "			,items:[{\r\n";
	$str .= "				xtype:'".$xtype."'\r\n";
	$str .= "				,name:'".$campos["COLUMN_NAME"]."'\r\n";
	$str .= "				,fieldLabel:'<?= Lang::get('".$nombre_tabla.".columns_title.".$campos["COLUMN_NAME"]."'); ?>'\r\n";
	$str .= "				,id:module+'".$campos["COLUMN_NAME"]."'\r\n";
	$str .= "				,allowBlank:false\r\n";
	$str .= "			}]\r\n";
	
	$arr_form_items[] = $str;
}

$contenido .= "var store".ucfirst($nombre_tabla)." = new Ext.data.JsonStore({\r\n";
$contenido .= "	url:'".$nombre_tabla."/list'\r\n";
$contenido .= "	,root:'data'\r\n";
$contenido .= "	,sortInfo:{field:'".$llave_primaria."',direction:'ASC'}\r\n";
$contenido .= "	,totalProperty:'total'\r\n";
$contenido .= "	,baseParams:{id:'<?= \$id; ?>'}\r\n";
$contenido .= "	,fields:[\r\n";
$contenido .= "		".implode(",\r\n		",$arr_str_fields)."\r\n";
$contenido .= "	]\r\n";
$contenido .= "});\r\n";


$contenido .= "var combo".ucfirst($nombre_tabla)." = new Ext.form.ComboBox({\r\n";
$contenido .= "	hiddenName:'".$nombre_tabla."'\r\n";
$contenido .= "	,id:module+'combo".ucfirst($nombre_tabla)."'\r\n";
$contenido .= "	,fieldLabel:'<?= Lang::get('".$nombre_tabla.".columns_title.".$campos["COLUMN_NAME"]."'); ?>'\r\n";
$contenido .= "	,store:store".ucfirst($nombre_tabla)."\r\n";
$contenido .= "	,valueField:'".$llave_primaria."'\r\n";
$contenido .= "	,displayField:'".$nombre_tabla."_name'\r\n";
$contenido .= "	,typeAhead:true\r\n";
$contenido .= "	,forceSelection:true\r\n";
$contenido .= "	,triggerAction:'all'\r\n";
$contenido .= "	,selectOnFocus:true\r\n";
$contenido .= "	,allowBlank:false\r\n";
$contenido .= "	,listeners:{\r\n";
$contenido .= "		select: {\r\n";
$contenido .= "			fn: function(combo,reg){\r\n";
$contenido .= "				Ext.getCmp(module + '".$llave_primaria."').setValue(reg.data.".$llave_primaria.");\r\n";
$contenido .= "			}\r\n";
$contenido .= "		}\r\n";
$contenido .= "	}\r\n";
$contenido .= "});\r\n";

$contenido .= "var cm".ucfirst($nombre_tabla)." = new Ext.grid.ColumnModel({\r\n";
$contenido .= "	columns:[\r\n";
$contenido .= "		".implode(",\r\n		",$arr_col_model)."\r\n";
$contenido .= "	]\r\n";
$contenido .= "	,defaults:{\r\n";
$contenido .= "		sortable:true\r\n";
$contenido .= "		,width:100\r\n";
$contenido .= "	}\r\n";
$contenido .= "});\r\n";

$contenido .= "var tb".ucfirst($nombre_tabla)." = new Ext.Toolbar();\r\n\r\n";

$contenido .= "var grid".ucfirst($nombre_tabla)." = new Ext.grid.GridPanel({\r\n";
$contenido .= "	store:store".ucfirst($nombre_tabla)."\r\n";
$contenido .= "	,id:module+'grid".ucfirst($nombre_tabla)."'\r\n";
$contenido .= "	,colModel:cm".ucfirst($nombre_tabla)."\r\n";
$contenido .= "	,viewConfig: {\r\n";
$contenido .= "		forceFit: true\r\n";
$contenido .= "		,scrollOffset:2\r\n";
$contenido .= "	}\r\n";
$contenido .= "	,sm:new Ext.grid.RowSelectionModel({singleSelect:true})\r\n";
$contenido .= "	,bbar:new Ext.PagingToolbar({pageSize:10, store:store".ucfirst($nombre_tabla).", displayInfo:true})\r\n";
$contenido .= "	,tbar:tb".ucfirst($nombre_tabla)."\r\n";
$contenido .= "	,loadMask:true\r\n";
$contenido .= "	,border:false\r\n";
$contenido .= "	,title:''\r\n";
$contenido .= "	,iconCls:'icon-grid'\r\n";
$contenido .= "	,plugins:[new Ext.ux.grid.Excel()]\r\n";
$contenido .= "});\r\n";

$contenido .= "var form".ucfirst($nombre_tabla)." = new Ext.FormPanel({\r\n";
$contenido .= "	baseCls:'x-panel-mc'\r\n";
$contenido .= "	,method:'POST'\r\n";
$contenido .= "	,baseParams:{accion:'act'}\r\n";
$contenido .= "	,autoWidth:true\r\n";
$contenido .= "	,autoScroll:true\r\n";
$contenido .= "	,trackResetOnLoad:true\r\n";
$contenido .= "	,monitorValid:true\r\n";
$contenido .= "	,bodyStyle:'padding:15px;'\r\n";
$contenido .= "	,reader: new Ext.data.JsonReader({\r\n";
$contenido .= "		root:'data'\r\n";
$contenido .= "		,totalProperty:'total'\r\n";
$contenido .= "		,fields:[\r\n";
$contenido .= "			".implode(",\r\n			",$arr_form_reader)."\r\n";
$contenido .= "		]\r\n";
$contenido .= "	})\r\n";
$contenido .= "	,items:[{\r\n";
$contenido .= "		xtype:'fieldset'\r\n";
$contenido .= "		,title:'Information'\r\n";
$contenido .= "		,layout:'column'\r\n";
$contenido .= "		,defaults:{\r\n";
$contenido .= "			columnWidth:0.33\r\n";
$contenido .= "			,layout:'form'\r\n";
$contenido .= "			,labelAlign:'top'\r\n";
$contenido .= "			,border:false\r\n";
$contenido .= "			,xtype:'panel'\r\n";
$contenido .= "			,bodyStyle:'padding:0 18px 0 0'\r\n";
$contenido .= "		}\r\n";
$contenido .= "		,items:[{\r\n";
$contenido .= "			".implode("		},{\r\n			",$arr_form_items)."";
$contenido .= "		}]\r\n";
$contenido .= "	}]\r\n";
$contenido .= "});\r\n";


$archivo = $nombre_tabla . "/" . $nombre_tabla."_store.js.php";

if(!file_exists($archivo)){
	$fp = fopen($archivo,"w+");
	fwrite($fp, $contenido);
	fclose($fp);
}
else {
	print $archivo . " ya existe, no se ha creado ";
}


print " termino";
?>
