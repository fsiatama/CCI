<?php 
$updateInfoExpo = ( $updateInfoExpo !== false ) ? Lang::get('shared.months.'.$updateInfoExpo['dateTo']->format('m')).' - '.$updateInfoExpo['dateTo']->format('Y') : '' ;
$updateInfoImpo = ( $updateInfoImpo !== false ) ? Lang::get('shared.months.'.$updateInfoImpo['dateTo']->format('m')).' - '.$updateInfoImpo['dateTo']->format('Y') : '' ;

$updateInfo = '
	' . Lang::get('indicador.reports.impo') . ' ' . Lang::get('update_info.columns_title.update_info_to') . ': ' . $updateInfoImpo . '
	<br>
	' . Lang::get('indicador.reports.expo') . ' ' . Lang::get('update_info.columns_title.update_info_to') . ': ' . $updateInfoExpo . '
';

$updateInfo = Inflector::compress($updateInfo);
?>
/*<script>*/
(function(){
	Ext.form.Field.prototype.msgTarget = 'side';
	var module = '<?= $module; ?>';
	var numberRecords = Math.floor((Ext.getCmp('tabpanel').getInnerHeight() - 280)/22);

	Ext.getCmp('tab-'+module+'_<?= $id; ?>').on('beforeclose', function(){
		/*elimiar cualquier estado de la grilla guardado con anterioridad */
		Ext.state.Manager.clear(gridTipo_indicador.getItemId());
	});

	
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
			,qtip: Ext.ux.lang.buttons.generate_tt
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
			 ,'<p style="margin:0 0 4px 8px"><b><?= Lang::get('tipo_indicador.columns_title.tipo_indicador_calculo'); ?>:</b> {tipo_indicador_calculo}</p>'
			 ,'<p style="margin:0 0 4px 8px"><b><?= Lang::get('tipo_indicador.columns_title.tipo_indicador_definicion'); ?>:</b> {tipo_indicador_definicion}</p>'
		)
	});

	var cmTipo_indicador = new Ext.grid.ColumnModel({
		columns:[
			gridTipo_indicadorExpander,
			{header:'Id', align:'left', hidden:true, dataIndex:'tipo_indicador_id' ,width:20},
			{header:'<?= Lang::get('tipo_indicador.columns_title.tipo_indicador_nombre'); ?>', align:'left', hidden:false, dataIndex:'tipo_indicador_nombre'},
			{header:'<?= Lang::get('tipo_indicador.columns_title.tipo_indicador_abrev'); ?>', align:'left', hidden:false, dataIndex:'tipo_indicador_abrev'},
			{header:'<?= Lang::get('tipo_indicador.columns_title.tipo_indicador_activador'); ?>', align:'left', hidden:true, dataIndex:'tipo_indicador_activador_title',hideable: false},
			{header:'<?= Lang::get('tipo_indicador.columns_title.tipo_indicador_calculo'); ?>', align:'left', hidden:true, dataIndex:'tipo_indicador_calculo',hideable: false},
			{header:'<?= Lang::get('tipo_indicador.columns_title.tipo_indicador_definicion'); ?>', align:'left', hidden:true, dataIndex:'tipo_indicador_definicion',hideable: false},
			gridTipo_indicadorAction
		]
		,defaults:{
			sortable:true
			,width:100
		}
	});

	var tbTipo_indicador = new Ext.Toolbar();

	var gridTipo_indicador = new Ext.grid.GridPanel({
		autoHeight:true //
		,autoWidth:true //
		,bbar:new Ext.PagingToolbar({pageSize:numberRecords, store:storeTipo_indicador, displayInfo:true})
		,border:true //
		,buttonAlign:'center'
		,colModel:cmTipo_indicador
		,columnLines:true //
		,enableColumnMove:false
		,enableColumnResize:false
		,iconCls:'silk-grid' //
		,id:module+'gridTipo_indicador'
		,layout:'fit' //
		,loadMask:true
		,margins:'10 15 5 0' //
		,monitorResize:true //
		,sm:new Ext.grid.RowSelectionModel({singleSelect:true}) //
		,stateful:true //
		,store:storeTipo_indicador
		,stripeRows:true //
		,tbar:tbTipo_indicador
		,title:'' //
		,viewConfig: { forceFit:true } //
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

	var panelTipo_indicador = new Ext.Panel({
		xtype:'panel'
		,id:module+'panelTipo_indicador'
		,layout:'column'
		,border:false
		,baseCls:'x-plain'
		,autoWidth:true
		,autoScroll:true
		,bodyStyle:	'padding:15px;position:relative;'
		,defaults:{
			columnWidth:1
			,border:false
			,xtype:'panel'
			,style:{padding:'10px'}
			,layout:'fit'
		}
		,items:[{
			defaults:{anchor:'100%'}
			,items:[{
				style:{padding:'0px'}
				,autoHeight:true
				,border:false
				,margins:'10 15 5 0'
				,html: '<div class="bootstrap-styles">' +
					'<div class="panel panel-default">' +
						'<div class="panel-heading">' +
							'<?= Lang::get('update_info.table_name'); ?>' +
						'</div>' +
						'<div class="panel-body">' +
								'<?= $updateInfo; ?>' +
						'</div>' +
					'</div>' +
				'</div>'
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[gridTipo_indicador]
		}]
	});

	/*elimiar cualquier estado de la grilla guardado con anterioridad */
	Ext.state.Manager.clear(gridTipo_indicador.getItemId());
	
	return panelTipo_indicador;	
	/*********************************************** Start functions***********************************************/
	
	function fnReport(record){
		var key = record.get('tipo_indicador_id');
		var abbrev = record.get('tipo_indicador_abrev');
		var data = {
			id:'indicator_' + key
			,iconCls:'silk-chart-bar-link'
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