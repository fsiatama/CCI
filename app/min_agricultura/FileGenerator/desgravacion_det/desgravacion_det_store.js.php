<?php
session_start();
include_once('../../lib/config.php');
?>
/*<script>*/
var storeDesgravacion_det = new Ext.data.JsonStore({
	url:'desgravacion_det/list'
	,root:'data'
	,sortInfo:{field:'desgravacion_det_desgravacion_acuerdo_det_acuerdo_id',direction:'ASC'}
	,totalProperty:'total'
	,baseParams:{id:'<?= $id; ?>'}
	,fields:[
		{name:'desgravacion_det_id', type:'float'},
		{name:'desgravacion_det_anio_ini', type:'float'},
		{name:'desgravacion_det_anio_fin', type:'float'},
		{name:'desgravacion_det_tasa', type:'float'},
		{name:'desgravacion_det_desgravacion_id', type:'float'},
		{name:'desgravacion_det_desgravacion_acuerdo_det_id', type:'float'},
		{name:'desgravacion_det_desgravacion_acuerdo_det_acuerdo_id', type:'float'}
	]
});
var comboDesgravacion_det = new Ext.form.ComboBox({
	hiddenName:'desgravacion_det'
	,id:module+'comboDesgravacion_det'
	,fieldLabel:'<?= Lang::get('desgravacion_det.columns_title.desgravacion_det_desgravacion_acuerdo_det_acuerdo_id'); ?>'
	,store:storeDesgravacion_det
	,valueField:'desgravacion_det_desgravacion_acuerdo_det_acuerdo_id'
	,displayField:'desgravacion_det_name'
	,typeAhead:true
	,forceSelection:true
	,triggerAction:'all'
	,selectOnFocus:true
	,allowBlank:false
	,listeners:{
		select: {
			fn: function(combo,reg){
				Ext.getCmp(module + 'desgravacion_det_desgravacion_acuerdo_det_acuerdo_id').setValue(reg.data.desgravacion_det_desgravacion_acuerdo_det_acuerdo_id);
			}
		}
	}
});
var cmDesgravacion_det = new Ext.grid.ColumnModel({
	columns:[
		{xtype:'numbercolumn', header:'<?= Lang::get('desgravacion_det.columns_title.desgravacion_det_id'); ?>', align:'right', hidden:false, dataIndex:'desgravacion_det_id'},
		{xtype:'numbercolumn', header:'<?= Lang::get('desgravacion_det.columns_title.desgravacion_det_anio_ini'); ?>', align:'right', hidden:false, dataIndex:'desgravacion_det_anio_ini'},
		{xtype:'numbercolumn', header:'<?= Lang::get('desgravacion_det.columns_title.desgravacion_det_anio_fin'); ?>', align:'right', hidden:false, dataIndex:'desgravacion_det_anio_fin'},
		{xtype:'numbercolumn', header:'<?= Lang::get('desgravacion_det.columns_title.desgravacion_det_tasa'); ?>', align:'right', hidden:false, dataIndex:'desgravacion_det_tasa'},
		{xtype:'numbercolumn', header:'<?= Lang::get('desgravacion_det.columns_title.desgravacion_det_desgravacion_id'); ?>', align:'right', hidden:false, dataIndex:'desgravacion_det_desgravacion_id'},
		{xtype:'numbercolumn', header:'<?= Lang::get('desgravacion_det.columns_title.desgravacion_det_desgravacion_acuerdo_det_id'); ?>', align:'right', hidden:false, dataIndex:'desgravacion_det_desgravacion_acuerdo_det_id'},
		{xtype:'numbercolumn', header:'<?= Lang::get('desgravacion_det.columns_title.desgravacion_det_desgravacion_acuerdo_det_acuerdo_id'); ?>', align:'right', hidden:false, dataIndex:'desgravacion_det_desgravacion_acuerdo_det_acuerdo_id'}
	]
	,defaults:{
		sortable:true
		,width:100
	}
});
var tbDesgravacion_det = new Ext.Toolbar();

var gridDesgravacion_det = new Ext.grid.GridPanel({
	store:storeDesgravacion_det
	,id:module+'gridDesgravacion_det'
	,colModel:cmDesgravacion_det
	,viewConfig: {
		forceFit: true
		,scrollOffset:2
	}
	,sm:new Ext.grid.RowSelectionModel({singleSelect:true})
	,bbar:new Ext.PagingToolbar({pageSize:10, store:storeDesgravacion_det, displayInfo:true})
	,tbar:tbDesgravacion_det
	,loadMask:true
	,border:false
	,title:''
	,iconCls:'icon-grid'
	,plugins:[new Ext.ux.grid.Excel()]
});
var formDesgravacion_det = new Ext.FormPanel({
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
			{name:'desgravacion_det_id', mapping:'desgravacion_det_id', type:'float'},
			{name:'desgravacion_det_anio_ini', mapping:'desgravacion_det_anio_ini', type:'float'},
			{name:'desgravacion_det_anio_fin', mapping:'desgravacion_det_anio_fin', type:'float'},
			{name:'desgravacion_det_tasa', mapping:'desgravacion_det_tasa', type:'float'},
			{name:'desgravacion_det_desgravacion_id', mapping:'desgravacion_det_desgravacion_id', type:'float'},
			{name:'desgravacion_det_desgravacion_acuerdo_det_id', mapping:'desgravacion_det_desgravacion_acuerdo_det_id', type:'float'},
			{name:'desgravacion_det_desgravacion_acuerdo_det_acuerdo_id', mapping:'desgravacion_det_desgravacion_acuerdo_det_acuerdo_id', type:'float'}
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
				,name:'desgravacion_det_id'
				,fieldLabel:'<?= Lang::get('desgravacion_det.columns_title.desgravacion_det_id'); ?>'
				,id:module+'desgravacion_det_id'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'numberfield'
				,name:'desgravacion_det_anio_ini'
				,fieldLabel:'<?= Lang::get('desgravacion_det.columns_title.desgravacion_det_anio_ini'); ?>'
				,id:module+'desgravacion_det_anio_ini'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'numberfield'
				,name:'desgravacion_det_anio_fin'
				,fieldLabel:'<?= Lang::get('desgravacion_det.columns_title.desgravacion_det_anio_fin'); ?>'
				,id:module+'desgravacion_det_anio_fin'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'numberfield'
				,name:'desgravacion_det_tasa'
				,fieldLabel:'<?= Lang::get('desgravacion_det.columns_title.desgravacion_det_tasa'); ?>'
				,id:module+'desgravacion_det_tasa'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'numberfield'
				,name:'desgravacion_det_desgravacion_id'
				,fieldLabel:'<?= Lang::get('desgravacion_det.columns_title.desgravacion_det_desgravacion_id'); ?>'
				,id:module+'desgravacion_det_desgravacion_id'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'numberfield'
				,name:'desgravacion_det_desgravacion_acuerdo_det_id'
				,fieldLabel:'<?= Lang::get('desgravacion_det.columns_title.desgravacion_det_desgravacion_acuerdo_det_id'); ?>'
				,id:module+'desgravacion_det_desgravacion_acuerdo_det_id'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'numberfield'
				,name:'desgravacion_det_desgravacion_acuerdo_det_acuerdo_id'
				,fieldLabel:'<?= Lang::get('desgravacion_det.columns_title.desgravacion_det_desgravacion_acuerdo_det_acuerdo_id'); ?>'
				,id:module+'desgravacion_det_desgravacion_acuerdo_det_acuerdo_id'
				,allowBlank:false
			}]
		}]
	}]
});
