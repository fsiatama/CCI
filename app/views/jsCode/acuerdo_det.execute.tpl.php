<?php
$partner        = (empty($mercado_nombre)) ? $pais : $mercado_nombre ;
$htmlCountryies = '';
foreach ($countryData as $key => $row) {
	$htmlCountryies .= '<li class="list-group-item"><span class="badge">'.($key + 1).'</span>'.$row['pais'].'</li>';
}
$acuerdo_descripcion = Inflector::compress($acuerdo_descripcion);

$updateInfo = ( $updateInfo !== false ) ? Lang::get('shared.months.'.$updateInfo['dateTo']->format('m')).' - '.$updateInfo['dateTo']->format('Y') : '' ;

?>

/*<script>*/
(function(){
	Ext.form.Field.prototype.msgTarget = 'side';
	Ext.ns('Acuerdo');
	var module = '<?= $module; ?>';

	var storeAcuerdo_det = new Ext.data.JsonStore({
		url:'acuerdo_det/execute'
		,root:'data'
		,id:module+'storeAcuerdo_det'
		,sortInfo:{field:'acuerdo_det_id',direction:'ASC'}
		,totalProperty:'total'
		,baseParams: {
			id: '<?= $id; ?>'
			,module: module
			,acuerdo_id: '<?= $acuerdo_id; ?>'
		}
		,id:module+'storeAcuerdo_det'
		,fields:[
			{name:'contingente_id', type:'float'},
			{name:'desgravacion_id', type:'float'},
			{name:'acuerdo_id', type:'float'},
			{name:'acuerdo_det_id', type:'float'},
			{name:'acuerdo_det_productos', type:'string'},
			{name:'acuerdo_det_productos_desc', type:'string'},
			{name:'contingente_msalvaguardia', type:'string'},
			{name:'pais', type:'string'},
			{name:'contingente_det_peso_neto', type:'float'},
			{name:'salvaguardia_peso_neto', type:'float'},
			{name:'peso_intra', type:'float'},
			{name:'ejecutado_intra', type:'float'},
			{name:'peso_extra', type:'float'},
			{name:'ejecutado_extra', type:'float'},
			{name:'estado_ctg', type:'string'},
			{name:'estado_ctg_tt', type:'string'},
			{name:'estado_svg', type:'string'},
			{name:'estado_svg_tt', type:'string'},

		]
	});

	storeAcuerdo_det.on('beforeload', function(){
		Ext.ux.bodyMask.show();
	});
	
	storeAcuerdo_det.on('load', function(store){
		Ext.ux.bodyMask.hide();
	});

	gridAcuerdo_detAction = new Ext.ux.grid.RowActions({
		header: Ext.ux.lang.grid.options
		,keepSelection:true
		,autoWidth:false
		,width:30
		,resizable:false
		,actions:[{
			iconCls:'silk-lorry'
			,qtip: '<?= Lang::get('contingente.analyze_quota_customs'); ?>'
			,align:'left'
		},{
			iconCls:'silk-anchor'
			,qtip: '<?= Lang::get('contingente.analyze_quota_bol'); ?>'
			,align:'left'
		}]
		,callbacks:{
			'silk-lorry':function(grid, record, action, row, col) {
				fnOpenDetail(record, 'customs');
			}
			,'silk-anchor':function(grid, record, action, row, col) {
				fnOpenDetail(record, 'bol');
			}
		}
	});

	var columnTplEstadoCtg = new Ext.XTemplate(
		'<div class="ux-row-action">' +
			'<tpl for=".">' +
				'<div class="ux-row-action-item {estado_ctg} ux-row-action-text" qtip="{estado_ctg_tt}">' +
					'<span qtip="{estado_ctg_tt}">{estado_ctg_tt}</span>' +
				'</div>' +
			'</tpl>' +
		'</div>'
	);
	var columnTplEstadoSvg = new Ext.XTemplate(
		'<div class="ux-row-action">',
			'<tpl for=".">',
				'<tpl if="contingente_msalvaguardia != \'0\'">',
					'<div class="ux-row-action-item {estado_svg} ux-row-action-text" qtip="{estado_svg_tt}">',
						'<span qtip="{estado_svg_tt}">{estado_svg_tt}</span>',
					'</div>',
				'</tpl>',
			'</tpl>',
		'</div>'
	);

	gridAcuerdo_detExpander = new Ext.grid.RowExpander({
		tpl: new Ext.Template(
			 '<p style="margin:0 0 4px 8px"><b><?= Lang::get('acuerdo_det.columns_title.acuerdo_det_productos'); ?>:</b> <br>{acuerdo_det_productos}</p>'
		)
	});

	var colModelAcuerdo_det = new Ext.grid.ColumnModel({
		defaultSortable: true
		,columns:[
			gridAcuerdo_detExpander,
			{header:'<?= Lang::get('acuerdo_det.columns_title.acuerdo_det_productos_desc'); ?>', dataIndex:'acuerdo_det_productos_desc', align:'left'},
			{header:'<?= Lang::get('acuerdo.partner_title'); ?>', dataIndex:'pais', align:'left'},
			{header:'<?= Lang::get('contingente_det.peso_contingente'); ?>', dataIndex:'contingente_det_peso_neto' ,'renderer':numberFormat , align:'right', hidden:true},
			{header:'<?= Lang::get('contingente_det.peso_salvaguardia'); ?>', dataIndex:'salvaguardia_peso_neto' ,'renderer':numberFormat , align:'right', hidden:true},
			{header:'<?= Lang::get('contingente_det.peso_ejecutado_intra'); ?>', dataIndex:'peso_intra' ,'renderer':numberFormat , align:'right'},
			{header:'% <?= Lang::get('contingente_det.valor_ejecutado_intra'); ?>', dataIndex:'ejecutado_intra' ,'renderer':rateFormat , align:'right'},
			{header:'<?= Lang::get('contingente_det.estado_contingente'); ?>', dataIndex:'estado_ctg_tt', xtype: 'templatecolumn', tpl: columnTplEstadoCtg },
			{header:'<?= Lang::get('contingente_det.estado_salvaguardia'); ?>', dataIndex:'estado_svg_tt', xtype: 'templatecolumn', tpl: columnTplEstadoSvg },
			gridAcuerdo_detAction
		]
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
			,scrollOffset:2
		}
		,enableColumnMove:false
		,id:module+'gridAcuerdo_det'
		,title: '<?= Lang::get('acuerdo_det.table_name') . " - " . Lang::get('update_info.table_name') . " " . Lang::get('update_info.columns_title.update_info_to') . " " . $updateInfo; ?>'
		,sm:new Ext.grid.RowSelectionModel({singleSelect:true})
		,bbar:new Ext.PagingToolbar({pageSize:10000, store:storeAcuerdo_det, displayInfo:true})
		,iconCls:'silk-grid'
		,layout:'fit'
		,autoHeight:true
		,autoWidth:true
		,margins:'10 15 5 0'
		,plugins:[gridAcuerdo_detAction, new Ext.ux.grid.Excel(), gridAcuerdo_detExpander]
		,listeners:{
			render: {
				fn: function(grid){
					storeAcuerdo_det.load();
				}
			}
		}
	});

	var panelAcuerdo_det = new Ext.Panel({
		xtype:'panel'
		,id:module+'panelAcuerdo_det'
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
							'<?= $acuerdo_nombre; ?>' +
						'</div>' +
						'<div class="panel-body"><p><?= ($acuerdo_descripcion); ?></p><p><?= $updateInfo; ?></p></div>' +
					'</div>' +
					'<div class="row">' +
						'<div class="col-md-4">' +
							'<div class="well well-sm nomargin">' +
						    	'<strong class="text-info margin-bottom-5"><span class="label label-info pull-right"><i class="fa fa-2x fa-calendar"></i></span> <?= Lang::get('acuerdo.columns_title.acuerdo_fvigente'); ?> </strong>' +
						    	'<p><?= $acuerdo_fvigente_title; ?></p>' +
							'</div>' +
						'</div>' +
						'<div class="col-md-4">' +
							'<div class="well well-sm nomargin">' +
						    	'<strong class="text-info margin-bottom-5"><span class="label label-info pull-right"><i class="fa fa-2x fa-money"></i></span> <?= Lang::get('acuerdo.columns_title.acuerdo_intercambio'); ?> </strong>' +
						    	'<p><?= $acuerdo_intercambio_title; ?></p>' +
							'</div>' +
						'</div>' +
						'<div class="col-md-4">' +
							'<div class="well well-sm nomargin">' +
						    	'<strong class="text-info margin-bottom-5"><span class="label label-info pull-right"><i class="fa fa-2x fa-globe"></i></span> <?= Lang::get('acuerdo.partner_title'); ?> </strong>' +
						    	'<p><?= $partner; ?></p>' +
							'</div>' +
						'</div>' +
					'</div>' +
				'</div>'
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[gridAcuerdo_det]
		}]
	});

	/*elimiar cualquier estado del Tree guardado con anterioridad */
	Ext.state.Manager.clear(gridAcuerdo_det.getItemId());

	return panelAcuerdo_det;
	/*********************************************** Start functions***********************************************/

	function fnOpenDetail (record, source) {
		var contingente_id  = record.get('contingente_id');
		var desgravacion_id = record.get('desgravacion_id');
		var acuerdo_id      = record.get('acuerdo_id');
		var acuerdo_det_id  = record.get('acuerdo_det_id');

		if(Ext.getCmp('tab-detail_'+module)){
			Ext.Msg.show({
				 title:Ext.ux.lang.messages.warning
				,msg:Ext.ux.lang.error.close_tab
				,buttons: Ext.Msg.OK
				,icon: Ext.Msg.WARNING
			});
		}
		else{
			var data = {
				id:'detail_' + module
				,iconCls:'silk-graph-line'
				,titleTab:'<?= $title; ?> - ' + Ext.ux.lang.buttons.detail
				,url: 'contingente/jscodeExecute'
				,params:{
					id:'<?= $id; ?>'
					,title: '<?= $title; ?> - ' + Ext.ux.lang.buttons.detail
					,module: 'detail_' + module
					,parent: module
					,contingente_id: contingente_id
					,desgravacion_id: desgravacion_id
					,acuerdo_id: acuerdo_id
					,acuerdo_det_id: acuerdo_det_id
					,source: source
				}
			};
			Ext.getCmp('oeste').addTab(this,this,data);
		}
	}

	/*********************************************** End functions***********************************************/
})()