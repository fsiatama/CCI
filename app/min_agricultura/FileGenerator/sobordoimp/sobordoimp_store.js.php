<?php
session_start();
include_once('../../lib/config.php');
?>
/*<script>*/
var storeSobordoimp = new Ext.data.JsonStore({
	url:'sobordoimp/list'
	,root:'data'
	,sortInfo:{field:'id',direction:'ASC'}
	,totalProperty:'total'
	,baseParams:{id:'<?= $id; ?>'}
	,fields:[
		{name:'id', type:'float'},
		{name:'anio', type:'float'},
		{name:'periodo', type:'float'},
		{name:'fecha', type:'string', dateFormat:'Y-m-d'},
		{name:'id_paisprocedencia', type:'string'},
		{name:'id_capitulo', type:'string'},
		{name:'id_partida', type:'string'},
		{name:'id_subpartida', type:'string'},
		{name:'peso_neto', type:'string'}
	]
});
var comboSobordoimp = new Ext.form.ComboBox({
	hiddenName:'sobordoimp'
	,id:module+'comboSobordoimp'
	,fieldLabel:'<?= Lang::get('sobordoimp.columns_title.peso_neto'); ?>'
	,store:storeSobordoimp
	,valueField:'id'
	,displayField:'sobordoimp_name'
	,typeAhead:true
	,forceSelection:true
	,triggerAction:'all'
	,selectOnFocus:true
	,allowBlank:false
	,listeners:{
		select: {
			fn: function(combo,reg){
				Ext.getCmp(module + 'id').setValue(reg.data.id);
			}
		}
	}
});
var cmSobordoimp = new Ext.grid.ColumnModel({
	columns:[
		{xtype:'numbercolumn', header:'<?= Lang::get('sobordoimp.columns_title.id'); ?>', align:'right', hidden:false, dataIndex:'id'},
		{xtype:'numbercolumn', header:'<?= Lang::get('sobordoimp.columns_title.anio'); ?>', align:'right', hidden:false, dataIndex:'anio'},
		{xtype:'numbercolumn', header:'<?= Lang::get('sobordoimp.columns_title.periodo'); ?>', align:'right', hidden:false, dataIndex:'periodo'},
		{xtype:'datecolumn', header:'<?= Lang::get('sobordoimp.columns_title.fecha'); ?>', align:'left', hidden:false, dataIndex:'fecha', format:'Y-m-d'},
		{header:'<?= Lang::get('sobordoimp.columns_title.id_paisprocedencia'); ?>', align:'left', hidden:false, dataIndex:'id_paisprocedencia'},
		{header:'<?= Lang::get('sobordoimp.columns_title.id_capitulo'); ?>', align:'left', hidden:false, dataIndex:'id_capitulo'},
		{header:'<?= Lang::get('sobordoimp.columns_title.id_partida'); ?>', align:'left', hidden:false, dataIndex:'id_partida'},
		{header:'<?= Lang::get('sobordoimp.columns_title.id_subpartida'); ?>', align:'left', hidden:false, dataIndex:'id_subpartida'},
		{header:'<?= Lang::get('sobordoimp.columns_title.peso_neto'); ?>', align:'left', hidden:false, dataIndex:'peso_neto'}
	]
	,defaults:{
		sortable:true
		,width:100
	}
});
var tbSobordoimp = new Ext.Toolbar();

var gridSobordoimp = new Ext.grid.GridPanel({
	store:storeSobordoimp
	,id:module+'gridSobordoimp'
	,colModel:cmSobordoimp
	,viewConfig: {
		forceFit: true
		,scrollOffset:2
	}
	,sm:new Ext.grid.RowSelectionModel({singleSelect:true})
	,bbar:new Ext.PagingToolbar({pageSize:10, store:storeSobordoimp, displayInfo:true})
	,tbar:tbSobordoimp
	,loadMask:true
	,border:false
	,title:''
	,iconCls:'icon-grid'
	,plugins:[new Ext.ux.grid.Excel()]
});
var formSobordoimp = new Ext.FormPanel({
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
			{name:'id', mapping:'id', type:'float'},
			{name:'anio', mapping:'anio', type:'float'},
			{name:'periodo', mapping:'periodo', type:'float'},
			{name:'fecha', mapping:'fecha', type:'string'},
			{name:'id_paisprocedencia', mapping:'id_paisprocedencia', type:'string'},
			{name:'id_capitulo', mapping:'id_capitulo', type:'string'},
			{name:'id_partida', mapping:'id_partida', type:'string'},
			{name:'id_subpartida', mapping:'id_subpartida', type:'string'},
			{name:'peso_neto', mapping:'peso_neto', type:'string'}
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
				,name:'id'
				,fieldLabel:'<?= Lang::get('sobordoimp.columns_title.id'); ?>'
				,id:module+'id'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'numberfield'
				,name:'anio'
				,fieldLabel:'<?= Lang::get('sobordoimp.columns_title.anio'); ?>'
				,id:module+'anio'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'numberfield'
				,name:'periodo'
				,fieldLabel:'<?= Lang::get('sobordoimp.columns_title.periodo'); ?>'
				,id:module+'periodo'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'datefield'
				,name:'fecha'
				,fieldLabel:'<?= Lang::get('sobordoimp.columns_title.fecha'); ?>'
				,id:module+'fecha'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'datefield'
				,name:'id_paisprocedencia'
				,fieldLabel:'<?= Lang::get('sobordoimp.columns_title.id_paisprocedencia'); ?>'
				,id:module+'id_paisprocedencia'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'datefield'
				,name:'id_capitulo'
				,fieldLabel:'<?= Lang::get('sobordoimp.columns_title.id_capitulo'); ?>'
				,id:module+'id_capitulo'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'datefield'
				,name:'id_partida'
				,fieldLabel:'<?= Lang::get('sobordoimp.columns_title.id_partida'); ?>'
				,id:module+'id_partida'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'datefield'
				,name:'id_subpartida'
				,fieldLabel:'<?= Lang::get('sobordoimp.columns_title.id_subpartida'); ?>'
				,id:module+'id_subpartida'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'datefield'
				,name:'peso_neto'
				,fieldLabel:'<?= Lang::get('sobordoimp.columns_title.peso_neto'); ?>'
				,id:module+'peso_neto'
				,allowBlank:false
			}]
		}]
	}]
});
