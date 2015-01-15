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
$tipo_indicador_html = Inflector::compress($tipo_indicador_html);
?>
/*<script>*/
(function(){
	Ext.form.Field.prototype.msgTarget = 'side';
	Ext.ns('Indicador');
	var module       = '<?= $module; ?>';
	var indicador_id = 0;
	var folder_id    = 0;

	var root = new Ext.tree.AsyncTreeNode({
		text: '<?= $title; ?>'
		,type: 'root'
		,draggable: false
		,id: module + 'root'
		,expanded: true
		,uiProvider: false
		,iconCls: 'silk-folder'
		,leaf: false
	});

	Indicador.tree = function() {
		Indicador.tree.superclass.constructor.call(this, {
			id: module + 'TreeIndicador'
			,header: false
			,collapseAllText: Ext.ux.lang.folder.collapse_all
			,collapseText: Ext.ux.lang.folder.collapse
			,deleteText: Ext.ux.lang.folder.delete_btn
			,deleteInfoText: Ext.ux.lang.folder.delete_info
			,expandAllText: Ext.ux.lang.folder.expand_all
			,expandText: Ext.ux.lang.folder.expand
			,insertText: Ext.ux.lang.folder.insert
			,newText: Ext.ux.lang.folder.new_btn
			,reallyWantText: Ext.ux.lang.folder.really_want
			,reloadText: Ext.ux.lang.folder.reload
			,renameText: Ext.ux.lang.folder.rename
			,ddAppendOnly:true
			,minSize: 230
			,maxSize: 500
			,region: 'west'
			,autoScroll: true
			,animate: true
			,containerScroll: true
			,border: false
			,enableDD: true
			,ddGroup:'treeIndicador'
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
				url:'indicador/treeAdmin'
				,baseParams:{
					tipo_indicador_id:'<?= $tipo_indicador_id; ?>'
					,id: '<?= $id; ?>'
					,module: module
					,actionId: 'list'
				}
				,baseAttrs: {
					 iconCls: 'silk-folder'
				}
			}
			,tbar:[
				Ext.ux.lang.folder.filter
			,{
				xtype:'trigger'
				,triggerClass:'x-form-clear-trigger'
				,onTriggerClick:function() {
					this.setValue('');
					IndicadorTree.filter.clear();
				}
				,id:module + 'filter'
				,enableKeyEvents:true
				,listeners:{
					keyup:{ buffer:150, fn:function(field, e) {
						if(Ext.EventObject.ESC == e.getKey()) {
							field.onTriggerClick();
						}
						else {
							var val = this.getRawValue();
							var re = new RegExp('.*' + val + '.*', 'i');
							IndicadorTree.filter.clear();
							IndicadorTree.filter.filter(re, 'text');
						}
					}}
				}
			}]
			,listeners: {
				'beforecollapsenode': function(node, deep, anim){
					initialPanel();
				}
				,'click': function(node, e){
					Ext.getCmp(module + 'btnEdit').setDisabled(!node.leaf);
					Ext.getCmp(module + 'btnDel').setDisabled(!node.leaf);

					if (node.leaf) {
						indicador_id = node.id;
						folder_id    = node.parentNode.id;
						IndicadorTree.consultar(node.id)
					} else {
						folder_id    = node.id;
						initialPanel();
					}
				}
				,'contextmenu': function(node, e){
					if(node.leaf){
						return false;
					}
					else{
						initialPanel();
					}
				}
			}
		});
	}
	Ext.extend(Indicador.tree, Ext.ux.tree.RemoteTreePanel, {
		consultar:function(indicador){
			var node = this.getNodeById(indicador);
			Ext.getCmp('tab-' + module).purgeListeners();
			if(node){
				Ext.getCmp(module + 'btnEdit').setDisabled(false);
				Ext.getCmp(module + 'btnDel').setDisabled(false);


				var dataViewer = new Ext.Panel({
					autoScroll: false
					,layout: 'fit'
					,autoShow: true
					,frame:false
					,border: false
					,autoDestroy:true
					,plugins: new Ext.ux.Plugin.RemoteComponent({
						url: 'indicador/jscodeExecute'
						,params:{
							indicador_id: indicador_id
							,id: '<?= $id; ?>'
							,tipo_indicador_id: '<?= $tipo_indicador_id; ?>'
							,tree: module + 'TreeIndicador'
							,module: 'execute_' + module
							,panel: 'tab-' + module
						}
						,disableCaching:false
						,method:'POST'
					})
					,items: []
				});
				var lp     = Ext.getCmp(module + 'lpIndicador');
				var remove = lp.removeAll(true);
				lp.add(dataViewer);
				lp.doLayout();
			}
		}
		,cargar:function(indicador){
			var node = this.getNodeById(indicador);
			indicador_id = indicador;
			if (node) {
				folder_id = node.parentNode.id;
			};
			this.getRootNode().reload();
		}
	});

	var IndicadorTree = new Indicador.tree();
	IndicadorTree.filter = new Ext.ux.tree.TreeFilterX(IndicadorTree);

	IndicadorTree.getLoader().on('load', function(loader, node, response){
		var parent = IndicadorTree.getRootNode();
		if (folder_id != '0') {
			parent = IndicadorTree.getNodeById(folder_id);
			if(parent){
				node = parent;
			}
			else{
				IndicadorTree.getRootNode().expand(true, false);
			}
		};
		if (parent) {
			IndicadorTree.fireEvent('click', parent);
		};
	});

	IndicadorTree.getLoader().on('beforeload', function(loader, node, callback){
		if(Ext.getCmp(module+'lpIndicador').items.items.length == 0){
			IndicadorTree.getRootNode().select();
			initialPanel();
		}
	});

	var indicadorLayout = new Ext.Panel({
		xtype: 'panel'
		,layout: 'border'
		,id:module + 'indicadorLayout'
		,border: false
		,items: [
			IndicadorTree
		,{
			region:'center'
			,layout: 'fit'
			,id: module + 'lpIndicador'
			,tbar: new Ext.Toolbar({
				enableOverflow: true
				,items: [{
					text: Ext.ux.lang.buttons.new_btn
					,iconCls: 'silk-report-add'
					,handler: function(){
						cfg_reporte('create');
					}
				},{
					text: Ext.ux.lang.buttons.edit
					,iconCls: 'silk-report-edit'
					,id: module + 'btnEdit'
					,disabled: true
					,handler: function(){
						cfg_reporte('modify');
					}
				},{
					text: Ext.ux.lang.buttons.delete_btn
					,iconCls: 'silk-application-delete'
					,id:module + 'btnDel'
					,disabled: true
					,handler: function(){
						var node;
						if(IndicadorTree.getSelectionModel().getSelectedNode()){
							node = IndicadorTree.getSelectionModel().getSelectedNode();
						}
						else{
							node = IndicadorTree.getRootNode();
						}
						IndicadorTree.removeNode(node);
					}
				}]
			})
			,items:[]
		}]
	});

	/*elimiar cualquier estado del Tree guardado con anterioridad */
	Ext.state.Manager.clear(IndicadorTree.getItemId());

	return indicadorLayout;
	/*********************************************** Start functions***********************************************/

	function initialPanel(){
		Ext.getCmp(module + 'btnEdit').setDisabled(true);
		Ext.getCmp(module + 'btnDel').setDisabled(true);
		Ext.getCmp('tab-' + module).purgeListeners();
		if(!Ext.getCmp(module+'initialPanel')){
			var lp = Ext.getCmp(module + 'lpIndicador');

			var remove = lp.removeAll(true);

			var initialPanel = {
				xtype:'panel'
				,id:module+'initialPanel'
				,border:false
				,autoScroll: false
				,layout: 'fit'
				,items:[{
					border:false
					,xtype:'panel'
					,autoWidth:true
					,autoScroll:true
					,border: false
					,baseCls:'bootstrap-styles'
					,layout:'column'
					,items: [{
						style:{padding:'0px'}
						,columnWidth:1
						,border: false
						//,layout:'fit'
						,html: '<?= $tipo_indicador_html; ?>'
					}]
				}]
			}
			Ext.getCmp(module+'lpIndicador').add(initialPanel);
			Ext.getCmp(module+'lpIndicador').doLayout();
		}
	}
	function cfg_reporte(action){
		Ext.getCmp('tab-' + module).purgeListeners();
		
		var url = 'indicador/jscodeCfg/' + action;
		
		var node;

		if(IndicadorTree.getSelectionModel().getSelectedNode()){
			node = IndicadorTree.getSelectionModel().getSelectedNode();
		}
		else{
			node = IndicadorTree.getRootNode();
		}
		var parent = (node.leaf) ? node.parentNode.id: node.id;

		var lp = Ext.getCmp(module + 'lpIndicador');

		var remove = lp.removeAll(true);
		var panelCfg = {
			autoScroll: false
			,layout: 'fit'
			,autoShow: true
			,frame:false
			,border: false
			,autoDestroy:true
			,plugins: new Ext.ux.Plugin.RemoteComponent({
				url:url
				,params:{
					indicador_id: indicador_id
					,id: '<?= $id; ?>'
					,tipo_indicador_id: '<?= $tipo_indicador_id; ?>'
					,parent: parent
					,tree: module + 'TreeIndicador'
					,module: 'cfg_' + module
				}
				,disableCaching:false
				,method:'POST'
			})
			,bbar:new Ext.ux.StatusBar({
				text:'',
				id:module+'sbPanel'
			})
		}

		lp.add(panelCfg);
		lp.doLayout();
	}

	/*********************************************** End functions***********************************************/
})()