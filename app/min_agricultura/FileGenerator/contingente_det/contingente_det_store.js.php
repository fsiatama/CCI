<?php
session_start();
include_once('../../lib/config.php');
?>
/*<script>*/
var storeContingente_det = new Ext.data.JsonStore({
	url:'contingente_det/list'
	,root:'data'
	,sortInfo:{field:'contingente_det_contingente_acuerdo_det_acuerdo_id',direction:'ASC'}
	,totalProperty:'total'
	,baseParams:{id:'<?= $id; ?>'}
	,fields:[
		{name:'contingente_det_id', type:'float'},
		{name:'contingente_det_anio_ini', type:'float'},
		{name:'contingente_det_anio_fin', type:'float'},
		{name:'contingente_det_peso_neto', type:'string'},
		{name:'contingente_det_contingente_id', type:'float'},
		{name:'contingente_det_contingente_acuerdo_det_id', type:'float'},
		{name:'contingente_det_contingente_acuerdo_det_acuerdo_id', type:'float'}
	]
});
var comboContingente_det = new Ext.form.ComboBox({
	hiddenName:'contingente_det'
	,id:module+'comboContingente_det'
	,fieldLabel:'<?= Lang::get('contingente_det.columns_title.contingente_det_contingente_acuerdo_det_acuerdo_id'); ?>'
	,store:storeContingente_det
	,valueField:'contingente_det_contingente_acuerdo_det_acuerdo_id'
	,displayField:'contingente_det_name'
	,typeAhead:true
	,forceSelection:true
	,triggerAction:'all'
	,selectOnFocus:true
	,allowBlank:false
	,listeners:{
		select: {
			fn: function(combo,reg){
				Ext.getCmp(module + 'contingente_det_contingente_acuerdo_det_acuerdo_id').setValue(reg.data.contingente_det_contingente_acuerdo_det_acuerdo_id);
			}
		}
	}
});
var cmContingente_det = new Ext.grid.ColumnModel({
	columns:[
		{xtype:'numbercolumn', header:'<?= Lang::get('contingente_det.columns_title.contingente_det_id'); ?>', align:'right', hidden:false, dataIndex:'contingente_det_id'},
		{xtype:'numbercolumn', header:'<?= Lang::get('contingente_det.columns_title.contingente_det_anio_ini'); ?>', align:'right', hidden:false, dataIndex:'contingente_det_anio_ini'},
		{xtype:'numbercolumn', header:'<?= Lang::get('contingente_det.columns_title.contingente_det_anio_fin'); ?>', align:'right', hidden:false, dataIndex:'contingente_det_anio_fin'},
		{header:'<?= Lang::get('contingente_det.columns_title.contingente_det_peso_neto'); ?>', align:'left', hidden:false, dataIndex:'contingente_det_peso_neto'},
		{xtype:'numbercolumn', header:'<?= Lang::get('contingente_det.columns_title.contingente_det_contingente_id'); ?>', align:'right', hidden:false, dataIndex:'contingente_det_contingente_id'},
		{xtype:'numbercolumn', header:'<?= Lang::get('contingente_det.columns_title.contingente_det_contingente_acuerdo_det_id'); ?>', align:'right', hidden:false, dataIndex:'contingente_det_contingente_acuerdo_det_id'},
		{xtype:'numbercolumn', header:'<?= Lang::get('contingente_det.columns_title.contingente_det_contingente_acuerdo_det_acuerdo_id'); ?>', align:'right', hidden:false, dataIndex:'contingente_det_contingente_acuerdo_det_acuerdo_id'}
	]
	,defaults:{
		sortable:true
		,width:100
	}
});
var tbContingente_det = new Ext.Toolbar();

var gridContingente_det = new Ext.grid.GridPanel({
	store:storeContingente_det
	,id:module+'gridContingente_det'
	,colModel:cmContingente_det
	,viewConfig: {
		forceFit: true
		,scrollOffset:2
	}
	,sm:new Ext.grid.RowSelectionModel({singleSelect:true})
	,bbar:new Ext.PagingToolbar({pageSize:10, store:storeContingente_det, displayInfo:true})
	,tbar:tbContingente_det
	,loadMask:true
	,border:false
	,title:''
	,iconCls:'icon-grid'
	,plugins:[new Ext.ux.grid.Excel()]
});
var formContingente_det = new Ext.FormPanel({
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
			{name:'contingente_det_id', mapping:'contingente_det_id', type:'float'},
			{name:'contingente_det_anio_ini', mapping:'contingente_det_anio_ini', type:'float'},
			{name:'contingente_det_anio_fin', mapping:'contingente_det_anio_fin', type:'float'},
			{name:'contingente_det_peso_neto', mapping:'contingente_det_peso_neto', type:'string'},
			{name:'contingente_det_contingente_id', mapping:'contingente_det_contingente_id', type:'float'},
			{name:'contingente_det_contingente_acuerdo_det_id', mapping:'contingente_det_contingente_acuerdo_det_id', type:'float'},
			{name:'contingente_det_contingente_acuerdo_det_acuerdo_id', mapping:'contingente_det_contingente_acuerdo_det_acuerdo_id', type:'float'}
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
				,name:'contingente_det_id'
				,fieldLabel:'<?= Lang::get('contingente_det.columns_title.contingente_det_id'); ?>'
				,id:module+'contingente_det_id'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'numberfield'
				,name:'contingente_det_anio_ini'
				,fieldLabel:'<?= Lang::get('contingente_det.columns_title.contingente_det_anio_ini'); ?>'
				,id:module+'contingente_det_anio_ini'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'numberfield'
				,name:'contingente_det_anio_fin'
				,fieldLabel:'<?= Lang::get('contingente_det.columns_title.contingente_det_anio_fin'); ?>'
				,id:module+'contingente_det_anio_fin'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'numberfield'
				,name:'contingente_det_peso_neto'
				,fieldLabel:'<?= Lang::get('contingente_det.columns_title.contingente_det_peso_neto'); ?>'
				,id:module+'contingente_det_peso_neto'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'numberfield'
				,name:'contingente_det_contingente_id'
				,fieldLabel:'<?= Lang::get('contingente_det.columns_title.contingente_det_contingente_id'); ?>'
				,id:module+'contingente_det_contingente_id'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'numberfield'
				,name:'contingente_det_contingente_acuerdo_det_id'
				,fieldLabel:'<?= Lang::get('contingente_det.columns_title.contingente_det_contingente_acuerdo_det_id'); ?>'
				,id:module+'contingente_det_contingente_acuerdo_det_id'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'numberfield'
				,name:'contingente_det_contingente_acuerdo_det_acuerdo_id'
				,fieldLabel:'<?= Lang::get('contingente_det.columns_title.contingente_det_contingente_acuerdo_det_acuerdo_id'); ?>'
				,id:module+'contingente_det_contingente_acuerdo_det_acuerdo_id'
				,allowBlank:false
			}]
		}]
	}]
});
