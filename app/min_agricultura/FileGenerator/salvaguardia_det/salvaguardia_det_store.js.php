<?php
session_start();
include_once('../../lib/config.php');
?>
/*<script>*/
var storeSalvaguardia_det = new Ext.data.JsonStore({
	url:'salvaguardia_det/list'
	,root:'data'
	,sortInfo:{field:'salvaguardia_det_salvaguardia_contingente_acuerdo_det_acuerdo_id',direction:'ASC'}
	,totalProperty:'total'
	,baseParams:{id:'<?= $id; ?>'}
	,fields:[
		{name:'salvaguardia_det_id', type:'float'},
		{name:'salvaguardia_det_anio_ini', type:'float'},
		{name:'salvaguardia_det_anio_fin', type:'float'},
		{name:'salvaguardia_det_peso_neto', type:'string'},
		{name:'salvaguardia_det_salvaguardia_id', type:'float'},
		{name:'salvaguardia_det_salvaguardia_contingente_id', type:'float'},
		{name:'salvaguardia_det_salvaguardia_contingente_acuerdo_det_id', type:'float'},
		{name:'salvaguardia_det_salvaguardia_contingente_acuerdo_det_acuerdo_id', type:'float'}
	]
});
var comboSalvaguardia_det = new Ext.form.ComboBox({
	hiddenName:'salvaguardia_det'
	,id:module+'comboSalvaguardia_det'
	,fieldLabel:'<?= Lang::get('salvaguardia_det.columns_title.salvaguardia_det_salvaguardia_contingente_acuerdo_det_acuerdo_id'); ?>'
	,store:storeSalvaguardia_det
	,valueField:'salvaguardia_det_salvaguardia_contingente_acuerdo_det_acuerdo_id'
	,displayField:'salvaguardia_det_name'
	,typeAhead:true
	,forceSelection:true
	,triggerAction:'all'
	,selectOnFocus:true
	,allowBlank:false
	,listeners:{
		select: {
			fn: function(combo,reg){
				Ext.getCmp(module + 'salvaguardia_det_salvaguardia_contingente_acuerdo_det_acuerdo_id').setValue(reg.data.salvaguardia_det_salvaguardia_contingente_acuerdo_det_acuerdo_id);
			}
		}
	}
});
var cmSalvaguardia_det = new Ext.grid.ColumnModel({
	columns:[
		{xtype:'numbercolumn', header:'<?= Lang::get('salvaguardia_det.columns_title.salvaguardia_det_id'); ?>', align:'right', hidden:false, dataIndex:'salvaguardia_det_id'},
		{xtype:'numbercolumn', header:'<?= Lang::get('salvaguardia_det.columns_title.salvaguardia_det_anio_ini'); ?>', align:'right', hidden:false, dataIndex:'salvaguardia_det_anio_ini'},
		{xtype:'numbercolumn', header:'<?= Lang::get('salvaguardia_det.columns_title.salvaguardia_det_anio_fin'); ?>', align:'right', hidden:false, dataIndex:'salvaguardia_det_anio_fin'},
		{header:'<?= Lang::get('salvaguardia_det.columns_title.salvaguardia_det_peso_neto'); ?>', align:'left', hidden:false, dataIndex:'salvaguardia_det_peso_neto'},
		{xtype:'numbercolumn', header:'<?= Lang::get('salvaguardia_det.columns_title.salvaguardia_det_salvaguardia_id'); ?>', align:'right', hidden:false, dataIndex:'salvaguardia_det_salvaguardia_id'},
		{xtype:'numbercolumn', header:'<?= Lang::get('salvaguardia_det.columns_title.salvaguardia_det_salvaguardia_contingente_id'); ?>', align:'right', hidden:false, dataIndex:'salvaguardia_det_salvaguardia_contingente_id'},
		{xtype:'numbercolumn', header:'<?= Lang::get('salvaguardia_det.columns_title.salvaguardia_det_salvaguardia_contingente_acuerdo_det_id'); ?>', align:'right', hidden:false, dataIndex:'salvaguardia_det_salvaguardia_contingente_acuerdo_det_id'},
		{xtype:'numbercolumn', header:'<?= Lang::get('salvaguardia_det.columns_title.salvaguardia_det_salvaguardia_contingente_acuerdo_det_acuerdo_id'); ?>', align:'right', hidden:false, dataIndex:'salvaguardia_det_salvaguardia_contingente_acuerdo_det_acuerdo_id'}
	]
	,defaults:{
		sortable:true
		,width:100
	}
});
var tbSalvaguardia_det = new Ext.Toolbar();

var gridSalvaguardia_det = new Ext.grid.GridPanel({
	store:storeSalvaguardia_det
	,id:module+'gridSalvaguardia_det'
	,colModel:cmSalvaguardia_det
	,viewConfig: {
		forceFit: true
		,scrollOffset:2
	}
	,sm:new Ext.grid.RowSelectionModel({singleSelect:true})
	,bbar:new Ext.PagingToolbar({pageSize:10, store:storeSalvaguardia_det, displayInfo:true})
	,tbar:tbSalvaguardia_det
	,loadMask:true
	,border:false
	,title:''
	,iconCls:'icon-grid'
	,plugins:[new Ext.ux.grid.Excel()]
});
var formSalvaguardia_det = new Ext.FormPanel({
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
			{name:'salvaguardia_det_id', mapping:'salvaguardia_det_id', type:'float'},
			{name:'salvaguardia_det_anio_ini', mapping:'salvaguardia_det_anio_ini', type:'float'},
			{name:'salvaguardia_det_anio_fin', mapping:'salvaguardia_det_anio_fin', type:'float'},
			{name:'salvaguardia_det_peso_neto', mapping:'salvaguardia_det_peso_neto', type:'string'},
			{name:'salvaguardia_det_salvaguardia_id', mapping:'salvaguardia_det_salvaguardia_id', type:'float'},
			{name:'salvaguardia_det_salvaguardia_contingente_id', mapping:'salvaguardia_det_salvaguardia_contingente_id', type:'float'},
			{name:'salvaguardia_det_salvaguardia_contingente_acuerdo_det_id', mapping:'salvaguardia_det_salvaguardia_contingente_acuerdo_det_id', type:'float'},
			{name:'salvaguardia_det_salvaguardia_contingente_acuerdo_det_acuerdo_id', mapping:'salvaguardia_det_salvaguardia_contingente_acuerdo_det_acuerdo_id', type:'float'}
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
				,name:'salvaguardia_det_id'
				,fieldLabel:'<?= Lang::get('salvaguardia_det.columns_title.salvaguardia_det_id'); ?>'
				,id:module+'salvaguardia_det_id'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'numberfield'
				,name:'salvaguardia_det_anio_ini'
				,fieldLabel:'<?= Lang::get('salvaguardia_det.columns_title.salvaguardia_det_anio_ini'); ?>'
				,id:module+'salvaguardia_det_anio_ini'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'numberfield'
				,name:'salvaguardia_det_anio_fin'
				,fieldLabel:'<?= Lang::get('salvaguardia_det.columns_title.salvaguardia_det_anio_fin'); ?>'
				,id:module+'salvaguardia_det_anio_fin'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'numberfield'
				,name:'salvaguardia_det_peso_neto'
				,fieldLabel:'<?= Lang::get('salvaguardia_det.columns_title.salvaguardia_det_peso_neto'); ?>'
				,id:module+'salvaguardia_det_peso_neto'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'numberfield'
				,name:'salvaguardia_det_salvaguardia_id'
				,fieldLabel:'<?= Lang::get('salvaguardia_det.columns_title.salvaguardia_det_salvaguardia_id'); ?>'
				,id:module+'salvaguardia_det_salvaguardia_id'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'numberfield'
				,name:'salvaguardia_det_salvaguardia_contingente_id'
				,fieldLabel:'<?= Lang::get('salvaguardia_det.columns_title.salvaguardia_det_salvaguardia_contingente_id'); ?>'
				,id:module+'salvaguardia_det_salvaguardia_contingente_id'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'numberfield'
				,name:'salvaguardia_det_salvaguardia_contingente_acuerdo_det_id'
				,fieldLabel:'<?= Lang::get('salvaguardia_det.columns_title.salvaguardia_det_salvaguardia_contingente_acuerdo_det_id'); ?>'
				,id:module+'salvaguardia_det_salvaguardia_contingente_acuerdo_det_id'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'numberfield'
				,name:'salvaguardia_det_salvaguardia_contingente_acuerdo_det_acuerdo_id'
				,fieldLabel:'<?= Lang::get('salvaguardia_det.columns_title.salvaguardia_det_salvaguardia_contingente_acuerdo_det_acuerdo_id'); ?>'
				,id:module+'salvaguardia_det_salvaguardia_contingente_acuerdo_det_acuerdo_id'
				,allowBlank:false
			}]
		}]
	}]
});
