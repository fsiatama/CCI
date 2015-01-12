<?php
session_start();
include_once('../../lib/config.php');
?>
/*<script>*/
var storeAcuerdo_det = new Ext.data.JsonStore({
	url:'acuerdo_det/list'
	,root:'data'
	,sortInfo:{field:'acuerdo_det_acuerdo_id',direction:'ASC'}
	,totalProperty:'total'
	,baseParams:{id:'<?= $id; ?>'}
	,fields:[
		{name:'acuerdo_det_id', type:'float'},
		{name:'acuerdo_det_arancel_base', type:'float'},
		{name:'acuerdo_det_productos', type:'string'},
		{name:'acuerdo_det_productos_desc', type:'string'},
		{name:'acuerdo_det_administracion', type:'string'},
		{name:'acuerdo_det_administrador', type:'string'},
		{name:'acuerdo_det_nperiodos', type:'float'},
		{name:'acuerdo_det_acuerdo_id', type:'float'},
		{name:'acuerdo_det_contingente_acumulado_pais', type:'string'}
	]
});
var comboAcuerdo_det = new Ext.form.ComboBox({
	hiddenName:'acuerdo_det'
	,id:module+'comboAcuerdo_det'
	,fieldLabel:'<?= Lang::get('acuerdo_det.columns_title.acuerdo_det_contingente_acumulado_pais'); ?>'
	,store:storeAcuerdo_det
	,valueField:'acuerdo_det_acuerdo_id'
	,displayField:'acuerdo_det_name'
	,typeAhead:true
	,forceSelection:true
	,triggerAction:'all'
	,selectOnFocus:true
	,allowBlank:false
	,listeners:{
		select: {
			fn: function(combo,reg){
				Ext.getCmp(module + 'acuerdo_det_acuerdo_id').setValue(reg.data.acuerdo_det_acuerdo_id);
			}
		}
	}
});
var cmAcuerdo_det = new Ext.grid.ColumnModel({
	columns:[
		{xtype:'numbercolumn', header:'<?= Lang::get('acuerdo_det.columns_title.acuerdo_det_id'); ?>', align:'right', hidden:false, dataIndex:'acuerdo_det_id'},
		{xtype:'numbercolumn', header:'<?= Lang::get('acuerdo_det.columns_title.acuerdo_det_arancel_base'); ?>', align:'right', hidden:false, dataIndex:'acuerdo_det_arancel_base'},
		{header:'<?= Lang::get('acuerdo_det.columns_title.acuerdo_det_productos'); ?>', align:'left', hidden:false, dataIndex:'acuerdo_det_productos'},
		{header:'<?= Lang::get('acuerdo_det.columns_title.acuerdo_det_productos_desc'); ?>', align:'left', hidden:false, dataIndex:'acuerdo_det_productos_desc'},
		{header:'<?= Lang::get('acuerdo_det.columns_title.acuerdo_det_administracion'); ?>', align:'left', hidden:false, dataIndex:'acuerdo_det_administracion'},
		{header:'<?= Lang::get('acuerdo_det.columns_title.acuerdo_det_administrador'); ?>', align:'left', hidden:false, dataIndex:'acuerdo_det_administrador'},
		{xtype:'numbercolumn', header:'<?= Lang::get('acuerdo_det.columns_title.acuerdo_det_nperiodos'); ?>', align:'right', hidden:false, dataIndex:'acuerdo_det_nperiodos'},
		{xtype:'numbercolumn', header:'<?= Lang::get('acuerdo_det.columns_title.acuerdo_det_acuerdo_id'); ?>', align:'right', hidden:false, dataIndex:'acuerdo_det_acuerdo_id'},
		{header:'<?= Lang::get('acuerdo_det.columns_title.acuerdo_det_contingente_acumulado_pais'); ?>', align:'left', hidden:false, dataIndex:'acuerdo_det_contingente_acumulado_pais'}
	]
	,defaults:{
		sortable:true
		,width:100
	}
});
var tbAcuerdo_det = new Ext.Toolbar();

var gridAcuerdo_det = new Ext.grid.GridPanel({
	store:storeAcuerdo_det
	,id:module+'gridAcuerdo_det'
	,colModel:cmAcuerdo_det
	,viewConfig: {
		forceFit: true
		,scrollOffset:2
	}
	,sm:new Ext.grid.RowSelectionModel({singleSelect:true})
	,bbar:new Ext.PagingToolbar({pageSize:10, store:storeAcuerdo_det, displayInfo:true})
	,tbar:tbAcuerdo_det
	,loadMask:true
	,border:false
	,title:''
	,iconCls:'icon-grid'
	,plugins:[new Ext.ux.grid.Excel()]
});
var formAcuerdo_det = new Ext.FormPanel({
	baseCls:'x-panel-mc'
	,method:'POST'
	,baseParams:{accion:'act'}
	,autoWidth:true
	,autoScroll:true
	,trackResetOnLoad:true
	,monitorValid:true
	,bodyStyle:'padding:15px;'
	,reader: new Ext.data.JsonReader({
		root:'data'
		,totalProperty:'total'
		,fields:[
			{name:'acuerdo_det_id', mapping:'acuerdo_det_id', type:'float'},
			{name:'acuerdo_det_arancel_base', mapping:'acuerdo_det_arancel_base', type:'float'},
			{name:'acuerdo_det_productos', mapping:'acuerdo_det_productos', type:'string'},
			{name:'acuerdo_det_productos_desc', mapping:'acuerdo_det_productos_desc', type:'string'},
			{name:'acuerdo_det_administracion', mapping:'acuerdo_det_administracion', type:'string'},
			{name:'acuerdo_det_administrador', mapping:'acuerdo_det_administrador', type:'string'},
			{name:'acuerdo_det_nperiodos', mapping:'acuerdo_det_nperiodos', type:'float'},
			{name:'acuerdo_det_acuerdo_id', mapping:'acuerdo_det_acuerdo_id', type:'float'},
			{name:'acuerdo_det_contingente_acumulado_pais', mapping:'acuerdo_det_contingente_acumulado_pais', type:'string'}
		]
	})
	,items:[{
		xtype:'fieldset'
		,title:'Information'
		,layout:'column'
		,defaults:{
			columnWidth:0.33
			,layout:'form'
			,labelAlign:'top'
			,border:false
			,xtype:'panel'
			,bodyStyle:'padding:0 18px 0 0'
		}
		,items:[{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'numberfield'
				,name:'acuerdo_det_id'
				,fieldLabel:'<?= Lang::get('acuerdo_det.columns_title.acuerdo_det_id'); ?>'
				,id:module+'acuerdo_det_id'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'numberfield'
				,name:'acuerdo_det_arancel_base'
				,fieldLabel:'<?= Lang::get('acuerdo_det.columns_title.acuerdo_det_arancel_base'); ?>'
				,id:module+'acuerdo_det_arancel_base'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'textfield'
				,name:'acuerdo_det_productos'
				,fieldLabel:'<?= Lang::get('acuerdo_det.columns_title.acuerdo_det_productos'); ?>'
				,id:module+'acuerdo_det_productos'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'textfield'
				,name:'acuerdo_det_productos_desc'
				,fieldLabel:'<?= Lang::get('acuerdo_det.columns_title.acuerdo_det_productos_desc'); ?>'
				,id:module+'acuerdo_det_productos_desc'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'textfield'
				,name:'acuerdo_det_administracion'
				,fieldLabel:'<?= Lang::get('acuerdo_det.columns_title.acuerdo_det_administracion'); ?>'
				,id:module+'acuerdo_det_administracion'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'textfield'
				,name:'acuerdo_det_administrador'
				,fieldLabel:'<?= Lang::get('acuerdo_det.columns_title.acuerdo_det_administrador'); ?>'
				,id:module+'acuerdo_det_administrador'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'numberfield'
				,name:'acuerdo_det_nperiodos'
				,fieldLabel:'<?= Lang::get('acuerdo_det.columns_title.acuerdo_det_nperiodos'); ?>'
				,id:module+'acuerdo_det_nperiodos'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'numberfield'
				,name:'acuerdo_det_acuerdo_id'
				,fieldLabel:'<?= Lang::get('acuerdo_det.columns_title.acuerdo_det_acuerdo_id'); ?>'
				,id:module+'acuerdo_det_acuerdo_id'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'numberfield'
				,name:'acuerdo_det_contingente_acumulado_pais'
				,fieldLabel:'<?= Lang::get('acuerdo_det.columns_title.acuerdo_det_contingente_acumulado_pais'); ?>'
				,id:module+'acuerdo_det_contingente_acumulado_pais'
				,allowBlank:false
			}]
		}]
	}]
});
