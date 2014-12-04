/*<script>*/
(function(){
	Ext.form.Field.prototype.msgTarget = 'side';
	var module = '<?= $module; ?>';
	var numberRecords = Math.floor((Ext.getCmp('tabpanel').getInnerHeight() - 120)/22);
	
	var storeTipo_indicador = new Ext.data.JsonStore({
		url:'tipo_indicador/list'
		,root:'data'
		,sortInfo:{field:'tipo_indicador_id',direction:'ASC'}
		,totalProperty:'total'
		,baseParams:{id:'<?= $id; ?>'}
		,fields:[
			{name:'tipo_indicador_id', type:'float'},
			{name:'tipo_indicador_nombre', type:'string'},
			{name:'tipo_indicador_abrev', type:'string'},
			{name:'tipo_indicador_activador_title', type:'string'},
			{name:'tipo_indicador_calculo', type:'string'},
			{name:'tipo_indicador_definicion', type:'string'}
		]
	});

	
	storeTipo_indicador.load({params:{start:0, limit:numberRecords}});
	
	gridTipo_indicadorAction = new Ext.ux.grid.RowActions({
		 header: Ext.ux.lang.grid.options
		,keepSelection:true
		,autoWidth:false
		,actions:[{
			iconCls:'silk-chart-bar-link'
			,tooltip: Ext.ux.lang.buttons.generate_tt
		}]
		,callbacks:{
			'silk-chart-bar-link':function(grid, record, action, row, col) {
				fnReport(record);
			}
		}
	});
	
	gridTipo_indicadorExpander = new Ext.grid.RowExpander({
		tpl: new Ext.Template(
			 '<br><p style="margin:0 0 4px 8px"><b><?= Lang::get('tipo_indicador.columns_title.tipo_indicador_nombre'); ?>:</b> {tipo_indicador_nombre}</p>'
			 ,'<p style="margin:0 0 4px 8px"><b><?= Lang::get('tipo_indicador.columns_title.tipo_indicador_definicion'); ?>:</b> {tipo_indicador_calculo}</p>'
			 ,'<p style="margin:0 0 4px 8px"><b><?= Lang::get('tipo_indicador.columns_title.tipo_indicador_definicion'); ?>:</b> {tipo_indicador_definicion}</p>'
		)
	});

	var cmTipo_indicador = new Ext.grid.ColumnModel({
		columns:[
			gridTipo_indicadorExpander,
			{header:'<?= Lang::get('tipo_indicador.columns_title.tipo_indicador_nombre'); ?>', align:'left', hidden:false, dataIndex:'tipo_indicador_nombre'},
			{header:'<?= Lang::get('tipo_indicador.columns_title.tipo_indicador_abrev'); ?>', align:'left', hidden:false, dataIndex:'tipo_indicador_abrev'},
			{header:'<?= Lang::get('tipo_indicador.columns_title.tipo_indicador_activador'); ?>', align:'left', hidden:false, dataIndex:'tipo_indicador_activador_title'},
			gridTipo_indicadorAction
		]
		,defaults:{
			sortable:true
			,width:100
		}
	});

	var tbTipo_indicador = new Ext.Toolbar();

	var gridTipo_indicador = new Ext.grid.GridPanel({
		store:storeTipo_indicador
		,id:module+'gridTipo_indicador'
		,colModel:cmTipo_indicador
		,viewConfig: {
			forceFit: true
			,scrollOffset:2
		}
		,sm:new Ext.grid.RowSelectionModel({singleSelect:true})
		,bbar:new Ext.PagingToolbar({pageSize:numberRecords, store:storeTipo_indicador, displayInfo:true})
		,enableColumnMove:false
		,enableColumnResize:false
		,tbar:tbTipo_indicador
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
				,disableIndexes:['tipo_indicador_activador_title']
			}) 
			,gridTipo_indicadorAction
			,gridTipo_indicadorExpander
		]
	});
	
	return gridTipo_indicador;	
	/*********************************************** Start functions***********************************************/
	
	function fnReport(record){
		var key = record.get('tipo_indicador_id');
		var abbrev = record.get('tipo_indicador_abrev');
		var data = {
			id:'indicator_' + key
			,iconCls:'silk-page-edit'
			,titleTab: abbrev
			,url:'indicador/jscode'
			,params:{
				id:'<?= $id; ?>'
				,title: abbrev
				,module: 'indicator_' + key
				,parent: module
				,tipo_indicador_id: key
			}
		};
		Ext.getCmp('oeste').addTab(this,this,data);
	}

	/*********************************************** End functions***********************************************/
})()