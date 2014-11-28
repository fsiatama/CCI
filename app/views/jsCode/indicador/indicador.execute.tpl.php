/*<script>*/
(function(){
	Ext.form.Field.prototype.msgTarget = 'side';
	var module = '<?= $module; ?>';
	var panelHeight = Math.floor(Ext.getCmp('tabpanel').getInnerHeight() - 260);

	var storeBalanza = new Ext.data.JsonStore({
		url:'indicador/execute'
		,root:'data'
		,sortInfo:{field:'anio',direction:'ASC'}
		,totalProperty:'total'
		,baseParams: {
			id: '<?= $id; ?>'
			,indicador_id: '<?= $indicador_id; ?>'
		}
		,fields:[
			{name:'anio', type:'float'},
			{name:'valor_impo', type:'float'},
			{name:'valor_expo', type:'float'},
			{name:'valor_balanza', type:'float'}
		]
	});
	
	storeBalanza.on('load', function(store){
		FusionCharts.setCurrentRenderer('javascript');
		if(FusionCharts(module + 'chartId')){
			FusionCharts(module + 'chartId').dispose();
		}
		var myChart = new FusionCharts('".AREA."', module + 'chartId', '100%', '100%', '0', '1');
		myChart.setTransparent(true);
		myChart.setJSONData(store.reader.jsonData.chartData);
		myChart.render(module + 'chart_panel_balanza');
		
	});
	var colModelBalanza = new Ext.grid.ColumnModel({
		columns:[
			{header:'<?= Lang::get('indicador.columns_title.anio'); ?>', dataIndex:'anio'},
			{header:'<?= Lang::get('indicador.columns_title.valor_impo'); ?>', dataIndex:'valor_impo' ,'renderer':numberFormat},
			{header:'<?= Lang::get('indicador.columns_title.valor_expo'); ?>', dataIndex:'valor_expo' ,'renderer':numberFormat},
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
		,bbar:new Ext.PagingToolbar({pageSize:1000, store:storeBalanza, displayInfo:true})
		,iconCls:'silk-grid'
		,plugins:[new Ext.ux.grid.Excel()]
		,layout:'fit'
		,height:300
		,autoWidth:true
		,margins:'10 15 5 0'
	});
	/*elimiar cualquier estado de la grilla guardado con anterioridad */
	Ext.state.Manager.clear(gridBalanza.getItemId());
	
	storeBalanza.load();
	/******************************************************************************************************************************************************************************/
	
	var indicadorContainer = new Ext.Panel({
		xtype:'panel'
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
			html: '<div class="bootstrap-styles">' +
						'<div class="page-head">' +
							'<h4 class="nopadding"><i class="styleColor fa fa-area-chart"></i> <?= $tipo_indicador_nombre; ?>: <small><?= $indicador_nombre; ?></small></h4>' +
				        	'<div class="clearfix"></div>' +
						'</div>' +
					'</div>'
			,listeners:{
				afterrender:{
					fn:function(){
					}				
				}
			}
		},{
			html: ''
			,tbar:[{text:'prueba'}]
		},{
			height:230
			,html:'<div id="' + module + 'chart_panel_balanza"></div>'
			,items:[{
				xtype:'panel'
				,id: module + 'chart_panel_balanza'
				,plain:true
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[gridBalanza]
		}]
	});
	
	return indicadorContainer;

	/*********************************************** Start functions***********************************************/
	function numberFormat(value, decimals){
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