<?php
session_start();
include_once('../../lib/config.php');
?>
/*<script>*/
var storeAlerta = new Ext.data.JsonStore({
	url:'alerta/list'
	,root:'data'
	,sortInfo:{field:'alerta_contingente_acuerdo_det_acuerdo_id',direction:'ASC'}
	,totalProperty:'total'
	,baseParams:{id:'<?= $id; ?>'}
	,fields:[
		{name:'alerta_id', type:'float'},
		{name:'alerta_contingente_verde', type:'float'},
		{name:'alerta_contingente_amarilla', type:'float'},
		{name:'alerta_contingente_roja', type:'float'},
		{name:'alerta_salvaguardia_verde', type:'float'},
		{name:'alerta_salvaguardia_amarilla', type:'float'},
		{name:'alerta_salvaguardia_roja', type:'float'},
		{name:'alerta_emails', type:'string'},
		{name:'alerta_contingente_id', type:'float'},
		{name:'alerta_contingente_acuerdo_det_id', type:'float'},
		{name:'alerta_contingente_acuerdo_det_acuerdo_id', type:'float'},
		{name:'alerta_disp1', type:'string'},
		{name:'alerta_disp2', type:'string'},
		{name:'alerta_disp3', type:'string'},
		{name:'alerta_disp4', type:'string'},
		{name:'alerta_disp5', type:'string'},
		{name:'alerta_disp6', type:'string'}
	]
});
var comboAlerta = new Ext.form.ComboBox({
	hiddenName:'alerta'
	,id:module+'comboAlerta'
	,fieldLabel:'<?= Lang::get('alerta.columns_title.alerta_disp6'); ?>'
	,store:storeAlerta
	,valueField:'alerta_contingente_acuerdo_det_acuerdo_id'
	,displayField:'alerta_name'
	,typeAhead:true
	,forceSelection:true
	,triggerAction:'all'
	,selectOnFocus:true
	,allowBlank:false
	,listeners:{
		select: {
			fn: function(combo,reg){
				Ext.getCmp(module + 'alerta_contingente_acuerdo_det_acuerdo_id').setValue(reg.data.alerta_contingente_acuerdo_det_acuerdo_id);
			}
		}
	}
});
var cmAlerta = new Ext.grid.ColumnModel({
	columns:[
		{xtype:'numbercolumn', header:'<?= Lang::get('alerta.columns_title.alerta_id'); ?>', align:'right', hidden:false, dataIndex:'alerta_id'},
		{xtype:'numbercolumn', header:'<?= Lang::get('alerta.columns_title.alerta_contingente_verde'); ?>', align:'right', hidden:false, dataIndex:'alerta_contingente_verde'},
		{xtype:'numbercolumn', header:'<?= Lang::get('alerta.columns_title.alerta_contingente_amarilla'); ?>', align:'right', hidden:false, dataIndex:'alerta_contingente_amarilla'},
		{xtype:'numbercolumn', header:'<?= Lang::get('alerta.columns_title.alerta_contingente_roja'); ?>', align:'right', hidden:false, dataIndex:'alerta_contingente_roja'},
		{xtype:'numbercolumn', header:'<?= Lang::get('alerta.columns_title.alerta_salvaguardia_verde'); ?>', align:'right', hidden:false, dataIndex:'alerta_salvaguardia_verde'},
		{xtype:'numbercolumn', header:'<?= Lang::get('alerta.columns_title.alerta_salvaguardia_amarilla'); ?>', align:'right', hidden:false, dataIndex:'alerta_salvaguardia_amarilla'},
		{xtype:'numbercolumn', header:'<?= Lang::get('alerta.columns_title.alerta_salvaguardia_roja'); ?>', align:'right', hidden:false, dataIndex:'alerta_salvaguardia_roja'},
		{header:'<?= Lang::get('alerta.columns_title.alerta_emails'); ?>', align:'left', hidden:false, dataIndex:'alerta_emails'},
		{xtype:'numbercolumn', header:'<?= Lang::get('alerta.columns_title.alerta_contingente_id'); ?>', align:'right', hidden:false, dataIndex:'alerta_contingente_id'},
		{xtype:'numbercolumn', header:'<?= Lang::get('alerta.columns_title.alerta_contingente_acuerdo_det_id'); ?>', align:'right', hidden:false, dataIndex:'alerta_contingente_acuerdo_det_id'},
		{xtype:'numbercolumn', header:'<?= Lang::get('alerta.columns_title.alerta_contingente_acuerdo_det_acuerdo_id'); ?>', align:'right', hidden:false, dataIndex:'alerta_contingente_acuerdo_det_acuerdo_id'},
		{header:'<?= Lang::get('alerta.columns_title.alerta_disp1'); ?>', align:'left', hidden:false, dataIndex:'alerta_disp1'},
		{header:'<?= Lang::get('alerta.columns_title.alerta_disp2'); ?>', align:'left', hidden:false, dataIndex:'alerta_disp2'},
		{header:'<?= Lang::get('alerta.columns_title.alerta_disp3'); ?>', align:'left', hidden:false, dataIndex:'alerta_disp3'},
		{header:'<?= Lang::get('alerta.columns_title.alerta_disp4'); ?>', align:'left', hidden:false, dataIndex:'alerta_disp4'},
		{header:'<?= Lang::get('alerta.columns_title.alerta_disp5'); ?>', align:'left', hidden:false, dataIndex:'alerta_disp5'},
		{header:'<?= Lang::get('alerta.columns_title.alerta_disp6'); ?>', align:'left', hidden:false, dataIndex:'alerta_disp6'}
	]
	,defaults:{
		sortable:true
		,width:100
	}
});
var tbAlerta = new Ext.Toolbar();

var gridAlerta = new Ext.grid.GridPanel({
	store:storeAlerta
	,id:module+'gridAlerta'
	,colModel:cmAlerta
	,viewConfig: {
		forceFit: true
		,scrollOffset:2
	}
	,sm:new Ext.grid.RowSelectionModel({singleSelect:true})
	,bbar:new Ext.PagingToolbar({pageSize:10, store:storeAlerta, displayInfo:true})
	,tbar:tbAlerta
	,loadMask:true
	,border:false
	,title:''
	,iconCls:'icon-grid'
	,plugins:[new Ext.ux.grid.Excel()]
});
var formAlerta = new Ext.FormPanel({
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
			{name:'alerta_id', mapping:'alerta_id', type:'float'},
			{name:'alerta_contingente_verde', mapping:'alerta_contingente_verde', type:'float'},
			{name:'alerta_contingente_amarilla', mapping:'alerta_contingente_amarilla', type:'float'},
			{name:'alerta_contingente_roja', mapping:'alerta_contingente_roja', type:'float'},
			{name:'alerta_salvaguardia_verde', mapping:'alerta_salvaguardia_verde', type:'float'},
			{name:'alerta_salvaguardia_amarilla', mapping:'alerta_salvaguardia_amarilla', type:'float'},
			{name:'alerta_salvaguardia_roja', mapping:'alerta_salvaguardia_roja', type:'float'},
			{name:'alerta_emails', mapping:'alerta_emails', type:'string'},
			{name:'alerta_contingente_id', mapping:'alerta_contingente_id', type:'float'},
			{name:'alerta_contingente_acuerdo_det_id', mapping:'alerta_contingente_acuerdo_det_id', type:'float'},
			{name:'alerta_contingente_acuerdo_det_acuerdo_id', mapping:'alerta_contingente_acuerdo_det_acuerdo_id', type:'float'},
			{name:'alerta_disp1', mapping:'alerta_disp1', type:'string'},
			{name:'alerta_disp2', mapping:'alerta_disp2', type:'string'},
			{name:'alerta_disp3', mapping:'alerta_disp3', type:'string'},
			{name:'alerta_disp4', mapping:'alerta_disp4', type:'string'},
			{name:'alerta_disp5', mapping:'alerta_disp5', type:'string'},
			{name:'alerta_disp6', mapping:'alerta_disp6', type:'string'}
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
				,name:'alerta_id'
				,fieldLabel:'<?= Lang::get('alerta.columns_title.alerta_id'); ?>'
				,id:module+'alerta_id'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'numberfield'
				,name:'alerta_contingente_verde'
				,fieldLabel:'<?= Lang::get('alerta.columns_title.alerta_contingente_verde'); ?>'
				,id:module+'alerta_contingente_verde'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'numberfield'
				,name:'alerta_contingente_amarilla'
				,fieldLabel:'<?= Lang::get('alerta.columns_title.alerta_contingente_amarilla'); ?>'
				,id:module+'alerta_contingente_amarilla'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'numberfield'
				,name:'alerta_contingente_roja'
				,fieldLabel:'<?= Lang::get('alerta.columns_title.alerta_contingente_roja'); ?>'
				,id:module+'alerta_contingente_roja'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'numberfield'
				,name:'alerta_salvaguardia_verde'
				,fieldLabel:'<?= Lang::get('alerta.columns_title.alerta_salvaguardia_verde'); ?>'
				,id:module+'alerta_salvaguardia_verde'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'numberfield'
				,name:'alerta_salvaguardia_amarilla'
				,fieldLabel:'<?= Lang::get('alerta.columns_title.alerta_salvaguardia_amarilla'); ?>'
				,id:module+'alerta_salvaguardia_amarilla'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'numberfield'
				,name:'alerta_salvaguardia_roja'
				,fieldLabel:'<?= Lang::get('alerta.columns_title.alerta_salvaguardia_roja'); ?>'
				,id:module+'alerta_salvaguardia_roja'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'textfield'
				,name:'alerta_emails'
				,fieldLabel:'<?= Lang::get('alerta.columns_title.alerta_emails'); ?>'
				,id:module+'alerta_emails'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'numberfield'
				,name:'alerta_contingente_id'
				,fieldLabel:'<?= Lang::get('alerta.columns_title.alerta_contingente_id'); ?>'
				,id:module+'alerta_contingente_id'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'numberfield'
				,name:'alerta_contingente_acuerdo_det_id'
				,fieldLabel:'<?= Lang::get('alerta.columns_title.alerta_contingente_acuerdo_det_id'); ?>'
				,id:module+'alerta_contingente_acuerdo_det_id'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'numberfield'
				,name:'alerta_contingente_acuerdo_det_acuerdo_id'
				,fieldLabel:'<?= Lang::get('alerta.columns_title.alerta_contingente_acuerdo_det_acuerdo_id'); ?>'
				,id:module+'alerta_contingente_acuerdo_det_acuerdo_id'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'numberfield'
				,name:'alerta_disp1'
				,fieldLabel:'<?= Lang::get('alerta.columns_title.alerta_disp1'); ?>'
				,id:module+'alerta_disp1'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'numberfield'
				,name:'alerta_disp2'
				,fieldLabel:'<?= Lang::get('alerta.columns_title.alerta_disp2'); ?>'
				,id:module+'alerta_disp2'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'numberfield'
				,name:'alerta_disp3'
				,fieldLabel:'<?= Lang::get('alerta.columns_title.alerta_disp3'); ?>'
				,id:module+'alerta_disp3'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'numberfield'
				,name:'alerta_disp4'
				,fieldLabel:'<?= Lang::get('alerta.columns_title.alerta_disp4'); ?>'
				,id:module+'alerta_disp4'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'numberfield'
				,name:'alerta_disp5'
				,fieldLabel:'<?= Lang::get('alerta.columns_title.alerta_disp5'); ?>'
				,id:module+'alerta_disp5'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'numberfield'
				,name:'alerta_disp6'
				,fieldLabel:'<?= Lang::get('alerta.columns_title.alerta_disp6'); ?>'
				,id:module+'alerta_disp6'
				,allowBlank:false
			}]
		}]
	}]
});
