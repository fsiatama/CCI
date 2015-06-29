<?php


$htmlProducts = '<ol class="list-group">';

foreach ($productsData as $row) {
	$htmlProducts .= '<li class="list-group-item"><span class="badge">'.$row['id_posicion'].'</span>'.$row['posicion'].'</li>';
}

$htmlProducts .= '</ol>';

$updateInfo = ( $updateInfo !== false ) ? Lang::get('shared.months.'.$updateInfo['dateTo']->format('m')).' - '.$updateInfo['dateTo']->format('Y') : '' ;


$htmlContingentStatus = '
	<div class="bootstrap-styles">
		<div class="row text-center countTo">
			<div class="col-md-4">
				<label>' . Lang::get('contingente_det.peso_contingente') . '</label>
				<strong id="\' + module + \'quotaWeight">0</strong>
			</div>
			<div class="col-md-4">
				<label>' . Lang::get('desgravacion_det.columns_title.desgravacion_det_tasa_intra') . '</label>
				<strong id="\' + module + \'tariffRate">0</strong>
			</div>
			<div class="col-md-4">
				<label>' . Lang::get('contingente_det.peso_salvaguardia') . '</label>
				<strong id="\' + module + \'safeguardWeight">0</strong>
			</div>
		</div>
	</div>
';


$htmlContingentStatus = Inflector::compress($htmlContingentStatus);

?>

/*<script>*/
(function(){
	Ext.form.Field.prototype.msgTarget = 'side';
	var module = '<?= $module.'_'.$acuerdo_id; ?>';
	var panelHeight = Math.floor(Ext.getCmp('tabpanel').getInnerHeight() - 260);

	var storeContingente = new Ext.data.JsonStore({
		url:'contingente/execute'
		,root:'data'
		,id:module+'storeContingente'
		,sortInfo:{field:'id',direction:'ASC'}
		,totalProperty:'total'
		,baseParams: {
			id: '<?= $id; ?>'
			,contingente_id: '<?= $contingente_id; ?>'
			,desgravacion_id: '<?= $desgravacion_id; ?>'
			,acuerdo_det_id: '<?= $acuerdo_det_id; ?>'
			,acuerdo_id: '<?= $acuerdo_id; ?>'
			,source:'customs'
		}
		,fields:[
			{name:'id', type:'float'},
			{name:'periodo', type:'string'},
			{name:'executedWeightIntra', type:'float'},
			{name:'executedRateIntra', type:'float'},
			{name:'executedWeightExtra', type:'float'},
			{name:'executedRateExtra', type:'float'},
			{name:'cumulativeWeight', type:'float'},
			{name:'cumulativeRate', type:'float'},
		]
	});

	storeContingente.on('beforeload', function(){
		var year   = Ext.getCmp(module + 'comboYear').getValue();
		var period = Ext.getCmp(module + 'comboPeriod').getValue();
		if (!year || !period) {
			return false;
		};
		this.setBaseParam('year', year);
		this.setBaseParam('period', period);
		Ext.ux.bodyMask.show();
	});
	
	storeContingente.on('load', function(store){
		var el = Ext.Element.get(module + 'quotaWeight');
		var average = numberFormat(store.reader.jsonData.quotaWeight);
		el.update(average);

		el = Ext.Element.get(module + 'safeguardWeight');
		var parent = el.findParent('div',5, true);
		parent.hide();
		if (store.reader.jsonData.safeguardWeight > 0) {
			average = numberFormat(store.reader.jsonData.safeguardWeight);
			el.update(average);
			parent.show();
		}
		
		el = Ext.Element.get(module + 'tariffRate');
		var parent = el.findParent('div',5, true);
		parent.hide();
		if (store.reader.jsonData.tariffRate !== false) {
			tariffRate = numberFormat(store.reader.jsonData.tariffRate);
			el.update(tariffRate);
			parent.show();
		}

		FusionCharts.setCurrentRenderer('javascript');
		disposeCharts();
		Ext.getCmp(module + 'gaugeChart2').hide();
		if (typeof(store.reader.jsonData.gaugeChartData) === 'object') {
			var chart = new FusionCharts('hlineargauge', module + 'AreaChartId', '100%', '100%', '0', '1');
			chart.setTransparent(true);
			chart.setJSONData(store.reader.jsonData.gaugeChartData);
			chart.render(module + 'AreaChart');
		}
		if (typeof(store.reader.jsonData.safeguardGaugeChartData) === 'object') {
			var chart = new FusionCharts('hlineargauge', module + 'SafeguardChartId', '100%', '100%', '0', '1');
			chart.setTransparent(true);
			chart.setJSONData(store.reader.jsonData.safeguardGaugeChartData);
			Ext.getCmp(module + 'gaugeChart2').show();
			chart.render(module + 'SafeguardChart');
		}
		Ext.ux.bodyMask.hide();
	});

	var colModelContingente = new Ext.grid.ColumnModel({
		defaultSortable: true
		,columns:[
			{header:'<?= Lang::get('indicador.columns_title.periodo'); ?>', dataIndex:'periodo', align:'left'},
			{header:'<?= Lang::get('contingente_det.peso_ejecutado_intra'); ?>', dataIndex:'executedWeightIntra' ,'renderer':numberFormat , align:'right'},
			{header:'<?= Lang::get('contingente_det.valor_ejecutado_intra'); ?>', dataIndex:'executedRateIntra' ,'renderer':rateFormat , align:'right'},
			{header:'<?= Lang::get('contingente_det.peso_ejecutado_extra'); ?>', dataIndex:'executedWeightExtra' ,'renderer':numberFormat , align:'right'},
			{header:'<?= Lang::get('contingente_det.valor_ejecutado_extra'); ?>', dataIndex:'executedRateExtra' ,'renderer':rateFormat , align:'right'},
			{header:'<?= Lang::get('contingente_det.peso_acumulado'); ?>', dataIndex:'cumulativeWeight' ,'renderer':numberFormat , align:'right', hidden: true},
			{header:'<?= Lang::get('contingente_det.valor_acumulado'); ?>', dataIndex:'cumulativeRate' ,'renderer':rateFormat , align:'right', hidden: true},
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
		,listeners:{
			render: {
				fn: function(grid){
					storeContingente.load();
				}
			}
		}
	});
	/*elimiar cualquier estado de la grilla guardado con anterioridad */
	Ext.state.Manager.clear(gridContingente.getItemId());
	
	var arrYears = <?= json_encode($yearsAvailable); ?>;
	var defaultYear = <?= end($yearsAvailable); ?>;
	
	var arrPeriods = <?= json_encode($periods); ?>;

	/******************************************************************************************************************************************************************************/
	
	var contingenteContainer = new Ext.Panel({
		xtype:'panel'
		,id:module + 'excuteContingenteContainer'
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
							'<?= Lang::get('contingente.table_name'); ?>' +
						'</div>' +
						'<div class="panel-body">' +
							'<dl class="dl-horizontal">' +
								'<dt><?= Lang::get('acuerdo.table_name'); ?></dt>' +
								'<dd><?= $acuerdo_nombre; ?></dd>' +
								'<dt><?= Lang::get('acuerdo.partner_title'); ?></dt>' +
								'<dd><?= $pais; ?></dd>' +
								'<dt><?= Lang::get('acuerdo_det.table_name'); ?></dt>' +
								'<dd><?= $acuerdo_det_productos_desc; ?></dd>' +
								'<dt><?= Lang::get('update_info.table_name') . " " . Lang::get('update_info.columns_title.update_info_to') . ":"; ?></dt>' +
								'<dd><?= $updateInfo; ?></dd>' +
								'<dt><?= Lang::get('indicador.reports.source') . ":"; ?></dt>' +
								'<dd><?= Lang::get('indicador.reports.customs'); ?></dd>' +
							'</dl>' +
						'</div>' +
					'</div>' +
				'</div>'
			}]
		},{
			height:140
			,html:'<div id="' + module + 'AreaChart"></div>'
			,id: module + 'gaugeChart1'
			,items:[{
				xtype:'panel'
				,id: module + 'AreaChart'
				,plain:true
			}]
		},{
			height:140
			,id: module + 'gaugeChart2'
			,html:'<div id="' + module + 'SafeguardChart"></div>'
			,hidden:true
			,items:[{
				xtype:'panel'
				,id: module + 'SafeguardChart'
				,plain:true
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
						storeContingente.load();
					}
				}]
			}]
		},{
			style:{padding:'0px'}
			,html: '<?= $htmlContingentStatus; ?>'
		},{
			defaults:{anchor:'100%'}
			,items:[gridContingente]
		}]
		,listeners:{
			beforedestroy: {
				fn: function(p){
					disposeCharts();
				}
			}
		}
	});

	Ext.getCmp('tab-<?= $module; ?>').on('deactivate', function(p){
		disposeCharts();
	});

	Ext.getCmp('tab-<?= $module; ?>').on('activate', function(p){
		storeContingente.load();
	});

	return contingenteContainer;

	/*********************************************** Start functions***********************************************/
	
	function disposeCharts () {

		if(FusionCharts(module + 'AreaChartId')){
			FusionCharts(module + 'AreaChartId').dispose();
		}
		if(FusionCharts(module + 'SafeguardChartId')){
			FusionCharts(module + 'SafeguardChartId').dispose();
		}
	}
	

	/*********************************************** End functions***********************************************/
})()