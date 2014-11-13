<?php
session_start();
include_once('../../lib/config.php');
?>
/*<script>*/
var storeMenu = new Ext.data.JsonStore({
	url:'proceso/menu/'
	,root:'datos'
	,sortInfo:{field:'menu_id',direction:'ASC'}
	,totalProperty:'total'
	,baseParams:{accion:'lista'}
	,fields:[
		{name:'menu_id', type:'float'},
		{name:'menu_name', type:'string'},
		{name:'menu_category_menu_id', type:'float'},
		{name:'menu_url', type:'string'},
		{name:'menu_order', type:'float'},
		{name:'menu_hidden', type:'string'}
	]
});
var comboMenu = new Ext.form.ComboBox({
	hiddenName:'menu'
	,id:modulo+'comboMenu'
	,fieldLabel:'<?php print _MENU; ?>'
	,store:storeMenu
	,valueField:'menu_id'
	,displayField:'menu_nombre'
	,typeAhead:true
	,forceSelection:true
	,triggerAction:'all'
	,selectOnFocus:true
});
var cmMenu = new Ext.grid.ColumnModel({
	columns:[
		{xtype:'numbercolumn', header:'<?php print _MENU_ID; ?>', align:'right', hidden:false, dataIndex:'menu_id'},
		{header:'<?php print _MENU_NAME; ?>', align:'left', hidden:false, dataIndex:'menu_name'},
		{xtype:'numbercolumn', header:'<?php print _MENU_CATEGORY_MENU_ID; ?>', align:'right', hidden:false, dataIndex:'menu_category_menu_id'},
		{header:'<?php print _MENU_URL; ?>', align:'left', hidden:false, dataIndex:'menu_url'},
		{xtype:'numbercolumn', header:'<?php print _MENU_ORDER; ?>', align:'right', hidden:false, dataIndex:'menu_order'},
		{header:'<?php print _MENU_HIDDEN; ?>', align:'left', hidden:false, dataIndex:'menu_hidden'}
	]
	,defaults:{
		sortable:true
		,width:100
	}
});
var tbMenu = new Ext.Toolbar();

var gridMenu = new Ext.grid.GridPanel({
	store:storeMenu
	,id:modulo+'gridMenu'
	,colModel:cmMenu
	,viewConfig: {
		forceFit: true
		,scrollOffset:2
	}
	,sm:new Ext.grid.RowSelectionModel({singleSelect:true})
	,bbar:new Ext.PagingToolbar({pageSize:10, store:storeMenu, displayInfo:true})
	,tbar:tbMenu
	,loadMask:true
	,border:false
	,title:''
	,iconCls:'icon-grid'
	,plugins:[new Ext.ux.grid.Excel()]
});
var formMenu = new Ext.FormPanel({
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
			{name:'menu_id', mapping:'menu_id', type:'float'},
			{name:'menu_name', mapping:'menu_name', type:'string'},
			{name:'menu_category_menu_id', mapping:'menu_category_menu_id', type:'float'},
			{name:'menu_url', mapping:'menu_url', type:'string'},
			{name:'menu_order', mapping:'menu_order', type:'float'},
			{name:'menu_hidden', mapping:'menu_hidden', type:'string'}
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
				,name:'menu_id'
				,fieldLabel:'<?php print _MENU_ID; ?>'
				,id:modulo+'menu_id'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				,xtype:'textfield'
				,name:'menu_name'
				,fieldLabel:'<?php print _MENU_NAME; ?>'
				,id:modulo+'menu_name'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				,xtype:'numberfield'
				,name:'menu_category_menu_id'
				,fieldLabel:'<?php print _MENU_CATEGORY_MENU_ID; ?>'
				,id:modulo+'menu_category_menu_id'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				,xtype:'textfield'
				,name:'menu_url'
				,fieldLabel:'<?php print _MENU_URL; ?>'
				,id:modulo+'menu_url'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				,xtype:'numberfield'
				,name:'menu_order'
				,fieldLabel:'<?php print _MENU_ORDER; ?>'
				,id:modulo+'menu_order'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				,xtype:'numberfield'
				,name:'menu_hidden'
				,fieldLabel:'<?php print _MENU_HIDDEN; ?>'
				,id:modulo+'menu_hidden'
				,allowBlank:false
			}]
		}]
	}]
});
