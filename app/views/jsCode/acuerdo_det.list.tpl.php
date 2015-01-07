/*<script>*/
(function(){
	Ext.form.Field.prototype.msgTarget = 'side';
	var module = '<?= $module; ?>';
	var numberRecords = Math.floor((Ext.getCmp('tabpanel').getInnerHeight() - 120)/22);
	
	var storeAcuerdo_det = new Ext.data.JsonStore({
		url:'acuerdo_det/list'
		,root:'data'
		,sortInfo:{field:'acuerdo_det_acuerdo_id',direction:'ASC'}
		,totalProperty:'total'
		,baseParams:{
			id:'<?= $id; ?>'
			,acuerdo_det_acuerdo_id:'<?= $acuerdo_id; ?>'
		}
		,fields:[
			{name:'acuerdo_det_id', type:'float'},
			{name:'acuerdo_det_arancel_base', type:'float'},
			{name:'acuerdo_det_productos', type:'string'},
			{name:'acuerdo_det_productos_desc', type:'string'},
			{name:'acuerdo_det_administracion', type:'string'},
			{name:'acuerdo_det_administrador', type:'string'},
			{name:'acuerdo_det_nperiodos', type:'float'},
			{name:'acuerdo_det_acuerdo_id', type:'float'}
		]
	});

	gridAcuerdo_detAction = new Ext.ux.grid.RowActions({
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
	var cmAcuerdo_det = new Ext.grid.ColumnModel({
		columns:[
			{xtype:'numbercolumn', header:'<?= Lang::get('acuerdo_det.columns_title.acuerdo_det_arancel_base'); ?>', align:'right', hidden:false, dataIndex:'acuerdo_det_arancel_base'},
			{header:'<?= Lang::get('acuerdo_det.columns_title.acuerdo_det_productos'); ?>', align:'left', hidden:false, dataIndex:'acuerdo_det_productos'},
			{header:'<?= Lang::get('acuerdo_det.columns_title.acuerdo_det_productos_desc'); ?>', align:'left', hidden:false, dataIndex:'acuerdo_det_productos_desc'},
			{header:'<?= Lang::get('acuerdo_det.columns_title.acuerdo_det_administracion'); ?>', align:'left', hidden:false, dataIndex:'acuerdo_det_administracion'},
			{header:'<?= Lang::get('acuerdo_det.columns_title.acuerdo_det_administrador'); ?>', align:'left', hidden:false, dataIndex:'acuerdo_det_administrador'},
			{xtype:'numbercolumn', header:'<?= Lang::get('acuerdo_det.columns_title.acuerdo_det_nperiodos'); ?>', align:'right', hidden:false, dataIndex:'acuerdo_det_nperiodos'},
			gridAcuerdo_detAction
		]
		,defaults:{
			sortable:true
			,width:100
		}
	});

	var tbAcuerdo_det = new Ext.Toolbar({
		items:[{
			text: Ext.ux.lang.buttons.add
			,iconCls: 'silk-add'
			,handler: function(){
				var data = {
					id:'add_' + module
					,iconCls:'silk-add'
					,titleTab:'<?= $title; ?> - ' + Ext.ux.lang.buttons.add
					,url:'acuerdo_det/jscode/create'
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

	var gridAcuerdo_det = new Ext.grid.GridPanel({
		store:storeAcuerdo_det
		,id:module + 'gridAcuerdo_det'
		,colModel:cmAcuerdo_det
		,viewConfig: {
			forceFit: true
			,scrollOffset:2
		}
		,sm: new Ext.grid.RowSelectionModel({
			singleSelect: true
		})
		,bbar:new Ext.PagingToolbar({pageSize:numberRecords, store:storeAcuerdo_det, displayInfo:true})
		,enableColumnMove:false
		,enableColumnResize:false
		,tbar:tbAcuerdo_det
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
			,gridAcuerdo_detAction
		]
	});
	
	return gridAcuerdo_det;	
	/*********************************************** Start functions***********************************************/
	
	function fnEditItm(record){
		var key = record.get('acuerdo_id');
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
				,url:'acuerdo_det/jscode/modify'
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
		Ext.Msg.confirm(
			Ext.ux.lang.messages.confirmation
			,Ext.ux.lang.messages.question_delete
			,function(btn){
			if(btn == 'yes'){
				var gridMask = new Ext.LoadMask(gridAcuerdo_det.getEl(), { msg: 'Erasing .....' });
				gridMask.show();
				var key = record.get('acuerdo_det_id');

				Ext.Ajax.request({
					 url:'acuerdo_det/delete'
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

	/*********************************************** End functions***********************************************/
})()