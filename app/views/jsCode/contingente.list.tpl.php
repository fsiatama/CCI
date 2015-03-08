<?php
$acuerdo_det_productos_desc = Inflector::compress($acuerdo_det_productos_desc);
?>
/*<script>*/
(function(){
	Ext.form.Field.prototype.msgTarget = 'side';
	var module = '<?= $module."_".$acuerdo_id; ?>';
	var numberRecords = Math.floor((Ext.getCmp('tabpanel').getInnerHeight() - 120)/22);
	Ext.getCmp('tab-<?= $module; ?>').on('beforeclose', function(){
		dialogoContingente_det.destroy();
	});

	/*********************************************** contingente_det grid ***********************************************/
	var storeContingente_det = new Ext.data.JsonStore({
		url:'contingente_det/list'
		,pruneModifiedRecords:true
		,root:'data'
		,sortInfo:{field:'contingente_det_anio_ini',direction:'ASC'}
		,totalProperty:'total'
		,baseParams:{
			id:'<?= $id; ?>'
			,contingente_det_contingente_acuerdo_det_id:'<?= $acuerdo_det_id; ?>'
			,contingente_det_contingente_acuerdo_det_acuerdo_id:'<?= $acuerdo_det_acuerdo_id; ?>'
		}
		,fields:[
			{name:'contingente_det_id', type:'float'},
			{name:'contingente_det_anio_ini', type:'string'},
			{name:'contingente_det_anio_fin', type:'string'},
			{name:'contingente_det_anio_fin_title', type:'string'},
			{name:'contingente_det_peso_neto', type:'float'},
			{name:'contingente_det_tipo_operacion', type:'string'},
			{name:'contingente_det_contingente_id', type:'float'},
			{name:'contingente_det_contingente_acuerdo_det_id', type:'float'},
			{name:'contingente_det_contingente_acuerdo_det_acuerdo_id', type:'float'}
		]
	});

	Ext.util.Format.comboRenderer = function(combo){
	    return function(value){
	        var record = combo.findRecord(combo.valueField, value);
	        return record ? record.get(combo.displayField) : combo.valueNotFoundText;
	    }
	}

	var arrOperatorType = <?= json_encode($arrOperatorType); ?>;
	var comboOperatorType = new Ext.form.ComboBox({
		typeAhead:false
		,forceSelection:true
		,selectOnFocus:true
		,allowBlank:false
		,triggerAction:'all'
		,store:arrOperatorType
		,mode:'local'
		,listClass: 'x-combo-list-small'
		,valueField: 'contingente_det_tipo_operacion'
		,displayField: 'displayText'
	});

	var cmContingente_det = new Ext.grid.ColumnModel({
		columns:[
			new Ext.grid.RowNumberer({width:25}),
		{
			xtype:'numbercolumn'
			,format: '0'
			,header:'<?= Lang::get('contingente_det.columns_title.contingente_det_anio_ini'); ?>'
			,dataIndex:'contingente_det_anio_ini'
		},{
			header:'<?= Lang::get('contingente_det.columns_title.contingente_det_anio_fin'); ?>'
			,dataIndex:'contingente_det_anio_fin_title'
		/*},{
			header: '<?= Lang::get('contingente_det.columns_title.contingente_det_tipo_operacion'); ?>'
			,dataIndex: 'contingente_det_tipo_operacion'
			,width: 130
			,editor:comboOperatorType
			,renderer: Ext.util.Format.comboRenderer(comboOperatorType)*/
		},{
			xtype:'numbercolumn'
			,header:'<?= Lang::get('contingente_det.columns_title.contingente_det_peso_neto'); ?>'
			,dataIndex:'contingente_det_peso_neto'
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
	var tbContingente_det = new Ext.Toolbar();

	var gridContingente_det = new Ext.grid.EditorGridPanel({
		store:storeContingente_det
		,id:module+'gridContingente_det'
		,colModel:cmContingente_det
		,viewConfig: {
			forceFit: true
		}
		,sm:new Ext.grid.RowSelectionModel({singleSelect:true})
		//,bbar:new Ext.PagingToolbar({pageSize:10, store:storeContingente_det, displayInfo:true})
		,tbar:tbContingente_det
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
				dialogoContingente_det.hide();
			}
		},{
			text:Ext.ux.lang.buttons.save
			,iconCls: 'icon-save'
			,handler:fnSaveDetail
		}]
	});

	var dialogoContingente_det = new Ext.Window({
		border:true
		,plain:true
		,closeAction:'hide'
		,id:module+'dialogoContingente_det'
		,width:550
		,layout:'fit'
		,autoHeight:true
		,modal:true
		,resizable:false
		,draggable:false
		,items:[gridContingente_det]
	});

	/*********************************************** contingente grid ***********************************************/
	var storeContingente = new Ext.data.JsonStore({
		url:'contingente/list'
		,root:'data'
		,sortInfo:{field:'contingente_id',direction:'ASC'}
		,totalProperty:'total'
		,baseParams:{
			id:'<?= $id; ?>'
			,contingente_acuerdo_det_id:'<?= $acuerdo_det_id; ?>'
			,contingente_acuerdo_det_acuerdo_id:'<?= $acuerdo_det_acuerdo_id; ?>'
		}
		,fields:[
			{name:'contingente_id', type:'float'},
			{name:'contingente_id_pais', type:'float'},
			{name:'pais', type:'string'},
			{name:'contingente_mcontingente', type:'string'},
			{name:'contingente_mcontingente_title', type:'string'},
			{name:'contingente_desc', type:'string'},
			{name:'contingente_msalvaguardia', type:'string'},
			{name:'contingente_msalvaguardia_title', type:'string'},
			{name:'contingente_salvaguardia_sobretasa', type:'float'},
			{name:'contingente_acuerdo_det_id', type:'float'},
			{name:'contingente_acuerdo_det_acuerdo_id', type:'float'},
			{name:'alerta_id', type:'float'},
			{name:'alerta_contingente_verde', type:'float'},
			{name:'alerta_contingente_amarilla', type:'float'},
			{name:'alerta_contingente_roja', type:'float'},
			{name:'alerta_salvaguardia_verde', type:'float'},
			{name:'alerta_salvaguardia_amarilla', type:'float'},
			{name:'alerta_salvaguardia_roja', type:'float'},
			{name:'alerta_emails', type:'string'},
		]
	});

	storeContingente.load({params:{start:0, limit:numberRecords}});

	gridContingenteAction = new Ext.ux.grid.RowActions({
		header: Ext.ux.lang.grid.options
		,keepSelection:true
		,autoWidth:false
		,actions:[{
			iconCls: 'silk-page-edit'
			,qtip: Ext.ux.lang.buttons.modify_tt
		},{
			 iconCls: 'fuel'
			,qtip: '<?= Lang::get('contingente_det.table_name'); ?>'
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

	var cmContingente = new Ext.grid.ColumnModel({
		columns:[
			{header:'<?= Lang::get('contingente.columns_title.contingente_id_pais'); ?>', hidden:false, dataIndex:'pais'},
			{header:'<?= Lang::get('contingente.columns_title.contingente_mcontingente'); ?>', hidden:false, dataIndex:'contingente_mcontingente_title'},
			{header:'<?= Lang::get('contingente.columns_title.contingente_desc'); ?>', hidden:false, dataIndex:'contingente_desc'},
			{header:'<?= Lang::get('contingente.columns_title.contingente_msalvaguardia'); ?>', hidden:false, dataIndex:'contingente_msalvaguardia_title'},
			{header:'<?= Lang::get('contingente.columns_title.contingente_salvaguardia_sobretasa'); ?>', align:'left', hidden:false, dataIndex:'contingente_salvaguardia_sobretasa'},
			gridContingenteAction
		]
		,defaults:{
			sortable:true
			,width:100
		}
	});

	var tbContingente = new Ext.Toolbar();

	var gridContingente = new Ext.grid.GridPanel({
		store:storeContingente
		,id:module + 'gridContingente'
		,colModel:cmContingente
		,viewConfig: {
			forceFit: true
			,scrollOffset:2
		}
		,sm: new Ext.grid.RowSelectionModel({
			singleSelect: true
		})
		,bbar:new Ext.PagingToolbar({pageSize:numberRecords, store:storeContingente, displayInfo:true})
		,enableColumnMove:false
		,enableColumnResize:false
		,tbar:tbContingente
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
			,gridContingenteAction
			//,gridContingenteExpander
		]
	});

	/*elimiar cualquier estado de la grilla guardado con anterioridad */
	Ext.state.Manager.clear(gridContingente.getItemId());

	var panelContingente = new Ext.Panel({
		xtype:'panel'
		,id:module+'panelContingente'
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
				html:'<div class="bootstrap-styles"><h4 class="text-center"><?= Lang::get('contingente.table_name'); ?></h4></div>'
			},
				gridContingente
			]
		}]
	});
	
	return panelContingente;
	/*********************************************** Start functions***********************************************/
	
	function fnSave () {
		if(formContingente.form.isValid()){
			params = {
				id: '<?= $id; ?>'
			};
			formContingente.getForm().submit({
				waitMsg: 'Saving....'
				,waitTitle:'Wait please...'
				,url:'contingente/modify'
				,params: params
				,success: function(form, action){
					storeContingente.load();
					formContingente.getForm().reset();
					dialogoContingente.hide();
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
		var key = record.get('contingente_id');
		if(Ext.getCmp('tab-edit_'+module)) {
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
				,url:'contingente/jscode/modify'
				,params:{
					id:'<?= $id; ?>'
					,title: '<?= $title; ?> - ' + Ext.ux.lang.buttons.modify
					,module: 'edit_' + module
					,parent: module
					,contingente_id: key
					,acuerdo_det_id:'<?= $acuerdo_det_id; ?>'
					,acuerdo_det_acuerdo_id:'<?= $acuerdo_det_acuerdo_id; ?>'
				}
			};
			Ext.getCmp('oeste').addTab(this,this,data);
		}







		/*formContingente.form.reset();
		formContingente.form.loadRecord(record);
		dialogoContingente.show();
		var radio = Ext.getCmp(module+'contingente_mcontingente');
		var value = record.get('contingente_mcontingente');
		radio.setValue([value]).fireEvent('change', radio, radio.getValue() );

		radio = Ext.getCmp(module+'contingente_msalvaguardia');
		value = record.get('contingente_msalvaguardia');
		radio.setValue([value]).fireEvent('change', radio, radio.getValue() );*/
	}
	function fnOpenDetail (record) {

		storeContingente_det.baseParams['contingente_det_contingente_id'] = record.get('contingente_id');

		storeContingente_det.load();

		dialogoContingente_det.show();

	}
	function fnSaveDetail () {
		Ext.ux.bodyMask.show();
		var records = storeContingente_det.getModifiedRecords();
		var data = [];
		Ext.each(records, function(r, i) {
			data.push(r.data);
		});
		Ext.Ajax.request({
			 url:'contingente_det/saveGrid'
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
					storeContingente_det.commitChanges();
					dialogoContingente_det.hide();
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