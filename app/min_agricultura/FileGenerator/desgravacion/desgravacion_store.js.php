<?php
session_start();
include_once('../../lib/config.php');
?>
/*<script>*/
var storeDesgravacion = new Ext.data.JsonStore({
	url:'desgravacion/list'
	,root:'data'
	,sortInfo:{field:'desgravacion_acuerdo_det_acuerdo_id',direction:'ASC'}
	,totalProperty:'total'
	,baseParams:{id:'<?= $id; ?>'}
	,fields:[
		{name:'desgravacion_id', type:'float'},
		{name:'desgravacion_id_pais', type:'float'},
		{name:'desgravacion_mdesgravacion', type:'string'},
		{name:'desgravacion_desc', type:'string'},
		{name:'desgravacion_acuerdo_det_id', type:'float'},
		{name:'desgravacion_acuerdo_det_acuerdo_id', type:'float'}
	]
});
var comboDesgravacion = new Ext.form.ComboBox({
	hiddenName:'desgravacion'
	,id:module+'comboDesgravacion'
	,fieldLabel:'<?= Lang::get('desgravacion.columns_title.desgravacion_acuerdo_det_acuerdo_id'); ?>'
	,store:storeDesgravacion
	,valueField:'desgravacion_acuerdo_det_acuerdo_id'
	,displayField:'desgravacion_name'
	,typeAhead:true
	,forceSelection:true
	,triggerAction:'all'
	,selectOnFocus:true
	,allowBlank:false
	,listeners:{
		select: {
			fn: function(combo,reg){
				Ext.getCmp(module + 'desgravacion_acuerdo_det_acuerdo_id').setValue(reg.data.desgravacion_acuerdo_det_acuerdo_id);
			}
		}
	}
});
var cmDesgravacion = new Ext.grid.ColumnModel({
	columns:[
		{xtype:'numbercolumn', header:'<?= Lang::get('desgravacion.columns_title.desgravacion_id'); ?>', align:'right', hidden:false, dataIndex:'desgravacion_id'},
		{xtype:'numbercolumn', header:'<?= Lang::get('desgravacion.columns_title.desgravacion_id_pais'); ?>', align:'right', hidden:false, dataIndex:'desgravacion_id_pais'},
		{header:'<?= Lang::get('desgravacion.columns_title.desgravacion_mdesgravacion'); ?>', align:'left', hidden:false, dataIndex:'desgravacion_mdesgravacion'},
		{header:'<?= Lang::get('desgravacion.columns_title.desgravacion_desc'); ?>', align:'left', hidden:false, dataIndex:'desgravacion_desc'},
		{xtype:'numbercolumn', header:'<?= Lang::get('desgravacion.columns_title.desgravacion_acuerdo_det_id'); ?>', align:'right', hidden:false, dataIndex:'desgravacion_acuerdo_det_id'},
		{xtype:'numbercolumn', header:'<?= Lang::get('desgravacion.columns_title.desgravacion_acuerdo_det_acuerdo_id'); ?>', align:'right', hidden:false, dataIndex:'desgravacion_acuerdo_det_acuerdo_id'}
	]
	,defaults:{
		sortable:true
		,width:100
	}
});
var tbDesgravacion = new Ext.Toolbar();

var gridDesgravacion = new Ext.grid.GridPanel({
	store:storeDesgravacion
	,id:module+'gridDesgravacion'
	,colModel:cmDesgravacion
	,viewConfig: {
		forceFit: true
		,scrollOffset:2
	}
	,sm:new Ext.grid.RowSelectionModel({singleSelect:true})
	,bbar:new Ext.PagingToolbar({pageSize:10, store:storeDesgravacion, displayInfo:true})
	,tbar:tbDesgravacion
	,loadMask:true
	,border:false
	,title:''
	,iconCls:'icon-grid'
	,plugins:[new Ext.ux.grid.Excel()]
});
var formDesgravacion = new Ext.FormPanel({
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
			{name:'desgravacion_id', mapping:'desgravacion_id', type:'float'},
			{name:'desgravacion_id_pais', mapping:'desgravacion_id_pais', type:'float'},
			{name:'desgravacion_mdesgravacion', mapping:'desgravacion_mdesgravacion', type:'string'},
			{name:'desgravacion_desc', mapping:'desgravacion_desc', type:'string'},
			{name:'desgravacion_acuerdo_det_id', mapping:'desgravacion_acuerdo_det_id', type:'float'},
			{name:'desgravacion_acuerdo_det_acuerdo_id', mapping:'desgravacion_acuerdo_det_acuerdo_id', type:'float'}
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
				,name:'desgravacion_id'
				,fieldLabel:'<?= Lang::get('desgravacion.columns_title.desgravacion_id'); ?>'
				,id:module+'desgravacion_id'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'numberfield'
				,name:'desgravacion_id_pais'
				,fieldLabel:'<?= Lang::get('desgravacion.columns_title.desgravacion_id_pais'); ?>'
				,id:module+'desgravacion_id_pais'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'numberfield'
				,name:'desgravacion_mdesgravacion'
				,fieldLabel:'<?= Lang::get('desgravacion.columns_title.desgravacion_mdesgravacion'); ?>'
				,id:module+'desgravacion_mdesgravacion'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'textfield'
				,name:'desgravacion_desc'
				,fieldLabel:'<?= Lang::get('desgravacion.columns_title.desgravacion_desc'); ?>'
				,id:module+'desgravacion_desc'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'numberfield'
				,name:'desgravacion_acuerdo_det_id'
				,fieldLabel:'<?= Lang::get('desgravacion.columns_title.desgravacion_acuerdo_det_id'); ?>'
				,id:module+'desgravacion_acuerdo_det_id'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'numberfield'
				,name:'desgravacion_acuerdo_det_acuerdo_id'
				,fieldLabel:'<?= Lang::get('desgravacion.columns_title.desgravacion_acuerdo_det_acuerdo_id'); ?>'
				,id:module+'desgravacion_acuerdo_det_acuerdo_id'
				,allowBlank:false
			}]
		}]
	}]
});
