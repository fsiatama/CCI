<?php
session_start();
include_once('../../lib/config.php');
?>
/*<script>*/
var storeCategory_menu = new Ext.data.JsonStore({
	url:'proceso/category_menu/'
	,root:'datos'
	,sortInfo:{field:'category_menu_id',direction:'ASC'}
	,totalProperty:'total'
	,baseParams:{accion:'lista'}
	,fields:[
		{name:'category_menu_id', type:'float'},
		{name:'category_menu_name', type:'string'},
		{name:'category_menu_order', type:'float'}
	]
});
var comboCategory_menu = new Ext.form.ComboBox({
	hiddenName:'category_menu'
	,id:modulo+'comboCategory_menu'
	,fieldLabel:'<?php print _CATEGORY_MENU; ?>'
	,store:storeCategory_menu
	,valueField:'category_menu_id'
	,displayField:'category_menu_nombre'
	,typeAhead:true
	,forceSelection:true
	,triggerAction:'all'
	,selectOnFocus:true
});
var cmCategory_menu = new Ext.grid.ColumnModel({
	columns:[
		{xtype:'numbercolumn', header:'<?php print _CATEGORY_MENU_ID; ?>', align:'right', hidden:false, dataIndex:'category_menu_id'},
		{header:'<?php print _CATEGORY_MENU_NAME; ?>', align:'left', hidden:false, dataIndex:'category_menu_name'},
		{xtype:'numbercolumn', header:'<?php print _CATEGORY_MENU_ORDER; ?>', align:'right', hidden:false, dataIndex:'category_menu_order'}
	]
	,defaults:{
		sortable:true
		,width:100
	}
});
var tbCategory_menu = new Ext.Toolbar();

var gridCategory_menu = new Ext.grid.GridPanel({
	store:storeCategory_menu
	,id:modulo+'gridCategory_menu'
	,colModel:cmCategory_menu
	,viewConfig: {
		forceFit: true
		,scrollOffset:2
	}
	,sm:new Ext.grid.RowSelectionModel({singleSelect:true})
	,bbar:new Ext.PagingToolbar({pageSize:10, store:storeCategory_menu, displayInfo:true})
	,tbar:tbCategory_menu
	,loadMask:true
	,border:false
	,title:''
	,iconCls:'icon-grid'
	,plugins:[new Ext.ux.grid.Excel()]
});
var formCategory_menu = new Ext.FormPanel({
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
			{name:'category_menu_id', mapping:'category_menu_id', type:'float'},
			{name:'category_menu_name', mapping:'category_menu_name', type:'string'},
			{name:'category_menu_order', mapping:'category_menu_order', type:'float'}
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
				,name:'category_menu_id'
				,fieldLabel:'<?php print _CATEGORY_MENU_ID; ?>'
				,id:modulo+'category_menu_id'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				,xtype:'textfield'
				,name:'category_menu_name'
				,fieldLabel:'<?php print _CATEGORY_MENU_NAME; ?>'
				,id:modulo+'category_menu_name'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				,xtype:'numberfield'
				,name:'category_menu_order'
				,fieldLabel:'<?php print _CATEGORY_MENU_ORDER; ?>'
				,id:modulo+'category_menu_order'
				,allowBlank:false
			}]
		}]
	}]
});
