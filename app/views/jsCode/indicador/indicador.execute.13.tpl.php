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

	var storeIndicador = new Ext.data.JsonStore({
		url:'indicador/execute'
		,root:'data'
		,id:module+'storeIndicador'
		,autoDestroy:true
		,sortInfo:{field:'valorarancel',direction:'DESC'}
		,totalProperty:'total'
		,baseParams: {
			id: '<?= $id; ?>'
			,indicador_id: '<?= $indicador_id; ?>'
		}
		,fields:[
			{name:'id', type:'float'},
			{name:'id_posicion', type:'string'},
			{name:'posicion', type:'string'},
			{name:'pais', type:'string'},
			{name:'valorarancel', type:'float'},
			{name:'arancel_pagado', type:'float'},
			{name:'participacion', type:'float'}
		]
	});

	storeIndicador.on('beforeload', function(){
		Ext.ux.bodyMask.show();
	});

	storeIndicador.on('load', function(store){

		var el = Ext.Element.get(module + 'weighted_average');
		var average = numberFormat(store.reader.jsonData.average);

		el.update(average);
		Ext.ux.bodyMask.hide();

	});
	var colModelIndicador = new Ext.grid.ColumnModel({
		columns:[
			{header:'<?= Lang::get('indicador.columns_title.posicion'); ?>', dataIndex:'id_posicion', align: 'left'},
			{header:'<?= Lang::get('indicador.columns_title.desc_posicion'); ?>', dataIndex:'posicion', align: 'left'},
			{header:'<?= Lang::get('indicador.columns_title.pais_origen'); ?>', dataIndex:'pais', align: 'left'},
			{header:'<?= Lang::get('indicador.columns_title.arancel_pagado'); ?>', dataIndex:'arancel_pagado' ,'renderer':numberFormat, align:'right'},
			{header:'<?= Lang::get('indicador.columns_title.valorarancel'); ?>', dataIndex:'valorarancel' ,'renderer':numberFormat, align:'right'},
			{header:'<?= Lang::get('indicador.columns_title.participacion'); ?>', dataIndex:'participacion','renderer':rateFormat, align:'right'},
		]
		,defaults: {
			sortable: true
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
					'<div class="col-md-4 col-md-offset-4">' +
						'<label>' + Ext.ux.lang.reports.weighted_average + '</label>' +
						'<strong id="' + module + 'weighted_average">0</strong>' +
					'</div>' +
				'</div>' +
			'</div>'
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