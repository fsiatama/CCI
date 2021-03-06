/*<script>*/
(function(){
	Ext.form.Field.prototype.msgTarget = 'side';
	var module = '<?= $module; ?>';
	var numberRecords = Math.floor((Ext.getCmp('tabpanel').getInnerHeight() - 120)/22);

	Ext.getCmp('tab-<?= $module; ?>_<?= $id; ?>').on('beforeclose', function(){
		dialogProducts.destroy();
	});

	var storePosicion  = new Ext.data.JsonStore({
		url:'posicion/list'
		,root:'data'
		,sortInfo:{field:'id_posicion',direction:'ASC'}
		,totalProperty:'total'
		,fields:[
			{name:'id_posicion', type:'string'}
			,{name:'posicion', type:'string'}
		]
	});

	var cmProducts = new Ext.grid.ColumnModel({
		columns:[{
			header: '<?= Lang::get('indicador.columns_title.posicion'); ?>'
			,dataIndex: 'id_posicion'
		},{
			header: '<?= Lang::get('indicador.columns_title.desc_posicion'); ?>'
			,dataIndex: 'posicion'
		}]
		,defaults:{
			sortable:true
		}
	});

	var gridProducts = new Ext.grid.GridPanel({
		store:storePosicion
		,id:module+'gridProducts'
		,colModel:cmProducts
		,viewConfig:{forceFit:true, scrollOffset:0}
	});

	var dialogProducts = new Ext.Window({
		id:module+'dialogProducts'
		,width:500
		,height:300
		,layout:'fit'
		,autoScroll:true
		,closeAction:'hide'
		,modal:true
		,items:[gridProducts]
	});
	
	var storeSector = new Ext.data.JsonStore({
		url:'sector/list'
		,root:'data'
		,sortInfo:{field:'sector_id',direction:'ASC'}
		,totalProperty:'total'
		,baseParams:{id:'<?= $id; ?>'}
		,fields:[
			{name:'sector_id', type:'float'},
			{name:'sector_nombre', type:'string'},
			{name:'sector_productos', type:'string'}
		]
	});
	
	storeSector.load({params:{start:0, limit:numberRecords}});
	
	gridSectorAction = new Ext.ux.grid.RowActions({
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
			iconCls: 'icon-view'
			,tooltip: Ext.ux.lang.buttons.detail_tt
		}]
		,callbacks:{
			'silk-delete':function(grid, record, action, row, col) {
				fnDeleteItem(record);
			}
			,'silk-page-edit':function(grid, record, action, row, col) {
				fnEditItm(record);
			}
			,'icon-view':function(grid, record, action, row, col) {
				fnViewDetail(record);
			}
		}
	});
	
	var cmSector = new Ext.grid.ColumnModel({
		columns:[
			{header:'<?= Lang::get('sector.columns_title.sector_nombre'); ?>', align:'left', hidden:false, dataIndex:'sector_nombre'},
			{header:'<?= Lang::get('sector.columns_title.sector_productos'); ?>', align:'left', hidden:false, dataIndex:'sector_productos'},
			gridSectorAction
		]
		,defaults:{
			sortable:true
			,width:100
		}
	});
		
	var tbSector = new Ext.Toolbar({
		items:[{
			text: Ext.ux.lang.buttons.add
			,iconCls: 'silk-add'
			,handler: function(){
				if(Ext.getCmp('tab-edit_'+module)){
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
						,url:'sector/jscode/create'
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
	var gridSector = new Ext.grid.GridPanel({
		store:storeSector
		,id:module + 'gridSector'
		,colModel:cmSector
		,viewConfig: {
			forceFit: true
			,scrollOffset:2
		}
		,sm: new Ext.grid.RowSelectionModel({
			singleSelect: true
		})
		,bbar:new Ext.PagingToolbar({pageSize:numberRecords, store:storeSector, displayInfo:true})
		,enableColumnMove:false
		,enableColumnResize:false
		,tbar:tbSector
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
			,gridSectorAction
		]
	});

	/*elimiar cualquier estado de la grilla guardado con anterioridad */
	Ext.state.Manager.clear(gridSector.getItemId());
	Ext.state.Manager.clear(dialogProducts.getItemId());
	
	return gridSector;	
	/*********************************************** Start functions***********************************************/

	function fnViewDetail (record) {
		var products = record.get('sector_productos').split(',');
		storePosicion.setBaseParam('query', products.join('|'));
		storePosicion.setBaseParam('valuesqry', true);
		storePosicion.load();
		dialogProducts.show();
	}
	
	function fnEditItm(record){
		var key = record.get('sector_id');
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
				,url:'sector/jscode/modify'
				,params:{
					id:'<?= $id; ?>'
					,title: '<?= $title; ?> - ' + Ext.ux.lang.buttons.modify
					,module: 'edit_' + module
					,parent: module
					,sector_id: key
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
				var gridMask = new Ext.LoadMask(gridSector.getEl(), { msg: 'Erasing .....' });
				gridMask.show();
				var key = record.get('sector_id');

				Ext.Ajax.request({
					 url:'sector/delete'
					,params: {
						id: '<?= $id; ?>'
						,sector_id: key
					}
					,callback: function(options, success, response){
						gridMask.hide();
						var json = Ext.util.JSON.decode(response.responseText);
						if (json.success){
							gridSector.store.reload();
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