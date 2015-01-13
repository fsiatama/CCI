<?php
session_start();
include_once('../../lib/config.php');
?>
/*<script>*/
var storeContingente = new Ext.data.JsonStore({
	url:'contingente/list'
	,root:'data'
	,sortInfo:{field:'contingente_acuerdo_det_acuerdo_id',direction:'ASC'}
	,totalProperty:'total'
	,baseParams:{id:'<?= $id; ?>'}
	,fields:[
		{name:'contingente_id', type:'float'},
		{name:'contingente_id_pais', type:'float'},
		{name:'contingente_mcontingente', type:'string'},
		{name:'contingente_desc', type:'string'},
		{name:'contingente_msalvaguardia', type:'string'},
		{name:'contingente_salvaguardia_sobretasa', type:'float'},
		{name:'contingente_acuerdo_det_id', type:'float'},
		{name:'contingente_acuerdo_det_acuerdo_id', type:'float'}
	]
});
var comboContingente = new Ext.form.ComboBox({
	hiddenName:'contingente'
	,id:module+'comboContingente'
	,fieldLabel:'<?= Lang::get('contingente.columns_title.contingente_acuerdo_det_acuerdo_id'); ?>'
	,store:storeContingente
	,valueField:'contingente_acuerdo_det_acuerdo_id'
	,displayField:'contingente_name'
	,typeAhead:true
	,forceSelection:true
	,triggerAction:'all'
	,selectOnFocus:true
	,allowBlank:false
	,listeners:{
		select: {
			fn: function(combo,reg){
				Ext.getCmp(module + 'contingente_acuerdo_det_acuerdo_id').setValue(reg.data.contingente_acuerdo_det_acuerdo_id);
			}
		}
	}
});
var cmContingente = new Ext.grid.ColumnModel({
	columns:[
		{xtype:'numbercolumn', header:'<?= Lang::get('contingente.columns_title.contingente_id'); ?>', align:'right', hidden:false, dataIndex:'contingente_id'},
		{xtype:'numbercolumn', header:'<?= Lang::get('contingente.columns_title.contingente_id_pais'); ?>', align:'right', hidden:false, dataIndex:'contingente_id_pais'},
		{header:'<?= Lang::get('contingente.columns_title.contingente_mcontingente'); ?>', align:'left', hidden:false, dataIndex:'contingente_mcontingente'},
		{header:'<?= Lang::get('contingente.columns_title.contingente_desc'); ?>', align:'left', hidden:false, dataIndex:'contingente_desc'},
		{header:'<?= Lang::get('contingente.columns_title.contingente_msalvaguardia'); ?>', align:'left', hidden:false, dataIndex:'contingente_msalvaguardia'},
		{xtype:'numbercolumn', header:'<?= Lang::get('contingente.columns_title.contingente_salvaguardia_sobretasa'); ?>', align:'right', hidden:false, dataIndex:'contingente_salvaguardia_sobretasa'},
		{xtype:'numbercolumn', header:'<?= Lang::get('contingente.columns_title.contingente_acuerdo_det_id'); ?>', align:'right', hidden:false, dataIndex:'contingente_acuerdo_det_id'},
		{xtype:'numbercolumn', header:'<?= Lang::get('contingente.columns_title.contingente_acuerdo_det_acuerdo_id'); ?>', align:'right', hidden:false, dataIndex:'contingente_acuerdo_det_acuerdo_id'}
	]
	,defaults:{
		sortable:true
		,width:100
	}
});
var tbContingente = new Ext.Toolbar();

var gridContingente = new Ext.grid.GridPanel({
	store:storeContingente
	,id:module+'gridContingente'
	,colModel:cmContingente
	,viewConfig: {
		forceFit: true
		,scrollOffset:2
	}
	,sm:new Ext.grid.RowSelectionModel({singleSelect:true})
	,bbar:new Ext.PagingToolbar({pageSize:10, store:storeContingente, displayInfo:true})
	,tbar:tbContingente
	,loadMask:true
	,border:false
	,title:''
	,iconCls:'icon-grid'
	,plugins:[new Ext.ux.grid.Excel()]
});
var formContingente = new Ext.FormPanel({
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
			{name:'contingente_id', mapping:'contingente_id', type:'float'},
			{name:'contingente_id_pais', mapping:'contingente_id_pais', type:'float'},
			{name:'contingente_mcontingente', mapping:'contingente_mcontingente', type:'string'},
			{name:'contingente_desc', mapping:'contingente_desc', type:'string'},
			{name:'contingente_msalvaguardia', mapping:'contingente_msalvaguardia', type:'string'},
			{name:'contingente_salvaguardia_sobretasa', mapping:'contingente_salvaguardia_sobretasa', type:'float'},
			{name:'contingente_acuerdo_det_id', mapping:'contingente_acuerdo_det_id', type:'float'},
			{name:'contingente_acuerdo_det_acuerdo_id', mapping:'contingente_acuerdo_det_acuerdo_id', type:'float'}
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
				,name:'contingente_id'
				,fieldLabel:'<?= Lang::get('contingente.columns_title.contingente_id'); ?>'
				,id:module+'contingente_id'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'numberfield'
				,name:'contingente_id_pais'
				,fieldLabel:'<?= Lang::get('contingente.columns_title.contingente_id_pais'); ?>'
				,id:module+'contingente_id_pais'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'numberfield'
				,name:'contingente_mcontingente'
				,fieldLabel:'<?= Lang::get('contingente.columns_title.contingente_mcontingente'); ?>'
				,id:module+'contingente_mcontingente'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'textfield'
				,name:'contingente_desc'
				,fieldLabel:'<?= Lang::get('contingente.columns_title.contingente_desc'); ?>'
				,id:module+'contingente_desc'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'textfield'
				,name:'contingente_msalvaguardia'
				,fieldLabel:'<?= Lang::get('contingente.columns_title.contingente_msalvaguardia'); ?>'
				,id:module+'contingente_msalvaguardia'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'numberfield'
				,name:'contingente_salvaguardia_sobretasa'
				,fieldLabel:'<?= Lang::get('contingente.columns_title.contingente_salvaguardia_sobretasa'); ?>'
				,id:module+'contingente_salvaguardia_sobretasa'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'numberfield'
				,name:'contingente_acuerdo_det_id'
				,fieldLabel:'<?= Lang::get('contingente.columns_title.contingente_acuerdo_det_id'); ?>'
				,id:module+'contingente_acuerdo_det_id'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'numberfield'
				,name:'contingente_acuerdo_det_acuerdo_id'
				,fieldLabel:'<?= Lang::get('contingente.columns_title.contingente_acuerdo_det_acuerdo_id'); ?>'
				,id:module+'contingente_acuerdo_det_acuerdo_id'
				,allowBlank:false
			}]
		}]
	}]
});
