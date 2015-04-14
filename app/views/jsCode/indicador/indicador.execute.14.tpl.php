<?php

$arrDescription  = explode('||', $indicador_campos);

$htmlDescription = '<ol class="breadcrumb">';

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
        Por encima de 100%, el producto colombiano está ganando participación en el mercado.
      </li>
      <li class="list-group-item">
        Por debajo de 100% está perdiendo participación.
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
			{name:'valor_impo_colombia', type:'float'},
			{name:'valor_impo_world', type:'float'},
		]
	});

	storeIndicador.on('beforeload', function(){
		var scale  = Ext.getCmp(module + 'comboScale').getValue();
		if (!scale) {
			return false;
		};
		this.setBaseParam('scale', scale);
		Ext.ux.bodyMask.show();
	});

	storeIndicador.on('load', function(store){

		var height = (store.reader.jsonData.total * 33) + 50;
		Ext.getCmp(module+'gridIndicador').setHeight(height);

		var el         = Ext.Element.get(module + 'growthRateColombia');
		var growthRate = rateFormat(store.reader.jsonData.growthRateColombia);
		el.update(growthRate);

		el         = Ext.Element.get(module + 'growthRateWorld');
		growthRate = rateFormat(store.reader.jsonData.growthRateWorld);
		el.update(growthRate);

		/*el         = Ext.Element.get(module + 'rateVariation');
		growthRate = rateFormat(store.reader.jsonData.rateVariation);
		el.update(growthRate);*/

		Ext.ux.bodyMask.hide();

	});
	var colModelIndicador = new Ext.grid.ColumnModel({
		columns:[
			{header:'<?= Lang::get('indicador.columns_title.periodo'); ?>', dataIndex:'periodo', align:'left'},
			{header:'<?= Lang::get('indicador.comtrade_columns_title.valor_impo_desde_col'); ?>', dataIndex:'valor_impo_colombia' ,'renderer':numberFormat, align:'right'},
			{header:'<?= Lang::get('indicador.comtrade_columns_title.valor_impo'); ?>', dataIndex:'valor_impo_world' ,'renderer':numberFormat, align:'right'},
		]
		,defaults: {
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
		,height:panelHeight
		,autoWidth:true
		,margins:'10 15 5 0'
	});
	/*elimiar cualquier estado de la grilla guardado con anterioridad */
	Ext.state.Manager.clear(gridIndicador.getItemId());

	var arrPeriods = <?= json_encode($periods); ?>;
	var arrScales = <?= json_encode($scales); ?>;

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
					'<div class="col-md-4 col-md-offset-2">' +
						'<label>' + Ext.ux.lang.reports.growthRateColombia + '</label>' +
						'<strong id="' + module + 'growthRateColombia">0</strong>' +
					'</div>' +
					'<div class="col-md-4">' +
						'<label>' + Ext.ux.lang.reports.growthRateWorld + '</label>' +
						'<strong id="' + module + 'growthRateWorld">0</strong>' +
					'</div>' +
					/*'<div class="col-md-4">' +
						'<label><?= Lang::get('indicador.reports.growVariation'); ?></label>' +
						'<strong id="' + module + 'rateVariation">0</strong>' +
					'</div>' +*/
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
				text: Ext.ux.lang.buttons.generate
				,iconCls: 'icon-refresh'
				,handler: function () {
					storeIndicador.load();
				}
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

	storeIndicador.load();

	return indicadorContainer;

	/*********************************************** Start functions***********************************************/
	function disposeCharts () {

	}

	/*********************************************** End functions***********************************************/
})()