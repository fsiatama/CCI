<?php
session_start();
include_once('../../lib/config.php');
?>
/*<script>*/
var storeTipo_indicador = new Ext.data.JsonStore({
	url:'tipo_indicador/list'
	,root:'data'
	,sortInfo:{field:'tipo_indicador_id',direction:'ASC'}
	,totalProperty:'total'
	,baseParams:{id:'<?= $id; ?>'}
	,fields:[
		{name:'tipo_indicador_id', type:'float'},
		{name:'tipo_indicador_nombre', type:'string'},
		{name:'tipo_indicador_abrev', type:'string'},
		{name:'tipo_indicador_activador', type:'string'},
		{name:'tipo_indicador_calculo', type:'string'},
		{name:'tipo_indicador_definicion', type:'string'},
		{name:'tipo_indicador_html', type:'string'}
	]
});
var comboTipo_indicador = new Ext.form.ComboBox({
	hiddenName:'tipo_indicador'
	,id:module+'comboTipo_indicador'
	,fieldLabel:'<?= Lang::get('tipo_indicador.columns_title.tipo_indicador_html'); ?>'
	,store:storeTipo_indicador
	,valueField:'tipo_indicador_id'
	,displayField:'tipo_indicador_name'
	,typeAhead:true
	,forceSelection:true
	,triggerAction:'all'
	,selectOnFocus:true
	,allowBlank:false
	,listeners:{
		select: {
			fn: function(combo,reg){
				Ext.getCmp(module + 'tipo_indicador_id').setValue(reg.data.tipo_indicador_id);
			}
		}
	}
});
var cmTipo_indicador = new Ext.grid.ColumnModel({
	columns:[
		{xtype:'numbercolumn', header:'<?= Lang::get('tipo_indicador.columns_title.tipo_indicador_id'); ?>', align:'right', hidden:false, dataIndex:'tipo_indicador_id'},
		{header:'<?= Lang::get('tipo_indicador.columns_title.tipo_indicador_nombre'); ?>', align:'left', hidden:false, dataIndex:'tipo_indicador_nombre'},
		{header:'<?= Lang::get('tipo_indicador.columns_title.tipo_indicador_abrev'); ?>', align:'left', hidden:false, dataIndex:'tipo_indicador_abrev'},
		{header:'<?= Lang::get('tipo_indicador.columns_title.tipo_indicador_activador'); ?>', align:'left', hidden:false, dataIndex:'tipo_indicador_activador'},
		{header:'<?= Lang::get('tipo_indicador.columns_title.tipo_indicador_calculo'); ?>', align:'left', hidden:false, dataIndex:'tipo_indicador_calculo'},
		{header:'<?= Lang::get('tipo_indicador.columns_title.tipo_indicador_definicion'); ?>', align:'left', hidden:false, dataIndex:'tipo_indicador_definicion'},
		{header:'<?= Lang::get('tipo_indicador.columns_title.tipo_indicador_html'); ?>', align:'left', hidden:false, dataIndex:'tipo_indicador_html'}
	]
	,defaults:{
		sortable:true
		,width:100
	}
});
var tbTipo_indicador = new Ext.Toolbar();

var gridTipo_indicador = new Ext.grid.GridPanel({
	store:storeTipo_indicador
	,id:module+'gridTipo_indicador'
	,colModel:cmTipo_indicador
	,viewConfig: {
		forceFit: true
		,scrollOffset:2
	}
	,sm:new Ext.grid.RowSelectionModel({singleSelect:true})
	,bbar:new Ext.PagingToolbar({pageSize:10, store:storeTipo_indicador, displayInfo:true})
	,tbar:tbTipo_indicador
	,loadMask:true
	,border:false
	,title:''
	,iconCls:'icon-grid'
	,plugins:[new Ext.ux.grid.Excel()]
});
var formTipo_indicador = new Ext.FormPanel({
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
			{name:'tipo_indicador_id', mapping:'tipo_indicador_id', type:'float'},
			{name:'tipo_indicador_nombre', mapping:'tipo_indicador_nombre', type:'string'},
			{name:'tipo_indicador_abrev', mapping:'tipo_indicador_abrev', type:'string'},
			{name:'tipo_indicador_activador', mapping:'tipo_indicador_activador', type:'string'},
			{name:'tipo_indicador_calculo', mapping:'tipo_indicador_calculo', type:'string'},
			{name:'tipo_indicador_definicion', mapping:'tipo_indicador_definicion', type:'string'},
			{name:'tipo_indicador_html', mapping:'tipo_indicador_html', type:'string'}
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
				,name:'tipo_indicador_id'
				,fieldLabel:'<?= Lang::get('tipo_indicador.columns_title.tipo_indicador_id'); ?>'
				,id:module+'tipo_indicador_id'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'textfield'
				,name:'tipo_indicador_nombre'
				,fieldLabel:'<?= Lang::get('tipo_indicador.columns_title.tipo_indicador_nombre'); ?>'
				,id:module+'tipo_indicador_nombre'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'textfield'
				,name:'tipo_indicador_abrev'
				,fieldLabel:'<?= Lang::get('tipo_indicador.columns_title.tipo_indicador_abrev'); ?>'
				,id:module+'tipo_indicador_abrev'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'textfield'
				,name:'tipo_indicador_activador'
				,fieldLabel:'<?= Lang::get('tipo_indicador.columns_title.tipo_indicador_activador'); ?>'
				,id:module+'tipo_indicador_activador'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'textfield'
				,name:'tipo_indicador_calculo'
				,fieldLabel:'<?= Lang::get('tipo_indicador.columns_title.tipo_indicador_calculo'); ?>'
				,id:module+'tipo_indicador_calculo'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'textfield'
				,name:'tipo_indicador_definicion'
				,fieldLabel:'<?= Lang::get('tipo_indicador.columns_title.tipo_indicador_definicion'); ?>'
				,id:module+'tipo_indicador_definicion'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'textfield'
				,name:'tipo_indicador_html'
				,fieldLabel:'<?= Lang::get('tipo_indicador.columns_title.tipo_indicador_html'); ?>'
				,id:module+'tipo_indicador_html'
				,allowBlank:false
			}]
		}]
	}]
});
