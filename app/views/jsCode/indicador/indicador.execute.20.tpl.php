<?php

$arrDescription  = explode('||', $indicador_campos);

$htmlDescription = '<ol class="breadcrumb">';

foreach ($arrDescription as $value) {
	$arr = explode(':', $value);
	$text = (empty($arr[1])) ? '' : $arr[1] ;
	$htmlDescription .= '<li class="active">'.$text.'</li>';
}

$htmlDescription .= '</ol>';

$htmlExplanation = '
  <div class="well bs-component">
    <p>
    	Por encima de 100%, las exportaciones agropecuarias y agroindustriales no tradicionales están creciendo por encima del total de las exportaciones agropecuarias y agroindustriales del país al mundo o a un mercado específico.
	</p>
  </div>
';
$htmlExplanation = Inflector::compress($htmlExplanation);

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
			{name:'valor_expo_no_tradi', type:'float'},
			{name:'valor_expo_agricola', type:'float'},
		]
	});

	storeIndicador.on('beforeload', function(){
		var scale         = Ext.getCmp(module + 'comboScale').getValue();
		var typeIndicator = Ext.getCmp(module + 'comboActivator').getValue();
		var chartType     = Ext.getCmp(module + 'comboCharts').getValue();
		if (!scale || !typeIndicator || !chartType) {
			return false;
		};

		this.setBaseParam('scale', scale);
		this.setBaseParam('typeIndicator', typeIndicator);
		this.setBaseParam('chartType', chartType);

		setColumnsTitle();
		Ext.ux.bodyMask.show();
	});

	storeIndicador.on('load', function(store){

		var height = (store.reader.jsonData.total * 33) + 50;
		Ext.getCmp(module+'gridIndicador').setHeight(height);

		var el         = Ext.Element.get(module + 'growthRateAgricultureNonTraditional');
		var growthRate = rateFormat(store.reader.jsonData.growthRateAgricultureNonTraditional);
		el.update(growthRate);

		el         = Ext.Element.get(module + 'growthRateAgriculture');
		growthRate = rateFormat(store.reader.jsonData.growthRateAgriculture);
		el.update(growthRate);

		el         = Ext.Element.get(module + 'rateVariation');
		growthRate = rateFormat(store.reader.jsonData.rateVariation);
		el.update(growthRate);

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
			{header:'<?= Lang::get('indicador.columns_title.valor_expo_no_tradi'); ?>', dataIndex:'valor_expo_no_tradi' ,'renderer':numberFormat, align: 'right'},
			{header:'<?= Lang::get('indicador.columns_title.valor_expo_agricola'); ?>', dataIndex:'valor_expo_agricola' ,'renderer':numberFormat, align: 'right'},
		]
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
		,height:panelHeight
		,autoWidth:true
		,margins:'10 15 5 0'
	});
	/*elimiar cualquier estado de la grilla guardado con anterioridad */
	Ext.state.Manager.clear(gridIndicador.getItemId());

	var arrScales    = <?= json_encode($scales); ?>;
	var arrActivator = <?= json_encode($activator); ?>;
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
			,html: '<div class="bootstrap-styles">' +
				'<div class="row text-center countTo">' +
					'<div class="col-md-4">' +
						'<label>' + Ext.ux.lang.reports.growthRateAgricultureNonTraditional + '</label>' +
						'<strong id="' + module + 'growthRateAgricultureNonTraditional">0</strong>' +
					'</div>' +
					'<div class="col-md-4">' +
						'<label>' + Ext.ux.lang.reports.growthRateAgriculture + '</label>' +
						'<strong id="' + module + 'growthRateAgriculture">0</strong>' +
					'</div>' +
					'<div class="col-md-4">' +
						'<label><?= Lang::get('indicador.reports.growVariation'); ?></label>' +
						'<strong id="' + module + 'rateVariation">0</strong>' +
					'</div>' +
				'</div>' +
			'</div>'
		},{
			style:{padding:'10px'}
			,border:true
			,html: ''
			,tbar:[{
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
					,text: '<?= Lang::get('tipo_indicador.columns_title.tipo_indicador_activador')?>: '
				},{
					xtype: 'combo'
					,store: arrActivator
					,id: module + 'comboActivator'
					,typeAhead: true
					,forceSelection: true
					,triggerAction: 'all'
					,selectOnFocus:true
					,value: '<?= $tipo_indicador_activador; ?>'
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
			style:{padding:'0px'}
			,html: '<div class="bootstrap-styles">' +
					'<div class="clearfix"></div><?= $htmlExplanation; ?>' +
			'</div>'
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

	function setColumnsTitle () {
		var typeIndicator = Ext.getCmp(module + 'comboActivator').getValue();
		var titleExpoNT   = ( typeIndicator == '<?= $tipo_indicador_activador; ?>' ) ? '<?= Lang::get('indicador.columns_title.valor_expo_no_tradi'); ?>' : '<?= Lang::get('indicador.columns_title.peso_expo_no_tradi'); ?>' ;
		var titleExpoAgro = ( typeIndicator == '<?= $tipo_indicador_activador; ?>' ) ? '<?= Lang::get('indicador.columns_title.valor_expo_agricola'); ?>' : '<?= Lang::get('indicador.columns_title.peso_expo_agricola'); ?>' ;
		colModelIndicador.setColumnHeader( 1, titleExpoNT );
		colModelIndicador.setColumnHeader( 2, titleExpoAgro );
	}

	/*********************************************** End functions***********************************************/
})()