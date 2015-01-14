<?php
session_start();
include_once('../../lib/config.php');
?>
/*<script>*/
var storeAudit = new Ext.data.JsonStore({
	url:'audit/list'
	,root:'data'
	,sortInfo:{field:'audit_id',direction:'ASC'}
	,totalProperty:'total'
	,baseParams:{id:'<?= $id; ?>'}
	,fields:[
		{name:'audit_id', type:'float'},
		{name:'audit_table', type:'string'},
		{name:'audit_script', type:'string'},
		{name:'audit_method', type:'string'},
		{name:'audit_parameters', type:'string'},
		{name:'audit_uinsert', type:'float'},
		{name:'audit_finsert', type:'date', dateFormat:'Y-m-d H:i:s'}
	]
});
var comboAudit = new Ext.form.ComboBox({
	hiddenName:'audit'
	,id:module+'comboAudit'
	,fieldLabel:'<?= Lang::get('audit.columns_title.audit_finsert'); ?>'
	,store:storeAudit
	,valueField:'audit_id'
	,displayField:'audit_name'
	,typeAhead:true
	,forceSelection:true
	,triggerAction:'all'
	,selectOnFocus:true
	,allowBlank:false
	,listeners:{
		select: {
			fn: function(combo,reg){
				Ext.getCmp(module + 'audit_id').setValue(reg.data.audit_id);
			}
		}
	}
});
var cmAudit = new Ext.grid.ColumnModel({
	columns:[
		{xtype:'numbercolumn', header:'<?= Lang::get('audit.columns_title.audit_id'); ?>', align:'right', hidden:false, dataIndex:'audit_id'},
		{header:'<?= Lang::get('audit.columns_title.audit_table'); ?>', align:'left', hidden:false, dataIndex:'audit_table'},
		{header:'<?= Lang::get('audit.columns_title.audit_script'); ?>', align:'left', hidden:false, dataIndex:'audit_script'},
		{header:'<?= Lang::get('audit.columns_title.audit_method'); ?>', align:'left', hidden:false, dataIndex:'audit_method'},
		{header:'<?= Lang::get('audit.columns_title.audit_parameters'); ?>', align:'left', hidden:false, dataIndex:'audit_parameters'},
		{xtype:'numbercolumn', header:'<?= Lang::get('audit.columns_title.audit_uinsert'); ?>', align:'right', hidden:false, dataIndex:'audit_uinsert'},
		{xtype:'datecolumn', header:'<?= Lang::get('audit.columns_title.audit_finsert'); ?>', align:'left', hidden:false, dataIndex:'audit_finsert', format:'Y-m-d, g:i a'}
	]
	,defaults:{
		sortable:true
		,width:100
	}
});
var tbAudit = new Ext.Toolbar();

var gridAudit = new Ext.grid.GridPanel({
	store:storeAudit
	,id:module+'gridAudit'
	,colModel:cmAudit
	,viewConfig: {
		forceFit: true
		,scrollOffset:2
	}
	,sm:new Ext.grid.RowSelectionModel({singleSelect:true})
	,bbar:new Ext.PagingToolbar({pageSize:10, store:storeAudit, displayInfo:true})
	,tbar:tbAudit
	,loadMask:true
	,border:false
	,title:''
	,iconCls:'icon-grid'
	,plugins:[new Ext.ux.grid.Excel()]
});
var formAudit = new Ext.FormPanel({
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
			{name:'audit_id', mapping:'audit_id', type:'float'},
			{name:'audit_table', mapping:'audit_table', type:'string'},
			{name:'audit_script', mapping:'audit_script', type:'string'},
			{name:'audit_method', mapping:'audit_method', type:'string'},
			{name:'audit_parameters', mapping:'audit_parameters', type:'string'},
			{name:'audit_uinsert', mapping:'audit_uinsert', type:'float'},
			{name:'audit_finsert', mapping:'audit_finsert', type:'date'}
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
				,name:'audit_id'
				,fieldLabel:'<?= Lang::get('audit.columns_title.audit_id'); ?>'
				,id:module+'audit_id'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'textfield'
				,name:'audit_table'
				,fieldLabel:'<?= Lang::get('audit.columns_title.audit_table'); ?>'
				,id:module+'audit_table'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'textfield'
				,name:'audit_script'
				,fieldLabel:'<?= Lang::get('audit.columns_title.audit_script'); ?>'
				,id:module+'audit_script'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'textfield'
				,name:'audit_method'
				,fieldLabel:'<?= Lang::get('audit.columns_title.audit_method'); ?>'
				,id:module+'audit_method'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'textfield'
				,name:'audit_parameters'
				,fieldLabel:'<?= Lang::get('audit.columns_title.audit_parameters'); ?>'
				,id:module+'audit_parameters'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'numberfield'
				,name:'audit_uinsert'
				,fieldLabel:'<?= Lang::get('audit.columns_title.audit_uinsert'); ?>'
				,id:module+'audit_uinsert'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'datefield'
				,name:'audit_finsert'
				,fieldLabel:'<?= Lang::get('audit.columns_title.audit_finsert'); ?>'
				,id:module+'audit_finsert'
				,allowBlank:false
			}]
		}]
	}]
});
