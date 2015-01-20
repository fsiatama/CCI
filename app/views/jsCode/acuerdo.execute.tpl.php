/*<script>*/
(function(){
	Ext.form.Field.prototype.msgTarget = 'side';
	Ext.ns('Indicador');
	var module       = '<?= $module; ?>';
	

				

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

	Acuerdo.tree = function() {
		Acuerdo.tree.superclass.constructor.call(this, {
			id: module + 'TreeAcuerdo'
			,header: false
            ,margins: '2 2 0 2'
	        ,rootVisible: false
	        ,minSize: 230
	        ,maxSize: 500
	        ,region: 'west'
	        ,autoScroll: true
	        ,animate: true
	        ,containerScroll: true
	        ,border: false
	        ,collapsible: true
			,collapseMode:'mini'
	        ,root: new Ext.tree.AsyncTreeNode()
            ,loader: {
            	url:'indicador/treeAdmin'
            	,baseParams:{
            		id: '<?= $id; ?>'
            		,module: module
            	}
            }
	        ,listeners: {
	            'render': function(tp){
                    tp.getSelectionModel().on('selectionchange', function(tree, node){
                        var el = Ext.getCmp('details-panel').body;
	                    if(node && node.leaf){
	                        tpl.overwrite(el, node.attributes);
	                    }else{
                            el.update(detailsText);
                        }
                    })
	            }
	        }
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
					
				}
			}
		});
	}
	Ext.extend(Indicador.tree, Tree.TreePanel, {
		consultar:function(indicador){
			/*var node = this.getNodeById(indicador);
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
			}*/
		}
	});

	var AcuerdoTree = new Acuerdo.tree();

	AcuerdoTree.getLoader().on('load', function(loader, node, response){
		/*var parent = AcuerdoTree.getRootNode();
		if (folder_id != '0') {
			parent = AcuerdoTree.getNodeById(folder_id);
			if(parent){
				node = parent;
			}
			else{
				AcuerdoTree.getRootNode().expand(true, false);
			}
		};
		if (parent) {
			AcuerdoTree.fireEvent('click', parent);
		};*/
	});

	AcuerdoTree.getLoader().on('beforeload', function(loader, node, callback){
		/*if(Ext.getCmp(module+'lpIndicador').items.items.length == 0){
			AcuerdoTree.getRootNode().select();
			initialPanel();
		}*/
	});

	var indicadorLayout = new Ext.Panel({
		xtype: 'panel'
		,layout: 'border'
		,id:module + 'acuerdoLayout'
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