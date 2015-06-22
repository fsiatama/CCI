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
		,sortInfo:{field:'valor_impo',direction:'DESC'}
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
			{name:'valor_impo', type:'float'},
			{name:'participacion', type:'float'}
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
		Ext.ux.bodyMask.show();
	});

	storeIndicador.on('load', function(store){

		var el = Ext.Element.get(module + 'weighted_average');
		var average = numberFormat(store.reader.jsonData.average);

		el.update(average);

		if (store.reader.jsonData.titleValue) {
			var titleValue = store.reader.jsonData.titleValue;
			setColumnsTitle(titleValue);
		}

		Ext.ux.bodyMask.hide();
	});

	var colModelIndicador = new Ext.grid.ColumnModel({
		columns:[
			{header:'<?= Lang::get('indicador.columns_title.pais'); ?>', dataIndex:'pais', align: 'left'},
			{header:'<?= Lang::get('indicador.columns_title.posicion'); ?>', dataIndex:'id_posicion', align: 'left'},
			{header:'<?= Lang::get('indicador.columns_title.desc_posicion'); ?>', dataIndex:'posicion', align: 'left'},
			{header:'<?= Lang::get('indicador.columns_title.valor_impo'); ?>', dataIndex:'valor_impo' ,'renderer':numberFormat, align:'right'},
			//{header:'<?= Lang::get('indicador.columns_title.valorarancel'); ?>', dataIndex:'valorarancel' ,'renderer':numberFormat, align:'right'},
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
		,bbar:new Ext.PagingToolbar({pageSize:30, store:storeIndicador, displayInfo:true})
		,iconCls:'silk-grid'
		,plugins:[new Ext.ux.grid.Excel()]
		,layout:'fit'
		,height:750
		,autoWidth:true
		,margins:'10 15 5 0'
	});
	/*elimiar cualquier estado de la grilla guardado con anterioridad */
	Ext.state.Manager.clear(gridIndicador.getItemId());

	var arrActivator = <?= json_encode($activator); ?>;
	var arrScales    = <?= json_encode($scales); ?>;

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
					'<h4 class="page-header"><i class="styleColor fa fa-area-chart"></i> <?= $tipo_indicador_nombre; ?>: <small><?= $indicador_nombre; ?></small></h4>' +
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
				xtype:'buttongroup',
				items: [{
					text: Ext.ux.lang.buttons.generate
					,iconCls: 'icon-refresh'
					,iconAlign: 'top'
					,handler: function () {
						storeIndicador.load({params:{start:0, limit:30}});
					}
				}]
			}]
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
	});

	storeIndicador.load({params:{start:0, limit:30}});

	return indicadorContainer;

	/*********************************************** Start functions***********************************************/
	
	function setColumnsTitle (titleValue) {
		colModelIndicador.setColumnHeader( 3, titleValue );
	}

	/*********************************************** End functions***********************************************/
})()