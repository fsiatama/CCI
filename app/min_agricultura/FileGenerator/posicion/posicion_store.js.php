<?php
session_start();
include_once('../../lib/config.php');
?>
/*<script>*/
var storePosicion = new Ext.data.JsonStore({
	url:'posicion/list'
	,root:'data'
	,sortInfo:{field:'id_posicion',direction:'ASC'}
	,totalProperty:'total'
	,baseParams:{id:'<?= $id; ?>'}
	,fields:[
		{name:'id_posicion', type:'string'},
		{name:'posicion', type:'string'}
	]
});
var comboPosicion = new Ext.form.ComboBox({
	hiddenName:'posicion'
	,id:module+'comboPosicion'
	,fieldLabel:'<?= Lang::get('posicion.columns_title.posicion'); ?>'
	,store:storePosicion
	,valueField:'id_posicion'
	,displayField:'posicion_name'
	,typeAhead:true
	,forceSelection:true
	,triggerAction:'all'
	,selectOnFocus:true
	,allowBlank:false
	,listeners:{
		select: {
			fn: function(combo,reg){
				Ext.getCmp(module + 'id_posicion').setValue(reg.data.id_posicion);
			}
		}
	}
});
var cmPosicion = new Ext.grid.ColumnModel({
	columns:[
		{header:'<?= Lang::get('posicion.columns_title.id_posicion'); ?>', align:'left', hidden:false, dataIndex:'id_posicion'},
		{header:'<?= Lang::get('posicion.columns_title.posicion'); ?>', align:'left', hidden:false, dataIndex:'posicion'}
	]
	,defaults:{
		sortable:true
		,width:100
	}
});
var tbPosicion = new Ext.Toolbar();

var gridPosicion = new Ext.grid.GridPanel({
	store:storePosicion
	,id:module+'gridPosicion'
	,colModel:cmPosicion
	,viewConfig: {
		forceFit: true
		,scrollOffset:2
	}
	,sm:new Ext.grid.RowSelectionModel({singleSelect:true})
	,bbar:new Ext.PagingToolbar({pageSize:10, store:storePosicion, displayInfo:true})
	,tbar:tbPosicion
	,loadMask:true
	,border:false
	,title:''
	,iconCls:'icon-grid'
	,plugins:[new Ext.ux.grid.Excel()]
});
var formPosicion = new Ext.FormPanel({
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
			{name:'id_posicion', mapping:'id_posicion', type:'string'},
			{name:'posicion', mapping:'posicion', type:'string'}
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
				xtype:'textfield'
				,name:'id_posicion'
				,fieldLabel:'<?= Lang::get('posicion.columns_title.id_posicion'); ?>'
				,id:module+'id_posicion'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'textfield'
				,name:'posicion'
				,fieldLabel:'<?= Lang::get('posicion.columns_title.posicion'); ?>'
				,id:module+'posicion'
				,allowBlank:false
			}]
		}]
	}]
});
