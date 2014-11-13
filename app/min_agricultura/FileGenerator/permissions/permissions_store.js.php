<?php
session_start();
include_once('../../lib/config.php');
?>
/*<script>*/
var storePermissions = new Ext.data.JsonStore({
	url:'proceso/permissions/'
	,root:'datos'
	,sortInfo:{field:'permissions_id',direction:'ASC'}
	,totalProperty:'total'
	,baseParams:{accion:'lista'}
	,fields:[
		{name:'permissions_id', type:'float'},
		{name:'permissions_profile_id', type:'float'},
		{name:'permissions_menu_id', type:'float'},
		{name:'permissions_list', type:'string'},
		{name:'permissions_modify', type:'string'},
		{name:'permissions_create', type:'string'},
		{name:'permissions_delete', type:'string'},
		{name:'permissions_export', type:'string'}
	]
});
var comboPermissions = new Ext.form.ComboBox({
	hiddenName:'permissions'
	,id:modulo+'comboPermissions'
	,fieldLabel:'<?php print _PERMISSIONS; ?>'
	,store:storePermissions
	,valueField:'permissions_id'
	,displayField:'permissions_nombre'
	,typeAhead:true
	,forceSelection:true
	,triggerAction:'all'
	,selectOnFocus:true
});
var cmPermissions = new Ext.grid.ColumnModel({
	columns:[
		{xtype:'numbercolumn', header:'<?php print _PERMISSIONS_ID; ?>', align:'right', hidden:false, dataIndex:'permissions_id'},
		{xtype:'numbercolumn', header:'<?php print _PERMISSIONS_PROFILE_ID; ?>', align:'right', hidden:false, dataIndex:'permissions_profile_id'},
		{xtype:'numbercolumn', header:'<?php print _PERMISSIONS_MENU_ID; ?>', align:'right', hidden:false, dataIndex:'permissions_menu_id'},
		{header:'<?php print _PERMISSIONS_LIST; ?>', align:'left', hidden:false, dataIndex:'permissions_list'},
		{header:'<?php print _PERMISSIONS_MODIFY; ?>', align:'left', hidden:false, dataIndex:'permissions_modify'},
		{header:'<?php print _PERMISSIONS_CREATE; ?>', align:'left', hidden:false, dataIndex:'permissions_create'},
		{header:'<?php print _PERMISSIONS_DELETE; ?>', align:'left', hidden:false, dataIndex:'permissions_delete'},
		{header:'<?php print _PERMISSIONS_EXPORT; ?>', align:'left', hidden:false, dataIndex:'permissions_export'}
	]
	,defaults:{
		sortable:true
		,width:100
	}
});
var tbPermissions = new Ext.Toolbar();

var gridPermissions = new Ext.grid.GridPanel({
	store:storePermissions
	,id:modulo+'gridPermissions'
	,colModel:cmPermissions
	,viewConfig: {
		forceFit: true
		,scrollOffset:2
	}
	,sm:new Ext.grid.RowSelectionModel({singleSelect:true})
	,bbar:new Ext.PagingToolbar({pageSize:10, store:storePermissions, displayInfo:true})
	,tbar:tbPermissions
	,loadMask:true
	,border:false
	,title:''
	,iconCls:'icon-grid'
	,plugins:[new Ext.ux.grid.Excel()]
});
var formPermissions = new Ext.FormPanel({
	baseCls:'x-panel-mc'
	,method:'POST'
	,baseParams:{accion:'act'}
	,autoWidth:true
	,autoScroll:true
	,trackResetOnLoad:true
	,monitorValid:true
	,bodyStyle:'padding:15px;'
	,reader: new Ext.data.JsonReader({
		root:'datos'
		,totalProperty:'total'
		,fields:[
			{name:'permissions_id', mapping:'permissions_id', type:'float'},
			{name:'permissions_profile_id', mapping:'permissions_profile_id', type:'float'},
			{name:'permissions_menu_id', mapping:'permissions_menu_id', type:'float'},
			{name:'permissions_list', mapping:'permissions_list', type:'string'},
			{name:'permissions_modify', mapping:'permissions_modify', type:'string'},
			{name:'permissions_create', mapping:'permissions_create', type:'string'},
			{name:'permissions_delete', mapping:'permissions_delete', type:'string'},
			{name:'permissions_export', mapping:'permissions_export', type:'string'}
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
				,xtype:'numberfield'
				,name:'permissions_id'
				,fieldLabel:'<?php print _PERMISSIONS_ID; ?>'
				,id:modulo+'permissions_id'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				,xtype:'numberfield'
				,name:'permissions_profile_id'
				,fieldLabel:'<?php print _PERMISSIONS_PROFILE_ID; ?>'
				,id:modulo+'permissions_profile_id'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				,xtype:'numberfield'
				,name:'permissions_menu_id'
				,fieldLabel:'<?php print _PERMISSIONS_MENU_ID; ?>'
				,id:modulo+'permissions_menu_id'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				,xtype:'numberfield'
				,name:'permissions_list'
				,fieldLabel:'<?php print _PERMISSIONS_LIST; ?>'
				,id:modulo+'permissions_list'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				,xtype:'numberfield'
				,name:'permissions_modify'
				,fieldLabel:'<?php print _PERMISSIONS_MODIFY; ?>'
				,id:modulo+'permissions_modify'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				,xtype:'numberfield'
				,name:'permissions_create'
				,fieldLabel:'<?php print _PERMISSIONS_CREATE; ?>'
				,id:modulo+'permissions_create'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				,xtype:'numberfield'
				,name:'permissions_delete'
				,fieldLabel:'<?php print _PERMISSIONS_DELETE; ?>'
				,id:modulo+'permissions_delete'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				,xtype:'numberfield'
				,name:'permissions_export'
				,fieldLabel:'<?php print _PERMISSIONS_EXPORT; ?>'
				,id:modulo+'permissions_export'
				,allowBlank:false
			}]
		}]
	}]
});
