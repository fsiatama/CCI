<?php

$arrDescription  = explode('||', $indicador_campos);

$htmlDescription = '<ol class="breadcrumb">';

foreach ($arrDescription as $value) {
	$arr = explode(':', $value);
	$text = (empty($arr[1])) ? '' : $arr[1] ;
	$htmlDescription .= '<li class="active">'.$text.'</li>';
}

$htmlDescription .= '</ol>';
array_splice($periods, -1);//elimina el periodo mensual

?>

/*<script>*/
(function(){
	Ext.form.Field.prototype.msgTarget = 'side';
	var module = '<?= $module.'_'.$indicador_id; ?>';
	var panelHeight = Math.floor(Ext.getCmp('tabpanel').getInnerHeight() - 260);

	var storeIndicador = new Ext.data.JsonStore({
		url:'indicador/execute'
		,root:'data'
		,id:module+'storeIndicador'
		,autoDestroy:true
		,sortInfo:{field:'id',direction:'ASC'}
		,totalProperty:'total'
		,baseParams: {
			id: '<?= $id; ?>'
			,indicador_id: '<?= $indicador_id; ?>'
		}
		,fields:[
			{name:'id', type:'float'},
			{name:'periodo', type:'string'},
			{name:'valor_expo_agricola_cop', type:'float'},
			{name:'pib_nacional', type:'float'},
			{name:'participacion', type:'float'}
		]
	});

	storeIndicador.on('beforeload', function(){
		//var year   = Ext.getCmp(module + 'comboYear').getValue();
		var period    = Ext.getCmp(module + 'comboPeriod').getValue();
		var scale     = Ext.getCmp(module + 'comboScale').getValue();
		var chartType = Ext.getCmp(module + 'comboCharts').getValue();
		if (!period || !scale || !chartType) {
			return false;
		};
		//this.setBaseParam('year', year);
		this.setBaseParam('period', period);
		this.setBaseParam('scale', scale);
		this.setBaseParam('chartType', chartType);
		Ext.ux.bodyMask.show();
	});

	storeIndicador.on('load', function(store){
		FusionCharts.setCurrentRenderer('javascript');

		disposeCharts();

		var chartType = Ext.getCmp(module + 'comboCharts').getValue();
		var chart     = new FusionCharts(chartType, module + 'ChartId', '100%', '100%', '0', '1');

		chart.setTransparent(true);
		chart.setJSONData(store.reader.jsonData.chartData);
		chart.render(module + 'Chart');

		Ext.ux.bodyMask.hide();
	});
	var colModelIndicador = new Ext.grid.ColumnModel({
		columns:[
			{header:'<?= Lang::get('indicador.columns_title.periodo'); ?>', dataIndex:'periodo', align: 'left'},
			{header:'<?= Lang::get('indicador.columns_title.valor_expo_agricola_cop'); ?>', dataIndex:'valor_expo_agricola_cop' ,'renderer':numberFormat},
			{header:'<?= Lang::get('pib.columns_title.pib_nacional'); ?>', dataIndex:'pib_nacional' ,'renderer':numberFormat},
			{header:'<?= Lang::get('indicador.columns_title.participacion'); ?>', dataIndex:'participacion','renderer':rateFormat},
		]
		,defaults: {
			sortable: true
			,align: 'right'
		}
	});

	var gridIndicador = new Ext.grid.GridPanel({
		border:true
		,monitorResize:true
		,store:storeIndicador
		,colModel:colModelIndicador
		,stateful:true
		,columnLines:true
		,stripeRows:true
		,viewConfig: {
			forceFit:true
		}
		,enableColumnMove:false
		,id:module+'gridIndicador'
		,sm:new Ext.grid.RowSelectionModel({singleSelect:true})
		,bbar:new Ext.PagingToolbar({pageSize:10000, store:storeIndicador, displayInfo:true})
		,iconCls:'silk-grid'
		,plugins:[new Ext.ux.grid.Excel()]
		,layout:'fit'
		,height:350
		,autoWidth:true
		,margins:'10 15 5 0'
	});
	/*elimiar cualquier estado de la grilla guardado con anterioridad */
	Ext.state.Manager.clear(gridIndicador.getItemId());

	var arrYears = <?= json_encode($yearsAvailable); ?>;
	var defaultYear = <?= end($yearsAvailable); ?>;

	var arrPeriods = <?= json_encode($periods); ?>;
	var arrScales = <?= json_encode($scales); ?>;
	var arrCharts    = <?= json_encode($charts); ?>;


	/******************************************************************************************************************************************************************************/

	var indicadorContainer = new Ext.Panel({
		xtype:'panel'
		,id:module + 'executeIndicadorContainer'
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
				xtype: 'buttongroup'
				,columns: 1
				,defaults: {
					scale: 'small'
				}
				,items: [{
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
					,width: 120
				}]
			},{
				xtype: 'buttongroup'
				,columns: 1
				,defaults: {
					scale: 'small'
				},
				items: [{
					xtype: 'label'
					,text: Ext.ux.lang.reports.selectScale + ': '
				},{
					xtype: 'combo'
					,store: arrScales
					,id: module + 'comboScale'
					,typeAhead: true
					,forceSelection: true
					,triggerAction: 'all'
					,selectOnFocus:true
					,value: 1
					,width: 150
				}]
			},{
				xtype: 'buttongroup'
				,columns: 1
				,defaults: {
					scale: 'small'
				},
				items: [{
					xtype: 'label'
					,text: Ext.ux.lang.reports.selectChart + ': '
				},{
					xtype: 'combo'
					,store: arrCharts
					,id: module + 'comboCharts'
					,typeAhead: true
					,forceSelection: true
					,triggerAction: 'all'
					,selectOnFocus:true
					,value: '<?= AREA; ?>'
					,width: 150
				}]
			},{
				xtype:'buttongroup',
				items: [{
					text: Ext.ux.lang.buttons.generate
					,iconCls: 'icon-refresh'
					,iconAlign: 'top'
					,handler: function () {
						storeIndicador.load();
					}
				}]
			}]
		},{
			height:430
			,html:'<div id="' + module + 'Chart"></div>'
			,items:[{
				xtype:'panel'
				,id: module + 'Chart'
				,plain:true
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[gridIndicador]
		}]
		,listeners:{
			beforedestroy: {
				fn: function(p){
					disposeCharts();
				}
			}
		}
	});

	Ext.getCmp('<?= $panel; ?>').on('deactivate', function(p) {
		disposeCharts();
	}, this);

	Ext.getCmp('<?= $panel; ?>').on('activate', function(p) {
		storeIndicador.load();
	}, this);

	storeIndicador.load();

	return indicadorContainer;

	/*********************************************** Start functions***********************************************/

	function disposeCharts () {
		if(FusionCharts(module + 'ChartId')){
			FusionCharts(module + 'ChartId').dispose();
		}
	}

	/*********************************************** End functions***********************************************/
})()