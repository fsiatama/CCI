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
			{name:'anio', type:'float'},
			{name:'valor_impo', type:'float'},
			{name:'valor_expo', type:'float'},
			{name:'valor_balanza', type:'float'}
		]
	});
	
	storeBalanza.on('load', function(store){
		FusionCharts.setCurrentRenderer('javascript');
		if(FusionCharts('myChartId_balanza".$pais."')){
			FusionCharts('myChartId_balanza".$pais."').dispose();
		}
		var myChart = new FusionCharts('".AREA."', 'myChartId_balanza".$pais."', '100%', '100%', '0', '1');
		myChart.setTransparent(true);
		myChart.setJSONData(store.reader.jsonData.json_grafico);
		myChart.render('chart_panel_balanza".$pais."');
		
	});
	var colModelBalanza = new Ext.grid.ColumnModel({
		columns:[
			{header:'<?= Lang::get('indicador.columns_title.anio'); ?>', hidden:false, dataIndex:'anio'},
			{header:'<?= Lang::get('indicador.columns_title.valor_impo'); ?>', hidden:false, dataIndex:'valor_impo' ,'renderer':numberFormat},
			{header:'<?= Lang::get('indicador.columns_title.valor_expo'); ?>', hidden:false, dataIndex:'valor_expo' ,'renderer':numberFormat},
			{header:'<?= Lang::get('indicador.columns_title.valor_balanza'); ?>', hidden:false, dataIndex:'valor_balanza' ,'renderer':numberFormat}
		]
		,defaults: {
			sortable:true
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
		,bbar:[]
		,tbar:[]
		,iconCls:'silk-grid'
		,plugins:[new Ext.ux.grid.Excel({position:'top', title:Ext.ux.lang.buttons.save_as})]
		,layout:'fit'
		,height:panelHeight
		,autoWidth:true
		,margins:'10 15 5 0'
	});
	/*elimiar cualquier estado de la grilla guardado con anterioridad */
	Ext.state.Manager.clear(gridBalanza.getItemId());
	
	storeBalanza.load();
	/******************************************************************************************************************************************************************************/
	
	var indicadorContainer = new Ext.Panel({
		xtype:'panel'
		,layout:'border'
		,border:false
		,bodyCssClass:'mainbar'
		,items:[{
			region:'north'
			,border:false
			,html: '<div class="bootstrap-styles">' +
						'<div class="page-head">' +
							'<h2 class="text-center"><?= $tipo_indicador_nombre; ?>: </h2>' +
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
			layout:'column'
			,baseCls:''
			,region:'center'
			,defaults:{columnWidth:1}
			,bodyStyle:'padding:10px;'
			,items:[{
				xtype:'fieldset'
				,title:''
				,collapsible:false
				,layout:'column'
				,defaults:{
					columnWidth:0.5
					,border:false
					,xtype:'panel'
					,style:{padding:'10px'}
					,layout:'fit'
				}
				,items:[{
					defaults:{anchor:'100%'}
					,height:panelHeight+20
					,html:'<div id="chart_panel_balanza"></div>'
					,items:[{
						xtype:'panel'
						,id:'chart_panel_balanza'
						,plain:true
					}]
				},{
					defaults:{anchor:'100%'}
					,items:[gridBalanza]
				}]
			}]
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