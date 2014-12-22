/*<script>*/
(function(){
	Ext.form.Field.prototype.msgTarget = 'side';
	var module = '<?= $module; ?>';
	var numberRecords = Math.floor((Ext.getCmp('tabpanel').getInnerHeight() - 120)/22);
	
	var storeProduccion = new Ext.data.JsonStore({
		url:'produccion/list'
		,root:'data'
		,sortInfo:{field:'produccion_anio',direction:'ASC'}
		,totalProperty:'total'
		,baseParams:{id:'<?= $id; ?>'}
		,fields:[
			{name:'produccion_id', type:'float'},
			{name:'produccion_sector_id', type:'float'},
			{name:'sector_nombre', type:'string'},
			{name:'produccion_anio', type:'string'},
			{name:'produccion_peso_neto', type:'float'},
		]
	});
	
	storeProduccion.load({params:{start:0, limit:numberRecords}});
	
	gridProduccionAction = new Ext.ux.grid.RowActions({
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
	
	var cmProduccion = new Ext.grid.ColumnModel({
		columns:[
			{header:'<?= Lang::get('produccion.columns_title.produccion_anio'); ?>', align:'left', hidden:false, dataIndex:'produccion_anio'},
			{header:'<?= Lang::get('sector.columns_title.sector_nombre'); ?>', align:'left', hidden:false, dataIndex:'sector_nombre'},
			{header:'<?= Lang::get('produccion.columns_title.produccion_peso_neto'); ?>' , align:'left', hidden:false, dataIndex:'produccion_peso_neto','renderer':numberFormat, align:'right'},
			gridProduccionAction
		]
		,defaults:{
			sortable:true
			,width:100
		}
	});
		
	var tbProduccion = new Ext.Toolbar({
		items:[{
			text: Ext.ux.lang.buttons.add
			,iconCls: 'silk-add'
			,handler: function(){				
				var data = {
					id:'add_' + module
					,iconCls:'silk-add'
					,titleTab:'<?= $title; ?> - ' + Ext.ux.lang.buttons.add
					,url:'produccion/jscode/create'
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
	var gridProduccion = new Ext.grid.GridPanel({
		store:storeProduccion
		,id:module + 'gridProduccion'
		,colModel:cmProduccion
		,viewConfig: {
			forceFit: true
			,scrollOffset:2
		}
		,sm: new Ext.grid.RowSelectionModel({
			singleSelect: true
		})
		,bbar:new Ext.PagingToolbar({pageSize:numberRecords, store:storeProduccion, displayInfo:true})
		,enableColumnMove:false
		,enableColumnResize:false
		,tbar:tbProduccion
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
			,gridProduccionAction
		]
	});
	
	return gridProduccion;	
	/*********************************************** Start functions***********************************************/
	
	function fnEditItm(record){
		var key = record.get('produccion_id');
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
				,url:'produccion/jscode/modify'
				,params:{
					id:'<?= $id; ?>'
					,title: '<?= $title; ?> - ' + Ext.ux.lang.buttons.modify
					,module: 'edit_' + module
					,parent: module
					,produccion_id: key
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
				var gridMask = new Ext.LoadMask(gridProduccion.getEl(), { msg: 'Erasing .....' });
				gridMask.show();
				var key = record.get('produccion_id');

				Ext.Ajax.request({
					 url:'produccion/delete'
					,params: {
						id: '<?= $id; ?>'
						,produccion_id: key
					}
					,callback: function(options, success, response){
						gridMask.hide();
						var json = Ext.util.JSON.decode(response.responseText);
						if (json.success){
							gridProduccion.store.reload();
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