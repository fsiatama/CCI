/*<script>*/
(function(){
	Ext.form.Field.prototype.msgTarget = 'side';
	var module = '<?= $module; ?>';
	var numberRecords = Math.floor((Ext.getCmp('tabpanel').getInnerHeight() - 120)/22);

	var storePais = new Ext.data.JsonStore({
		url:'pais/list'
		,id:module+'storePais'
		,root:'data'
		,sortInfo:{field:'id_pais',direction:'ASC'}
		,totalProperty:'total'
		,baseParams:{
			id:'<?= $id; ?>'
			,valuesqry: true
		}
		,fields:[
			{name:'id_pais', type:'float'},
			{name:'pais', type:'string'},
			{name:'pais_iata', type:'string'},
		]
	});

	var listViewCountries = new Ext.list.ListView({
		store: storePais,
		reserveScrollOffset: true,
		columns: [{
			header: '<?= Lang::get('pais.columns_title.pais'); ?>',
			dataIndex: 'pais'
		},{
			header: '<?= Lang::get('pais.columns_title.pais_iata'); ?>',
			dataIndex: 'pais_iata'
		}]
	});

	var dialogCountries = new Ext.Window({
		id:module+'dialogCountries'
		,layout:'fit'
		,width:230
		,autoHeight:true
		,modal:true
		,draggable:false
		,resizable:false
		//,items:[formPeriodo]
		,closeAction:'hide'		
		,border:true
		,plain:true
	});
	
	var storeMercado = new Ext.data.JsonStore({
		url:'mercado/list'
		,root:'data'
		,sortInfo:{field:'mercado_id',direction:'ASC'}
		,totalProperty:'total'
		,baseParams:{id:'<?= $id; ?>'}
		,fields:[
			{name:'mercado_id', type:'float'},
			{name:'mercado_nombre', type:'string'},
			{name:'mercado_paises', type:'string'}
		]
	});
	
	storeMercado.load({params:{start:0, limit:numberRecords}});
	
	gridMercadoAction = new Ext.ux.grid.RowActions({
		header: Ext.ux.lang.grid.options
		,keepSelection:true
		,autoWidth:false
		,actions:[{
			iconCls:'silk-delete'
			,tooltip: Ext.ux.lang.buttons.delete_tt
		},{
			 iconCls: 'silk-page-edit'
			,tooltip: Ext.ux.lang.buttons.modify_tt
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
	
	var cmMercado = new Ext.grid.ColumnModel({
		columns:[
			{header:'<?= Lang::get('mercado.columns_title.mercado_nombre'); ?>', align:'left', hidden:false, dataIndex:'mercado_nombre'},
			{header:'<?= Lang::get('mercado.columns_title.mercado_paises'); ?>', align:'left', hidden:false, dataIndex:'mercado_paises'},
			gridMercadoAction
		]
		,defaults:{
			sortable:true
			,width:100
		}
	});
		
	var tbMercado = new Ext.Toolbar({
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
						,url:'mercado/jscode/create'
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
	var gridMercado = new Ext.grid.GridPanel({
		store:storeMercado
		,id:module + 'gridMercado'
		,colModel:cmMercado
		,viewConfig: {
			forceFit: true
			,scrollOffset:2
		}
		,sm: new Ext.grid.RowSelectionModel({
			singleSelect: true
		})
		,bbar:new Ext.PagingToolbar({pageSize:numberRecords, store:storeMercado, displayInfo:true})
		,enableColumnMove:false
		,enableColumnResize:false
		,tbar:tbMercado
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
			,gridMercadoAction
		]
	});
	
	/*elimiar cualquier estado de la grilla guardado con anterioridad */
	Ext.state.Manager.clear(gridMercado.getItemId());

	return gridMercado;	
	/*********************************************** Start functions***********************************************/

	function fnViewDetail (record) {
		var countries = record.get('mercado_paises').split(',');
		storePais.setBaseParam('query', countries.join('|'));
		storePais.load();
	}
	
	function fnEditItm(record){
		var key = record.get('mercado_id');
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
				,url:'mercado/jscode/modify'
				,params:{
					id:'<?= $id; ?>'
					,title: '<?= $title; ?> - ' + Ext.ux.lang.buttons.modify
					,module: 'edit_' + module
					,parent: module
					,mercado_id: key
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
				var gridMask = new Ext.LoadMask(gridMercado.getEl(), { msg: 'Erasing .....' });
				gridMask.show();
				var key = record.get('mercado_id');

				Ext.Ajax.request({
					 url:'mercado/delete'
					,params: {
						id: '<?= $id; ?>'
						,mercado_id: key
					}
					,callback: function(options, success, response){
						gridMask.hide();
						var json = Ext.util.JSON.decode(response.responseText);
						if (json.success){
							gridMercado.store.reload();
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