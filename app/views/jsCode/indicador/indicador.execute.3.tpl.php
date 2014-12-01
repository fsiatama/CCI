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
	var module = '<?= $module; ?>';
	var panelHeight = Math.floor(Ext.getCmp('tabpanel').getInnerHeight() - 260);

	var storeBalanza = new Ext.data.JsonStore({
		url:'indicador/execute'
		,root:'data'
		,sortInfo:{field:'id',direction:'ASC'}
		,totalProperty:'total'
		,baseParams: {
			id: '<?= $id; ?>'
			,indicador_id: '<?= $indicador_id; ?>'
		}
		,fields:[
			{name:'id', type:'float'},
			{name:'firstPeriod', type:'string'},
			{name:'firstValImpo', type:'float'},
			{name:'firstValExpo', type:'float'},
			{name:'lastPeriod', type:'string'},
			{name:'lastValImpo', type:'float'},
			{name:'lastValExpo', type:'float'},
			{name:'valor_balanza', type:'float'}
		]
	});

	storeBalanza.on('beforeload', function(){
		var period = Ext.getCmp(module + 'comboPeriod').getValue();
		if (!period) {
			return false;
		};
		this.setBaseParam('period', period);
	});
	
	storeBalanza.on('load', function(store){
		FusionCharts.setCurrentRenderer('javascript');
		if(FusionCharts(module + 'ColumnChartId')){
			FusionCharts(module + 'ColumnChartId').dispose();
		}
		var chart = new FusionCharts('<?= COLUMNAS; ?>', module + 'ColumnChartId', '100%', '100%', '0', '1');
		chart.setTransparent(true);
		chart.setJSONData(store.reader.jsonData.columnChartData);
		chart.render(module + 'ColumnChart');

		/*if(FusionCharts(module + 'AreaChartId')){
			FusionCharts(module + 'AreaChartId').dispose();
		}
		var chart = new FusionCharts('<?= AREA; ?>', module + 'AreaChartId', '100%', '100%', '0', '1');
		chart.setTransparent(true);
		chart.setJSONData(store.reader.jsonData.areaChartData);
		chart.render(module + 'AreaChart');*/
		
	});
	var colModelBalanza = new Ext.grid.ColumnModel({
		columns:[
			{header:'<?= Lang::get('indicador.columns_title.periodo'); ?>', dataIndex:'firstPeriod'},
			{header:'<?= Lang::get('indicador.columns_title.valor_impo'); ?>', dataIndex:'firstValImpo' ,'renderer':numberFormat},
			{header:'<?= Lang::get('indicador.columns_title.valor_expo'); ?>', dataIndex:'firstValExpo' ,'renderer':numberFormat},
			{header:'<?= Lang::get('indicador.columns_title.periodo'); ?>', dataIndex:'lastPeriod'},
			{header:'<?= Lang::get('indicador.columns_title.valor_impo'); ?>', dataIndex:'lastValImpo' ,'renderer':numberFormat},
			{header:'<?= Lang::get('indicador.columns_title.valor_expo'); ?>', dataIndex:'lastValExpo' ,'renderer':numberFormat},
			{header:'<?= Lang::get('indicador.columns_title.valor_balanza'); ?>', dataIndex:'valor_balanza' ,'renderer':numberFormat}
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
		//,bbar:new Ext.PagingToolbar({pageSize:1000, store:storeBalanza, displayInfo:true})
		,iconCls:'silk-grid'
		//,plugins:[new Ext.ux.grid.Excel()]
		,layout:'fit'
		,height:300
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
	});
	
	storeBalanza.load();

	return indicadorContainer;

	/*********************************************** Start functions***********************************************/
	function numberFormat (value, decimals) {
		if(!isNaN(parseFloat(value)) && isFinite(value)){
			if(decimals){
				return Ext.util.Format.number(value,'0,0.00');
			}
			else{
				return Ext.util.Format.number(value,'0,0');
			}
		}
		else{
			return value;
		}
	}

	/*********************************************** End functions***********************************************/
})()