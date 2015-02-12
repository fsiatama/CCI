<?php

$arrDescription  = explode('||', $indicador_campos);

$htmlDescription = '<ol class="breadcrumb">';

foreach ($arrDescription as $value) {
	$arr = explode(':', $value);
	$text = (empty($arr[1])) ? '' : $arr[1] ;
	$htmlDescription .= '<li class="active">'.$text.'</li>';
}

$htmlDescription .= '</ol>';

?>

/*<script>*/
(function(){
	Ext.form.Field.prototype.msgTarget = 'side';
	var module = '<?= $module.'_'.$indicador_id; ?>';
	var panelHeight = Math.floor(Ext.getCmp('tabpanel').getInnerHeight() - 260);

	var storeBalanza = new Ext.data.JsonStore({
		url:'indicador/execute'
		,root:'data'
		,id:module+'storeBalanza'
		,sortInfo:{field:'id',direction:'ASC'}
		,totalProperty:'total'
		,baseParams: {
			id: '<?= $id; ?>'
			,indicador_id: '<?= $indicador_id; ?>'
		}
		,fields:[
			{name:'id', type:'float'},
			{name:'firstPeriod', type:'string'},
			{name:'firstValue', type:'float'},
			{name:'lastPeriod', type:'string'},
			{name:'lastValue', type:'float'},
			{name:'rateVariation', type:'float'}
		]
	});

	storeBalanza.on('beforeload', function(){
		var period = Ext.getCmp(module + 'comboPeriod').getValue();
		if (!period) {
			return false;
		};
		this.setBaseParam('period', period);
		Ext.ux.bodyMask.show();
	});
	
	storeBalanza.on('load', function(store){
		FusionCharts.setCurrentRenderer('javascript');
		
		disposeCharts();

		var chart = new FusionCharts('<?= COLUMNAS; ?>', module + 'ColumnChartId', '100%', '100%', '0', '1');
		chart.setTransparent(true);
		chart.setJSONData(store.reader.jsonData.columnChartData);
		chart.render(module + 'ColumnChart');
		Ext.ux.bodyMask.hide();
	});

	var titles = [
		{header: Ext.ux.lang.reports.initialRange, colspan: 2, align: 'center'},
		{header: Ext.ux.lang.reports.finalRange, colspan: 2, align: 'center'},
		{header: '', colspan: 1, align: 'center'}
	];

	var group = new Ext.ux.grid.ColumnHeaderGroup({
		rows: [titles]
	});

	var colModelBalanza = new Ext.grid.ColumnModel({
		columns:[
			{header:'<?= Lang::get('indicador.columns_title.periodo'); ?>', dataIndex:'firstPeriod', align:'left'},
			{header:'<?= Lang::get('indicador.columns_title.numero_empresas_expo'); ?>', dataIndex:'firstValue' ,'renderer':integerFormat},
			{header:'<?= Lang::get('indicador.columns_title.periodo'); ?>', dataIndex:'lastPeriod', align:'left'},
			{header:'<?= Lang::get('indicador.columns_title.numero_empresas_expo'); ?>', dataIndex:'lastValue' ,'renderer':integerFormat},
			{header:'<?= Lang::get('indicador.reports.variation'); ?>', dataIndex:'rateVariation' ,'renderer':rateFormat}
		]
		,defaults: {
			sortable: true
			,align: 'right'
		}
	});
	
	var gridBalanza = new Ext.grid.GridPanel({
		border:true
		,monitorResize:true
		,store:storeBalanza
		,colModel:colModelBalanza
		,stateful:true
		,columnLines:true
		,stripeRows:true
		,viewConfig: {
			forceFit:true
		}
		,enableColumnMove:false
		,id:module+'gridBalanza'			
		,sm:new Ext.grid.RowSelectionModel({singleSelect:true})
		,bbar:new Ext.PagingToolbar({pageSize:10000, store:storeBalanza, displayInfo:true})
		,iconCls:'silk-grid'
		,plugins:[group, new Ext.ux.grid.Excel()]
		,layout:'fit'
		,height:400
		,autoWidth:true
		,margins:'10 15 5 0'
	});
	/*elimiar cualquier estado de la grilla guardado con anterioridad */
	Ext.state.Manager.clear(gridBalanza.getItemId());
	
	var arrPeriods = <?= json_encode($periods); ?>;

	/******************************************************************************************************************************************************************************/
	
	var indicadorContainer = new Ext.Panel({
		xtype:'panel'
		,id:module + 'excuteIndicadorContainer'
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
			style:{padding:'0px'}
			,html: '<div class="bootstrap-styles">' +
				'<div class="page-head">' +
					'<h4 class="nopadding"><i class="styleColor fa fa-area-chart"></i> <?= $tipo_indicador_nombre; ?>: <small><?= $indicador_nombre; ?></small></h4>' +
					'<div class="clearfix"></div><?= $htmlDescription; ?>' +
				'</div>' +
			'</div>'
		},{
			style:{padding:'0px'}
			,border:true
			,html: ''
			,tbar:[{
				xtype: 'label'
				,text: Ext.ux.lang.reports.selectPeriod + ': '
			},{
				xtype: 'combo'
				,store: arrPeriods
				,id: module + 'comboPeriod'
				,typeAhead: true
				,forceSelection: true
				,triggerAction: 'all'
				,selectOnFocus:true
				,value: 12
				,width: 100
			},'-',{
				text: Ext.ux.lang.buttons.generate
				,iconCls: 'icon-refresh'
				,handler: function () {
					storeBalanza.load();
				}
			}]
		},{
			height:430
			,html:'<div id="' + module + 'ColumnChart"></div>'
			,items:[{
				xtype:'panel'
				,id: module + 'ColumnChart'
				,plain:true
			}]
		/*},{
			height:430
			,html:'<div id="' + module + 'AreaChart"></div>'
			,items:[{
				xtype:'panel'
				,id: module + 'AreaChart'
				,plain:true
			}]*/
		},{
			defaults:{anchor:'100%'}
			,items:[gridBalanza]
		}]
		,listeners:{
			beforedestroy: {
				fn: function(p){
					disposeCharts();
				}
			}
		}
	});

	Ext.getCmp('<?= $panel; ?>').on('deactivate', function(p){
		disposeCharts();
	});

	Ext.getCmp('<?= $panel; ?>').on('activate', function(p){
		storeBalanza.load();
	});
	
	storeBalanza.load();

	return indicadorContainer;

	/*********************************************** Start functions***********************************************/
	function disposeCharts () {
		if(FusionCharts(module + 'ColumnChartId')){
			FusionCharts(module + 'ColumnChartId').dispose();
		}		
	}

	/*********************************************** End functions***********************************************/
})()