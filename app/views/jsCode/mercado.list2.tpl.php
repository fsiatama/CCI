/*<script>*/
(function(){
	Ext.form.Field.prototype.msgTarget = 'side';
	var module = '<?= $module; ?>';
	var numberRecords = Math.floor((Ext.getCmp('tabpanel').getInnerHeight() - 120)/22);

	Ext.getCmp('tab-<?= $module; ?>_<?= $id; ?>').on('beforeclose', function(){
		dialogCountries.destroy();
	});

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

	var cmCountries = new Ext.grid.ColumnModel({
		columns:[{
			header: '<?= Lang::get('pais.columns_title.pais'); ?>'
			,dataIndex: 'pais'
		},{
			header: '<?= Lang::get('pais.columns_title.pais_iata'); ?>'
			,dataIndex: 'pais_iata'
		}]
		,defaults:{
			sortable:true
		}
	});

	var gridCountries = new Ext.grid.GridPanel({
		store:storePais
		,id:module+'gridCountries'
		,colModel:cmCountries
		,viewConfig:{forceFit:true, scrollOffset:0}
	});

	var dialogCountries = new Ext.Window({
		id:module+'dialogCountries'
		,width:500
		,height:300
		,layout:'fit'
		,autoScroll:true
		,closeAction:'hide'
		,modal:true
		,items:[gridCountries]
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
			 iconCls: 'icon-view'
			,tooltip: Ext.ux.lang.buttons.detail_tt
		}]
		,callbacks:{
			'icon-view':function(grid, record, action, row, col) {
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
		items:[]
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
	Ext.state.Manager.clear(dialogCountries.getItemId());

	return gridMercado;	
	/*********************************************** Start functions***********************************************/

	function fnViewDetail (record) {
		var countries = record.get('mercado_paises').split(',');
		storePais.setBaseParam('query', countries.join('|'));
		storePais.load();
		dialogCountries.show();
	}
	
	/*********************************************** End functions***********************************************/
})()