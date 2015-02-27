<?php
session_start();
include_once('../../lib/config.php');
?>
/*<script>*/
var storeMercado = new Ext.data.JsonStore({
	url:'mercado/list'
	,root:'data'
	,sortInfo:{field:'mercado_id',direction:'ASC'}
	,totalProperty:'total'
	,baseParams:{id:'<?= $id; ?>'}
	,fields:[
		{name:'mercado_id', type:'float'},
		{name:'mercado_nombre', type:'string'},
		{name:'mercado_paises', type:'string'},
		{name:'mercado_bandera', type:'string'},
		{name:'mercado_uinsert', type:'float'},
		{name:'mercado_finsert', type:'date', dateFormat:'Y-m-d H:i:s'},
		{name:'mercado_uupdate', type:'float'},
		{name:'mercado_fupdate', type:'date', dateFormat:'Y-m-d H:i:s'}
	]
});
var comboMercado = new Ext.form.ComboBox({
	hiddenName:'mercado'
	,id:module+'comboMercado'
	,fieldLabel:'<?= Lang::get('mercado.columns_title.mercado_fupdate'); ?>'
	,store:storeMercado
	,valueField:'mercado_id'
	,displayField:'mercado_name'
	,typeAhead:true
	,forceSelection:true
	,triggerAction:'all'
	,selectOnFocus:true
	,allowBlank:false
	,listeners:{
		select: {
			fn: function(combo,reg){
				Ext.getCmp(module + 'mercado_id').setValue(reg.data.mercado_id);
			}
		}
	}
});
var cmMercado = new Ext.grid.ColumnModel({
	columns:[
		{xtype:'numbercolumn', header:'<?= Lang::get('mercado.columns_title.mercado_id'); ?>', align:'right', hidden:false, dataIndex:'mercado_id'},
		{header:'<?= Lang::get('mercado.columns_title.mercado_nombre'); ?>', align:'left', hidden:false, dataIndex:'mercado_nombre'},
		{header:'<?= Lang::get('mercado.columns_title.mercado_paises'); ?>', align:'left', hidden:false, dataIndex:'mercado_paises'},
		{header:'<?= Lang::get('mercado.columns_title.mercado_bandera'); ?>', align:'left', hidden:false, dataIndex:'mercado_bandera'},
		{xtype:'numbercolumn', header:'<?= Lang::get('mercado.columns_title.mercado_uinsert'); ?>', align:'right', hidden:false, dataIndex:'mercado_uinsert'},
		{xtype:'datecolumn', header:'<?= Lang::get('mercado.columns_title.mercado_finsert'); ?>', align:'left', hidden:false, dataIndex:'mercado_finsert', format:'Y-m-d, g:i a'},
		{xtype:'numbercolumn', header:'<?= Lang::get('mercado.columns_title.mercado_uupdate'); ?>', align:'right', hidden:false, dataIndex:'mercado_uupdate'},
		{xtype:'datecolumn', header:'<?= Lang::get('mercado.columns_title.mercado_fupdate'); ?>', align:'left', hidden:false, dataIndex:'mercado_fupdate', format:'Y-m-d, g:i a'}
	]
	,defaults:{
		sortable:true
		,width:100
	}
});
var tbMercado = new Ext.Toolbar();

var gridMercado = new Ext.grid.GridPanel({
	store:storeMercado
	,id:module+'gridMercado'
	,colModel:cmMercado
	,viewConfig: {
		forceFit: true
		,scrollOffset:2
	}
	,sm:new Ext.grid.RowSelectionModel({singleSelect:true})
	,bbar:new Ext.PagingToolbar({pageSize:10, store:storeMercado, displayInfo:true})
	,tbar:tbMercado
	,loadMask:true
	,border:false
	,title:''
	,iconCls:'icon-grid'
	,plugins:[new Ext.ux.grid.Excel()]
});
var formMercado = new Ext.FormPanel({
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
			{name:'mercado_id', mapping:'mercado_id', type:'float'},
			{name:'mercado_nombre', mapping:'mercado_nombre', type:'string'},
			{name:'mercado_paises', mapping:'mercado_paises', type:'string'},
			{name:'mercado_bandera', mapping:'mercado_bandera', type:'string'},
			{name:'mercado_uinsert', mapping:'mercado_uinsert', type:'float'},
			{name:'mercado_finsert', mapping:'mercado_finsert', type:'date'},
			{name:'mercado_uupdate', mapping:'mercado_uupdate', type:'float'},
			{name:'mercado_fupdate', mapping:'mercado_fupdate', type:'date'}
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
				,name:'mercado_id'
				,fieldLabel:'<?= Lang::get('mercado.columns_title.mercado_id'); ?>'
				,id:module+'mercado_id'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'textfield'
				,name:'mercado_nombre'
				,fieldLabel:'<?= Lang::get('mercado.columns_title.mercado_nombre'); ?>'
				,id:module+'mercado_nombre'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'textfield'
				,name:'mercado_paises'
				,fieldLabel:'<?= Lang::get('mercado.columns_title.mercado_paises'); ?>'
				,id:module+'mercado_paises'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'textfield'
				,name:'mercado_bandera'
				,fieldLabel:'<?= Lang::get('mercado.columns_title.mercado_bandera'); ?>'
				,id:module+'mercado_bandera'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'numberfield'
				,name:'mercado_uinsert'
				,fieldLabel:'<?= Lang::get('mercado.columns_title.mercado_uinsert'); ?>'
				,id:module+'mercado_uinsert'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'datefield'
				,name:'mercado_finsert'
				,fieldLabel:'<?= Lang::get('mercado.columns_title.mercado_finsert'); ?>'
				,id:module+'mercado_finsert'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'numberfield'
				,name:'mercado_uupdate'
				,fieldLabel:'<?= Lang::get('mercado.columns_title.mercado_uupdate'); ?>'
				,id:module+'mercado_uupdate'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'datefield'
				,name:'mercado_fupdate'
				,fieldLabel:'<?= Lang::get('mercado.columns_title.mercado_fupdate'); ?>'
				,id:module+'mercado_fupdate'
				,allowBlank:false
			}]
		}]
	}]
});
