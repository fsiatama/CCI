<?php
$acuerdo_descripcion = Inflector::compress($acuerdo_descripcion);
?>
/*<script>*/
(function(){
	Ext.form.Field.prototype.msgTarget = 'side';
	var module = '<?= $module."_".$acuerdo_id; ?>';
	var numberRecords = Math.floor((Ext.getCmp('tabpanel').getInnerHeight() - 120)/22);
	
	var storeAcuerdo_det = new Ext.data.JsonStore({
		url:'acuerdo_det/list'
		,root:'data'
		,sortInfo:{field:'acuerdo_det_acuerdo_id',direction:'ASC'}
		,totalProperty:'total'
		,baseParams:{
			id:'<?= $id; ?>'
			,acuerdo_det_acuerdo_id:'<?= $acuerdo_id; ?>'
		}
		,fields:[
			{name:'acuerdo_det_id', type:'float'},
			{name:'acuerdo_det_arancel_base', type:'float'},
			{name:'acuerdo_det_productos', type:'string'},
			{name:'acuerdo_det_productos_desc', type:'string'},
			{name:'acuerdo_det_administracion', type:'string'},
			{name:'acuerdo_det_administrador', type:'string'},
			{name:'acuerdo_det_nperiodos', type:'float'},
			{name:'acuerdo_det_acuerdo_id', type:'float'}
		]
	});

	storeAcuerdo_det.load({params:{start:0, limit:numberRecords}});

	gridAcuerdo_detAction = new Ext.ux.grid.RowActions({
		header: Ext.ux.lang.grid.options
		,keepSelection:true
		,autoWidth:false
		,actions:[{
			iconCls:'silk-delete'
			,tooltip: Ext.ux.lang.buttons.delete_tt
		},{
			iconCls: 'silk-page-edit'
			,tooltip: Ext.ux.lang.buttons.modify_tt
		},{
			iconCls: 'silk-basket'
			,tooltip: '<?= Lang::get('contingente.table_name'); ?>'
		}]
		,callbacks:{
			'silk-delete':function(grid, record, action, row, col) {
				fnDeleteItem(record);
			}
			,'silk-page-edit':function(grid, record, action, row, col) {
				fnEditItm(record);
			}
			,'silk-basket':function(grid, record, action, row, col) {
				fnOpenQuote(record);
			}
		}
	});
	gridAcuerdo_detExpander = new Ext.grid.RowExpander({
		tpl: new Ext.Template(
			 '<br><p style="margin:0 0 4px 8px"><b><?= Lang::get('acuerdo_det.columns_title.acuerdo_det_administracion'); ?>:</b> {acuerdo_det_administracion}</p>'
			 ,'<p style="margin:0 0 4px 8px"><b><?= Lang::get('acuerdo_det.columns_title.acuerdo_det_administrador'); ?>:</b> {acuerdo_det_administrador}</p>'
		)
	});
	var cmAcuerdo_det = new Ext.grid.ColumnModel({
		columns:[
			gridAcuerdo_detExpander,
			{header:'<?= Lang::get('acuerdo_det.columns_title.acuerdo_det_arancel_base'); ?>', align:'right', hidden:false, dataIndex:'acuerdo_det_arancel_base','renderer':rateFormat},
			{header:'<?= Lang::get('acuerdo_det.columns_title.acuerdo_det_productos'); ?>', align:'left', hidden:false, dataIndex:'acuerdo_det_productos'},
			{header:'<?= Lang::get('acuerdo_det.columns_title.acuerdo_det_productos_desc'); ?>', align:'left', hidden:false, dataIndex:'acuerdo_det_productos_desc'},
			{header:'<?= Lang::get('acuerdo_det.columns_title.acuerdo_det_nperiodos'); ?>', align:'right', hidden:false, dataIndex:'acuerdo_det_nperiodos'},
			gridAcuerdo_detAction
		]
		,defaults:{
			sortable:true
			,width:100
		}
	});

	var tbAcuerdo_det = new Ext.Toolbar({
		items:[{
			text: Ext.ux.lang.buttons.add
			,iconCls: 'silk-add'
			,handler: function(){
				if(Ext.getCmp('tab-edit_'+module)){
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
						,url:'acuerdo_det/jscode/create'
						,params:{
							id:'<?= $id; ?>'
							,title: '<?= $title; ?> - ' + Ext.ux.lang.buttons.add
							,module: 'add_' + module
							,parent: module
							,acuerdo_det_acuerdo_id:'<?= $acuerdo_id; ?>'
						}
					};
					Ext.getCmp('oeste').addTab(this,this,data);
				}
			}
		}]
	});

	var gridAcuerdo_det = new Ext.grid.GridPanel({
		store:storeAcuerdo_det
		,id:module + 'gridAcuerdo_det'
		,colModel:cmAcuerdo_det
		,viewConfig: {
			forceFit: true
			,scrollOffset:2
		}
		,sm: new Ext.grid.RowSelectionModel({
			singleSelect: true
		})
		//,bbar:new Ext.PagingToolbar({pageSize:numberRecords, store:storeAcuerdo_det, displayInfo:true})
		,enableColumnMove:false
		,enableColumnResize:false
		,tbar:tbAcuerdo_det
		,loadMask:true
		,border:false
		,frame: false
		,baseCls: 'x-panel-mc'
		,buttonAlign:'center'
		,title:''
		,iconCls:'icon-grid'
		,autoHeight:true
		,autoWidth:true
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
			,gridAcuerdo_detAction
			,gridAcuerdo_detExpander
		]
	});

	/*elimiar cualquier estado de la grilla guardado con anterioridad */
	Ext.state.Manager.clear(gridAcuerdo_det.getItemId());

	var panelAcuerdo_det = new Ext.Panel({
		xtype:'panel'
		,id:module+'panelAcuerdo_det'
		,layout:'border'
		,border:false
		,bodyCssClass:'x-plain'
		,bodyStyle:	'padding:15px;position:relative;'
		,autoWidth:true
		,autoScroll:true
		,items:[{
			region:'north'
			,border:false
			,bodyStyle:'padding:15px;'
			,html: '<div class="bootstrap-styles">' +
				'<div class="page-head">' +
					'<h4 class="nopadding"><i class="styleColor fa fa-cubes"></i> <?= $acuerdo_nombre; ?></h4>' +
					'<div class="clearfix"></div><p><?= $acuerdo_descripcion; ?></p>' +
				'</div>' +
			'</div>'
		},{
			layout:'column'
			,region:'center'
			,border:false
			,defaults:{columnWidth:1,border:false}
			,bodyStyle:'padding:10px;'
			,items:[
			{
				html:'<div class="bootstrap-styles"><h4 class="text-center"><?= Lang::get('acuerdo_det.table_name'); ?></h4></div>'
			},
				gridAcuerdo_det
			]
		}]
	});
	
	return panelAcuerdo_det;
	/*********************************************** Start functions***********************************************/
	
	function fnEditItm(record){
		var key = record.get('acuerdo_det_id');
		var pkey = record.get('acuerdo_det_acuerdo_id');
		if(Ext.getCmp('tab-add_'+module)){
			Ext.Msg.show({
				 title:Ext.ux.lang.messages.warning
				,msg:Ext.ux.lang.error.close_tab
				,buttons: Ext.Msg.OK
				,icon: Ext.Msg.WARNING
			});
		}
		else{
			var data = {
				id:'edit_' + module
				,iconCls:'silk-page-edit'
				,titleTab:'<?= $title; ?> - ' + Ext.ux.lang.buttons.modify
				,url:'acuerdo_det/jscode/modify'
				,params:{
					id:'<?= $id; ?>'
					,title: '<?= $title; ?> - ' + Ext.ux.lang.buttons.modify
					,module: 'edit_' + module
					,parent: module
					,acuerdo_det_id: key
					,acuerdo_det_acuerdo_id: pkey
				}
			};
			Ext.getCmp('oeste').addTab(this,this,data);
		}
	}
	function fnDeleteItem(record){
		if(Ext.getCmp('tab-quote_'+module) || Ext.getCmp('tab-add_'+module) || Ext.getCmp('tab-edit_'+module)) {
			Ext.Msg.show({
				 title:Ext.ux.lang.messages.warning
				,msg:Ext.ux.lang.error.close_tab
				,buttons: Ext.Msg.OK
				,icon: Ext.Msg.WARNING
			});
		} else {
			Ext.Msg.confirm(
				Ext.ux.lang.messages.confirmation
				,Ext.ux.lang.messages.question_delete
				,function(btn){
				if(btn == 'yes'){
					var gridMask = new Ext.LoadMask(gridAcuerdo_det.getEl(), { msg: 'Erasing .....' });
					gridMask.show();
					var key = record.get('acuerdo_det_id');

					Ext.Ajax.request({
						 url:'acuerdo_det/delete'
						,params: {
							id: '<?= $id; ?>'
							,acuerdo_det_id: key
						}
						,callback: function(options, success, response){
							gridMask.hide();
							var json = Ext.util.JSON.decode(response.responseText);
							if (json.success){
								gridAcuerdo_det.store.reload();
							}
							else{
								Ext.Msg.show({
								   title:Ext.ux.lang.messages.error,
								   buttons: Ext.Msg.OK,
								   msg:json.error,
								   animEl: 'elId',
								   icon: Ext.MessageBox.ERROR
								});
							}
						}
					});
				};
			});
		}
	}
	function fnOpenQuote (record) {
		var key = record.get('acuerdo_det_id');
		var pkey = record.get('acuerdo_det_acuerdo_id');
		if(Ext.getCmp('tab-quote_'+module)){
			Ext.Msg.show({
				 title:Ext.ux.lang.messages.warning
				,msg:Ext.ux.lang.error.close_tab
				,buttons: Ext.Msg.OK
				,icon: Ext.Msg.WARNING
			});
		}
		else{
			var data = {
				id:'quote_' + module
				,iconCls:'silk-basket'
				,titleTab:'<?= Lang::get('contingente.table_name'); ?>'
				,url:'contingente/jscode'
				,params:{
					id:'<?= $id; ?>'
					,title: '<?= Lang::get('contingente.table_name'); ?>'
					,module: 'quote_' + module
					,parent: module
					,acuerdo_det_id: key
					,acuerdo_det_acuerdo_id: pkey
				}
			};
			Ext.getCmp('oeste').addTab(this,this,data);
		}
	}

	/*********************************************** End functions***********************************************/
})()