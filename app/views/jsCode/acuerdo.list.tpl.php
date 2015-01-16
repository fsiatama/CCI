/*<script>*/
(function(){
	Ext.form.Field.prototype.msgTarget = 'side';
	var module = '<?= $module; ?>';
	var numberRecords = Math.floor((Ext.getCmp('tabpanel').getInnerHeight() - 120)/22);
	
	var storeAcuerdo = new Ext.data.JsonStore({
		url:'acuerdo/list'
		,root:'data'
		,sortInfo:{field:'acuerdo_id',direction:'ASC'}
		,totalProperty:'total'
		,baseParams:{id:'<?= $id; ?>'}
		,fields:[
			{name:'acuerdo_id', type:'float'},
			{name:'acuerdo_nombre', type:'string'},
			{name:'acuerdo_descripcion', type:'string'},
			{name:'acuerdo_intercambio', type:'string'},
			{name:'acuerdo_intercambio_title', type:'string'},
			{name:'acuerdo_fvigente', type:'string'}
		]
	});
	
	storeAcuerdo.load({params:{start:0, limit:numberRecords}});
	
	gridAcuerdoAction = new Ext.ux.grid.RowActions({
		header: Ext.ux.lang.grid.options
		,keepSelection:true
		,autoWidth:false
		,actions:[{
			iconCls:'silk-delete'
			,qtip: Ext.ux.lang.buttons.delete_tt
		},{
			iconCls: 'silk-page-edit'
			,qtip: Ext.ux.lang.buttons.modify_tt
		},{
			iconCls: 'silk-cart'
			,qtip: '<?= Lang::get('acuerdo_det.table_name'); ?>'
		},{
			iconCls:'silk-chart-bar-link'
			,qtip: '<?= Lang::get('acuerdo.analyze_agreement'); ?>'
		}]
		,callbacks:{
			'silk-delete':function(grid, record, action, row, col) {
				fnDeleteItem(record);
			}
			,'silk-page-edit':function(grid, record, action, row, col) {
				fnEditItm(record);
			}
			,'silk-cart':function(grid, record, action, row, col) {
				fnOpenDetail(record);
			}
			,'silk-chart-bar-link':function(grid, record, action, row, col) {
				fnReport(record);
			}
		}
	});

	gridAcuerdoExpander = new Ext.grid.RowExpander({
		tpl: new Ext.Template(
			 '<br><p style="margin:0 0 4px 8px"><b><?= Lang::get('acuerdo.columns_title.acuerdo_descripcion'); ?>:</b> {acuerdo_descripcion}</p>'
		)
	});
	
	var cmAcuerdo = new Ext.grid.ColumnModel({
		columns:[
			gridAcuerdoExpander,
			{header:'<?= Lang::get('acuerdo.columns_title.acuerdo_nombre'); ?>', align:'left', hidden:false, dataIndex:'acuerdo_nombre'},
			{header:'<?= Lang::get('acuerdo.columns_title.acuerdo_descripcion'); ?>', align:'left', hidden:false, dataIndex:'acuerdo_descripcion'},
			{header:'<?= Lang::get('acuerdo.columns_title.acuerdo_intercambio'); ?>', align:'left', hidden:false, dataIndex:'acuerdo_intercambio_title'},
			{header:'<?= Lang::get('acuerdo.columns_title.acuerdo_fvigente'); ?>', align:'left', hidden:false, dataIndex:'acuerdo_fvigente'},
			gridAcuerdoAction
		]
		,defaults:{
			sortable:true
			,width:100
		}
	});
		
	var tbAcuerdo = new Ext.Toolbar({
		items:[{
			text: Ext.ux.lang.buttons.add
			,iconCls: 'silk-add'
			,handler: function(){
				if(Ext.getCmp('tab-add_'+module) || Ext.getCmp('tab-detail_'+module) || Ext.getCmp('tab-edit_'+module)){
					Ext.Msg.show({
						title:Ext.ux.lang.messages.warning
						,msg:Ext.ux.lang.error.close_tab
						,buttons: Ext.Msg.OK
						,icon: Ext.Msg.WARNING
					});
				}
				else{
					var data = {
						id:'add_' + module
						,iconCls:'silk-add'
						,titleTab:'<?= $title; ?> - ' + Ext.ux.lang.buttons.add
						,url:'acuerdo/jscode/create'
						,params:{
							id:'<?= $id; ?>'
							,title: '<?= $title; ?> - ' + Ext.ux.lang.buttons.add
							,module: 'add_' + module
							,parent: module
						}
					};
					Ext.getCmp('oeste').addTab(this,this,data);
				}
			}
		}]
	});
	var gridAcuerdo = new Ext.grid.GridPanel({
		store:storeAcuerdo
		,id:module + 'gridAcuerdo'
		,colModel:cmAcuerdo
		,viewConfig: {
			forceFit: true
			,scrollOffset:2
		}
		,sm: new Ext.grid.RowSelectionModel({
			singleSelect: true
		})
		,bbar:new Ext.PagingToolbar({pageSize:numberRecords, store:storeAcuerdo, displayInfo:true})
		,enableColumnMove:false
		,enableColumnResize:false
		,tbar:tbAcuerdo
		,loadMask:true
		,border:false
		,frame: false
		,baseCls: 'x-panel-mc'
		,buttonAlign:'center'
		,title:''
		,iconCls:'icon-grid'
		,stripeRows: true
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
			,gridAcuerdoAction
			,gridAcuerdoExpander
		]
	});

	/*elimiar cualquier estado de la grilla guardado con anterioridad */
	Ext.state.Manager.clear(gridAcuerdo.getItemId());
	
	return gridAcuerdo;	
	/*********************************************** Start functions***********************************************/
	
	function fnEditItm(record){
		var key = record.get('acuerdo_id');
		if(Ext.getCmp('tab-add_'+module) || Ext.getCmp('tab-detail_'+module) || Ext.getCmp('tab-edit_'+module)){
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
				,url:'acuerdo/jscode/modify'
				,params:{
					id:'<?= $id; ?>'
					,title: '<?= $title; ?> - ' + Ext.ux.lang.buttons.modify
					,module: 'edit_' + module
					,parent: module
					,acuerdo_id: key
				}
			};
			Ext.getCmp('oeste').addTab(this,this,data);
		}
	}
	function fnDeleteItem(record){
		if(Ext.getCmp('tab-detail_'+module) || Ext.getCmp('tab-add_'+module) || Ext.getCmp('tab-edit_'+module)) {
			Ext.Msg.show({
				 title:Ext.ux.lang.messages.warning
				,msg:Ext.ux.lang.error.close_tab
				,buttons: Ext.Msg.OK
				,icon: Ext.Msg.WARNING
			});
		} else {
			Ext.Msg.confirm(
				Ext.ux.lang.messages.confirmation
				,Ext.ux.lang.messages.question_delete
				,function(btn){
				if(btn == 'yes'){
					var gridMask = new Ext.LoadMask(gridAcuerdo.getEl(), { msg: 'Erasing .....' });
					gridMask.show();
					var key = record.get('acuerdo_id');

					Ext.Ajax.request({
						 url:'acuerdo/delete'
						,params: {
							id: '<?= $id; ?>'
							,acuerdo_id: key
						}
						,callback: function(options, success, response){
							gridMask.hide();
							var json = Ext.util.JSON.decode(response.responseText);
							if (json.success){
								gridAcuerdo.store.reload();
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
		
	}
	function fnOpenDetail (record) {
		var key = record.get('acuerdo_id');
		if(Ext.getCmp('tab-add_'+module) || Ext.getCmp('tab-detail_'+module) || Ext.getCmp('tab-edit_'+module)){
			Ext.Msg.show({
				 title:Ext.ux.lang.messages.warning
				,msg:Ext.ux.lang.error.close_tab
				,buttons: Ext.Msg.OK
				,icon: Ext.Msg.WARNING
			});
		}
		else{
			var data = {
				id:'detail_' + module
				,iconCls:'silk-cart'
				,titleTab:'<?= $title; ?> - ' + Ext.ux.lang.buttons.detail
				,url:'acuerdo_det/jscode'
				,params:{
					id:'<?= $id; ?>'
					,title: '<?= $title; ?> - ' + Ext.ux.lang.buttons.detail
					,module: 'detail_' + module
					,parent: module
					,acuerdo_id: key
				}
			};
			Ext.getCmp('oeste').addTab(this,this,data);
		}
	}

	function fnReport(record){
		var key   = record.get('acuerdo_id');
		var title = record.get('acuerdo_nombre');
		var data  = {
			id:'indicator_' + key
			,iconCls:'silk-chart-bar-link'
			,titleTab: title
			,url:'acuerdo/jscodeExecute'
			,params:{
				id:'<?= $id; ?>'
				,title: title
				,module: 'indicator_' + key
				,parent: module
				,acuerdo_id: key
			}
		};
		Ext.getCmp('oeste').addTab(this,this,data);
	}

	/*********************************************** End functions***********************************************/
})()