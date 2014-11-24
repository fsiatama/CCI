<?php
//var_dump($_REQUEST);
/*
Parametros que llegan
  'id' => string '4' (length=1)
  'title' => string '%BCt' (length=4)
  'module' => string 'indicator_3' (length=11)
  'parent' => string 'analisis_de_indicadores' (length=23)
  'tipo_indicador_id' => string '3' (length=1)
  'is_template' => boolean true
  'tipo_indicador_nombre' => string 'Variación de la Balanza Comercial' (length=34)
  'tipo_indicador_abrev' => string '%BCt' (length=4)
  'tipo_indicador_activador' => string 'precio' (length=6)
  'tipo_indicador_calculo' => string '%BCt= (Xijt-Mijtp2) - (Xijt-Mijt-p1) /(Xijt-Mijt-p1)

Donde Xijt = Exportaciones de un producto i por un país j en un periodo t+1, Mij = Importaciones de un producto i a un país j en un periodo t.' (length=216)
  'tipo_indicador_definicion' => string 'Se define como indicador para establecer el crecimiento o decrecimiento del la balanza comercial antes y después de la firma de un TLC o en cualquier periodo de tiempo.' (length=169)
  'action' => string 'list' (length=4)
*/
?>
/*<script>*/
(function(){
	Ext.form.Field.prototype.msgTarget = 'side';
	var module = '<?= $module; ?>';
	
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

	return 'gridUser';	
	/*********************************************** Start functions***********************************************/
	
	

	/*********************************************** End functions***********************************************/
})()