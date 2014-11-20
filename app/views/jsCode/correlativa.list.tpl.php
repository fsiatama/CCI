/*<script>*/
(function(){
	Ext.form.Field.prototype.msgTarget = 'side';
	var module = '<?= $module; ?>';
	var numberRecords = Math.floor((Ext.getCmp('tabpanel').getInnerHeight() - 120)/22);
	
	var storeCorrelativa = new Ext.data.JsonStore({
		url:'correlativa/list'
		,root:'data'
		,sortInfo:{field:'correlativa_id',direction:'ASC'}
		,totalProperty:'total'
		,baseParams:{id:'<?= $id; ?>'}
		,fields:[
			{name:'correlativa_id', type:'float'},
			{name:'correlativa_fvigente', type:'date', dateFormat:'Y-m-d'},
			{name:'correlativa_decreto', type:'string'},
			{name:'correlativa_observacion', type:'string'},
			{name:'correlativa_origen', type:'string'},
			{name:'correlativa_destino', type:'string'}
		]
	});
	
	storeCorrelativa.load({params:{start:0, limit:numberRecords}});
	
	gridCorrelativaExpander = new Ext.grid.RowExpander({
		tpl: new Ext.Template(
			 '<br><p style="margin:0 0 4px 8px"><b><?= Lang::get('correlativa.columns_title.correlativa_observacion'); ?>:</b><br>{correlativa_observacion}</p>'
			 ,'<p style="margin:0 0 4px 8px"><b><?= Lang::get('correlativa.columns_title.correlativa_origen'); ?>:</b> {correlativa_origen}</p>'
			 ,'<p style="margin:0 0 4px 8px"><b><?= Lang::get('correlativa.columns_title.correlativa_destino'); ?>:</b> {correlativa_destino}</p>'
		)
	});

	gridCorrelativaAction = new Ext.ux.grid.RowActions({
		 header: Ext.ux.lang.grid.options
		,keepSelection:true
		,autoWidth:false
		,actions:[{
			iconCls:'silk-delete'
			,tooltip: Ext.ux.lang.buttons.delete_tt
		},{
			 iconCls: 'silk-page-edit'
			,tooltip: Ext.ux.lang.buttons.modify_tt
		}]
		,callbacks:{
			'silk-delete':function(grid, record, action, row, col) {
				fnDeleteItem(record);
			}
			,'silk-page-edit':function(grid, record, action, row, col) {
				fnEditItm(record);
			}
		}
	});
	
	var cmCorrelativa = new Ext.grid.ColumnModel({
		columns:[
			gridCorrelativaExpander,
			{header:'<?= Lang::get('correlativa.columns_title.correlativa_decreto'); ?>', align:'left', hidden:false, dataIndex:'correlativa_decreto'},
			{xtype:'datecolumn', header:'<?= Lang::get('correlativa.columns_title.correlativa_fvigente'); ?>', align:'left', hidden:false, dataIndex:'correlativa_fvigente', format:'Y-m-d'},
			{header:'<?= Lang::get('correlativa.columns_title.correlativa_observacion'); ?>', align:'left', hidden:false, dataIndex:'correlativa_observacion'},
			{header:'<?= Lang::get('correlativa.columns_title.correlativa_origen'); ?>', align:'left', hidden:false, dataIndex:'correlativa_origen'},
			{header:'<?= Lang::get('correlativa.columns_title.correlativa_destino'); ?>', align:'left', hidden:false, dataIndex:'correlativa_destino'},
			gridCorrelativaAction
		]
		,defaults:{
			sortable:true
			,width:100
		}
	});
		
	var tbCorrelativa = new Ext.Toolbar({
		items:[{
			text: Ext.ux.lang.buttons.add
			,iconCls: 'silk-add'
			,handler: function(){				
				var data = {
					id:'add_' + module
					,iconCls:'silk-add'
					,titleTab:'<?= $title; ?> - ' + Ext.ux.lang.buttons.add
					,url:'correlativa/jscode/create'
					,params:{
						id:'<?= $id; ?>'
						,title: '<?= $title; ?> - ' + Ext.ux.lang.buttons.add
						,module: 'add_' + module
						,parent: module
					}
				};
				Ext.getCmp('oeste').addTab(this,this,data);
			}
		}]
	});
	var gridCorrelativa = new Ext.grid.GridPanel({
		store:storeCorrelativa
		,id:module + 'gridCorrelativa'
		,colModel:cmCorrelativa
		,viewConfig: {
			forceFit: true
			,scrollOffset:2
		}
		,sm: new Ext.grid.RowSelectionModel({
			singleSelect: true
		})
		,bbar:new Ext.PagingToolbar({pageSize:numberRecords, store:storeCorrelativa, displayInfo:true})
		,enableColumnMove:false
		,enableColumnResize:false
		,tbar:tbCorrelativa
		,loadMask:true
		,border:false
		,frame: false
		,baseCls: 'x-panel-mc'
		,buttonAlign:'center'
		,title:''
		,iconCls:'icon-grid'
		,plugins:[
			new Ext.ux.grid.Search({
				iconCls:'silk-zoom'
				,searchText: Ext.ux.lang.grid.search
				,selectAllText: Ext.ux.lang.grid.selectAllText
				,id:module+'SearchBox'
				,minChars:2
				,width:200
				,mode:'remote'
				,align:'right'
				,position:top
				,disableIndexes:[]
			}) 
			,gridCorrelativaAction
			,gridCorrelativaExpander
		]
	});
	
	return gridCorrelativa;	
	/*********************************************** Start functions***********************************************/
	
	function fnEditItm(record){
		var key = record.get('correlativa_id');
		if(Ext.getCmp('tab-add_'+module)){
			Ext.Msg.show({
				 title:Ext.ux.lang.messages.warning
				,msg:Ext.ux.lang.error.close_tab
				,buttons: Ext.Msg.OK
				,icon: Ext.Msg.WARNING
			});
		}
		else{
			var data = {
				id:'edit_' + module
				,iconCls:'silk-page-edit'
				,titleTab:'<?= $title; ?> - ' + Ext.ux.lang.buttons.modify
				,url:'correlativa/jscode/modify'
				,params:{
					id:'<?= $id; ?>'
					,title: '<?= $title; ?> - ' + Ext.ux.lang.buttons.modify
					,module: 'edit_' + module
					,parent: module
					,correlativa_id: key
				}
			};
			Ext.getCmp('oeste').addTab(this,this,data);
		}
	}
	function fnDeleteItem(record){
		Ext.Msg.confirm(
			Ext.ux.lang.messages.confirmation
			,Ext.ux.lang.messages.question_delete
			,function(btn){
			if(btn == 'yes'){
				var gridMask = new Ext.LoadMask(gridCorrelativa.getEl(), { msg: 'Erasing .....' });
				gridMask.show();
				var key = record.get('correlativa_id');

				Ext.Ajax.request({
					 url:'correlativa/delete'
					,params: {
						id: '<?= $id; ?>'
						,correlativa_id: 000002
					}
					,callback: function(options, success, response){
						gridMask.hide();
						var json = Ext.util.JSON.decode(response.responseText);
						if (json.success){
							gridCorrelativa.store.reload();
						}
						else{
							Ext.Msg.show({
							   title:Ext.ux.lang.messages.error,
							   buttons: Ext.Msg.OK,
							   msg:json.error,
							   animEl: 'elId',
							   icon: Ext.MessageBox.ERROR
							});
						}
					}
				});
			};
		});
	}

	/*********************************************** End functions***********************************************/
})()