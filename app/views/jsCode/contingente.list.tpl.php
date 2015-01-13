<?php
$acuerdo_det_productos_desc = Inflector::compress($acuerdo_det_productos_desc);
?>
/*<script>*/
(function(){
	Ext.form.Field.prototype.msgTarget = 'side';
	var module = '<?= $module."_".$acuerdo_id; ?>';
	var numberRecords = Math.floor((Ext.getCmp('tabpanel').getInnerHeight() - 120)/22);
	Ext.getCmp('tab-<?= $module; ?>').on('beforeclose', function(){
		dialogoContingente.destroy();
	});

	/*********************************************** contingente Form ***********************************************/
	var formContingente = new Ext.FormPanel({
		labelAlign:'top'
		,method:'POST'
		,url:'contingente/modify'
		,autoWidth:true
		,autoHeight:true
		,autoScroll:true
		,trackResetOnLoad:false
		,buttonAlign:'center'
		,monitorValid:true
		,reader: new Ext.data.JsonReader({
			root:'datos',totalProperty: 'total'
			,fields:[
				{name:'contingente_id', mapping:'contingente_id', type:'float'},
				{name:'contingente_id_pais', mapping:'contingente_id_pais', type:'float'},
				{name:'contingente_mcontingente', mapping:'contingente_mcontingente', type:'string'},
				{name:'contingente_desc', mapping:'contingente_desc', type:'string'},
				{name:'contingente_msalvaguardia', mapping:'contingente_msalvaguardia', type:'string'},
				{name:'contingente_salvaguardia_sobretasa', mapping:'contingente_salvaguardia_sobretasa', type:'float'},
				{name:'contingente_acuerdo_det_id', mapping:'contingente_acuerdo_det_id', type:'float'},
				{name:'contingente_acuerdo_det_acuerdo_id', mapping:'contingente_acuerdo_det_acuerdo_id', type:'float'}
			]
		})
		,items:[{
			xtype:'fieldset'
			,title:'<?= Lang::get('contingente.table_name'); ?>'
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
					,fieldLabel:'<?= Lang::get('contingente.columns_title.contingente_mcontingente'); ?>'
					,id:module+'contingente_mcontingente'
					,allowBlank:false
					,items: [{
						boxLabel:Ext.ux.lang.form.radioBtnYes
						,inputValue:1
						,name:'contingente_mcontingente'
					},{
						boxLabel:Ext.ux.lang.form.radioBtnNo
						,checked:true
						,inputValue:0
						,name:'contingente_mcontingente'
					}]
				},{
					html:'<div class="bootstrap-styles"><p class="text-danger"><?= Lang::get('contingente.alerts.contingente_mcontingente'); ?></p></div>'
				}]
			},{
				defaults:{anchor:'98%'}
				,items:[{
					xtype: 'textarea'
					,id: module+'contingente_desc'
					,name: 'contingente_desc'
					,fieldLabel: '<?= Lang::get('contingente.columns_title.contingente_desc'); ?>'
					,allowBlank: true
					,enableKeyEvents: true
					,grow: true
					,growMin: 60
					,growMax: 100
				}]
			},{
				defaults:{anchor:'88%',border:false}
				,items:[{
					xtype:'radiogroup'
					,fieldLabel:'<?= Lang::get('contingente.columns_title.contingente_msalvaguardia'); ?>'
					,id:module+'contingente_msalvaguardia'
					,allowBlank:false
					,items: [{
						boxLabel:Ext.ux.lang.form.radioBtnYes
						,inputValue:1
						,name:'contingente_msalvaguardia'
					},{
						boxLabel:Ext.ux.lang.form.radioBtnNo
						,checked:true
						,inputValue:0
						,name:'contingente_msalvaguardia'
					}]
					,listeners:{
						'change': {
							fn: function(radio, checked){
								if(checked){
									var disable = (checked.inputValue == '1')?false:true;
									Ext.getCmp(module+'contingente_salvaguardia_sobretasa').setDisabled(disable);
									if(disable){
										Ext.getCmp(module+'contingente_salvaguardia_sobretasa').setValue('');
										Ext.getCmp(module+'contingente_salvaguardia_sobretasa').clearInvalid();
									}
								}
							}
						}
					}
				}]
			},{
				defaults:{anchor:'98%'}
				,items:[{
					xtype: 'numberfield'
					,id: module+'contingente_salvaguardia_sobretasa'
					,name: 'contingente_salvaguardia_sobretasa'
					,fieldLabel: '<?= Lang::get('contingente.columns_title.contingente_salvaguardia_sobretasa'); ?>'
					,allowBlank: false
					,maxValue: 100
					,minValue: 0
				}]
			},{
				xtype:'hidden'
				,name:'contingente_id'
			},{
				xtype:'hidden'
				,name:'contingente_id_pais'
			},{
				xtype:'hidden'
				,name:'contingente_acuerdo_det_id'
			},{
				xtype:'hidden'
				,name:'contingente_acuerdo_det_acuerdo_id'
			}]
		}]
		,buttons: [{
			text:Ext.ux.lang.buttons.cancel
			,iconCls: 'silk-cancel'
			,handler: function(){
				formContingente.getForm().reset();
				dialogoContingente.hide();
			}
		},{
			text:Ext.ux.lang.buttons.save
			,iconCls: 'icon-save'
			,handler:fnSave
		}]
	});

	var dialogoContingente = new Ext.Window({
		border:true
		,plain:true
		,closeAction:'hide'
		,id:module+'dialogoContingente'
		,width:550
		,layout:'fit'
		,autoHeight:true
		,modal:true
		,resizable:false
		,draggable:false
		,items:[formContingente]
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
			 iconCls: 'silk-cart'
			,qtip: '<?= Lang::get('acuerdo_det.table_name'); ?>'
		}]
		,callbacks:{
			'silk-page-edit':function(grid, record, action, row, col) {
				fnEditItm(record);
			}
			,'silk-cart':function(grid, record, action, row, col) {
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
	function fnEditItm(record){
		formContingente.form.reset();
		formContingente.form.loadRecord(record);
		Ext.getCmp(module+'contingente_mcontingente').setValue(record.get('contingente_mcontingente'));
		dialogoContingente.show();
	}
	
	/*********************************************** End functions***********************************************/
})()