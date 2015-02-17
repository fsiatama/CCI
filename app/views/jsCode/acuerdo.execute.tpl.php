<?php
$updateInfo = ( $updateInfo !== false ) ? Lang::get('shared.months.'.$updateInfo['dateTo']->format('m')).' - '.$updateInfo['dateTo']->format('Y') : '' ;
?>

/*<script>*/
(function(){
	Ext.form.Field.prototype.msgTarget = 'side';
	var module = '<?= $module; ?>';
	//var numberRecords = Math.floor((Ext.getCmp('tabpanel').getInnerHeight() - 120)/22);
	var numberRecords = Math.floor((Ext.getCmp('tabpanel').getInnerHeight() - 280)/22);

	var storeAcuerdo = new Ext.data.JsonStore({
		url:'acuerdo/list'
		,root:'data'
		,sortInfo:{field:'acuerdo_id',direction:'ASC'}
		,totalProperty:'total'
		,baseParams:{id:'<?= $id; ?>'}
		,fields:[
			{name:'acuerdo_id', type:'float'},
			{name:'acuerdo_nombre', type:'string'},
			{name:'acuerdo_descripcion', type:'string'},
			{name:'acuerdo_intercambio', type:'string'},
			{name:'acuerdo_intercambio_title', type:'string'},
			{name:'acuerdo_fvigente', type:'string'},
			{name:'acuerdo_fvigente_title', type:'string'},
		]
	});

	storeAcuerdo.load({params:{start:0, limit:numberRecords}});

	gridAcuerdoAction = new Ext.ux.grid.RowActions({
		header: Ext.ux.lang.grid.options
		,keepSelection:true
		,autoWidth:false
		,width:30
		,resizable:false
		,actions:[{
			iconCls:'silk-chart-bar-link'
			,qtip: '<?= Lang::get('acuerdo.analyze_agreement'); ?>'
		}]
		,callbacks:{
			'silk-chart-bar-link':function(grid, record, action, row, col) {
				fnReport(record);
			}
		}
	});

	gridAcuerdoExpander = new Ext.grid.RowExpander({
		tpl: new Ext.Template(
			 '<br><p style="margin:0 0 4px 8px"><b><?= Lang::get('acuerdo.columns_title.acuerdo_descripcion'); ?>:</b> {acuerdo_descripcion}</p>'
		)
	});

	var colModelAcuerdo = new Ext.grid.ColumnModel({
		columns:[
			gridAcuerdoExpander,
			{header:'<?= Lang::get('acuerdo.columns_title.acuerdo_nombre'); ?>', align:'left', hidden:false, dataIndex:'acuerdo_nombre'},
			{header:'<?= Lang::get('acuerdo.columns_title.acuerdo_descripcion'); ?>', align:'left', hidden:false, dataIndex:'acuerdo_descripcion'},
			{header:'<?= Lang::get('acuerdo.columns_title.acuerdo_intercambio'); ?>', align:'left', hidden:false, dataIndex:'acuerdo_intercambio_title'},
			{header:'<?= Lang::get('acuerdo.columns_title.acuerdo_fvigente'); ?>', align:'left', hidden:false, dataIndex:'acuerdo_fvigente_title'},
			gridAcuerdoAction
		]
		,defaults:{
			sortable:true
			,width:100
		}
	});

	var tbAcuerdo = new Ext.Toolbar({
		items:[{
			text: Ext.ux.lang.buttons.add
			,iconCls: 'silk-add'
			,handler: function(){
				if(Ext.getCmp('tab-add_'+module) || Ext.getCmp('tab-detail_'+module) || Ext.getCmp('tab-edit_'+module)){
					Ext.Msg.show({
						title:Ext.ux.lang.messages.warning
						,msg:Ext.ux.lang.error.close_tab
						,buttons: Ext.Msg.OK
						,icon: Ext.Msg.WARNING
					});
				}
				else{
					var data = {
						id:'add_' + module
						,iconCls:'silk-add'
						,titleTab:'<?= $title; ?> - ' + Ext.ux.lang.buttons.add
						,url:'acuerdo/jscode/create'
						,params:{
							id:'<?= $id; ?>'
							,title: '<?= $title; ?> - ' + Ext.ux.lang.buttons.add
							,module: 'add_' + module
							,parent: module
						}
					};
					Ext.getCmp('oeste').addTab(this,this,data);
				}
			}
		}]
	});

	var gridAcuerdo = new Ext.grid.GridPanel({
		border:true
		,monitorResize:true
		,store:storeAcuerdo
		,colModel:colModelAcuerdo
		,stateful:true
		,columnLines:true
		,stripeRows:true
		,viewConfig: {
			forceFit:true
		}
		,id:module+'gridAcuerdo'
		,title:''
		,sm:new Ext.grid.RowSelectionModel({singleSelect:true})
		,iconCls:'silk-grid'
		,layout:'fit'
		,autoHeight:true
		,autoWidth:true
		,margins:'10 15 5 0'
		,plugins:[
			new Ext.ux.grid.Search({
				iconCls:'silk-zoom'
				,searchText: Ext.ux.lang.grid.search
				,selectAllText: Ext.ux.lang.grid.selectAllText
				,id:module+'SearchBox'
				,minChars:2
				,width:200
				,mode:'remote'
				,align:'right'
				,position:top
				,disableIndexes:[]
			})
			,gridAcuerdoAction
			,gridAcuerdoExpander
		]
		,bbar:new Ext.PagingToolbar({pageSize:numberRecords, store:storeAcuerdo, displayInfo:true})
		,tbar:tbAcuerdo
	});

	var panelAcuerdo = new Ext.Panel({
		xtype:'panel'
		,id:module+'panelAcuerdo'
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
							'<?= $title; ?>' +
						'</div>' +
						'<div class="panel-body"><p><?= Lang::get('update_info.table_name') . " " . Lang::get('update_info.columns_title.update_info_to') . ": " . $updateInfo; ?></p></div>' +
					'</div>' +
				'</div>'
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[gridAcuerdo]
		}]
	});

	/*elimiar cualquier estado de la grilla guardado con anterioridad */
	Ext.state.Manager.clear(gridAcuerdo.getItemId());

	return panelAcuerdo;
	/*********************************************** Start functions***********************************************/

	function fnReport(record){
		var key   = record.get('acuerdo_id');
		var title = record.get('acuerdo_nombre');
		var data  = {
			id:'indicator_' + key
			,iconCls:'silk-chart-bar-link'
			,titleTab: title
			,url:'acuerdo_det/jscodeExecute'
			,params:{
				id:'<?= $id; ?>'
				,title: title
				,module: 'indicator_' + key
				,parent: module
				,acuerdo_id: key
			}
		};
		Ext.getCmp('oeste').addTab(this,this,data);
	}

	/*********************************************** End functions***********************************************/
})()