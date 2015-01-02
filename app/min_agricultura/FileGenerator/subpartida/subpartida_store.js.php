<?php
session_start();
include_once('../../lib/config.php');
?>
/*<script>*/
var storeSubpartida = new Ext.data.JsonStore({
	url:'subpartida/list'
	,root:'data'
	,sortInfo:{field:'id_subpartida',direction:'ASC'}
	,totalProperty:'total'
	,baseParams:{id:'<?= $id; ?>'}
	,fields:[
		{name:'id_subpartida', type:'string'},
		{name:'subpartida', type:'string'},
		{name:'id_capitulo', type:'string'},
		{name:'id_partida', type:'string'}
	]
});
var comboSubpartida = new Ext.form.ComboBox({
	hiddenName:'subpartida'
	,id:module+'comboSubpartida'
	,fieldLabel:'<?= Lang::get('subpartida.columns_title.id_partida'); ?>'
	,store:storeSubpartida
	,valueField:'id_subpartida'
	,displayField:'subpartida_name'
	,typeAhead:true
	,forceSelection:true
	,triggerAction:'all'
	,selectOnFocus:true
	,allowBlank:false
	,listeners:{
		select: {
			fn: function(combo,reg){
				Ext.getCmp(module + 'id_subpartida').setValue(reg.data.id_subpartida);
			}
		}
	}
});
var cmSubpartida = new Ext.grid.ColumnModel({
	columns:[
		{header:'<?= Lang::get('subpartida.columns_title.id_subpartida'); ?>', align:'left', hidden:false, dataIndex:'id_subpartida'},
		{header:'<?= Lang::get('subpartida.columns_title.subpartida'); ?>', align:'left', hidden:false, dataIndex:'subpartida'},
		{header:'<?= Lang::get('subpartida.columns_title.id_capitulo'); ?>', align:'left', hidden:false, dataIndex:'id_capitulo'},
		{header:'<?= Lang::get('subpartida.columns_title.id_partida'); ?>', align:'left', hidden:false, dataIndex:'id_partida'}
	]
	,defaults:{
		sortable:true
		,width:100
	}
});
var tbSubpartida = new Ext.Toolbar();

var gridSubpartida = new Ext.grid.GridPanel({
	store:storeSubpartida
	,id:module+'gridSubpartida'
	,colModel:cmSubpartida
	,viewConfig: {
		forceFit: true
		,scrollOffset:2
	}
	,sm:new Ext.grid.RowSelectionModel({singleSelect:true})
	,bbar:new Ext.PagingToolbar({pageSize:10, store:storeSubpartida, displayInfo:true})
	,tbar:tbSubpartida
	,loadMask:true
	,border:false
	,title:''
	,iconCls:'icon-grid'
	,plugins:[new Ext.ux.grid.Excel()]
});
var formSubpartida = new Ext.FormPanel({
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
			{name:'id_subpartida', mapping:'id_subpartida', type:'string'},
			{name:'subpartida', mapping:'subpartida', type:'string'},
			{name:'id_capitulo', mapping:'id_capitulo', type:'string'},
			{name:'id_partida', mapping:'id_partida', type:'string'}
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
				xtype:''
				,name:'id_subpartida'
				,fieldLabel:'<?= Lang::get('subpartida.columns_title.id_subpartida'); ?>'
				,id:module+'id_subpartida'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'textfield'
				,name:'subpartida'
				,fieldLabel:'<?= Lang::get('subpartida.columns_title.subpartida'); ?>'
				,id:module+'subpartida'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'textfield'
				,name:'id_capitulo'
				,fieldLabel:'<?= Lang::get('subpartida.columns_title.id_capitulo'); ?>'
				,id:module+'id_capitulo'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'textfield'
				,name:'id_partida'
				,fieldLabel:'<?= Lang::get('subpartida.columns_title.id_partida'); ?>'
				,id:module+'id_partida'
				,allowBlank:false
			}]
		}]
	}]
});
