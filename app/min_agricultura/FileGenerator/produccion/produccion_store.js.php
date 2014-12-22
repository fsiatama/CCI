<?php
session_start();
include_once('../../lib/config.php');
?>
/*<script>*/
var storeProduccion = new Ext.data.JsonStore({
	url:'produccion/list'
	,root:'data'
	,sortInfo:{field:'produccion_id',direction:'ASC'}
	,totalProperty:'total'
	,baseParams:{id:'<?= $id; ?>'}
	,fields:[
		{name:'produccion_id', type:'float'},
		{name:'produccion_sector_id', type:'float'},
		{name:'produccion_anio', type:'float'},
		{name:'produccion_peso_neto', type:'string'},
		{name:'produccion_finsert', type:'date', dateFormat:'Y-m-d H:i:s'},
		{name:'produccion_uinsert', type:'float'},
		{name:'produccion_fupdate', type:'date', dateFormat:'Y-m-d H:i:s'},
		{name:'produccion_uupdate', type:'float'}
	]
});
var comboProduccion = new Ext.form.ComboBox({
	hiddenName:'produccion'
	,id:module+'comboProduccion'
	,fieldLabel:'<?= Lang::get('produccion.columns_title.produccion_uupdate'); ?>'
	,store:storeProduccion
	,valueField:'produccion_id'
	,displayField:'produccion_name'
	,typeAhead:true
	,forceSelection:true
	,triggerAction:'all'
	,selectOnFocus:true
	,allowBlank:false
	,listeners:{
		select: {
			fn: function(combo,reg){
				Ext.getCmp(module + 'produccion_id').setValue(reg.data.produccion_id);
			}
		}
	}
});
var cmProduccion = new Ext.grid.ColumnModel({
	columns:[
		{xtype:'numbercolumn', header:'<?= Lang::get('produccion.columns_title.produccion_id'); ?>', align:'right', hidden:false, dataIndex:'produccion_id'},
		{xtype:'numbercolumn', header:'<?= Lang::get('produccion.columns_title.produccion_sector_id'); ?>', align:'right', hidden:false, dataIndex:'produccion_sector_id'},
		{xtype:'numbercolumn', header:'<?= Lang::get('produccion.columns_title.produccion_anio'); ?>', align:'right', hidden:false, dataIndex:'produccion_anio'},
		{header:'<?= Lang::get('produccion.columns_title.produccion_peso_neto'); ?>', align:'left', hidden:false, dataIndex:'produccion_peso_neto'},
		{xtype:'datecolumn', header:'<?= Lang::get('produccion.columns_title.produccion_finsert'); ?>', align:'left', hidden:false, dataIndex:'produccion_finsert', format:'Y-m-d, g:i a'},
		{xtype:'numbercolumn', header:'<?= Lang::get('produccion.columns_title.produccion_uinsert'); ?>', align:'right', hidden:false, dataIndex:'produccion_uinsert'},
		{xtype:'datecolumn', header:'<?= Lang::get('produccion.columns_title.produccion_fupdate'); ?>', align:'left', hidden:false, dataIndex:'produccion_fupdate', format:'Y-m-d, g:i a'},
		{xtype:'numbercolumn', header:'<?= Lang::get('produccion.columns_title.produccion_uupdate'); ?>', align:'right', hidden:false, dataIndex:'produccion_uupdate'}
	]
	,defaults:{
		sortable:true
		,width:100
	}
});
var tbProduccion = new Ext.Toolbar();

var gridProduccion = new Ext.grid.GridPanel({
	store:storeProduccion
	,id:module+'gridProduccion'
	,colModel:cmProduccion
	,viewConfig: {
		forceFit: true
		,scrollOffset:2
	}
	,sm:new Ext.grid.RowSelectionModel({singleSelect:true})
	,bbar:new Ext.PagingToolbar({pageSize:10, store:storeProduccion, displayInfo:true})
	,tbar:tbProduccion
	,loadMask:true
	,border:false
	,title:''
	,iconCls:'icon-grid'
	,plugins:[new Ext.ux.grid.Excel()]
});
var formProduccion = new Ext.FormPanel({
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
			{name:'produccion_id', mapping:'produccion_id', type:'float'},
			{name:'produccion_sector_id', mapping:'produccion_sector_id', type:'float'},
			{name:'produccion_anio', mapping:'produccion_anio', type:'float'},
			{name:'produccion_peso_neto', mapping:'produccion_peso_neto', type:'string'},
			{name:'produccion_finsert', mapping:'produccion_finsert', type:'date'},
			{name:'produccion_uinsert', mapping:'produccion_uinsert', type:'float'},
			{name:'produccion_fupdate', mapping:'produccion_fupdate', type:'date'},
			{name:'produccion_uupdate', mapping:'produccion_uupdate', type:'float'}
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
				,name:'produccion_id'
				,fieldLabel:'<?= Lang::get('produccion.columns_title.produccion_id'); ?>'
				,id:module+'produccion_id'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'numberfield'
				,name:'produccion_sector_id'
				,fieldLabel:'<?= Lang::get('produccion.columns_title.produccion_sector_id'); ?>'
				,id:module+'produccion_sector_id'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'numberfield'
				,name:'produccion_anio'
				,fieldLabel:'<?= Lang::get('produccion.columns_title.produccion_anio'); ?>'
				,id:module+'produccion_anio'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'numberfield'
				,name:'produccion_peso_neto'
				,fieldLabel:'<?= Lang::get('produccion.columns_title.produccion_peso_neto'); ?>'
				,id:module+'produccion_peso_neto'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'datefield'
				,name:'produccion_finsert'
				,fieldLabel:'<?= Lang::get('produccion.columns_title.produccion_finsert'); ?>'
				,id:module+'produccion_finsert'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'numberfield'
				,name:'produccion_uinsert'
				,fieldLabel:'<?= Lang::get('produccion.columns_title.produccion_uinsert'); ?>'
				,id:module+'produccion_uinsert'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'datefield'
				,name:'produccion_fupdate'
				,fieldLabel:'<?= Lang::get('produccion.columns_title.produccion_fupdate'); ?>'
				,id:module+'produccion_fupdate'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'numberfield'
				,name:'produccion_uupdate'
				,fieldLabel:'<?= Lang::get('produccion.columns_title.produccion_uupdate'); ?>'
				,id:module+'produccion_uupdate'
				,allowBlank:false
			}]
		}]
	}]
});
