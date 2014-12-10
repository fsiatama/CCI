/*<script>*/
(function(){
	Ext.form.Field.prototype.msgTarget = 'side';
	var module = '<?= $module; ?>';
	var numberRecords = Math.floor((Ext.getCmp('tabpanel').getInnerHeight() - 120)/22);
	
	var storeSector = new Ext.data.JsonStore({
		url:'sector/list'
		,root:'data'
		,sortInfo:{field:'id_sector',direction:'ASC'}
		,totalProperty:'total'
		,baseParams:{id:'<?= $id; ?>'}
		,fields:[
			{name:'id_sector', type:'float'},
			{name:'sector', type:'string'},
			{name:'id_posicion', type:'string'}
		]
	});
	
	storeSector.load({params:{start:0, limit:numberRecords}});
	
	gridSectorAction = new Ext.ux.grid.RowActions({
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
	
	var cmSector = new Ext.grid.ColumnModel({
		columns:[
			{header:'<?= Lang::get('sector.columns_title.sector'); ?>', align:'left', hidden:false, dataIndex:'sector'},
			{header:'<?= Lang::get('sector.columns_title.id_posicion'); ?>', align:'left', hidden:false, dataIndex:'id_posicion'},
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
	
	return gridSector;	
	/*********************************************** Start functions***********************************************/
	
	function fnEditItm(record){
		var key = record.get('id_sector');
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
					,id_sector: key
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
				var key = record.get('id_sector');

				Ext.Ajax.request({
					 url:'sector/delete'
					,params: {
						id: '<?= $id; ?>'
						,id_sector: key
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