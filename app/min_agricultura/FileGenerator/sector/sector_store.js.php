<?php
session_start();
include_once('../../lib/config.php');
?>
/*<script>*/
var storeSector = new Ext.data.JsonStore({
	url:'sector/list'
	,root:'data'
	,sortInfo:{field:'sector_id',direction:'ASC'}
	,totalProperty:'total'
	,baseParams:{id:'<?= $id; ?>'}
	,fields:[
		{name:'sector_id', type:'float'},
		{name:'sector_nombre', type:'string'},
		{name:'sector_productos', type:'string'},
		{name:'sector_uinsert', type:'float'},
		{name:'sector_finsert', type:'date', dateFormat:'Y-m-d H:i:s'},
		{name:'sector_uupdate', type:'float'},
		{name:'sector_fupdate', type:'date', dateFormat:'Y-m-d H:i:s'}
	]
});
var comboSector = new Ext.form.ComboBox({
	hiddenName:'sector'
	,id:module+'comboSector'
	,fieldLabel:'<?= Lang::get('sector.columns_title.sector_fupdate'); ?>'
	,store:storeSector
	,valueField:'sector_id'
	,displayField:'sector_name'
	,typeAhead:true
	,forceSelection:true
	,triggerAction:'all'
	,selectOnFocus:true
	,allowBlank:false
	,listeners:{
		select: {
			fn: function(combo,reg){
				Ext.getCmp(module + 'sector_id').setValue(reg.data.sector_id);
			}
		}
	}
});
var cmSector = new Ext.grid.ColumnModel({
	columns:[
		{xtype:'numbercolumn', header:'<?= Lang::get('sector.columns_title.sector_id'); ?>', align:'right', hidden:false, dataIndex:'sector_id'},
		{header:'<?= Lang::get('sector.columns_title.sector_nombre'); ?>', align:'left', hidden:false, dataIndex:'sector_nombre'},
		{header:'<?= Lang::get('sector.columns_title.sector_productos'); ?>', align:'left', hidden:false, dataIndex:'sector_productos'},
		{xtype:'numbercolumn', header:'<?= Lang::get('sector.columns_title.sector_uinsert'); ?>', align:'right', hidden:false, dataIndex:'sector_uinsert'},
		{xtype:'datecolumn', header:'<?= Lang::get('sector.columns_title.sector_finsert'); ?>', align:'left', hidden:false, dataIndex:'sector_finsert', format:'Y-m-d, g:i a'},
		{xtype:'numbercolumn', header:'<?= Lang::get('sector.columns_title.sector_uupdate'); ?>', align:'right', hidden:false, dataIndex:'sector_uupdate'},
		{xtype:'datecolumn', header:'<?= Lang::get('sector.columns_title.sector_fupdate'); ?>', align:'left', hidden:false, dataIndex:'sector_fupdate', format:'Y-m-d, g:i a'}
	]
	,defaults:{
		sortable:true
		,width:100
	}
});
var tbSector = new Ext.Toolbar();

var gridSector = new Ext.grid.GridPanel({
	store:storeSector
	,id:module+'gridSector'
	,colModel:cmSector
	,viewConfig: {
		forceFit: true
		,scrollOffset:2
	}
	,sm:new Ext.grid.RowSelectionModel({singleSelect:true})
	,bbar:new Ext.PagingToolbar({pageSize:10, store:storeSector, displayInfo:true})
	,tbar:tbSector
	,loadMask:true
	,border:false
	,title:''
	,iconCls:'icon-grid'
	,plugins:[new Ext.ux.grid.Excel()]
});
var formSector = new Ext.FormPanel({
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
			{name:'sector_id', mapping:'sector_id', type:'float'},
			{name:'sector_nombre', mapping:'sector_nombre', type:'string'},
			{name:'sector_productos', mapping:'sector_productos', type:'string'},
			{name:'sector_uinsert', mapping:'sector_uinsert', type:'float'},
			{name:'sector_finsert', mapping:'sector_finsert', type:'date'},
			{name:'sector_uupdate', mapping:'sector_uupdate', type:'float'},
			{name:'sector_fupdate', mapping:'sector_fupdate', type:'date'}
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
				,name:'sector_id'
				,fieldLabel:'<?= Lang::get('sector.columns_title.sector_id'); ?>'
				,id:module+'sector_id'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'textfield'
				,name:'sector_nombre'
				,fieldLabel:'<?= Lang::get('sector.columns_title.sector_nombre'); ?>'
				,id:module+'sector_nombre'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'textfield'
				,name:'sector_productos'
				,fieldLabel:'<?= Lang::get('sector.columns_title.sector_productos'); ?>'
				,id:module+'sector_productos'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'numberfield'
				,name:'sector_uinsert'
				,fieldLabel:'<?= Lang::get('sector.columns_title.sector_uinsert'); ?>'
				,id:module+'sector_uinsert'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'datefield'
				,name:'sector_finsert'
				,fieldLabel:'<?= Lang::get('sector.columns_title.sector_finsert'); ?>'
				,id:module+'sector_finsert'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'numberfield'
				,name:'sector_uupdate'
				,fieldLabel:'<?= Lang::get('sector.columns_title.sector_uupdate'); ?>'
				,id:module+'sector_uupdate'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'datefield'
				,name:'sector_fupdate'
				,fieldLabel:'<?= Lang::get('sector.columns_title.sector_fupdate'); ?>'
				,id:module+'sector_fupdate'
				,allowBlank:false
			}]
		}]
	}]
});
