<?php
$acuerdo_det_productos_desc = Inflector::compress($acuerdo_det_productos_desc);
?>
/*<script>*/
(function(){
	Ext.form.Field.prototype.msgTarget = 'side';
	var module = '<?= $module."_".$acuerdo_id; ?>';
	var numberRecords = Math.floor((Ext.getCmp('tabpanel').getInnerHeight() - 120)/22);
	Ext.getCmp('tab-<?= $module; ?>').on('beforeclose', function(){
		dialogoDesgravacion_det.destroy();
		dialogoDesgravacion.destroy();
	});

	/*********************************************** desgravacion_det grid ***********************************************/
	var storeDesgravacion_det = new Ext.data.JsonStore({
		url:'desgravacion_det/list'
		,pruneModifiedRecords:true
		,root:'data'
		,sortInfo:{field:'desgravacion_det_anio_ini',direction:'ASC'}
		,totalProperty:'total'
		,baseParams:{
			id:'<?= $id; ?>'
			,desgravacion_det_desgravacion_acuerdo_det_id:'<?= $acuerdo_det_id; ?>'
			,desgravacion_det_desgravacion_acuerdo_det_acuerdo_id:'<?= $acuerdo_det_acuerdo_id; ?>'
		}
		,fields:[
			{name:'desgravacion_det_id', type:'float'},
			{name:'desgravacion_det_anio_ini', type:'string'},
			{name:'desgravacion_det_anio_fin', type:'string'},
			{name:'desgravacion_det_anio_fin_title', type:'string'},
			{name:'desgravacion_det_tasa', type:'float'},
			{name:'desgravacion_det_desgravacion_id', type:'float'},
			{name:'desgravacion_det_desgravacion_acuerdo_det_id', type:'float'},
			{name:'desgravacion_det_desgravacion_acuerdo_det_acuerdo_id', type:'float'}
		]
	});

	var cmDesgravacion_det = new Ext.grid.ColumnModel({
		columns:[
			new Ext.grid.RowNumberer({width:25}),
		{
			xtype:'numbercolumn'
			,format: '0'
			,header:'<?= Lang::get('desgravacion_det.columns_title.desgravacion_det_anio_ini'); ?>'
			,dataIndex:'desgravacion_det_anio_ini'
		},{
			header:'<?= Lang::get('desgravacion_det.columns_title.desgravacion_det_anio_fin'); ?>'
			,dataIndex:'desgravacion_det_anio_fin_title'
		},{
			xtype:'numbercolumn'
			,header:'<?= Lang::get('desgravacion_det.columns_title.desgravacion_det_tasa'); ?>'
			,dataIndex:'desgravacion_det_tasa'
			,editor:new Ext.form.NumberField({
				allowBlank:false
				,minValue:1
			}) 
		}]
		,defaults:{
			sortable:false
			,align:'right'
			,width:100
		}
	});
	var tbDesgravacion_det = new Ext.Toolbar();

	var gridDesgravacion_det = new Ext.grid.EditorGridPanel({
		store:storeDesgravacion_det
		,id:module+'gridDesgravacion_det'
		,colModel:cmDesgravacion_det
		,viewConfig: {
			forceFit: true
		}
		,sm:new Ext.grid.RowSelectionModel({singleSelect:true})
		//,bbar:new Ext.PagingToolbar({pageSize:10, store:storeDesgravacion_det, displayInfo:true})
		,tbar:tbDesgravacion_det
		,loadMask:true
		,border:false
		,title:''
		,autoWidth:true
		,height:350
		,autoScroll:true
		,enableColumnHide:false
		,enableColumnMove:false
		,enableColumnResize:false
		,iconCls:'icon-grid'
		,stripeRows: true
		,buttonAlign:'center'
		,buttons: [{
			text:Ext.ux.lang.buttons.cancel
			,iconCls: 'silk-cancel'
			,handler: function(){
				dialogoDesgravacion_det.hide();
			}
		},{
			text:Ext.ux.lang.buttons.save
			,iconCls: 'icon-save'
			,handler:fnSaveDetail
		}]
	});

	var dialogoDesgravacion_det = new Ext.Window({
		border:true
		,plain:true
		,closeAction:'hide'
		,id:module+'dialogoDesgravacion_det'
		,width:550
		,layout:'fit'
		,autoHeight:true
		,modal:true
		,resizable:false
		,draggable:false
		,items:[gridDesgravacion_det]
	});

	/*********************************************** desgravacion Form ***********************************************/
	var formDesgravacion = new Ext.FormPanel({
		labelAlign:'top'
		,method:'POST'
		,url:'desgravacion/modify'
		,autoWidth:true
		,autoHeight:true
		,autoScroll:true
		,trackResetOnLoad:false
		,buttonAlign:'center'
		,monitorValid:true
		,bodyStyle:'padding:5px'
		,reader: new Ext.data.JsonReader({
			root:'datos',totalProperty: 'total'
			,fields:[
				{name:'desgravacion_id', mapping:'desgravacion_id', type:'float'},
				{name:'desgravacion_id_pais', mapping:'desgravacion_id_pais', type:'float'},
				{name:'desgravacion_mdesgravacion', mapping:'desgravacion_mdesgravacion', type:'string'},
				{name:'desgravacion_desc', mapping:'desgravacion_desc', type:'string'},
				{name:'desgravacion_acuerdo_det_id', mapping:'desgravacion_acuerdo_det_id', type:'float'},
				{name:'desgravacion_acuerdo_det_acuerdo_id', mapping:'desgravacion_acuerdo_det_acuerdo_id', type:'float'}
			]
		})
		,items:[{
			xtype:'fieldset'
			,title:'<?= Lang::get('desgravacion.table_name'); ?>'
			,layout:'column'
			,flex: 1
			,defaults:{
				columnWidth:1
				,layout:'form'
				,labelAlign:'top'
				,border:false
				,xtype:'panel'
				,bodyStyle:'padding:0 18px 0 0'
			}
			,items:[{
				defaults:{anchor:'88%',border:false}
				,items:[{
					xtype:'radiogroup'
					,fieldLabel:'<?= Lang::get('desgravacion.columns_title.desgravacion_mdesgravacion'); ?>'
					,id:module+'desgravacion_mdesgravacion'
					,allowBlank:false
					,items: [{
						boxLabel:Ext.ux.lang.form.radioBtnYes
						,inputValue:1
						,name:'desgravacion_mdesgravacion'
					},{
						boxLabel:Ext.ux.lang.form.radioBtnNo
						,checked:true
						,inputValue:0
						,name:'desgravacion_mdesgravacion'
					}]
					,listeners:{
						'change': {
							fn: function(radio, checked){
								if(checked){
									
								}
							}
						}
					}
				},{
					html:'<div class="bootstrap-styles"><p class="text-danger"><?= Lang::get('desgravacion.alerts.desgravacion_mdesgravacion'); ?></p></div>'
				}]
			},{
				defaults:{anchor:'98%'}
				,items:[{
					xtype: 'textarea'
					,id: module+'desgravacion_desc'
					,name: 'desgravacion_desc'
					,fieldLabel: '<?= Lang::get('desgravacion.columns_title.desgravacion_desc'); ?>'
					,allowBlank: true
					,enableKeyEvents: true
					,grow: true
					,growMin: 60
					,growMax: 100
				}]
			},{
				xtype:'hidden'
				,name:'desgravacion_id'
			},{
				xtype:'hidden'
				,name:'desgravacion_id_pais'
			},{
				xtype:'hidden'
				,name:'desgravacion_acuerdo_det_id'
			},{
				xtype:'hidden'
				,name:'desgravacion_acuerdo_det_acuerdo_id'
			}]
		}]
		,buttons: [{
			text:Ext.ux.lang.buttons.cancel
			,iconCls: 'silk-cancel'
			,handler: function(){
				formDesgravacion.getForm().reset();
				dialogoDesgravacion.hide();
			}
		},{
			text:Ext.ux.lang.buttons.save
			,iconCls: 'icon-save'
			,handler:fnSave
		}]
	});

	var dialogoDesgravacion = new Ext.Window({
		border:true
		,plain:true
		,closeAction:'hide'
		,id:module+'dialogoDesgravacion'
		,width:550
		,layout:'fit'
		,autoHeight:true
		,modal:true
		,resizable:false
		,draggable:false
		,items:[formDesgravacion]
	});
	
	/*********************************************** desgravacion grid ***********************************************/
	
	///continuar desde aca 2015-01-18
	var storeDesgravacion = new Ext.data.JsonStore({
		url:'desgravacion/list'
		,root:'data'
		,sortInfo:{field:'desgravacion_id',direction:'ASC'}
		,totalProperty:'total'
		,baseParams:{
			id:'<?= $id; ?>'
			,desgravacion_acuerdo_det_id:'<?= $acuerdo_det_id; ?>'
			,desgravacion_acuerdo_det_acuerdo_id:'<?= $acuerdo_det_acuerdo_id; ?>'
		}
		,fields:[
			{name:'desgravacion_id', type:'float'},
			{name:'desgravacion_id_pais', type:'float'},
			{name:'pais', type:'string'},
			{name:'desgravacion_mdesgravacion', type:'string'},
			{name:'desgravacion_mdesgravacion_title', type:'string'},
			{name:'desgravacion_desc', type:'string'},
			{name:'desgravacion_acuerdo_det_id', type:'float'},
			{name:'desgravacion_acuerdo_det_acuerdo_id', type:'float'},
		]
	});

	storeDesgravacion.load({params:{start:0, limit:numberRecords}});

	gridDesgravacionAction = new Ext.ux.grid.RowActions({
		header: Ext.ux.lang.grid.options
		,keepSelection:true
		,autoWidth:false
		,actions:[{
			iconCls: 'silk-page-edit'
			,qtip: Ext.ux.lang.buttons.modify_tt
		},{
			 iconCls: 'fuel'
			,qtip: '<?= Lang::get('desgravacion_det.table_name'); ?>'
		}]
		,callbacks:{
			'silk-page-edit':function(grid, record, action, row, col) {
				fnEditItm(record);
			}
			,'fuel':function(grid, record, action, row, col) {
				fnOpenDetail(record);
			}
		}
	});

	var cmDesgravacion = new Ext.grid.ColumnModel({
		columns:[
			{header:'<?= Lang::get('desgravacion.columns_title.desgravacion_id_pais'); ?>', hidden:false, dataIndex:'pais'},
			{header:'<?= Lang::get('desgravacion.columns_title.desgravacion_mdesgravacion'); ?>', hidden:false, dataIndex:'desgravacion_mdesgravacion_title'},
			{header:'<?= Lang::get('desgravacion.columns_title.desgravacion_desc'); ?>', hidden:false, dataIndex:'desgravacion_desc'},
			gridDesgravacionAction
		]
		,defaults:{
			sortable:true
			,width:100
		}
	});

	var tbDesgravacion = new Ext.Toolbar();

	var gridDesgravacion = new Ext.grid.GridPanel({
		store:storeDesgravacion
		,id:module + 'gridDesgravacion'
		,colModel:cmDesgravacion
		,viewConfig: {
			forceFit: true
			,scrollOffset:2
		}
		,sm: new Ext.grid.RowSelectionModel({
			singleSelect: true
		})
		,bbar:new Ext.PagingToolbar({pageSize:numberRecords, store:storeDesgravacion, displayInfo:true})
		,enableColumnMove:false
		,enableColumnResize:false
		,tbar:tbDesgravacion
		,loadMask:true
		,border:false
		,frame: false
		,baseCls: 'x-panel-mc'
		,buttonAlign:'center'
		,title:''
		,iconCls:'icon-grid'
		,stripeRows: true
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
			,gridDesgravacionAction
			//,gridDesgravacionExpander
		]
	});

	/*elimiar cualquier estado de la grilla guardado con anterioridad */
	Ext.state.Manager.clear(gridDesgravacion.getItemId());

	var panelDesgravacion = new Ext.Panel({
		xtype:'panel'
		,id:module+'panelDesgravacion'
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
					'<div class="clearfix"></div>' +
					'<ol class="breadcrumb"><li class="active"><?= $acuerdo_det_productos_desc; ?></li></ol>' +
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
				html:'<div class="bootstrap-styles"><h4 class="text-center"><?= Lang::get('desgravacion.table_name'); ?></h4></div>'
			},
				gridDesgravacion
			]
		}]
	});
	
	return panelDesgravacion;
	/*********************************************** Start functions***********************************************/
	
	function fnSave () {
		if(formDesgravacion.form.isValid()){
			params = {
				id: '<?= $id; ?>'
			};
			formDesgravacion.getForm().submit({
				waitMsg: 'Saving....'
				,waitTitle:'Wait please...'
				,url:'desgravacion/modify'
				,params: params
				,success: function(form, action){
					storeDesgravacion.load();
					formDesgravacion.getForm().reset();
					dialogoDesgravacion.hide();
				}
				,failure:function(form, action){
					Ext.Msg.show({
					   title:'Error',
					   buttons: Ext.Msg.OK,
					   msg:Ext.decode(action.response.responseText).error,
					   animEl: 'elId',
					   icon: Ext.MessageBox.ERROR
					});
				}
			});
		}
		else{
			Ext.Msg.show({
				title: Ext.ux.lang.messages.warning
				,msg: Ext.ux.lang.error.empty_fields
				,buttons: Ext.Msg.OK
				,icon: Ext.Msg.WARNING
			});
		}
	}
	function fnEditItm (record) {
		
		formDesgravacion.form.reset();
		formDesgravacion.form.loadRecord(record);
		dialogoDesgravacion.show();
		var radio = Ext.getCmp(module+'desgravacion_mdesgravacion');
		var value = record.get('desgravacion_mdesgravacion');
		radio.setValue([value]).fireEvent('change', radio, radio.getValue() );
	}
	function fnOpenDetail (record) {

		storeDesgravacion_det.baseParams['desgravacion_det_desgravacion_id'] = record.get('desgravacion_id');

		storeDesgravacion_det.load();

		dialogoDesgravacion_det.show();

	}
	function fnSaveDetail () {
		Ext.ux.bodyMask.show();
		var records = storeDesgravacion_det.getModifiedRecords();
		var data = [];
		Ext.each(records, function(r, i) {
			data.push(r.data);
		});
		Ext.Ajax.request({
			 url:'desgravacion_det/saveGrid'
			,method:'POST'
			,scope:this
			,timeout:100000
			,params:{
				data:Ext.util.JSON.encode(data)
				,id: '<?= $id; ?>'
			}
			,callback:function(options, success, response){
				Ext.ux.bodyMask.hide();
				var json = Ext.util.JSON.decode(response.responseText);
				if (json.success) {
					storeDesgravacion_det.commitChanges();
					dialogoDesgravacion_det.hide();
				} else {
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
	}
	
	/*********************************************** End functions***********************************************/
})()