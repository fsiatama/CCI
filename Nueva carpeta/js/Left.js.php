<?php
//error_reporting(E_ERROR | E_PARSE);
//ini_set("display_errors",true);
//Trae la sesión que esté asignada
session_start();

//Variables de configuración del sistema
include ("../lib/config.php");
include (PATH_RAIZ."lib/lib_sesion.php");
include (PATH_RAIZ."lib/lib_funciones.php");
include_once (PATH_RAIZ.'lib/conexion/conexion.php');

?>
/*<script>*/

var idioma = 'es';

<?php

include(PATH_RAIZ.'ssgroup/lib/categoria_menu/categoria_menuAdo.php');
$categoria_menuAdo = new Categoria_menuAdo('ssgroup');
$categoria_menu    = new Categoria_menu;

include(PATH_RAIZ.'ssgroup/lib/opc_menu/opc_menuAdo.php');
$opc_menuAdo = new Opc_menuAdo('ssgroup');
$opc_menu    = new Opc_menu;

include(PATH_RAIZ.'ssgroup/lib/categoria_menu_using/categoria_menu_usingAdo.php');
$categoria_menu_usingAdo = new Categoria_menu_usingAdo('ssgroup');
$categoria_menu_using    = new Categoria_menu_using;

$rsCategoria_menu = $categoria_menuAdo->lista($categoria_menu);
$arr_modulos = array();
$arr_categorias = array();
foreach($rsCategoria_menu as $key => $data){
	$menu_alias = strtolower(str_replace(" ","_",$data["categoria_menu_nombre"]));
	$str  = "menu_".$menu_alias." = function(){";
	$categoria_menu_using->setCategoria_menu_using_categoria_menu_id($data["categoria_menu_id"]);
	$rsCategoria_menu_using = $categoria_menu_usingAdo->lista($categoria_menu_using);
	if($rsCategoria_menu_using){
		foreach($rsCategoria_menu_using as $key1 => $data1){
			$str .= "using('".$data1["categoria_menu_using_js"]."');";
		}
	}

	$str .= "return \"{";
	$str .= "title:'".$data["categoria_menu_nombre"]."'";
	$str .= ",iconCls:'".$menu_alias."'";
	$str .= ",items:[";

	$opc_menu->setOpc_menu_categoria_menu_id($data["categoria_menu_id"]);
	$rsOpc_menu = $opc_menuAdo->listaPerfil($opc_menu, $_SESSION['session_perfil']);
	$arr_menu_itms = array();
	if($rsOpc_menu){
		foreach($rsOpc_menu as $key1 => $data1){
			if($data1["opc_menu_oculto"] == 0){
				$opc_menu_alias = strtolower(str_replace(" ","_",$data1["opc_menu_nombre"]));
				$opc_menu_alias = str_replace("&_","",$opc_menu_alias);
				if($data["categoria_menu_id"] == CATEGORIA_RESUME_OBL || $data["categoria_menu_id"] == CATEGORIA_RESUME_OPC){
					//los icons del resume se colocan de acuerdo a la seecion si esta completa o incompleta
					$cls = validar_seccion_cv($data1["opc_menu_id"], $_SESSION["session_usuario_id"]);
				}
				else{
					$cls = $opc_menu_alias;
				}
				$str1  = "{";
					$str1 .= "id:'".$opc_menu_alias."'";
					$str1 .= ",title:'".$data1["opc_menu_nombre"]."'";
					$str1 .= ",iconCls:'".$cls."'";
					//$str1 .= ",disabled:'true'";
					$str1 .= ",titleTab: '".$data1["opc_menu_nombre"]."'";
					$str1 .= ",url:'".$data1["opc_menu_url"]."'";
					$str1 .= ",params:{code:'".$data1["opc_menu_id"]."',id:'".$opc_menu_alias."',url:'".$data1["opc_menu_url"]."'}";
					$str1 .= ",handler:Ext.getCmp('oeste').addTab";
				$str1 .= "}";
				$arr_menu_itms[] = $str1;
			}
		}
		$str .= implode(",",$arr_menu_itms);
				$str .= "]";
			$str .= "}\";";
		$str .= "};";
		$arr_modulos[] = "menu_".$menu_alias."();";
		$arr_categorias[] = $str;
	}
}

print implode("\r\n",$arr_categorias);


?>


Left = function() {

	Left.superclass.constructor.call(this, {
		 id: 'oeste'
		,region: 'west'
		,layout: 'border'
		,title:	'Menu'
		,margins: '5 0 5 5'
		,cmargins: '5 5 5 5'
		,split: true
		,collapsible:true
		,width:	160
		,border: false
		,frame:	true
		,items: [{
			 id:'menupersonal'
			,region:'center'
			,layout:'links'
			,margins:'0 0 0 0'
			,split:false
			,collapsible:false
			,autoScroll:true
			,border:false
			,frame:false
			,alwaysShowTabs:true
			,maskDisabled:false
			,escale:'medium'
			,defaults: {
				 bodyStyle:'padding-left:8px; padding-top:3px; padding-bottom:3px;'
			}
			,layoutConfig: {
				 hideCollapseTool:false
				,renderHidden:false
				,titleCollapse:true
				,animate:true
			}
		}]
	});

	this.getMenu();
};

Ext.extend(Left, Ext.Panel, {
	getMenu: function(response){
		//var listadoDeModulos = '';
		<?php
			print "var listadoDeModulos = " . str_replace(";","",implode(" + ', ' + ",$arr_modulos)).";\r\n";
		?>
		listadoDeModulos = '['+listadoDeModulos+']';
		listadoDeModulos = Ext.util.JSON.decode(listadoDeModulos);
		this.addMenu(listadoDeModulos);

	}
	,addMenu: function(data){
		var accordion = Ext.getCmp('menupersonal');
		for (var key in data) {
			var p = new Ext.Panel({
				frame:false,
				border:false,
				autoHeight:true,
				title:data[key].title,
				items:data[key].items,
				iconCls:data[key].iconCls
			});
			if (p.title != undefined) {
				if ((p.title).length != 0) {
					accordion.add(p).show();
					accordion.doLayout();
				}
			}
		}
	}
	,addTab: function(event,element,linkCmp){
		var tabPanel = Ext.getCmp('tabpanel');

		var id = 'tab-'+linkCmp.id;
		var title = linkCmp.titleTab;
		var iconCls = linkCmp.iconCls;
		var url = '<?php print URL_RAIZ; ?>'+linkCmp.url;
		var params = linkCmp.params;
		var mantenimiento = linkCmp.mantenimiento;

		var tab = tabPanel.getItem(id);

		if(mantenimiento){
			Ext.getCmp('oeste').mantenimiento();
		}else if(!tab){
			tab = tabPanel.add({
				 id: id
				,title: title
				,iconCls: iconCls
				,closable: true
				,autoScroll: false
				,autoShow: true
				,border: false
				,frame: false
				,buttonAlign: 'center'
				,layout: 'fit'
				,plugins: new Ext.ux.Plugin.RemoteComponent({
					 url: url
					,params: params
					,disableCaching: false
					,method: 'POST'
					,loadOn: 'show'
					,mask: Ext.getCmp('tabpanel').body
					,maskConfig:{
						msg: Ext.LoadMask.prototype.msg
					}
				})
			});
    	}
		tabPanel.doLayout();
		tabPanel.setActiveTab(tab);
	}
});