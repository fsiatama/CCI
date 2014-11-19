<?php
//ini_set('display_errors', '1');
//Trae la sesión que esté asignada
session_start();
//Incluye el diccionario

//include($_SESSION['session_diccionario']);
//Variables de configuración del sistema
include ("../lib/config.php");
include_once(PATH_APP."lib/idioma.php");
include_once(PATH_APP."lib/lib_sesion.php");

$descripcion = utf8_decode($descripcion);

include_once(PATH_RAIZ.'sicex_r/lib/pais/paisAdo.php');
$paisAdo = new PaisAdo('sicex_r');
$paisObj    = new Pais;
$paisObj->setPais_id($pais);
$rsPais  = $paisAdo->lista($paisObj);
//print_r($paisDs);
//$pais_nombre = ($rsPais[0]["pais_nombre"]);
//$pais_nombre = str_replace("ñ","n",$pais_nombre);
//$pais_nombre = "_".strtoupper(str_replace(" ","_",$pais_nombre));

$pais_iso = $rsPais[0]["pais_uupdate"];

$bandera = explode(".",$rsPais[0]["pais_bandera"]);

$idm = "en";
if($_GET['cambiaidioma'] == "ES"){
	$idm = "es";
}

$url = "http://api.worldbank.org/".$idm."/countries/".$pais_iso."?format=json";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
$resultado = json_decode(curl_exec($ch), true);

$pais_nombre = $resultado[1][0]["name"];

$url_mapa = "http://maps.googleapis.com/maps/api/staticmap?markers=".$pais_nombre."&language=".$idm."&sensor=false";

$year_fin = date("Y") - 2;
$year_ini = $year_fin - 5;
$url_pib = "http://api.worldbank.org/".$idm."/countries/".$pais_iso."/indicators/NY.GDP.MKTP.CD?date=".$year_fin."&format=json";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url_pib);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
$resultado_pib = json_decode(curl_exec($ch), true);

$url_poblacion = "http://api.worldbank.org/".$idm."/countries/".$pais_iso."/indicators/SP.POP.TOTL?date=".$year_fin."&format=json";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url_poblacion);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
$resultado_poblacion = json_decode(curl_exec($ch), true);

setlocale(LC_MONETARY, 'en_US');
$html = '
	<div class="">
		<div class="page-head">
			<h2 class="pull-left"><i class="icon med '.$bandera[0].'"></i> '.utf8_decode($pais_nombre).'</h2>
        	<div class="clearfix"></div>
		</div>
		<div class="matter">
			<div class="container-fluid">
				<div class="row-fluid">
					<div class="span12">
					  <!-- List starts -->
					  <ul class="today-datas">
						<!-- List #1 -->
						<li>
						  <!-- Graph -->
						  <div><span id="todayspark1" class="spark">'.number_format($resultado_poblacion[1][0]["value"]).'</span></div>
						  <!-- Text -->
						  <div class="datas-text">'.utf8_decode($resultado_poblacion[1][0]["indicator"]["value"]).' ('.$year_fin.')</div>
						</li>
						<li>
						  <div><span id="todayspark2" class="spark">'.utf8_decode($resultado[1][0]["capitalCity"]).'</span></div>
						  <div class="datas-text">'._CAPITAL.'</div>
						</li>
						<li>
						  <div><span id="todayspark3" class="spark">'.utf8_decode($resultado[1][0]["incomeLevel"]["value"]).'</span></div>
						  <div class="datas-text">'._NIVEL_INGRESOS.'</div>
						</li>
						<li>
						  <div><span id="todayspark4" class="spark">'.money_format('%i', $resultado_pib[1][0]["value"]).'</span></div>
						  <div class="datas-text">'.utf8_decode($resultado_pib[1][0]["indicator"]["value"]).' ('.$year_fin.')</div>
						</li> 
					  </ul> 
					</div>
				</div>
			</div>
		</div>
	</div>
';
		
		
$html = comprimir($html);

?>
/*<script>*/


(function(){
	
	<?php print "var tabid = 'tab-".$id."';"; ?>
	//recolector de basura
	Ext.getCmp(tabid).on('beforeclose', function(){
		dialogoReportes.destroy();
		dialogoPeriodo.destroy();
		if(Ext.getCmp(modulo+'helpWindow')){
			Ext.getCmp(modulo+'helpWindow').destroy();
		}
	});
	
	Ext.form.Field.prototype.msgTarget = 'side';	
	Ext.ns('Reportes');
	var modulo = 'sisduan-<?php print $id; ?>';	
	var reporte_id = 0;
	
	<?php
	print "
	var meses = [
		[1,'"._ENERO."'],
		[2,'"._FEBRERO."'],
		[3,'"._MARZO."'],
		[4,'"._ABRIL."'],
		[5,'"._MAYO."'],
		[6,'"._JUNIO."'],
		[7,'"._JULIO."'],
		[8,'"._AGOSTO."'],
		[9,'"._SEPTIEMBRE."'],
		[10,'"._OCTUBRE."'],
		[11,'"._NOVIEMBRE."'],
		[12,'"._DICIEMBRE."']
	];
	var periodos = [
		 [".PERIODOPERSONALIZADO.",'"._PERIODOPERSONALIZADO."']
		,[".ULTIMO_ANO.",'"._ULTIMO_ANO."']
		,[".ULTIMO_SEMESTRE.",'"._ULTIMO_SEMESTRE."']
		,[".ULTIMO_TRIMESTRE.",'"._ULTIMO_TRIMESTRE."']
		,[".ULTIMO_BIMESTRE.",'"._ULTIMO_BIMESTRE."']
		,[".ULTIMO_MES.",'"._ULTIMO_MES."']
	];
	var anios = [[".implode("],[",$_ano)."]];
	";
	?>
	var storePeriodoPersonalizado = new Ext.data.ArrayStore({
		fields:['periodo','periodoDes']
		,data:periodos
	});
	var storeAnio = new Ext.data.ArrayStore({
		fields:['anio']
		,data:anios
	});	
	var storeMesIni = new Ext.data.ArrayStore({
		fields:['perini','mes']
		,data:meses
	});
	var storeMesFin = new Ext.data.ArrayStore({
		fields:['perfin','mes']
		,data:meses
	});
	
	var formPeriodo = new Ext.FormPanel({
		labelAlign:'top'
		,autoWidth:true
		,autoHeight:true
		,autoScroll:true
		,buttonAlign:'center'
		,border:false
		,id:modulo+'formPeriodo'
		,items:[{
			xtype:'fieldset'
			,title:''
			,layout:'column'
			,defaults:{columnWidth:1,layout:'form',border:false,xtype:'panel',bodyStyle:'padding:15px'}
			,items:[{
				xtype:'label'
				,text:'<?php print _PERIODOPREDEFINIDO; ?>'
			},{
				xtype:'combo'
				,store:storePeriodoPersonalizado
				,displayField:'periodoDes'
				,valueField:'periodo'
				,typeAhead: true
				,mode:'local'
				,name:'periodoPersonalizado'
				,allowBlank:false
				,forceSelection:true
				,triggerAction:'all'
				,selectOnFocus:true
				,id:modulo+'periodoPersonalizado'
				,listeners:{
					select: {
						fn: function(combo,reg){
							var logica = false;
							if(reg.data.periodo != <?php print PERIODOPERSONALIZADO; ?>){
								logica = true;
								Ext.getCmp(modulo+'mesIni').setValue('').clearInvalid();
								Ext.getCmp(modulo+'mesFin').setValue('').clearInvalid();
								Ext.getCmp(modulo+'anio').setValue('').clearInvalid();
							}
							Ext.getCmp(modulo+'mesIni').setDisabled(logica);
							Ext.getCmp(modulo+'mesFin').setDisabled(logica);
							Ext.getCmp(modulo+'anio').setDisabled(logica);
						}
					}
				}
			},{
				xtype:'label'
				,text:'<?php print _ANIO; ?>'
			},{
				xtype:'combo'
				,store:storeAnio
				,displayField:'anio'
				,valueField:'anio'
				,typeAhead: true
				,mode:'local'
				,name:'anio'
				,allowBlank:false
				,forceSelection:true
				,triggerAction:'all'
				,selectOnFocus:true
				,id:modulo+'anio'
			},{
				xtype:'label'
				,text:'<?php print _PERIODODESDE; ?>'
			},{
				xtype:'combo'
				,store:storeMesIni
				,displayField:'mes'
				,valueField:'perini'
				,typeAhead: true
				,mode:'local'
				,name:'perini'
				,allowBlank:false
				,forceSelection:true
				,triggerAction:'all'
				,selectOnFocus:true
				,id:modulo+'mesIni'
			},{
				xtype:'label'
				,text:'<?php print _PERIODOHASTA; ?>'
			},{
				xtype:'combo'
				,store:storeMesFin
				,displayField:'mes'
				,valueField:'perfin'
				,typeAhead: true
				,mode:'local'
				,name:'perfin'
				,allowBlank:false
				,forceSelection:true
				,triggerAction:'all'
				,selectOnFocus:true
				,id:modulo+'mesFin'
			}]
		}]
		,buttons: [{
			 text: "<?php print _BTNCANCEL; ?>"
			,iconCls: 'icon-close'
			,handler: function(){
				//formPeriodo.getForm().reset();
				dialogoPeriodo.hide();
			}
		},{
			 text:'<?php print _BTNACEPT; ?>'
			,iconCls:'silk-accept'
			,scope:this
			,handler:function(){
				if(formPeriodo.getForm().isValid()){
					dialogoPeriodo.hide();
					var node;
					if(sisduanReportesTree.getSelectionModel().getSelectedNode()){
						node = sisduanReportesTree.getSelectionModel().getSelectedNode();
					}
					else{
						node = sisduanReportesTree.getRootNode();
					}
					if(node.leaf){
						sisduanReportesTree.consultar(node.id);
					}
				}
			}
		}]
	});
	var dialogoPeriodo = new Ext.Window({
		id:modulo+'dialogoPeriodo'
		,layout:'fit'
		,width:230
		,autoHeight:true
		,modal:true
		,draggable:false
		,resizable:false
		,items:[formPeriodo]
		,closeAction:'hide'		
		,border:true
		,plain:true
	});
	
	var formReportes = new Ext.FormPanel({
		labelAlign:'top'
		,method:'POST'
		,url:'proceso/reportes/'
		,autoWidth:true
		,autoHeight:true
		,autoScroll:true
		,buttonAlign:'center'
		,monitorValid:true
		,items:[{
			xtype:'fieldset'
			,layout:'column'
			,defaults:{columnWidth:1,layout:'form',labelAlign:'top',border:false,xtype:'panel'}
			,items:[{
				defaults:{anchor:'100%'}
				,items:[{
					xtype:'panel'
					,border:false
					,html:'<div class=\"row-fluid marginr20\">' +
						'	<div class=\"well span12\">' +
						'		<p class="padded10"><i class="icon-info-sign"></i>&nbsp; <?php print _AYUDAENVIARREPORTE; ?></p>' +
						'	</div>' +
						'</div>'
				}]
			},{
				defaults:{anchor:'88%'}
				,items:[{
					xtype:'radiogroup'
					,fieldLabel:'<?php print _TITENVIARREPORTE; ?>'
					,id:modulo+'reportes_enviar'
					,allowBlank:false
					,items: [
						{boxLabel: '<?php print _SI; ?>', inputValue:1, name:'reportes_enviar'},
						{boxLabel: '<?php print _NO; ?>', inputValue:0, name:'reportes_enviar'}
					]
					,listeners:{
						'change': {
							fn: function(radio, checked){
							}
						}
					}
				}]
			}]
		},{
			xtype:'hidden'
			,name:'accion'
			,id:modulo+'reportesAccion'
			,value:'actEnvio'
		},{
			xtype:'hidden'
			,name:'reporte'
			,id:modulo+'reporte'
		}]
		,buttons: [{
			text: '<?php print _BTNCANCEL; ?>'
			,iconCls: 'silk-cancel'
			,handler: function(){
				formReportes.getForm().reset();
				dialogoReportes.hide();
			}
		},{
			text: '<?php print _BTNACEPT; ?>'
			,iconCls: 'icon-save'
			,handler:function(){
				if(formReportes.getForm().isValid()){
					formReportes.getForm().submit({
						waitMsg:'Saving....'
						,waitTitle:'Wait please...'
						,reset:true
						,success:function(formReportes, action){
							sisduanReportesTree.getRootNode().reload();
							dialogoReportes.hide();
						}
						,failure:function(formReportes, action){
							dialogoReportes.hide();
							Ext.Msg.show({
							   title:'Error',
							   buttons:Ext.Msg.OK,
							   msg:Ext.decode(action.response.responseText).errors.reason,
							   animEl:'elId',
							   icon:Ext.MessageBox.ERROR
							});
						}
					});
				}
			}
		}]
	});

	var dialogoReportes = new Ext.Window({
		border:true
		,plain:true
		,closeAction:'hide'
		,id:modulo+'dialogoReportes'
		,width:550
		,layout:'fit'
		,autoHeight:true
		,modal:true
		,resizable:false
		,draggable:false
		,items:[formReportes]
	});
	
	var root = new Ext.tree.AsyncTreeNode({
		 text: '<?php print $descripcion; ?>'
		,type: 'root'
		,draggable: false
		,id: modulo+'root'
		,expanded: true
		,uiProvider: false
		,iconCls: 'silk-folder'
	});	
	
	Reportes.tree = function() {	
		Reportes.tree.superclass.constructor.call(this, {
			id: modulo+'reportes'
			,header: false
			,collapseAllText:'<?php print _COLLAPSEALLTEXT; ?>'
			,collapseText:'<?php print _COLLAPSETEXT; ?>'
			,deleteText:'<?php print _DELETETEXT; ?>'
			,deleteInfoText:'<?php print _DELETEINFOTEXT; ?>'
			,expandAllText:'<?php print _EXPANDALLTEXT; ?>'
			,expandText:'<?php print _EXPANDTEXT; ?>'
			,insertText:'<?php print _INSERTTEXT; ?>'
			,newText:'<?php print _NEWTEXT; ?>'
			,reallyWantText:'<?php print _REALLYWANTTEXT; ?>'
			,reloadText:'<?php print _RELOADTEXT; ?>'
			,renameText:'<?php print _RENAMETEXT; ?>'
			,ddAppendOnly:true
			,minSize: 230
			,maxSize: 500
			,region: 'west'
			,autoScroll: true
			,animate: true
			,containerScroll: true
			,border: false
			,enableDD: true
			,ddGroup:'treeReportes'
			,rootVisible: true
			,maskDisabled: false
			,useArrows: true
			,collapsible: true
			,collapseMode:'mini'
			,lines: true
			,split: true
			,width:	200
			,root:root
			,loader: {
				 url:'proceso/reportes/'
				,baseParams:{
					 accion:'lista'
					 ,pais_id:<?php print $pais; ?>
					 ,producto:'<?php print $producto; ?>'
				}
				,baseAttrs: {
					 iconCls: 'silk-folder'
				}
			}
			,tbar:['Filter:', {
				 xtype:'trigger'
				,triggerClass:'x-form-clear-trigger'
				,onTriggerClick:function() {
					this.setValue('');
					sisduanReportesTree.filter.clear();
				}
				,id:'filter'
				,enableKeyEvents:true
				,listeners:{
					keyup:{buffer:150, fn:function(field, e) {
						if(Ext.EventObject.ESC == e.getKey()) {
							field.onTriggerClick();
						}
						else {
							var val = this.getRawValue();
							var re = new RegExp('.*' + val + '.*', 'i');
							sisduanReportesTree.filter.clear();
							sisduanReportesTree.filter.filter(re, 'text');
						}
					}}
				}
			},'->',{
				 tooltip:'<?php print _AYUDA; ?>'
				,iconCls:'silk-help'
				,text:'<?php print _AYUDA; ?>'
				,handler:function(){
					ayuda_reportes_tree();
				}
			}]
			,listeners: {
				'beforecollapsenode': function(node, deep, anim){
					panelInicial();
				}
				,'click': function(node, e){
					locationbar.setNode(node);
					Ext.getCmp(modulo+'btnEdit').setDisabled(!node.leaf);
					Ext.getCmp(modulo+'btnCopy').setDisabled(!node.leaf);
					Ext.getCmp(modulo+'btnDel').setDisabled(!node.leaf);
					Ext.getCmp(modulo+'btnDateEdit').setDisabled(!node.leaf);
					Ext.getCmp(modulo+'btnSend').setDisabled(!node.attributes.descargar);
					Ext.getCmp(modulo+'btnDown').setDisabled(!node.attributes.descargar);
					Ext.getCmp(modulo+'formPeriodo').getForm().reset();
					Ext.getCmp(modulo+'periodoPersonalizado').setValue('<?php print PERIODOPERSONALIZADO; ?>');
					Ext.getCmp(modulo+'mesIni').setDisabled(false);
					Ext.getCmp(modulo+'mesFin').setDisabled(false);
					Ext.getCmp(modulo+'anio').setDisabled(false);
					if(node.leaf){
						reporte_id = node.id;
						sisduanReportesTree.consultar(node.id)
					}
					else{
						panelInicial();
					}
				}
				,'contextmenu': function(node, e){
					if(node.leaf){
						return false;
					}
					else{
						panelInicial();
					}
				}
			}
		});
	}
	Ext.extend(Reportes.tree, Ext.ux.tree.RemoteTreePanel, {
		consultar:function(reporte){
			var node = this.getNodeById(reporte);
			if(node){
				Ext.getCmp(modulo+'btnEdit').setDisabled(false);
				Ext.getCmp(modulo+'btnCopy').setDisabled(false);
				Ext.getCmp(modulo+'btnDel').setDisabled(false);
				Ext.getCmp(modulo+'btnDateEdit').setDisabled(false);
				Ext.getCmp(modulo+'btnSend').setDisabled(!node.attributes.descargar);
				Ext.getCmp(modulo+'btnDown').setDisabled(!node.attributes.descargar);
				Ext.getCmp(modulo+'reporte').setValue(node.id);
				Ext.getCmp(modulo+'reportes_enviar').setValue(node.attributes.enviar);
				
				var periodo = Ext.encode(Ext.getCmp(modulo+'formPeriodo').getForm().getFieldValues());
				//Ext.getCmp(modulo+'formPeriodo').getForm().reset();
				var dataViewer = new Ext.Panel({
					autoScroll: false
					,layout: 'fit'
					,autoShow: true
					,frame:false
					,border: false
					,autoDestroy:true
					,plugins: new Ext.ux.Plugin.RemoteComponent({
						 url:'jscode/reporte_sisduan/'
						,params:{reporte:reporte, periodo:periodo,tree:modulo+'reportes'}
						,disableCaching: true
						,method: 'POST'
						,mask: Ext.getBody()
						,maskConfig: {
							msg: Ext.LoadMask.prototype.msg
						}
					})
					,items:[]
				});
				var lp = Ext.getCmp(modulo+'lpReportes');
				var remove = lp.removeAll(true);
				lp.add(dataViewer);
				lp.doLayout();
			}
		}
		,cargar:function(reporte){
			var node = this.getNodeById(reporte);
			reporte_id = reporte;
			this.getRootNode().reload();
		}
	});
	
	var sisduanReportesTree = new Reportes.tree();	
	sisduanReportesTree.filter = new Ext.ux.tree.TreeFilterX(sisduanReportesTree);
	
	sisduanReportesTree.getLoader().on('load', function(loader, node, callback){
		if(reporte_id != 0){
			var nodo = sisduanReportesTree.getNodeById(reporte_id);
			if(nodo){
				node = nodo;
			}
			else{
				sisduanReportesTree.getRootNode().expand(true, false);
			}
		}
		sisduanReportesTree.fireEvent('click', node);
	});
	
	sisduanReportesTree.getLoader().on('beforeload', function(loader, node, callback){		
		if(Ext.getCmp(modulo+'lpReportes').items.items.length == 0){
			sisduanReportesTree.getRootNode().select();
			panelInicial();
		}
	});
	
	var locationbar = new Ext.ux.LocationBar({
		 height: 28
		,tree: sisduanReportesTree
		,region: 'north'
		,noReload: false
		,selectHandler: function(node){
			node.parentNode ? !node.parentNode.isExpanded() ? node.parentNode.expand() : false : false;
			sisduanReportesTree.fireEvent('click', node);
		}
		,reloadHandler: function(node){
			sisduanReportesTree.fireEvent('click', node);
		}
    });
	
	var layout = new Ext.Panel({
		 xtype: 'panel'
		,layout: 'border'
		,id:modulo+'layoutpanel'
		,border: false
		,items: [
			locationbar
			,sisduanReportesTree
			,{
				region:'center'
				,layout: 'fit'
				,id:modulo+'lpReportes'
				,tbar:new Ext.Toolbar({
					enableOverflow: true
					,items:[{
						text:'<?php print _BTNADD; ?>'
						,iconCls:'silk-report-add'
						,handler:function(){
							cfg_reporte(<?php print ADD; ?>);
						}
					},{
						text:'<?php print _PERIODO; ?>'
						,iconCls:'silk-date-edit'
						,id:modulo+'btnDateEdit'
						,disabled:true
						,handler:function(){
							//Ext.getCmp(modulo+'periodoPersonalizado').setValue('<?php print PERIODOPERSONALIZADO; ?>');
							dialogoPeriodo.show();
						}
					},{
						xtype:'splitbutton'
						,text:'<?php print _DOWNLOAD; ?>'
						,iconCls:'icon-excel'
						,id:modulo+'btnDown'
						,disabled:true
						,handler:function(){
							var node;
							if(sisduanReportesTree.getSelectionModel().getSelectedNode()){
								node = sisduanReportesTree.getSelectionModel().getSelectedNode();
							}
							else{
								node = sisduanReportesTree.getRootNode();
							}
							var periodo = Ext.encode(Ext.getCmp(modulo+'formPeriodo').getForm().getFieldValues());
							excel(node.id, 1, 'proceso/declaraciones/', node.attributes.acumulado, periodo);
						}
						,menu:[{
							text:'<?php print _EXCEL2010; ?>'
							,handler:function(){
								var node;
								if(sisduanReportesTree.getSelectionModel().getSelectedNode()){
									node = sisduanReportesTree.getSelectionModel().getSelectedNode();
								}
								else{
									node = sisduanReportesTree.getRootNode();
								}
								excel(node.id, 1, 'proceso/declaraciones/', node.attributes.acumulado);
							}
						},{
							text:'<?php print _EXCEL97; ?>'
							,handler:function(){
								var node;
								if(sisduanReportesTree.getSelectionModel().getSelectedNode()){
									node = sisduanReportesTree.getSelectionModel().getSelectedNode();
								}
								else{
									node = sisduanReportesTree.getRootNode();
								}
								excel(node.id, 2, 'proceso/declaraciones/', node.attributes.acumulado);
							}
						}]
					},{
						text:'<?php print _BTNEDIT; ?>'
						,iconCls:'silk-report-edit'
						,id:modulo+'btnEdit'
						,disabled:true
						,handler:function(){
							cfg_reporte(<?php print EDIT; ?>);
						}
					},{
						text:'<?php print _BTNADDAS; ?>'
						,iconCls:'silk-page-copy'
						,id:modulo+'btnCopy'
						,disabled:true
						,handler:function(){
							cfg_reporte(<?php print COPY; ?>);
						}
					},{
						text:'<?php print _DEL; ?>'
						,iconCls:'silk-application-delete'
						,id:modulo+'btnDel'
						,disabled:true
						,handler:function(){
							var node;
							if(sisduanReportesTree.getSelectionModel().getSelectedNode()){
								node = sisduanReportesTree.getSelectionModel().getSelectedNode();
							}
							else{
								node = sisduanReportesTree.getRootNode();
							}
							sisduanReportesTree.removeNode(node);
						}
					},{
						text:'<?php print _BTNSEND; ?>'
						,iconCls:'silk-email'
						,id:modulo+'btnSend'
						,disabled:true
						,handler:function(){
							dialogoReportes.show();
						}
					},{
						text:'<?php print _AYUDA; ?>'
						,iconCls:'silk-help'
						,id:modulo+'btnAyuda'
						,handler:function(){
							try {
								Ext.destroy(Ext.get('downloadIframe'));
							}
							catch(e){}
							Ext.DomHelper.append(document.body, {
								tag:'iframe'
								,id:'downloadIframe'
								,frameBorder:0
								,width:0
								,height:0
								,css:'display:none;visibility:hidden;height:0px;'
								,src:'<?php print URL_RAIZ; ?>ayuda/index.php?file=reports-help.pdf'
							});
						}
					}]
				})
				,items:[]
			}
		]
	});
	
	//----------------------------------------------- FUNCIONES ----------------------------------------------//
	
	function panelInicial(){
		Ext.getCmp(modulo+'btnEdit').setDisabled(true);
		Ext.getCmp(modulo+'btnCopy').setDisabled(true);
		Ext.getCmp(modulo+'btnDel').setDisabled(true);
		Ext.getCmp(modulo+'btnDateEdit').setDisabled(true);
		Ext.getCmp(modulo+'btnDown').setDisabled(true);
		Ext.getCmp(modulo+'btnSend').setDisabled(true);
		
		if(!Ext.getCmp(modulo+'panelInicial')){
			var lp = Ext.getCmp(modulo+'lpReportes');
			var remove = lp.removeAll(true);
			var altura = Math.floor(Ext.getCmp('tabpanel').getInnerHeight() - 330);
			var storeChartBalanza = new Ext.data.JsonStore({
				url:'proceso/estadisticas/'
				,root:'datos'
				,baseParams:{'pais_id':<?php print $pais; ?>}
				,fields:[
					{type:'integer', name:'date'}
					,{type:'float', name:'value'}
				]
			});
			storeChartBalanza.on('load', function(store){
				FusionCharts.setCurrentRenderer('javascript');
				if(FusionCharts('myChartId<?php print $id; ?>')){
					FusionCharts('myChartId<?php print $id; ?>').dispose();
				}
				var myChart = new FusionCharts('<?php print AREA; ?>', 'myChartId<?php print $id; ?>', '100%', '100%', '0', '1');
				myChart.setTransparent(true);
				myChart.setJSONData(store.reader.jsonData.json_grafico);
				if(Ext.getCmp('chart_panel_balanza<?php print $id; ?>')){
					myChart.render('chart_panel_balanza<?php print $id; ?>');
				}
			});
			var panelInicial = {
				xtype:'panel'
				,layout:'border'
				,id:modulo+'panelInicial'
				,border:false
				,bodyCssClass:'mainbar'
				,items:[{
					region:'north'
					,baseCls:''
					,html:'<?php print $html; ?>'
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
					,items:[{
						xtype:'fieldset'
						,title:''
						,collapsible:false
						,layout:'column'
						,bodyStyle:'padding:10px;'
						,defaults:{
							columnWidth:0.5
							,border:true
							,xtype:'panel'
							,style:{padding:'10px'}
							,layout:'fit'
						}
						,items: [{
							defaults:{anchor:'100%'}
							,border:false
							,cls:'widget'
							,items:[{
								xtype:'box'
								,id:modulo+'map_img'
								,height:altura+25
								,autoEl:{
									tag:'div'
									,style:'text-align:center;'
									,cls:'padd'
									,children:[{
										tag:'img'
										,src:''
										,cls:'img-thumbnail'
									}]
								}
								,listeners:{
									'afterrender': {
										fn: function(panel){
											var w = panel.getWidth();
											var h = panel.getHeight();
											var url = '<?php print $url_mapa; ?>&size=' + w + 'x' + h;
											Ext.getCmp(modulo+'map_img').getEl().dom.firstChild.src = url;
										}
									}
								}
							}]
						},{
							defaults:{anchor:'100%'}
							,title:'<?php print _BALANZA_PIB; ?>'
							,height:altura+25
							,items:[{
								xtype:'panel'
								,id:'chart_panel_balanza<?php print $id; ?>'
								,plain:true
								,listeners: {
									'render':{
										fn: function(panel){
											storeChartBalanza.load();
										}
									}
								}
							}]
						}]
					}]
				}]
			}
			
			Ext.getCmp(modulo+'lpReportes').add(panelInicial);
			Ext.getCmp(modulo+'lpReportes').doLayout();
		}
	}
	function ayuda_reportes_tree(){
		var win = Ext.getCmp('helpWindow');
		if(!win){
			var helpWindow = new Ext.Window({
				id:'helpWindow'
				,title:'<?php print _AYUDA; ?>'
				,resizable:true
				//,modal:true
				,layout:'fit'
				,width:750
				,height:500
				,items:[{
					xtype:'panel'
					,border:false
					,autoScroll:true
					,html:'<div class="caption padded10"><i class="icon-info-sign"></i>&nbsp; <?php print _AYUDAPANTALLAPRINCIPALREPORTES; ?></div>' +
						'<div class="caption padded10"><i class="icon-info-sign"></i>&nbsp; <strong><?php print _VIDEOHOME1; ?></strong></div>' +
						'<div class=\"row-fluid\">' +
						'	<div class=\"span12\">' +
						'		<video id="help_video1" class="video-js vjs-default-skin" controls width="820" height="400" poster="http://appnew.sicex.com/ayuda/videos/help1.png" preload="auto" data-setup="{}">' +
						'  		 <source type="video/webm" src="http://appnew.sicex.com/ayuda/videos/help1.webm">' +
						'		 <source type="video/mp4" src="http://appnew.sicex.com/ayuda/videos/help1.mp4">' +
						'		</video>' +
						'	</div>' +
						'</div>' +
						'<div class="caption padded10"><i class="icon-info-sign"></i>&nbsp; <strong><?php print _VIDEOHOME2; ?></strong></div>' +
						'<div class=\"row-fluid\">' +
						'	<div class=\"span12\">' +
						'		<video id="help_video2" class="video-js vjs-default-skin" controls width="820" height="400" poster="http://appnew.sicex.com/ayuda/videos/help1.png" preload="auto" data-setup="{}">' +
						'  		 <source type="video/webm" src="http://appnew.sicex.com/ayuda/videos/help2.webm">' +
						'		 <source type="video/mp4" src="http://appnew.sicex.com/ayuda/videos/help2.mp4">' +
						'		</video>' +
						'	</div>' +
						'</div>' +
						'<div class="caption padded10"><i class="icon-info-sign"></i>&nbsp; <strong><?php print _VIDEOHOME3; ?></strong></div>' +
						'<div class=\"row-fluid\">' +
						'	<div class=\"span12\">' +
						'		<video id="help_video3" class="video-js vjs-default-skin" controls width="820" height="400" poster="http://appnew.sicex.com/ayuda/videos/help1.png" preload="auto" data-setup="{}">' +
						'  		 <source type="video/webm" src="http://appnew.sicex.com/ayuda/videos/help3.webm">' +
						'		 <source type="video/mp4" src="http://appnew.sicex.com/ayuda/videos/help3.mp4">' +
						'		</video>' +
						'	</div>' +
						'</div>' 
				}]
				,listeners:{
					'render': {
						fn: function(win){
						}
					}
				}
			}).show();
		}
	}
	function cfg_reporte(accion){
		var node;
		if(sisduanReportesTree.getSelectionModel().getSelectedNode()){
			node = sisduanReportesTree.getSelectionModel().getSelectedNode();
		}
		else{
			node = sisduanReportesTree.getRootNode();
		}
		var padre   = (node.leaf)?node.parentNode.id:node.id;
		var reporte = Ext.getCmp(modulo+'reporte').getValue();
		
		var lp = Ext.getCmp(modulo+'lpReportes');
		var remove = lp.removeAll(true);
		var panelInicial = {
			xtype:'panel'
			,border:false
			,layout:'fit'
			,plugins: new Ext.ux.Plugin.RemoteComponent({
				 url:'jscode/cfg_reporte/'
				,params:{
					 reporte:reporte
					,accion:accion
					,descripcion:'<?php print $descripcion; ?>'
					,producto:<?php print $producto; ?>
					,padre:padre
					,pais_id:<?php print $pais; ?>
					,contenedor:modulo+'reportWizard'
					,tree:modulo+'reportes'
					,modulo:modulo
				}
				,disableCaching: true
				,method: 'POST'
			})
			,bbar:new Ext.ux.StatusBar({
				text:'',
				id:modulo+'sbPanel'
			})
		}
		
		Ext.getCmp(modulo+'lpReportes').add(panelInicial);
		Ext.getCmp(modulo+'lpReportes').doLayout();
	}
	
	//----------------------------------------------- FIN FUNCIONES ----------------------------------------------//
	
	return layout;

})()