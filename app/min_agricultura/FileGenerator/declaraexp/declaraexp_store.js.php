<?php
session_start();
include_once('../../lib/config.php');
?>
/*<script>*/
var storeDeclaraexp = new Ext.data.JsonStore({
	url:'declaraexp/list'
	,root:'data'
	,sortInfo:{field:'id',direction:'ASC'}
	,totalProperty:'total'
	,baseParams:{id:'<?= $id; ?>'}
	,fields:[
		{name:'id', type:'float'},
		{name:'anio', type:'float'},
		{name:'periodo', type:'float'},
		{name:'id_empresa', type:'string'},
		{name:'id_paisdestino', type:'float'},
		{name:'id_capitulo', type:'string'},
		{name:'id_partida', type:'string'},
		{name:'id_subpartida', type:'string'},
		{name:'id_posicion', type:'string'},
		{name:'id_ciiu', type:'float'},
		{name:'valorfob', type:'string'},
		{name:'valorcif', type:'string'},
		{name:'peso_neto', type:'string'}
	]
});
var comboDeclaraexp = new Ext.form.ComboBox({
	hiddenName:'declaraexp'
	,id:module+'comboDeclaraexp'
	,fieldLabel:'<?= Lang::get('declaraexp.columns_title.peso_neto'); ?>'
	,store:storeDeclaraexp
	,valueField:'id'
	,displayField:'declaraexp_name'
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
var cmDeclaraexp = new Ext.grid.ColumnModel({
	columns:[
		{xtype:'numbercolumn', header:'<?= Lang::get('declaraexp.columns_title.id'); ?>', align:'right', hidden:false, dataIndex:'id'},
		{xtype:'numbercolumn', header:'<?= Lang::get('declaraexp.columns_title.anio'); ?>', align:'right', hidden:false, dataIndex:'anio'},
		{xtype:'numbercolumn', header:'<?= Lang::get('declaraexp.columns_title.periodo'); ?>', align:'right', hidden:false, dataIndex:'periodo'},
		{header:'<?= Lang::get('declaraexp.columns_title.id_empresa'); ?>', align:'left', hidden:false, dataIndex:'id_empresa'},
		{xtype:'numbercolumn', header:'<?= Lang::get('declaraexp.columns_title.id_paisdestino'); ?>', align:'right', hidden:false, dataIndex:'id_paisdestino'},
		{header:'<?= Lang::get('declaraexp.columns_title.id_capitulo'); ?>', align:'left', hidden:false, dataIndex:'id_capitulo'},
		{header:'<?= Lang::get('declaraexp.columns_title.id_partida'); ?>', align:'left', hidden:false, dataIndex:'id_partida'},
		{header:'<?= Lang::get('declaraexp.columns_title.id_subpartida'); ?>', align:'left', hidden:false, dataIndex:'id_subpartida'},
		{header:'<?= Lang::get('declaraexp.columns_title.id_posicion'); ?>', align:'left', hidden:false, dataIndex:'id_posicion'},
		{xtype:'numbercolumn', header:'<?= Lang::get('declaraexp.columns_title.id_ciiu'); ?>', align:'right', hidden:false, dataIndex:'id_ciiu'},
		{header:'<?= Lang::get('declaraexp.columns_title.valorfob'); ?>', align:'left', hidden:false, dataIndex:'valorfob'},
		{header:'<?= Lang::get('declaraexp.columns_title.valorcif'); ?>', align:'left', hidden:false, dataIndex:'valorcif'},
		{header:'<?= Lang::get('declaraexp.columns_title.peso_neto'); ?>', align:'left', hidden:false, dataIndex:'peso_neto'}
	]
	,defaults:{
		sortable:true
		,width:100
	}
});
var tbDeclaraexp = new Ext.Toolbar();

var gridDeclaraexp = new Ext.grid.GridPanel({
	store:storeDeclaraexp
	,id:module+'gridDeclaraexp'
	,colModel:cmDeclaraexp
	,viewConfig: {
		forceFit: true
		,scrollOffset:2
	}
	,sm:new Ext.grid.RowSelectionModel({singleSelect:true})
	,bbar:new Ext.PagingToolbar({pageSize:10, store:storeDeclaraexp, displayInfo:true})
	,tbar:tbDeclaraexp
	,loadMask:true
	,border:false
	,title:''
	,iconCls:'icon-grid'
	,plugins:[new Ext.ux.grid.Excel()]
});
var formDeclaraexp = new Ext.FormPanel({
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
			{name:'id_empresa', mapping:'id_empresa', type:'string'},
			{name:'id_paisdestino', mapping:'id_paisdestino', type:'float'},
			{name:'id_capitulo', mapping:'id_capitulo', type:'string'},
			{name:'id_partida', mapping:'id_partida', type:'string'},
			{name:'id_subpartida', mapping:'id_subpartida', type:'string'},
			{name:'id_posicion', mapping:'id_posicion', type:'string'},
			{name:'id_ciiu', mapping:'id_ciiu', type:'float'},
			{name:'valorfob', mapping:'valorfob', type:'string'},
			{name:'valorcif', mapping:'valorcif', type:'string'},
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
				,fieldLabel:'<?= Lang::get('declaraexp.columns_title.id'); ?>'
				,id:module+'id'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'numberfield'
				,name:'anio'
				,fieldLabel:'<?= Lang::get('declaraexp.columns_title.anio'); ?>'
				,id:module+'anio'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'numberfield'
				,name:'periodo'
				,fieldLabel:'<?= Lang::get('declaraexp.columns_title.periodo'); ?>'
				,id:module+'periodo'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'textfield'
				,name:'id_empresa'
				,fieldLabel:'<?= Lang::get('declaraexp.columns_title.id_empresa'); ?>'
				,id:module+'id_empresa'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'numberfield'
				,name:'id_paisdestino'
				,fieldLabel:'<?= Lang::get('declaraexp.columns_title.id_paisdestino'); ?>'
				,id:module+'id_paisdestino'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'numberfield'
				,name:'id_capitulo'
				,fieldLabel:'<?= Lang::get('declaraexp.columns_title.id_capitulo'); ?>'
				,id:module+'id_capitulo'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'numberfield'
				,name:'id_partida'
				,fieldLabel:'<?= Lang::get('declaraexp.columns_title.id_partida'); ?>'
				,id:module+'id_partida'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'numberfield'
				,name:'id_subpartida'
				,fieldLabel:'<?= Lang::get('declaraexp.columns_title.id_subpartida'); ?>'
				,id:module+'id_subpartida'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'numberfield'
				,name:'id_posicion'
				,fieldLabel:'<?= Lang::get('declaraexp.columns_title.id_posicion'); ?>'
				,id:module+'id_posicion'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'numberfield'
				,name:'id_ciiu'
				,fieldLabel:'<?= Lang::get('declaraexp.columns_title.id_ciiu'); ?>'
				,id:module+'id_ciiu'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'numberfield'
				,name:'valorfob'
				,fieldLabel:'<?= Lang::get('declaraexp.columns_title.valorfob'); ?>'
				,id:module+'valorfob'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'numberfield'
				,name:'valorcif'
				,fieldLabel:'<?= Lang::get('declaraexp.columns_title.valorcif'); ?>'
				,id:module+'valorcif'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'numberfield'
				,name:'peso_neto'
				,fieldLabel:'<?= Lang::get('declaraexp.columns_title.peso_neto'); ?>'
				,id:module+'peso_neto'
				,allowBlank:false
			}]
		}]
	}]
});
