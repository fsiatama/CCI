<?php

$arrDescription  = explode('||', $indicador_campos);

//var_dump($arrDescription);

$htmlDescription  = '<ol class="breadcrumb">';
//$htmlDescription .= '<li class="">' . Lang::get('indicador.reports.ESME') . '</li>';

foreach ($arrDescription as $value) {
	$arr              = explode(':', $value);
	$text             = (empty($arr[1])) ? '' : $arr[1] ;
	$htmlDescription .= '<li class="active">'.$text.'</li>';
}

$htmlDescription .= '</ol>';

$htmlExplanation = '
  <div class="well bs-component">
    <ul class="list-group">
      <li class="list-group-item">
        Por encima de 100%, las exportaciones agropecuarias y agroindustriales están creciendo por encima de las exportaciones totales del país al mundo o a un mercado específico.
      </li>
      <li class="list-group-item">
        Por debajo de 100%, las exportaciones agropecuarias y agroindustriales están creciendo por debajo de las exportaciones totales del país al mundo o a un mercado específico.
      </li>
    </ul>
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
			{name:'valor_expo_agricola', type:'float'},
			{name:'valor_expo', type:'float'},
			{name:'valor_expo_sin_minero', type:'float'},
		]
	});


	storeIndicador.on('beforeload', function(){
		var scale         = Ext.getCmp(module + 'comboScale').getValue();
		var typeIndicator = Ext.getCmp(module + 'comboActivator').getValue();
		if (!scale || !typeIndicator) {
			return false;
		}
		this.setBaseParam('scale', scale);
		this.setBaseParam('typeIndicator', typeIndicator);
		setColumnsTitle();
		Ext.ux.bodyMask.show();
	});

	storeIndicador.on('load', function(store){

		var height = (store.reader.jsonData.total * 33) + 50;
		Ext.getCmp(module+'gridIndicador').setHeight(height);

		var el         = Ext.Element.get(module + 'growthRateExpo');
		var growthRate = rateFormat(store.reader.jsonData.growthRateExpo);
		el.update(growthRate);

		el         = Ext.Element.get(module + 'growthRateAgriculture');
		growthRate = rateFormat(store.reader.jsonData.growthRateAgriculture);
		el.update(growthRate);

		el         = Ext.Element.get(module + 'growthRateExpoWithoutMining');
		growthRate = rateFormat(store.reader.jsonData.growthRateExpoWithoutMining);
		el.update(growthRate);

		el         = Ext.Element.get(module + 'rateVariation');
		growthRate = rateFormat(store.reader.jsonData.rateVariation);
		el.update(growthRate);

		FusionCharts.setCurrentRenderer('javascript');

		disposeCharts();

		var chart = new FusionCharts('<?= COLUMNAS; ?>', module + 'ColumnChartId', '100%', '100%', '0', '1');
		chart.setTransparent(true);
		chart.setJSONData(store.reader.jsonData.columnChartData);
		chart.render(module + 'ColumnChart');

		Ext.ux.bodyMask.hide();

	});
	var colModelIndicador = new Ext.grid.ColumnModel({
		columns:[
			{header:'<?= Lang::get('indicador.columns_title.periodo'); ?>', dataIndex:'periodo', align: 'left'},
			{header:'<?= Lang::get('indicador.columns_title.valor_expo_agricola'); ?>', dataIndex:'valor_expo_agricola' ,'renderer':numberFormat, align: 'right'},
			{header:'<?= Lang::get('indicador.columns_title.valor_expo_sin_minero'); ?>', dataIndex:'valor_expo_sin_minero' ,'renderer':numberFormat, align: 'right'},
			{header:'<?= Lang::get('indicador.columns_title.valor_expo'); ?>', dataIndex:'valor_expo' ,'renderer':numberFormat, align: 'right'},
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
			,html: '<div class="bootstrap-styles">' +
				'<div class="row text-center countTo">' +
					'<div class="col-lg-3 col-md-6">' +
						'<label>' + Ext.ux.lang.reports.growthRateAgriculture + '</label>' +
						'<strong id="' + module + 'growthRateAgriculture">0</strong>' +
					'</div>' +
					'<div class="col-lg-3 col-md-6">' +
						'<label>' + Ext.ux.lang.reports.growthRateExpoWithoutMining + '</label>' +
						'<strong id="' + module + 'growthRateExpoWithoutMining">0</strong>' +
					'</div>' +
					'<div class="col-lg-3 col-md-6">' +
						'<label>' + Ext.ux.lang.reports.growthRateExpo + '</label>' +
						'<strong id="' + module + 'growthRateExpo">0</strong>' +
					'</div>' +
					'<div class="col-lg-3 col-md-6">' +
						'<label><?= Lang::get('indicador.reports.growVariation'); ?></label>' +
						'<strong id="' + module + 'rateVariation">0</strong>' +
					'</div>' +
				'</div>' +
			'</div>'
		},{
			style:{padding:'0px'}
			,html: '<div class="bootstrap-styles">' +
					'<div class="clearfix"></div><?= $htmlExplanation; ?>' +
			'</div>'
		},{
			style:{padding:'0px'}
			,border:true
			,html: ''
			,tbar:[{
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
			},'-',{
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
			},'-',{
				text: Ext.ux.lang.buttons.generate
				,iconCls: 'icon-refresh'
				,handler: function () {
					storeIndicador.load();
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
		if(FusionCharts(module + 'ColumnChartId')){
			FusionCharts(module + 'ColumnChartId').dispose();
		}
	}

	function setColumnsTitle () {
		var typeIndicator = Ext.getCmp(module + 'comboActivator').getValue();
		var titleExpo = ( typeIndicator == '<?= $tipo_indicador_activador; ?>' ) ? '<?= Lang::get('indicador.columns_title.valor_expo_agricola'); ?>' : '<?= Lang::get('indicador.columns_title.peso_expo_agricola'); ?>' ;
		colModelIndicador.setColumnHeader( 1, titleExpo );

		var titleExpo = ( typeIndicator == '<?= $tipo_indicador_activador; ?>' ) ? '<?= Lang::get('indicador.columns_title.valor_expo_sin_minero'); ?>' : '<?= Lang::get('indicador.columns_title.peso_expo_sin_minero'); ?>' ;
		colModelIndicador.setColumnHeader( 2, titleExpo );

		var titleExpo = ( typeIndicator == '<?= $tipo_indicador_activador; ?>' ) ? '<?= Lang::get('indicador.columns_title.valor_expo'); ?>' : '<?= Lang::get('indicador.columns_title.peso_expo'); ?>' ;
		colModelIndicador.setColumnHeader( 3, titleExpo );
	}

	/*********************************************** End functions***********************************************/
})()