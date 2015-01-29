<?php
session_start();
include_once('../../lib/config.php');
?>
/*<script>*/
var storeUpdate_info = new Ext.data.JsonStore({
	url:'update_info/list'
	,root:'data'
	,sortInfo:{field:'update_info_id',direction:'ASC'}
	,totalProperty:'total'
	,baseParams:{id:'<?= $id; ?>'}
	,fields:[
		{name:'update_info_id', type:'float'},
		{name:'update_info_product', type:'string'},
		{name:'update_info_trade', type:'string'},
		{name:'update_info_from', type:'string', dateFormat:'Y-m-d'},
		{name:'update_info_to', type:'string', dateFormat:'Y-m-d'}
	]
});
var comboUpdate_info = new Ext.form.ComboBox({
	hiddenName:'update_info'
	,id:module+'comboUpdate_info'
	,fieldLabel:'<?= Lang::get('update_info.columns_title.update_info_to'); ?>'
	,store:storeUpdate_info
	,valueField:'update_info_id'
	,displayField:'update_info_name'
	,typeAhead:true
	,forceSelection:true
	,triggerAction:'all'
	,selectOnFocus:true
	,allowBlank:false
	,listeners:{
		select: {
			fn: function(combo,reg){
				Ext.getCmp(module + 'update_info_id').setValue(reg.data.update_info_id);
			}
		}
	}
});
var cmUpdate_info = new Ext.grid.ColumnModel({
	columns:[
		{xtype:'numbercolumn', header:'<?= Lang::get('update_info.columns_title.update_info_id'); ?>', align:'right', hidden:false, dataIndex:'update_info_id'},
		{header:'<?= Lang::get('update_info.columns_title.update_info_product'); ?>', align:'left', hidden:false, dataIndex:'update_info_product'},
		{header:'<?= Lang::get('update_info.columns_title.update_info_trade'); ?>', align:'left', hidden:false, dataIndex:'update_info_trade'},
		{xtype:'datecolumn', header:'<?= Lang::get('update_info.columns_title.update_info_from'); ?>', align:'left', hidden:false, dataIndex:'update_info_from', format:'Y-m-d'},
		{xtype:'datecolumn', header:'<?= Lang::get('update_info.columns_title.update_info_to'); ?>', align:'left', hidden:false, dataIndex:'update_info_to', format:'Y-m-d'}
	]
	,defaults:{
		sortable:true
		,width:100
	}
});
var tbUpdate_info = new Ext.Toolbar();

var gridUpdate_info = new Ext.grid.GridPanel({
	store:storeUpdate_info
	,id:module+'gridUpdate_info'
	,colModel:cmUpdate_info
	,viewConfig: {
		forceFit: true
		,scrollOffset:2
	}
	,sm:new Ext.grid.RowSelectionModel({singleSelect:true})
	,bbar:new Ext.PagingToolbar({pageSize:10, store:storeUpdate_info, displayInfo:true})
	,tbar:tbUpdate_info
	,loadMask:true
	,border:false
	,title:''
	,iconCls:'icon-grid'
	,plugins:[new Ext.ux.grid.Excel()]
});
var formUpdate_info = new Ext.FormPanel({
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
			{name:'update_info_id', mapping:'update_info_id', type:'float'},
			{name:'update_info_product', mapping:'update_info_product', type:'string'},
			{name:'update_info_trade', mapping:'update_info_trade', type:'string'},
			{name:'update_info_from', mapping:'update_info_from', type:'string'},
			{name:'update_info_to', mapping:'update_info_to', type:'string'}
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
				,name:'update_info_id'
				,fieldLabel:'<?= Lang::get('update_info.columns_title.update_info_id'); ?>'
				,id:module+'update_info_id'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'numberfield'
				,name:'update_info_product'
				,fieldLabel:'<?= Lang::get('update_info.columns_title.update_info_product'); ?>'
				,id:module+'update_info_product'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'numberfield'
				,name:'update_info_trade'
				,fieldLabel:'<?= Lang::get('update_info.columns_title.update_info_trade'); ?>'
				,id:module+'update_info_trade'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'datefield'
				,name:'update_info_from'
				,fieldLabel:'<?= Lang::get('update_info.columns_title.update_info_from'); ?>'
				,id:module+'update_info_from'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'datefield'
				,name:'update_info_to'
				,fieldLabel:'<?= Lang::get('update_info.columns_title.update_info_to'); ?>'
				,id:module+'update_info_to'
				,allowBlank:false
			}]
		}]
	}]
});
