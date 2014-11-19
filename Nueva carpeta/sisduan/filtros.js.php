<?php
//Trae la sesión que esté asignada
session_start();

//Variables de configuración del sistema
include ("../lib/config.php");
include (PATH_APP."lib/idioma.php");
include (PATH_APP."lib/lib_sesion.php");
include (PATH_APP."lib/lib_sphinx.php");
include (PATH_APP."lib/lib_funciones.php");

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

foreach($filtrosIntercambioSisduan[$intercambio] as $key => $filtro){
	if($filtro['filtro'] == $id){
		$filtro_nombre = traducir(($filtro['nombre']));
		$is_fulltext   = ($filtro['tabla'] == 'fulltext')?true:false;
	}
}

if($filtro_nombre == ""){
	$respuesta = array(
		"success"=>false,
		"errors"=>array("reason"=>"Filtro no definido")
	);
}
if(!$is_fulltext){
	$return = "
	/*<script>*/
	(function(){
		var store_".$id." = new Ext.data.JsonStore({
			 url:'proceso/datos_filtros/'
			,root:'datos'
			,remoteSort:true
			,totalProperty:'total'
			,fields:[
				 {name:'valor_id',type:'string'}
				,{name:'valor_desc',type:'string'}
				,{name:'valor_desc_ori',type:'string'}
			]
			,baseParams:{
				accion:'lista'
				,paisId:'". $paisId."'
				,filtroId:'". $id."'
				,intercambio:'". $intercambio."'
				,producto:'". $producto."'
			}
			,listeners:{
				'beforeload':{
					fn:function(store, options){
						store.setBaseParam('selected', Ext.getCmp('".$modulo."combo_". $id."').getValue());
					}
				}
			}
		});
		var resultTpl = new Ext.XTemplate(
			'<tpl for=\".\"><div class=\"search-item\"><span><b>{valor_id}</b>&nbsp;-&nbsp;{valor_desc}</span></div></tpl>'
		);
		var combo_".$id." = new Ext.ux.form.SuperBoxSelect({
			id:'".$modulo."combo_".$id."'
			,xtype:'superboxselect'
			,fieldLabel:'". _BUSCAR."'
			,emptyText:'".($filtro_nombre)."... '
			,resizable:false
			,displayFieldTpl:'({valor_id}) - {valor_desc_ori}'
			,name:'".$id."'
			,anchor:'88%'
			,store:store_".$id."
			,minChars:2
			,displayField:'valor_desc_ori'
			,valueField:'valor_id'
			,forceSelection:true
			,allowNewData:true
			,extraItemCls:'x-tag'
			,allowBlank:false
			,extraItemStyle:'border-width:2px'
			,stackItems:true
			,tpl:resultTpl
			,mode:'remote'
			,queryDelay:0
			,triggerAction:'all'
			,itemSelector:'.search-item'
			,pageSize:10
			,listeners:{
				newitem: function(bs,v,f){
					v = v +'';
					v = v.slice(0,1).toUpperCase() + v.slice(1).toLowerCase();
					var newObj = {
						valor_desc: v,
						valor_desc_ori: v
					};
					console.log(bs);
					bs.addNewItem(newObj);
				}
			}
		});
		return combo_".$id.";
	})()
	";
}
else{
	$return = "
		
		/*<script>*/
		
		(function(){
			
			var combo_".$id." = new Ext.form.TextField({
				id:'".$modulo."combo_".$id."'
				,fieldLabel:'"._BUSCAR."'
				,emptyText:'".($filtro_nombre)."... '
				,name:'".$id."'
				,anchor:'88%'
				,allowBlank:false
				,xtype:'textfield'
			});
			
			return combo_".$id.";
		})()
	";
}
print ($return);
exit();