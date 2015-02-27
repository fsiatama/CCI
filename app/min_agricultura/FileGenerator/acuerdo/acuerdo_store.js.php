<?php
session_start();
include_once('../../lib/config.php');
?>
/*<script>*/
var storeAcuerdo = new Ext.data.JsonStore({
	url:'acuerdo/list'
	,root:'data'
	,sortInfo:{field:'acuerdo_id',direction:'ASC'}
	,totalProperty:'total'
	,baseParams:{id:'<?= $id; ?>'}
	,fields:[
		{name:'acuerdo_id', type:'float'},
		{name:'acuerdo_nombre', type:'string'},
		{name:'acuerdo_descripcion', type:'string'},
		{name:'acuerdo_intercambio', type:'string'},
		{name:'acuerdo_fvigente', type:'string', dateFormat:'Y-m-d'},
		{name:'acuerdo_ffirma', type:'string', dateFormat:'Y-m-d'},
		{name:'acuerdo_ley', type:'string'},
		{name:'acuerdo_decreto', type:'string'},
		{name:'acuerdo_url', type:'string'},
		{name:'acuerdo_tipo_acuerdo', type:'string'},
		{name:'acuerdo_uinsert', type:'float'},
		{name:'acuerdo_finsert', type:'date', dateFormat:'Y-m-d H:i:s'},
		{name:'acuerdo_uupdate', type:'float'},
		{name:'acuerdo_fupdate', type:'date', dateFormat:'Y-m-d H:i:s'},
		{name:'acuerdo_mercado_id', type:'float'},
		{name:'acuerdo_id_pais', type:'float'}
	]
});
var comboAcuerdo = new Ext.form.ComboBox({
	hiddenName:'acuerdo'
	,id:module+'comboAcuerdo'
	,fieldLabel:'<?= Lang::get('acuerdo.columns_title.acuerdo_id_pais'); ?>'
	,store:storeAcuerdo
	,valueField:'acuerdo_id'
	,displayField:'acuerdo_name'
	,typeAhead:true
	,forceSelection:true
	,triggerAction:'all'
	,selectOnFocus:true
	,allowBlank:false
	,listeners:{
		select: {
			fn: function(combo,reg){
				Ext.getCmp(module + 'acuerdo_id').setValue(reg.data.acuerdo_id);
			}
		}
	}
});
var cmAcuerdo = new Ext.grid.ColumnModel({
	columns:[
		{xtype:'numbercolumn', header:'<?= Lang::get('acuerdo.columns_title.acuerdo_id'); ?>', align:'right', hidden:false, dataIndex:'acuerdo_id'},
		{header:'<?= Lang::get('acuerdo.columns_title.acuerdo_nombre'); ?>', align:'left', hidden:false, dataIndex:'acuerdo_nombre'},
		{header:'<?= Lang::get('acuerdo.columns_title.acuerdo_descripcion'); ?>', align:'left', hidden:false, dataIndex:'acuerdo_descripcion'},
		{header:'<?= Lang::get('acuerdo.columns_title.acuerdo_intercambio'); ?>', align:'left', hidden:false, dataIndex:'acuerdo_intercambio'},
		{xtype:'datecolumn', header:'<?= Lang::get('acuerdo.columns_title.acuerdo_fvigente'); ?>', align:'left', hidden:false, dataIndex:'acuerdo_fvigente', format:'Y-m-d'},
		{xtype:'datecolumn', header:'<?= Lang::get('acuerdo.columns_title.acuerdo_ffirma'); ?>', align:'left', hidden:false, dataIndex:'acuerdo_ffirma', format:'Y-m-d'},
		{header:'<?= Lang::get('acuerdo.columns_title.acuerdo_ley'); ?>', align:'left', hidden:false, dataIndex:'acuerdo_ley'},
		{header:'<?= Lang::get('acuerdo.columns_title.acuerdo_decreto'); ?>', align:'left', hidden:false, dataIndex:'acuerdo_decreto'},
		{header:'<?= Lang::get('acuerdo.columns_title.acuerdo_url'); ?>', align:'left', hidden:false, dataIndex:'acuerdo_url'},
		{header:'<?= Lang::get('acuerdo.columns_title.acuerdo_tipo_acuerdo'); ?>', align:'left', hidden:false, dataIndex:'acuerdo_tipo_acuerdo'},
		{xtype:'numbercolumn', header:'<?= Lang::get('acuerdo.columns_title.acuerdo_uinsert'); ?>', align:'right', hidden:false, dataIndex:'acuerdo_uinsert'},
		{xtype:'datecolumn', header:'<?= Lang::get('acuerdo.columns_title.acuerdo_finsert'); ?>', align:'left', hidden:false, dataIndex:'acuerdo_finsert', format:'Y-m-d, g:i a'},
		{xtype:'numbercolumn', header:'<?= Lang::get('acuerdo.columns_title.acuerdo_uupdate'); ?>', align:'right', hidden:false, dataIndex:'acuerdo_uupdate'},
		{xtype:'datecolumn', header:'<?= Lang::get('acuerdo.columns_title.acuerdo_fupdate'); ?>', align:'left', hidden:false, dataIndex:'acuerdo_fupdate', format:'Y-m-d, g:i a'},
		{xtype:'numbercolumn', header:'<?= Lang::get('acuerdo.columns_title.acuerdo_mercado_id'); ?>', align:'right', hidden:false, dataIndex:'acuerdo_mercado_id'},
		{xtype:'numbercolumn', header:'<?= Lang::get('acuerdo.columns_title.acuerdo_id_pais'); ?>', align:'right', hidden:false, dataIndex:'acuerdo_id_pais'}
	]
	,defaults:{
		sortable:true
		,width:100
	}
});
var tbAcuerdo = new Ext.Toolbar();

var gridAcuerdo = new Ext.grid.GridPanel({
	store:storeAcuerdo
	,id:module+'gridAcuerdo'
	,colModel:cmAcuerdo
	,viewConfig: {
		forceFit: true
		,scrollOffset:2
	}
	,sm:new Ext.grid.RowSelectionModel({singleSelect:true})
	,bbar:new Ext.PagingToolbar({pageSize:10, store:storeAcuerdo, displayInfo:true})
	,tbar:tbAcuerdo
	,loadMask:true
	,border:false
	,title:''
	,iconCls:'icon-grid'
	,plugins:[new Ext.ux.grid.Excel()]
});
var formAcuerdo = new Ext.FormPanel({
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
			{name:'acuerdo_id', mapping:'acuerdo_id', type:'float'},
			{name:'acuerdo_nombre', mapping:'acuerdo_nombre', type:'string'},
			{name:'acuerdo_descripcion', mapping:'acuerdo_descripcion', type:'string'},
			{name:'acuerdo_intercambio', mapping:'acuerdo_intercambio', type:'string'},
			{name:'acuerdo_fvigente', mapping:'acuerdo_fvigente', type:'string'},
			{name:'acuerdo_ffirma', mapping:'acuerdo_ffirma', type:'string'},
			{name:'acuerdo_ley', mapping:'acuerdo_ley', type:'string'},
			{name:'acuerdo_decreto', mapping:'acuerdo_decreto', type:'string'},
			{name:'acuerdo_url', mapping:'acuerdo_url', type:'string'},
			{name:'acuerdo_tipo_acuerdo', mapping:'acuerdo_tipo_acuerdo', type:'string'},
			{name:'acuerdo_uinsert', mapping:'acuerdo_uinsert', type:'float'},
			{name:'acuerdo_finsert', mapping:'acuerdo_finsert', type:'date'},
			{name:'acuerdo_uupdate', mapping:'acuerdo_uupdate', type:'float'},
			{name:'acuerdo_fupdate', mapping:'acuerdo_fupdate', type:'date'},
			{name:'acuerdo_mercado_id', mapping:'acuerdo_mercado_id', type:'float'},
			{name:'acuerdo_id_pais', mapping:'acuerdo_id_pais', type:'float'}
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
				,name:'acuerdo_id'
				,fieldLabel:'<?= Lang::get('acuerdo.columns_title.acuerdo_id'); ?>'
				,id:module+'acuerdo_id'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'textfield'
				,name:'acuerdo_nombre'
				,fieldLabel:'<?= Lang::get('acuerdo.columns_title.acuerdo_nombre'); ?>'
				,id:module+'acuerdo_nombre'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'textfield'
				,name:'acuerdo_descripcion'
				,fieldLabel:'<?= Lang::get('acuerdo.columns_title.acuerdo_descripcion'); ?>'
				,id:module+'acuerdo_descripcion'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'textfield'
				,name:'acuerdo_intercambio'
				,fieldLabel:'<?= Lang::get('acuerdo.columns_title.acuerdo_intercambio'); ?>'
				,id:module+'acuerdo_intercambio'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'datefield'
				,name:'acuerdo_fvigente'
				,fieldLabel:'<?= Lang::get('acuerdo.columns_title.acuerdo_fvigente'); ?>'
				,id:module+'acuerdo_fvigente'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'datefield'
				,name:'acuerdo_ffirma'
				,fieldLabel:'<?= Lang::get('acuerdo.columns_title.acuerdo_ffirma'); ?>'
				,id:module+'acuerdo_ffirma'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'textfield'
				,name:'acuerdo_ley'
				,fieldLabel:'<?= Lang::get('acuerdo.columns_title.acuerdo_ley'); ?>'
				,id:module+'acuerdo_ley'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'textfield'
				,name:'acuerdo_decreto'
				,fieldLabel:'<?= Lang::get('acuerdo.columns_title.acuerdo_decreto'); ?>'
				,id:module+'acuerdo_decreto'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'textfield'
				,name:'acuerdo_url'
				,fieldLabel:'<?= Lang::get('acuerdo.columns_title.acuerdo_url'); ?>'
				,id:module+'acuerdo_url'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'textfield'
				,name:'acuerdo_tipo_acuerdo'
				,fieldLabel:'<?= Lang::get('acuerdo.columns_title.acuerdo_tipo_acuerdo'); ?>'
				,id:module+'acuerdo_tipo_acuerdo'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'numberfield'
				,name:'acuerdo_uinsert'
				,fieldLabel:'<?= Lang::get('acuerdo.columns_title.acuerdo_uinsert'); ?>'
				,id:module+'acuerdo_uinsert'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'datefield'
				,name:'acuerdo_finsert'
				,fieldLabel:'<?= Lang::get('acuerdo.columns_title.acuerdo_finsert'); ?>'
				,id:module+'acuerdo_finsert'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'numberfield'
				,name:'acuerdo_uupdate'
				,fieldLabel:'<?= Lang::get('acuerdo.columns_title.acuerdo_uupdate'); ?>'
				,id:module+'acuerdo_uupdate'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'datefield'
				,name:'acuerdo_fupdate'
				,fieldLabel:'<?= Lang::get('acuerdo.columns_title.acuerdo_fupdate'); ?>'
				,id:module+'acuerdo_fupdate'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'numberfield'
				,name:'acuerdo_mercado_id'
				,fieldLabel:'<?= Lang::get('acuerdo.columns_title.acuerdo_mercado_id'); ?>'
				,id:module+'acuerdo_mercado_id'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'numberfield'
				,name:'acuerdo_id_pais'
				,fieldLabel:'<?= Lang::get('acuerdo.columns_title.acuerdo_id_pais'); ?>'
				,id:module+'acuerdo_id_pais'
				,allowBlank:false
			}]
		}]
	}]
});
