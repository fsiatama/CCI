<?php
session_start();
include_once('../../lib/config.php');
?>
/*<script>*/
var storeUser = new Ext.data.JsonStore({
	url:'user/list'
	,root:'data'
	,sortInfo:{field:'user_id',direction:'ASC'}
	,totalProperty:'total'
	,baseParams:{id:'<?= $id; ?>'}
	,fields:[
		{name:'user_id', type:'float'},
		{name:'user_full_name', type:'string'},
		{name:'user_email', type:'string'},
		{name:'user_password', type:'string'},
		{name:'user_active', type:'string'},
		{name:'user_profile_id', type:'float'},
		{name:'user_uinsert', type:'float'},
		{name:'user_finsert', type:'date', dateFormat:'Y-m-d H:i:s'},
		{name:'user_uupdate', type:'float'},
		{name:'user_fupdate', type:'date', dateFormat:'Y-m-d H:i:s'}
	]
});
var comboUser = new Ext.form.ComboBox({
	hiddenName:'user'
	,id:module+'comboUser'
	,fieldLabel:'<?= Lang::get('user.columns_title.user_fupdate'); ?>'
	,store:storeUser
	,valueField:'user_id'
	,displayField:'user_name'
	,typeAhead:true
	,forceSelection:true
	,triggerAction:'all'
	,selectOnFocus:true
	,listeners:{
		select: {
			fn: function(combo,reg){
				Ext.getCmp(module + 'user_id').setValue(reg.data.user_id);
			}
		}
	}
});
var cmUser = new Ext.grid.ColumnModel({
	columns:[
		{xtype:'numbercolumn', header:'<?= Lang::get('user.columns_title.user_id'); ?>', align:'right', hidden:false, dataIndex:'user_id'},
		{header:'<?= Lang::get('user.columns_title.user_full_name'); ?>', align:'left', hidden:false, dataIndex:'user_full_name'},
		{header:'<?= Lang::get('user.columns_title.user_email'); ?>', align:'left', hidden:false, dataIndex:'user_email'},
		{header:'<?= Lang::get('user.columns_title.user_password'); ?>', align:'left', hidden:false, dataIndex:'user_password'},
		{header:'<?= Lang::get('user.columns_title.user_active'); ?>', align:'left', hidden:false, dataIndex:'user_active'},
		{xtype:'numbercolumn', header:'<?= Lang::get('user.columns_title.user_profile_id'); ?>', align:'right', hidden:false, dataIndex:'user_profile_id'},
		{xtype:'numbercolumn', header:'<?= Lang::get('user.columns_title.user_uinsert'); ?>', align:'right', hidden:false, dataIndex:'user_uinsert'},
		{xtype:'datecolumn', header:'<?= Lang::get('user.columns_title.user_finsert'); ?>', align:'left', hidden:false, dataIndex:'user_finsert', format:'Y-m-d, g:i a'},
		{xtype:'numbercolumn', header:'<?= Lang::get('user.columns_title.user_uupdate'); ?>', align:'right', hidden:false, dataIndex:'user_uupdate'},
		{xtype:'datecolumn', header:'<?= Lang::get('user.columns_title.user_fupdate'); ?>', align:'left', hidden:false, dataIndex:'user_fupdate', format:'Y-m-d, g:i a'}
	]
	,defaults:{
		sortable:true
		,width:100
	}
});
var tbUser = new Ext.Toolbar();

var gridUser = new Ext.grid.GridPanel({
	store:storeUser
	,id:module+'gridUser'
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
		root:'data'
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
			{name:'user_uupdate', mapping:'user_uupdate', type:'float'},
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
				xtype:'numberfield'
				,name:'user_id'
				,fieldLabel:'<?= Lang::get('user.columns_title.user_id'); ?>'
				,id:module+'user_id'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'textfield'
				,name:'user_full_name'
				,fieldLabel:'<?= Lang::get('user.columns_title.user_full_name'); ?>'
				,id:module+'user_full_name'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'textfield'
				,name:'user_email'
				,fieldLabel:'<?= Lang::get('user.columns_title.user_email'); ?>'
				,id:module+'user_email'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'textfield'
				,name:'user_password'
				,fieldLabel:'<?= Lang::get('user.columns_title.user_password'); ?>'
				,id:module+'user_password'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'textfield'
				,name:'user_active'
				,fieldLabel:'<?= Lang::get('user.columns_title.user_active'); ?>'
				,id:module+'user_active'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'numberfield'
				,name:'user_profile_id'
				,fieldLabel:'<?= Lang::get('user.columns_title.user_profile_id'); ?>'
				,id:module+'user_profile_id'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'numberfield'
				,name:'user_uinsert'
				,fieldLabel:'<?= Lang::get('user.columns_title.user_uinsert'); ?>'
				,id:module+'user_uinsert'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'datefield'
				,name:'user_finsert'
				,fieldLabel:'<?= Lang::get('user.columns_title.user_finsert'); ?>'
				,id:module+'user_finsert'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'numberfield'
				,name:'user_uupdate'
				,fieldLabel:'<?= Lang::get('user.columns_title.user_uupdate'); ?>'
				,id:module+'user_uupdate'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'datefield'
				,name:'user_fupdate'
				,fieldLabel:'<?= Lang::get('user.columns_title.user_fupdate'); ?>'
				,id:module+'user_fupdate'
				,allowBlank:false
			}]
		}]
	}]
});
