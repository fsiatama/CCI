<?php


$htmlProducts = '<ol class="list-group">';

foreach ($productsData as $row) {
	$htmlProducts .= '<li class="list-group-item"><span class="badge">'.$row['id_posicion'].'</span>'.$row['posicion'].'</li>';
}

$htmlProducts .= '</ol>';

?>

/*<script>*/
(function(){
	Ext.form.Field.prototype.msgTarget = 'side';
	var module = '<?= $module.'_'.$acuerdo_id; ?>';
	var panelHeight = Math.floor(Ext.getCmp('tabpanel').getInnerHeight() - 260);

	var chartsId = [];

	var storeContingente = new Ext.data.JsonStore({
		url:'contingente/execute'
		,root:'data'
		,id:module+'storeContingente'
		,sortInfo:{field:'id',direction:'ASC'}
		,totalProperty:'total'
		,baseParams: {
			id: '<?= $id; ?>'
			,acuerdo_id: '<?= $acuerdo_id; ?>'
			,acuerdo_det_id: '<?= $acuerdo_det_id; ?>'
			,summary: true
		}
		,fields:[
			{name:'id', type:'float'},
			{name:'periodo', type:'string'},
			{name:'pais', type:'string'},
			{name:'quotaWeight', type:'float'},
			{name:'executedWeight', type:'float'},
			{name:'rate', type:'float'},
		]
	});

	var storeAcuerdo_det = new Ext.data.JsonStore({
		url:'contingente/execute'
		,reader: new Ext.data.JsonReader()
		,remoteSort: true
		,baseParams: {
			id: '<?= $id; ?>'
			,acuerdo_id: '<?= $acuerdo_id; ?>'
			,acuerdo_det_id: '<?= $acuerdo_det_id; ?>'
			,summary: false
		}
		,id:module+'storeAcuerdo_det'
	});

	storeAcuerdo_det.on('beforeload', function(){
		var year   = Ext.getCmp(module + 'comboYear').getValue();
		var period = Ext.getCmp(module + 'comboPeriod').getValue();
		if (!year || !period) {
			return false;
		};
		this.setBaseParam('year', year);
		this.setBaseParam('period', period);
		Ext.ux.bodyMask.show();
	});
	storeContingente.on('beforeload', function(){
		var year   = Ext.getCmp(module + 'comboYear').getValue();
		var period = Ext.getCmp(module + 'comboPeriod').getValue();
		if (!year || !period) {
			return false;
		};
		this.setBaseParam('year', year);
		this.setBaseParam('period', period);
		//Ext.ux.bodyMask.show();
	});
	
	storeAcuerdo_det.on('load', function(store){
		if (typeof(store.reader.jsonData.columns) === 'object') {
			var columns = [];
			var cm = gridAcuerdo_det.getColumnModel();

			Ext.each(store.reader.jsonData.columns, function(column) {
				if (column.renderer == 'numberFormat') {
					columns.push({
						header:column.header
						,dataIndex:column.dataIndex
						,sortable:false
						,align:'right'
						,renderer:numberFormat
						,hidden:column.hidden
					});
				} else if (column.renderer == 'rateFormat') {
					columns.push({
						header:column.header
						,dataIndex:column.dataIndex
						,sortable:false
						,align:'right'
						,renderer:rateFormat
						,hidden:column.hidden
					});
				} else {
					columns.push(column);
				};
			});

			cm.setConfig(columns);

			gridAcuerdo_det.reconfigure(store, cm);

			storeContingente.load();

		}
		FusionCharts.setCurrentRenderer('javascript');
		disposeCharts();
		if (typeof(store.reader.jsonData.chartsData) === 'object') {
			var chartsDiv = Ext.get(module + 'chartsDiv');
			var html      = '';
			var divClass  = '';
			if (store.reader.jsonData.chartsData.length == 1) {
				//divClass = 'col-md-offset-3'
			};
			Ext.each(store.reader.jsonData.chartsData, function(chartData) {
				html += '<div class="col-xs-9 ' + divClass + '" id="' + module + '_gaugeChart_' + chartData.id + '"></div>';
			});
			chartsDiv.update(html);
			Ext.each(store.reader.jsonData.chartsData, function(chartData) {
				var divId = module + '_gaugeChart_' + chartData.id;
				var chart = new FusionCharts('angulargauge', divId + 'Id', '100%', '350', '0', '1');
				chart.setTransparent(true);
				chart.setJSONData(chartData.data);
				chart.render(divId);
				chartsId.push(divId + 'Id');
			});
		}
		Ext.ux.bodyMask.hide();
	});

	var colModelAcuerdo_det = new Ext.grid.ColumnModel({
		columns:[]
	});
	
	var gridAcuerdo_det = new Ext.grid.GridPanel({
		border:true
		,monitorResize:true
		,store:storeAcuerdo_det
		,colModel:colModelAcuerdo_det
		,stateful:true
		,columnLines:true
		,stripeRows:true
		,viewConfig: {
			forceFit:true
		}
		,enableColumnMove:false
		,id:module+'gridAcuerdo_det'
		,title: '<?= Lang::get('contingente.table_name'); ?> - ' + Ext.ux.lang.reports.detail
		,sm:new Ext.grid.RowSelectionModel({singleSelect:true})
		,bbar: ['->']
		,iconCls:'silk-grid'
		,plugins:[new Ext.ux.grid.Excel()]
		,layout:'fit'
		,autoHeight:true
		,autoWidth:true
		,margins:'10 15 5 0'
		,listeners:{
			render: {
				fn: function(grid){
					storeAcuerdo_det.load();
				}
			}
		}
	});

	var colModelContingente = new Ext.grid.ColumnModel({
		columns:[
			{header:'<?= Lang::get('indicador.columns_title.periodo'); ?>', dataIndex:'periodo', align:'left'},
			{header:'<?= Lang::get('acuerdo.partner_title'); ?>', dataIndex:'pais', align:'left'},
			{header:'<?= Lang::get('contingente_det.peso_contingente'); ?>', dataIndex:'quotaWeight' ,'renderer':numberFormat , align:'right'},
			{header:'<?= Lang::get('contingente_det.peso_ejecutado'); ?>', dataIndex:'executedWeight' ,'renderer':numberFormat , align:'right'},
			{header:'% <?= Lang::get('contingente_det.valor_ejecutado'); ?>', dataIndex:'rate' ,'renderer':rateFormat , align:'right'}
		]
	});

	var gridContingente = new Ext.grid.GridPanel({
		border:true
		,monitorResize:true
		,store:storeContingente
		,colModel:colModelContingente
		,stateful:true
		,columnLines:true
		,stripeRows:true
		,viewConfig: {
			forceFit:true
		}
		,enableColumnMove:false
		,id:module+'gridContingente'
		,title: '<?= Lang::get('contingente.table_name'); ?>'
		,sm:new Ext.grid.RowSelectionModel({singleSelect:true})
		,bbar: ['->']
		,iconCls:'silk-grid'
		,plugins:[new Ext.ux.grid.Excel()]
		,layout:'fit'
		,enableColumnMove:false
		,enableColumnResize:false
		,autoHeight:true
		,autoWidth:true
		,margins:'10 15 5 0'
	});
	/*elimiar cualquier estado de la grilla guardado con anterioridad */
	Ext.state.Manager.clear(gridAcuerdo_det.getItemId());
	Ext.state.Manager.clear(gridContingente.getItemId());
	
	var arrYears = <?= json_encode($yearsAvailable); ?>;
	var defaultYear = <?= end($yearsAvailable); ?>;
	
	var arrPeriods = <?= json_encode($periods); ?>;

	/******************************************************************************************************************************************************************************/
	
	var acuerdo_detContainer = new Ext.Panel({
		xtype:'panel'
		,id:module + 'excuteAcuerdo_detContainer'
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
					'<div class="page-head">' +
						'<h4 class="nopadding"><i class="styleColor fa fa-area-chart"></i> <?= $acuerdo_nombre; ?>: <small><?= $acuerdo_det_productos_desc; ?></small></h4>' +
						'<div class="clearfix"></div><?= $htmlProducts; ?>' +
					'</div>' +
				'</div>'
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				style:{padding:'0px'}
				,border:true
				,html: ''
				,autoHeight:true
				,margins:'10 15 5 0'
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
					,listeners:{
						select: {
							fn: function(combo,reg){
								//Ext.getCmp(module + 'comboYear').setDisabled(combo.getValue() == 12);
							}
						}
					}
				},'-',{
					xtype: 'label'
					,text: Ext.ux.lang.reports.selectYear + ': '
				},{
					xtype: 'combo'
					,store: arrYears
					,id: module + 'comboYear'
					,typeAhead: true
					,forceSelection: true
					,triggerAction: 'all'
					,selectOnFocus:true
					,value: defaultYear
					,width: 100
				},'-',{
					text: Ext.ux.lang.buttons.generate
					,iconCls: 'icon-refresh'
					,handler: function () {
						/*var html = Ext.getCmp(module + 'excuteAcuerdo_detContainer').getEl().dom.innerHTML;
						console.log(html);*/
						storeAcuerdo_det.load();
					}
				}]
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[gridContingente]
		},{
			defaults:{anchor:'95%'}
			,items:[{
				style:{padding:'0px'}
				,autoHeight:true
				,border:false
				,margins:'10 15 5 0'
				//,title: Ext.ux.lang.reports.charts
				,html: '<div class="bootstrap-styles">' +
					'<div class="container">' +
						'<div class="row" id="' + module + 'chartsDiv">' +
						'</div>' +
					'</div>' +
				'</div>'
			}]
		}]
		,listeners:{
			beforedestroy: {
				fn: function(p){
					disposeCharts();
				}
			}
		}
	});

	/*Ext.getCmp('tab-' + module).on('deactivate', function(p){
		disposeCharts();
	});

	Ext.getCmp('tab-' + module).on('activate', function(p){
		storeAcuerdo_det.load();
	});*/

	return acuerdo_detContainer;

	/*********************************************** Start functions***********************************************/
	
	function disposeCharts () {
		var chartsDiv  = Ext.get(module + 'chartsDiv');
		Ext.each(chartsId, function(chart) {
			if(FusionCharts(chart)){
				FusionCharts(chart).dispose();
			}
		});

		/*if(FusionCharts(module + 'AreaChartId')){
			FusionCharts(module + 'AreaChartId').dispose();
		}*/
	}
	

	/*********************************************** End functions***********************************************/
})()