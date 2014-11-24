<?php
session_start();
include_once('../../lib/config.php');
?>
/*<script>*/
var storeIndicador = new Ext.data.JsonStore({
	url:'indicador/list'
	,root:'data'
	,sortInfo:{field:'indicador_id',direction:'ASC'}
	,totalProperty:'total'
	,baseParams:{id:'<?= $id; ?>'}
	,fields:[
		{name:'indicador_id', type:'float'},
		{name:'indicador_nombre', type:'string'},
		{name:'indicador_tipo_indicador_id', type:'float'},
		{name:'indicador_campos', type:'string'},
		{name:'indicador_filtros', type:'string'},
		{name:'indicador_leaf', type:'string'},
		{name:'indicador_uinsert', type:'float'},
		{name:'indicador_finsert', type:'date', dateFormat:'Y-m-d H:i:s'},
		{name:'indicador_fupdate', type:'date', dateFormat:'Y-m-d H:i:s'}
	]
});
var comboIndicador = new Ext.form.ComboBox({
	hiddenName:'indicador'
	,id:module+'comboIndicador'
	,fieldLabel:'<?= Lang::get('indicador.columns_title.indicador_fupdate'); ?>'
	,store:storeIndicador
	,valueField:'indicador_id'
	,displayField:'indicador_name'
	,typeAhead:true
	,forceSelection:true
	,triggerAction:'all'
	,selectOnFocus:true
	,listeners:{
		select: {
			fn: function(combo,reg){
				Ext.getCmp(module + 'indicador_id').setValue(reg.data.indicador_id);
			}
		}
	}
});
var cmIndicador = new Ext.grid.ColumnModel({
	columns:[
		{xtype:'numbercolumn', header:'<?= Lang::get('indicador.columns_title.indicador_id'); ?>', align:'right', hidden:false, dataIndex:'indicador_id'},
		{header:'<?= Lang::get('indicador.columns_title.indicador_nombre'); ?>', align:'left', hidden:false, dataIndex:'indicador_nombre'},
		{xtype:'numbercolumn', header:'<?= Lang::get('indicador.columns_title.indicador_tipo_indicador_id'); ?>', align:'right', hidden:false, dataIndex:'indicador_tipo_indicador_id'},
		{header:'<?= Lang::get('indicador.columns_title.indicador_campos'); ?>', align:'left', hidden:false, dataIndex:'indicador_campos'},
		{header:'<?= Lang::get('indicador.columns_title.indicador_filtros'); ?>', align:'left', hidden:false, dataIndex:'indicador_filtros'},
		{header:'<?= Lang::get('indicador.columns_title.indicador_leaf'); ?>', align:'left', hidden:false, dataIndex:'indicador_leaf'},
		{xtype:'numbercolumn', header:'<?= Lang::get('indicador.columns_title.indicador_uinsert'); ?>', align:'right', hidden:false, dataIndex:'indicador_uinsert'},
		{xtype:'datecolumn', header:'<?= Lang::get('indicador.columns_title.indicador_finsert'); ?>', align:'left', hidden:false, dataIndex:'indicador_finsert', format:'Y-m-d, g:i a'},
		{xtype:'datecolumn', header:'<?= Lang::get('indicador.columns_title.indicador_fupdate'); ?>', align:'left', hidden:false, dataIndex:'indicador_fupdate', format:'Y-m-d, g:i a'}
	]
	,defaults:{
		sortable:true
		,width:100
	}
});
var tbIndicador = new Ext.Toolbar();

var gridIndicador = new Ext.grid.GridPanel({
	store:storeIndicador
	,id:module+'gridIndicador'
	,colModel:cmIndicador
	,viewConfig: {
		forceFit: true
		,scrollOffset:2
	}
	,sm:new Ext.grid.RowSelectionModel({singleSelect:true})
	,bbar:new Ext.PagingToolbar({pageSize:10, store:storeIndicador, displayInfo:true})
	,tbar:tbIndicador
	,loadMask:true
	,border:false
	,title:''
	,iconCls:'icon-grid'
	,plugins:[new Ext.ux.grid.Excel()]
});
var formIndicador = new Ext.FormPanel({
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
			{name:'indicador_id', mapping:'indicador_id', type:'float'},
			{name:'indicador_nombre', mapping:'indicador_nombre', type:'string'},
			{name:'indicador_tipo_indicador_id', mapping:'indicador_tipo_indicador_id', type:'float'},
			{name:'indicador_campos', mapping:'indicador_campos', type:'string'},
			{name:'indicador_filtros', mapping:'indicador_filtros', type:'string'},
			{name:'indicador_leaf', mapping:'indicador_leaf', type:'string'},
			{name:'indicador_uinsert', mapping:'indicador_uinsert', type:'float'},
			{name:'indicador_finsert', mapping:'indicador_finsert', type:'date'},
			{name:'indicador_fupdate', mapping:'indicador_fupdate', type:'date'}
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
				,name:'indicador_id'
				,fieldLabel:'<?= Lang::get('indicador.columns_title.indicador_id'); ?>'
				,id:module+'indicador_id'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'textfield'
				,name:'indicador_nombre'
				,fieldLabel:'<?= Lang::get('indicador.columns_title.indicador_nombre'); ?>'
				,id:module+'indicador_nombre'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'numberfield'
				,name:'indicador_tipo_indicador_id'
				,fieldLabel:'<?= Lang::get('indicador.columns_title.indicador_tipo_indicador_id'); ?>'
				,id:module+'indicador_tipo_indicador_id'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'textfield'
				,name:'indicador_campos'
				,fieldLabel:'<?= Lang::get('indicador.columns_title.indicador_campos'); ?>'
				,id:module+'indicador_campos'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'textfield'
				,name:'indicador_filtros'
				,fieldLabel:'<?= Lang::get('indicador.columns_title.indicador_filtros'); ?>'
				,id:module+'indicador_filtros'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'textfield'
				,name:'indicador_leaf'
				,fieldLabel:'<?= Lang::get('indicador.columns_title.indicador_leaf'); ?>'
				,id:module+'indicador_leaf'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'numberfield'
				,name:'indicador_uinsert'
				,fieldLabel:'<?= Lang::get('indicador.columns_title.indicador_uinsert'); ?>'
				,id:module+'indicador_uinsert'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'datefield'
				,name:'indicador_finsert'
				,fieldLabel:'<?= Lang::get('indicador.columns_title.indicador_finsert'); ?>'
				,id:module+'indicador_finsert'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'datefield'
				,name:'indicador_fupdate'
				,fieldLabel:'<?= Lang::get('indicador.columns_title.indicador_fupdate'); ?>'
				,id:module+'indicador_fupdate'
				,allowBlank:false
			}]
		}]
	}]
});
