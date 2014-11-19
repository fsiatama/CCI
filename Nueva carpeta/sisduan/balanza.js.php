<?php
//Trae la sesión que esté asignada
session_start();
//Variables de configuración del sistema
include_once("../lib/config.php");
include_once(PATH_APP."lib/idioma.php");
include_once(PATH_APP."lib/lib_sphinx.php");
include_once(PATH_APP."lib/lib_funciones.php");
include_once(PATH_APP."lib/lib_sesion.php");

$descripcion = utf8_decode($descripcion);

include_once(PATH_RAIZ.'sicex_r/lib/pais/paisAdo.php');
$paisAdo = new PaisAdo('sicex_r');
$paisObj    = new Pais;
$paisObj->setPais_id($pais);
$rsPais  = $paisAdo->lista($paisObj);
$bandera = explode(".",$rsPais[0]["pais_bandera"]);

$campos_store    = array();
$campos_cm       = array();

$campos_store[] = array("name"=>"id", "type"=>"float");
$campos_store[] = array("name"=>"ano", "type"=>"string");
$campos_store[] = array("name"=>"valor_impo", "type"=>"float");
$campos_store[] = array("name"=>"valor_expo", "type"=>"float");
$campos_store[] = array("name"=>"valor_balanza", "type"=>"float");

$campos_cm[] = array("dataIndex"=>"ano", "header"=>utf8_encode(_ANO), "align"=>"right", "width"=>50);
$campos_cm[] = array("dataIndex"=>"valor_expo", "header"=>utf8_encode(_EXPO), "align"=>"right", "renderer"=>"function()numberFormat");
$campos_cm[] = array("dataIndex"=>"valor_impo", "header"=>utf8_encode(_IMPO), "align"=>"right", "renderer"=>"function()numberFormat");
$campos_cm[] = array("dataIndex"=>"valor_balanza", "header"=>utf8_encode(_BALANZA), "align"=>"right", "renderer"=>"function()numberFormat");


$html = '
	<div class="">
		<div class="page-head">
			<h2 class="center"><i class="icon med '.$bandera[0].'"></i> '.utf8_decode($descripcion).'</h2>
        	<div class="clearfix"></div>
		</div>
	</div>
';
$html = comprimir($html);


?>
/*<script>*/

(function(){
	var params = <?php print json_encode($_POST); ?>;
	var modulo = 'balanza-<?php print $id; ?>';
	
<?php
	print "
	var storePais_".FILTRO_PAISORIGEN." = new Ext.data.JsonStore({
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
			,paisId:'".$pais."'
			,filtroId:'".FILTRO_PAISORIGEN."'
			,intercambio:'0'
		}
	});
	var comboPais_".FILTRO_PAISORIGEN." = new Ext.form.ComboBox({
		id:modulo+'pais'
		,hiddenName:'".FILTRO_PAISORIGEN."'
		,fieldLabel:'"._TABPAISCIUPRODESTEXT."'
		,store:storePais_".FILTRO_PAISORIGEN."
		,displayField:'valor_desc_ori'
		,valueField:'valor_id'
		,typeAhead:false
		,forceSelection:true
		,hideTrigger:true
		,selectOnFocus:true
		,anchor:'88%'
		,emptyText:'".(_BUSCAR)."... '
	});
	
	var storePosicion_".FILTRO_POSICION." = new Ext.data.JsonStore({
		 url:'proceso/datos_filtros/'
		,root:'datos'
		,remoteSort:true
		,totalProperty:'total'
		,fields:[
			 {name:'valor_id',type:'string'}
			,{name:'valor_desc',type:'string'}
			,{name:'valor_desc_ori',type:'string', convert:function(txt,row){ return row.valor_id + ' - ' + txt; }}
		]
		,baseParams:{
			accion:'lista'
			,paisId:'".$pais."'
			,filtroId:'".FILTRO_POSICION."'
			,intercambio:'0'
		}
	});
	var comboPosicion_".FILTRO_POSICION." = new Ext.form.ComboBox({
		id:modulo+'posicion'
		,hiddenName:'".FILTRO_POSICION."'
		,fieldLabel:'"._POSICION."'
		,store:storePosicion_".FILTRO_POSICION."
		,displayField:'valor_desc_ori'
		,valueField:'valor_id'
		,typeAhead:false
		,forceSelection:true
		,hideTrigger:true
		,minChars:2
		,selectOnFocus:true
		,anchor:'88%'
		,emptyText:'".(_BUSCAR)."... '
	});
	
	var formFiltroBalanza = new Ext.form.FormPanel({
		id:modulo+'formFiltroBalanza'
		,layout:'column'
		,margins:'5 0 0 0'
		,cmargins:'5 0 0 0'
		,buttonAlign:'center'
		,bodyStyle:'padding:10px;'
		,bodyCssClass:'matter'
		,autoHeight:true
		,items:[{
			columnWidth:.5
			,layout:'form'
			,labelAlign:'top'
			,bodyStyle:'padding:10px;'
			,items:[comboPais_".FILTRO_PAISORIGEN."]
		},{
			columnWidth:.5
			,layout:'form'
			,labelAlign:'top'
			,autoHeight:true
			,bodyStyle:'padding:10px;'
			,items:[comboPosicion_".FILTRO_POSICION."]
		}]
		,buttons:[{
			text:'"._BTNCLEAN."'
			,iconCls:'icon-clear'
			,handler:function(){
				formFiltroBalanza.getForm().reset();
			}
		},{
			text:'"._BUSCAR."'
			,iconCls: 'icon-find'
			,handler: function(){
				storeBalanza.load();
			}
		}]	
	});
	
	/****************************************************** Objetos para la informacion de la balanza comercial********************************************************************/
	var altura = Math.floor(Ext.getCmp('tabpanel').getInnerHeight() - 260);
		
	var storeBalanza = new Ext.data.GroupingStore({
		proxy:new Ext.data.HttpProxy({
			url:'proceso/balanza/'
			,timeout: 1000000000
			,method: 'POST'
		})
		,autoLoad:false
		,reader: new Ext.data.JsonReader({
			root: 'datos'
			,totalProperty: 'total'
		}
		,".json_encode($campos_store)."
		)
		,remoteSort:true
		,baseParams:{accion:'balanza', pais_id:".$pais."}
	});
	
	storeBalanza.on('beforeload', function(){
		var filtros_adicionales = [];
		if(Ext.getCmp(modulo+'pais').getValue()){
			filtros_adicionales.push({'".FILTRO_PAISORIGEN."' : Ext.getCmp(modulo+'pais').getValue()});
		}
		if(Ext.getCmp(modulo+'posicion').getValue()){
			filtros_adicionales.push({'".FILTRO_POSICION."' : Ext.getCmp(modulo+'posicion').getValue()});
		}
		storeBalanza.baseParams['filtros_adicionales'] = Ext.encode(filtros_adicionales);
	});
	storeBalanza.on('load', function(store){
		FusionCharts.setCurrentRenderer('javascript');
		if(FusionCharts('myChartId_balanza".$pais."')){
			FusionCharts('myChartId_balanza".$pais."').dispose();
		}
		var myChart = new FusionCharts('".AREA."', 'myChartId_balanza".$pais."', '100%', '100%', '0', '1');
		myChart.setTransparent(true);
		myChart.setJSONData(store.reader.jsonData.json_grafico);
		myChart.render('chart_panel_balanza".$pais."');
		
	});
	var colModelBalanza = new Ext.grid.ColumnModel({
		columns:".json_encode_jsfunc($campos_cm)."
		,defaults: {
			sortable:true
		}
	});
	
	var gridBalanza = new Ext.grid.GridPanel({
		border:true
		//,disabled:true
		,monitorResize:true
		,store:storeBalanza
		,colModel:colModelBalanza
		,stateful:true
		,columnLines:true
		,stripeRows:true
		,viewConfig: {
			forceFit:true
		}
		,enableColumnMove:false
		,id:modulo+'gridBalanza'			
		,sm:new Ext.grid.RowSelectionModel({singleSelect:true})
		,bbar:[]
		,tbar:[]
		,iconCls:'silk-grid'
		,plugins:[new Ext.ux.grid.Excel({position:'top', title:'"._SAVEAS."'})]
		,layout:'fit'
		,height:altura
		,autoWidth:true
		,margins:'10 15 5 0'
	});
	/*elimiar cualquier estado de la grilla guardado con anterioridad */
	Ext.state.Manager.clear(gridBalanza.getItemId());
	
	storeBalanza.load();
	/******************************************************************************************************************************************************************************/
	
	var container = new Ext.Panel({
		xtype:'panel'
		,layout:'border'
		,border:false
		,bodyCssClass:'mainbar'
		,items:[{
			region:'north'
			,border:false
			,html:'".$html."'
			,listeners:{
				afterrender:{
					fn:function(){
					}				
				}
			}
		},{
			layout:'column'
			,baseCls:''
			,region:'center'
			,defaults:{columnWidth:1}
			,bodyStyle:'padding:10px;'
			,items:[
				formFiltroBalanza
			,{
				xtype:'fieldset'
				,title:''
				,collapsible:false
				,layout:'column'
				,defaults:{
					columnWidth:0.5
					,border:false
					,xtype:'panel'
					,style:{padding:'10px'}
					,layout:'fit'
				}
				,items:[{
					defaults:{anchor:'100%'}
					,height:altura+20
					,html:'<div id=\"chart_panel_balanza".$pais."\"></div>'
					,items:[{
						xtype:'panel'
						,id:'chart_panel_balanza".$pais."'
						,plain:true
					}]
				},{
					defaults:{anchor:'100%'}
					,items:[gridBalanza]
				}]
			}]
		}]
	});
	
	function numberFormat(value, decimals){
		if(!isNaN(parseFloat(value)) && isFinite(value)){
			if(decimals){
				return Ext.util.Format.number(value,'0,0.00');
			}
			else{
				return Ext.util.Format.number(value,'0,0');
			}
		}
		else{
			return value;
		}
	}
	
	";
?>			
	return container;

})()