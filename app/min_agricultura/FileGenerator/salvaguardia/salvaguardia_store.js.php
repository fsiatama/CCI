<?php
session_start();
include_once('../../lib/config.php');
?>
/*<script>*/
var storeSalvaguardia = new Ext.data.JsonStore({
	url:'salvaguardia/list'
	,root:'data'
	,sortInfo:{field:'salvaguardia_contingente_acuerdo_det_acuerdo_id',direction:'ASC'}
	,totalProperty:'total'
	,baseParams:{id:'<?= $id; ?>'}
	,fields:[
		{name:'salvaguardia_id', type:'float'},
		{name:'salvaguardia_msalvaguardia', type:'string'},
		{name:'salvaguardia_contingente_id', type:'float'},
		{name:'salvaguardia_contingente_acuerdo_det_id', type:'float'},
		{name:'salvaguardia_contingente_acuerdo_det_acuerdo_id', type:'float'}
	]
});
var comboSalvaguardia = new Ext.form.ComboBox({
	hiddenName:'salvaguardia'
	,id:module+'comboSalvaguardia'
	,fieldLabel:'<?= Lang::get('salvaguardia.columns_title.salvaguardia_contingente_acuerdo_det_acuerdo_id'); ?>'
	,store:storeSalvaguardia
	,valueField:'salvaguardia_contingente_acuerdo_det_acuerdo_id'
	,displayField:'salvaguardia_name'
	,typeAhead:true
	,forceSelection:true
	,triggerAction:'all'
	,selectOnFocus:true
	,allowBlank:false
	,listeners:{
		select: {
			fn: function(combo,reg){
				Ext.getCmp(module + 'salvaguardia_contingente_acuerdo_det_acuerdo_id').setValue(reg.data.salvaguardia_contingente_acuerdo_det_acuerdo_id);
			}
		}
	}
});
var cmSalvaguardia = new Ext.grid.ColumnModel({
	columns:[
		{xtype:'numbercolumn', header:'<?= Lang::get('salvaguardia.columns_title.salvaguardia_id'); ?>', align:'right', hidden:false, dataIndex:'salvaguardia_id'},
		{header:'<?= Lang::get('salvaguardia.columns_title.salvaguardia_msalvaguardia'); ?>', align:'left', hidden:false, dataIndex:'salvaguardia_msalvaguardia'},
		{xtype:'numbercolumn', header:'<?= Lang::get('salvaguardia.columns_title.salvaguardia_contingente_id'); ?>', align:'right', hidden:false, dataIndex:'salvaguardia_contingente_id'},
		{xtype:'numbercolumn', header:'<?= Lang::get('salvaguardia.columns_title.salvaguardia_contingente_acuerdo_det_id'); ?>', align:'right', hidden:false, dataIndex:'salvaguardia_contingente_acuerdo_det_id'},
		{xtype:'numbercolumn', header:'<?= Lang::get('salvaguardia.columns_title.salvaguardia_contingente_acuerdo_det_acuerdo_id'); ?>', align:'right', hidden:false, dataIndex:'salvaguardia_contingente_acuerdo_det_acuerdo_id'}
	]
	,defaults:{
		sortable:true
		,width:100
	}
});
var tbSalvaguardia = new Ext.Toolbar();

var gridSalvaguardia = new Ext.grid.GridPanel({
	store:storeSalvaguardia
	,id:module+'gridSalvaguardia'
	,colModel:cmSalvaguardia
	,viewConfig: {
		forceFit: true
		,scrollOffset:2
	}
	,sm:new Ext.grid.RowSelectionModel({singleSelect:true})
	,bbar:new Ext.PagingToolbar({pageSize:10, store:storeSalvaguardia, displayInfo:true})
	,tbar:tbSalvaguardia
	,loadMask:true
	,border:false
	,title:''
	,iconCls:'icon-grid'
	,plugins:[new Ext.ux.grid.Excel()]
});
var formSalvaguardia = new Ext.FormPanel({
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
			{name:'salvaguardia_id', mapping:'salvaguardia_id', type:'float'},
			{name:'salvaguardia_msalvaguardia', mapping:'salvaguardia_msalvaguardia', type:'string'},
			{name:'salvaguardia_contingente_id', mapping:'salvaguardia_contingente_id', type:'float'},
			{name:'salvaguardia_contingente_acuerdo_det_id', mapping:'salvaguardia_contingente_acuerdo_det_id', type:'float'},
			{name:'salvaguardia_contingente_acuerdo_det_acuerdo_id', mapping:'salvaguardia_contingente_acuerdo_det_acuerdo_id', type:'float'}
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
				,name:'salvaguardia_id'
				,fieldLabel:'<?= Lang::get('salvaguardia.columns_title.salvaguardia_id'); ?>'
				,id:module+'salvaguardia_id'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'numberfield'
				,name:'salvaguardia_msalvaguardia'
				,fieldLabel:'<?= Lang::get('salvaguardia.columns_title.salvaguardia_msalvaguardia'); ?>'
				,id:module+'salvaguardia_msalvaguardia'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'numberfield'
				,name:'salvaguardia_contingente_id'
				,fieldLabel:'<?= Lang::get('salvaguardia.columns_title.salvaguardia_contingente_id'); ?>'
				,id:module+'salvaguardia_contingente_id'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'numberfield'
				,name:'salvaguardia_contingente_acuerdo_det_id'
				,fieldLabel:'<?= Lang::get('salvaguardia.columns_title.salvaguardia_contingente_acuerdo_det_id'); ?>'
				,id:module+'salvaguardia_contingente_acuerdo_det_id'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'numberfield'
				,name:'salvaguardia_contingente_acuerdo_det_acuerdo_id'
				,fieldLabel:'<?= Lang::get('salvaguardia.columns_title.salvaguardia_contingente_acuerdo_det_acuerdo_id'); ?>'
				,id:module+'salvaguardia_contingente_acuerdo_det_acuerdo_id'
				,allowBlank:false
			}]
		}]
	}]
});
