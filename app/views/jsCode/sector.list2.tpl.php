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
			iconCls: 'icon-view'
			,tooltip: Ext.ux.lang.buttons.detail_tt
		}]
		,callbacks:{
			'icon-view':function(grid, record, action, row, col) {
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
		items:[]
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

	/*********************************************** End functions***********************************************/
})()