<?php
session_start();
include_once('../../lib/config.php');
?>
/*<script>*/
var storeUser = new Ext.data.JsonStore({
	url:'proceso/user/'
	,root:'datos'
	,sortInfo:{field:'user_id',direction:'ASC'}
	,totalProperty:'total'
	,baseParams:{accion:'lista'}
	,fields:[
		{name:'user_id', type:'float'},
		{name:'user_full_name', type:'string'},
		{name:'user_email', type:'string'},
		{name:'user_password', type:'string'},
		{name:'user_active', type:'string'},
		{name:'user_profile_id', type:'float'},
		{name:'user_uinsert', type:'float'},
		{name:'user_finsert', type:'date', dateFormat:'Y-m-d H:i:s'},
		{name:'user_fupdate', type:'date', dateFormat:'Y-m-d H:i:s'}
	]
});
var comboUser = new Ext.form.ComboBox({
	hiddenName:'user'
	,id:modulo+'comboUser'
	,fieldLabel:'<?php print _USER; ?>'
	,store:storeUser
	,valueField:'user_id'
	,displayField:'user_nombre'
	,typeAhead:true
	,forceSelection:true
	,triggerAction:'all'
	,selectOnFocus:true
});
var cmUser = new Ext.grid.ColumnModel({
	columns:[
		{xtype:'numbercolumn', header:'<?php print _USER_ID; ?>', align:'right', hidden:false, dataIndex:'user_id'},
		{header:'<?php print _USER_FULL_NAME; ?>', align:'left', hidden:false, dataIndex:'user_full_name'},
		{header:'<?php print _USER_EMAIL; ?>', align:'left', hidden:false, dataIndex:'user_email'},
		{header:'<?php print _USER_PASSWORD; ?>', align:'left', hidden:false, dataIndex:'user_password'},
		{header:'<?php print _USER_ACTIVE; ?>', align:'left', hidden:false, dataIndex:'user_active'},
		{xtype:'numbercolumn', header:'<?php print _USER_PROFILE_ID; ?>', align:'right', hidden:false, dataIndex:'user_profile_id'},
		{xtype:'numbercolumn', header:'<?php print _USER_UINSERT; ?>', align:'right', hidden:false, dataIndex:'user_uinsert'},
		{xtype:'datecolumn', header:'<?php print _USER_FINSERT; ?>', align:'left', hidden:false, dataIndex:'user_finsert', format:'Y-m-d, g:i a'},
		{xtype:'datecolumn', header:'<?php print _USER_FUPDATE; ?>', align:'left', hidden:false, dataIndex:'user_fupdate', format:'Y-m-d, g:i a'}
	]
	,defaults:{
		sortable:true
		,width:100
	}
});
var tbUser = new Ext.Toolbar();

var gridUser = new Ext.grid.GridPanel({
	store:storeUser
	,id:modulo+'gridUser'
	,colModel:cmUser
	,viewConfig: {
		forceFit: true
		,scrollOffset:2
	}
	,sm:new Ext.grid.RowSelectionModel({singleSelect:true})
	,bbar:new Ext.PagingToolbar({pageSize:10, store:storeUser, displayInfo:true})
	,tbar:tbUser
	,loadMask:true
	,border:false
	,title:''
	,iconCls:'icon-grid'
	,plugins:[new Ext.ux.grid.Excel()]
});
var formUser = new Ext.FormPanel({
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
			{name:'user_id', mapping:'user_id', type:'float'},
			{name:'user_full_name', mapping:'user_full_name', type:'string'},
			{name:'user_email', mapping:'user_email', type:'string'},
			{name:'user_password', mapping:'user_password', type:'string'},
			{name:'user_active', mapping:'user_active', type:'string'},
			{name:'user_profile_id', mapping:'user_profile_id', type:'float'},
			{name:'user_uinsert', mapping:'user_uinsert', type:'float'},
			{name:'user_finsert', mapping:'user_finsert', type:'date'},
			{name:'user_fupdate', mapping:'user_fupdate', type:'date'}
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
				,name:'user_id'
				,fieldLabel:'<?php print _USER_ID; ?>'
				,id:modulo+'user_id'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				,xtype:'textfield'
				,name:'user_full_name'
				,fieldLabel:'<?php print _USER_FULL_NAME; ?>'
				,id:modulo+'user_full_name'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				,xtype:'textfield'
				,name:'user_email'
				,fieldLabel:'<?php print _USER_EMAIL; ?>'
				,id:modulo+'user_email'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				,xtype:'textfield'
				,name:'user_password'
				,fieldLabel:'<?php print _USER_PASSWORD; ?>'
				,id:modulo+'user_password'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				,xtype:'textfield'
				,name:'user_active'
				,fieldLabel:'<?php print _USER_ACTIVE; ?>'
				,id:modulo+'user_active'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				,xtype:'numberfield'
				,name:'user_profile_id'
				,fieldLabel:'<?php print _USER_PROFILE_ID; ?>'
				,id:modulo+'user_profile_id'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				,xtype:'numberfield'
				,name:'user_uinsert'
				,fieldLabel:'<?php print _USER_UINSERT; ?>'
				,id:modulo+'user_uinsert'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				,xtype:'datefield'
				,name:'user_finsert'
				,fieldLabel:'<?php print _USER_FINSERT; ?>'
				,id:modulo+'user_finsert'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				,xtype:'datefield'
				,name:'user_fupdate'
				,fieldLabel:'<?php print _USER_FUPDATE; ?>'
				,id:modulo+'user_fupdate'
				,allowBlank:false
			}]
		}]
	}]
});
