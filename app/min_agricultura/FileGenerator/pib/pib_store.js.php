<?php
session_start();
include_once('../../lib/config.php');
?>
/*<script>*/
var storePib = new Ext.data.JsonStore({
	url:'pib/list'
	,root:'data'
	,sortInfo:{field:'pib_id',direction:'ASC'}
	,totalProperty:'total'
	,baseParams:{id:'<?= $id; ?>'}
	,fields:[
		{name:'pib_id', type:'float'},
		{name:'pib_anio', type:'float'},
		{name:'pib_periodo', type:'float'},
		{name:'pib_valor', type:'string'},
		{name:'pib_finsert', type:'date', dateFormat:'Y-m-d H:i:s'},
		{name:'pib_uinsert', type:'float'},
		{name:'pib_fupdate', type:'date', dateFormat:'Y-m-d H:i:s'},
		{name:'pib_uupdate', type:'float'}
	]
});
var comboPib = new Ext.form.ComboBox({
	hiddenName:'pib'
	,id:module+'comboPib'
	,fieldLabel:'<?= Lang::get('pib.columns_title.pib_uupdate'); ?>'
	,store:storePib
	,valueField:'pib_id'
	,displayField:'pib_name'
	,typeAhead:true
	,forceSelection:true
	,triggerAction:'all'
	,selectOnFocus:true
	,allowBlank:false
	,listeners:{
		select: {
			fn: function(combo,reg){
				Ext.getCmp(module + 'pib_id').setValue(reg.data.pib_id);
			}
		}
	}
});
var cmPib = new Ext.grid.ColumnModel({
	columns:[
		{xtype:'numbercolumn', header:'<?= Lang::get('pib.columns_title.pib_id'); ?>', align:'right', hidden:false, dataIndex:'pib_id'},
		{xtype:'numbercolumn', header:'<?= Lang::get('pib.columns_title.pib_anio'); ?>', align:'right', hidden:false, dataIndex:'pib_anio'},
		{xtype:'numbercolumn', header:'<?= Lang::get('pib.columns_title.pib_periodo'); ?>', align:'right', hidden:false, dataIndex:'pib_periodo'},
		{header:'<?= Lang::get('pib.columns_title.pib_valor'); ?>', align:'left', hidden:false, dataIndex:'pib_valor'},
		{xtype:'datecolumn', header:'<?= Lang::get('pib.columns_title.pib_finsert'); ?>', align:'left', hidden:false, dataIndex:'pib_finsert', format:'Y-m-d, g:i a'},
		{xtype:'numbercolumn', header:'<?= Lang::get('pib.columns_title.pib_uinsert'); ?>', align:'right', hidden:false, dataIndex:'pib_uinsert'},
		{xtype:'datecolumn', header:'<?= Lang::get('pib.columns_title.pib_fupdate'); ?>', align:'left', hidden:false, dataIndex:'pib_fupdate', format:'Y-m-d, g:i a'},
		{xtype:'numbercolumn', header:'<?= Lang::get('pib.columns_title.pib_uupdate'); ?>', align:'right', hidden:false, dataIndex:'pib_uupdate'}
	]
	,defaults:{
		sortable:true
		,width:100
	}
});
var tbPib = new Ext.Toolbar();

var gridPib = new Ext.grid.GridPanel({
	store:storePib
	,id:module+'gridPib'
	,colModel:cmPib
	,viewConfig: {
		forceFit: true
		,scrollOffset:2
	}
	,sm:new Ext.grid.RowSelectionModel({singleSelect:true})
	,bbar:new Ext.PagingToolbar({pageSize:10, store:storePib, displayInfo:true})
	,tbar:tbPib
	,loadMask:true
	,border:false
	,title:''
	,iconCls:'icon-grid'
	,plugins:[new Ext.ux.grid.Excel()]
});
var formPib = new Ext.FormPanel({
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
			{name:'pib_id', mapping:'pib_id', type:'float'},
			{name:'pib_anio', mapping:'pib_anio', type:'float'},
			{name:'pib_periodo', mapping:'pib_periodo', type:'float'},
			{name:'pib_valor', mapping:'pib_valor', type:'string'},
			{name:'pib_finsert', mapping:'pib_finsert', type:'date'},
			{name:'pib_uinsert', mapping:'pib_uinsert', type:'float'},
			{name:'pib_fupdate', mapping:'pib_fupdate', type:'date'},
			{name:'pib_uupdate', mapping:'pib_uupdate', type:'float'}
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
				,name:'pib_id'
				,fieldLabel:'<?= Lang::get('pib.columns_title.pib_id'); ?>'
				,id:module+'pib_id'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'numberfield'
				,name:'pib_anio'
				,fieldLabel:'<?= Lang::get('pib.columns_title.pib_anio'); ?>'
				,id:module+'pib_anio'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'numberfield'
				,name:'pib_periodo'
				,fieldLabel:'<?= Lang::get('pib.columns_title.pib_periodo'); ?>'
				,id:module+'pib_periodo'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'numberfield'
				,name:'pib_valor'
				,fieldLabel:'<?= Lang::get('pib.columns_title.pib_valor'); ?>'
				,id:module+'pib_valor'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'datefield'
				,name:'pib_finsert'
				,fieldLabel:'<?= Lang::get('pib.columns_title.pib_finsert'); ?>'
				,id:module+'pib_finsert'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'numberfield'
				,name:'pib_uinsert'
				,fieldLabel:'<?= Lang::get('pib.columns_title.pib_uinsert'); ?>'
				,id:module+'pib_uinsert'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'datefield'
				,name:'pib_fupdate'
				,fieldLabel:'<?= Lang::get('pib.columns_title.pib_fupdate'); ?>'
				,id:module+'pib_fupdate'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'numberfield'
				,name:'pib_uupdate'
				,fieldLabel:'<?= Lang::get('pib.columns_title.pib_uupdate'); ?>'
				,id:module+'pib_uupdate'
				,allowBlank:false
			}]
		}]
	}]
});
