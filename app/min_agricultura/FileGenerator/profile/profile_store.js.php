<?php
session_start();
include_once('../../lib/config.php');
?>
/*<script>*/
var storeProfile = new Ext.data.JsonStore({
	url:'profile/list'
	,root:'data'
	,sortInfo:{field:'profile_id',direction:'ASC'}
	,totalProperty:'total'
	,fields:[
		{name:'profile_id', type:'float'},
		{name:'profile_name', type:'string'}
	]
});
var comboProfile = new Ext.form.ComboBox({
	hiddenName:'profile'
	,id:module+'comboProfile'
	,fieldLabel:'<?= Lang::get('profile.columns_title.profile_name'); ?>'
	,store:storeProfile
	,valueField:'profile_id'
	,displayField:'profile_nombre'
	,typeAhead:true
	,forceSelection:true
	,triggerAction:'all'
	,selectOnFocus:true
	,listeners:{
		select: {
			fn: function(combo,reg){
				Ext.getCmp('profile_id').setValue(reg.data.profile_id);
			}
		}
	}
});
var cmProfile = new Ext.grid.ColumnModel({
	columns:[
		{xtype:'numbercolumn', header:'<?= Lang::get('profile.columns_title.profile_id'); ?>', align:'right', hidden:false, dataIndex:'profile_id'},
		{header:'<?= Lang::get('profile.columns_title.profile_name'); ?>', align:'left', hidden:false, dataIndex:'profile_name'}
	]
	,defaults:{
		sortable:true
		,width:100
	}
});
var tbProfile = new Ext.Toolbar();

var gridProfile = new Ext.grid.GridPanel({
	store:storeProfile
	,id:module+'gridProfile'
	,colModel:cmProfile
	,viewConfig: {
		forceFit: true
		,scrollOffset:2
	}
	,sm:new Ext.grid.RowSelectionModel({singleSelect:true})
	,bbar:new Ext.PagingToolbar({pageSize:10, store:storeProfile, displayInfo:true})
	,tbar:tbProfile
	,loadMask:true
	,border:false
	,title:''
	,iconCls:'icon-grid'
	,plugins:[new Ext.ux.grid.Excel()]
});
var formProfile = new Ext.FormPanel({
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
			{name:'profile_id', mapping:'profile_id', type:'float'},
			{name:'profile_name', mapping:'profile_name', type:'string'}
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
				,name:'profile_id'
				,fieldLabel:'<?= Lang::get('profile.columns_title.profile_id'); ?>'
				,id:module+'profile_id'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'textfield'
				,name:'profile_name'
				,fieldLabel:'<?= Lang::get('profile.columns_title.profile_name'); ?>'
				,id:module+'profile_name'
				,allowBlank:false
			}]
		}]
	}]
});
