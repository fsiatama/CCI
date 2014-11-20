<?php
session_start();
include_once('../../lib/config.php');
?>
/*<script>*/
var storeCorrelativa = new Ext.data.JsonStore({
	url:'correlativa/list'
	,root:'data'
	,sortInfo:{field:'correlativa_id',direction:'ASC'}
	,totalProperty:'total'
	,baseParams:{id:'<?= $id; ?>'}
	,fields:[
		{name:'correlativa_id', type:'float'},
		{name:'correlativa_fvigente', type:'string', dateFormat:'Y-m-d'},
		{name:'correlativa_decreto', type:'string'},
		{name:'correlativa_observacion', type:'string'},
		{name:'correlativa_origen', type:'string'},
		{name:'correlativa_destino', type:'string'},
		{name:'correlativa_uinsert', type:'float'},
		{name:'correlativa_finsert', type:'date', dateFormat:'Y-m-d H:i:s'},
		{name:'correlativa_uupdate', type:'float'},
		{name:'correlativa_fupdate', type:'date', dateFormat:'Y-m-d H:i:s'}
	]
});
var comboCorrelativa = new Ext.form.ComboBox({
	hiddenName:'correlativa'
	,id:module+'comboCorrelativa'
	,fieldLabel:'<?= Lang::get('correlativa.columns_title.correlativa_fupdate'); ?>'
	,store:storeCorrelativa
	,valueField:'correlativa_id'
	,displayField:'correlativa_name'
	,typeAhead:true
	,forceSelection:true
	,triggerAction:'all'
	,selectOnFocus:true
	,listeners:{
		select: {
			fn: function(combo,reg){
				Ext.getCmp(module + 'correlativa_id').setValue(reg.data.correlativa_id);
			}
		}
	}
});
var cmCorrelativa = new Ext.grid.ColumnModel({
	columns:[
		{xtype:'numbercolumn', header:'<?= Lang::get('correlativa.columns_title.correlativa_id'); ?>', align:'right', hidden:false, dataIndex:'correlativa_id'},
		{xtype:'datecolumn', header:'<?= Lang::get('correlativa.columns_title.correlativa_fvigente'); ?>', align:'left', hidden:false, dataIndex:'correlativa_fvigente', format:'Y-m-d'},
		{header:'<?= Lang::get('correlativa.columns_title.correlativa_decreto'); ?>', align:'left', hidden:false, dataIndex:'correlativa_decreto'},
		{header:'<?= Lang::get('correlativa.columns_title.correlativa_observacion'); ?>', align:'left', hidden:false, dataIndex:'correlativa_observacion'},
		{header:'<?= Lang::get('correlativa.columns_title.correlativa_origen'); ?>', align:'left', hidden:false, dataIndex:'correlativa_origen'},
		{header:'<?= Lang::get('correlativa.columns_title.correlativa_destino'); ?>', align:'left', hidden:false, dataIndex:'correlativa_destino'},
		{xtype:'numbercolumn', header:'<?= Lang::get('correlativa.columns_title.correlativa_uinsert'); ?>', align:'right', hidden:false, dataIndex:'correlativa_uinsert'},
		{xtype:'datecolumn', header:'<?= Lang::get('correlativa.columns_title.correlativa_finsert'); ?>', align:'left', hidden:false, dataIndex:'correlativa_finsert', format:'Y-m-d, g:i a'},
		{xtype:'numbercolumn', header:'<?= Lang::get('correlativa.columns_title.correlativa_uupdate'); ?>', align:'right', hidden:false, dataIndex:'correlativa_uupdate'},
		{xtype:'datecolumn', header:'<?= Lang::get('correlativa.columns_title.correlativa_fupdate'); ?>', align:'left', hidden:false, dataIndex:'correlativa_fupdate', format:'Y-m-d, g:i a'}
	]
	,defaults:{
		sortable:true
		,width:100
	}
});
var tbCorrelativa = new Ext.Toolbar();

var gridCorrelativa = new Ext.grid.GridPanel({
	store:storeCorrelativa
	,id:module+'gridCorrelativa'
	,colModel:cmCorrelativa
	,viewConfig: {
		forceFit: true
		,scrollOffset:2
	}
	,sm:new Ext.grid.RowSelectionModel({singleSelect:true})
	,bbar:new Ext.PagingToolbar({pageSize:10, store:storeCorrelativa, displayInfo:true})
	,tbar:tbCorrelativa
	,loadMask:true
	,border:false
	,title:''
	,iconCls:'icon-grid'
	,plugins:[new Ext.ux.grid.Excel()]
});
var formCorrelativa = new Ext.FormPanel({
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
			{name:'correlativa_id', mapping:'correlativa_id', type:'float'},
			{name:'correlativa_fvigente', mapping:'correlativa_fvigente', type:'string'},
			{name:'correlativa_decreto', mapping:'correlativa_decreto', type:'string'},
			{name:'correlativa_observacion', mapping:'correlativa_observacion', type:'string'},
			{name:'correlativa_origen', mapping:'correlativa_origen', type:'string'},
			{name:'correlativa_destino', mapping:'correlativa_destino', type:'string'},
			{name:'correlativa_uinsert', mapping:'correlativa_uinsert', type:'float'},
			{name:'correlativa_finsert', mapping:'correlativa_finsert', type:'date'},
			{name:'correlativa_uupdate', mapping:'correlativa_uupdate', type:'float'},
			{name:'correlativa_fupdate', mapping:'correlativa_fupdate', type:'date'}
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
				,name:'correlativa_id'
				,fieldLabel:'<?= Lang::get('correlativa.columns_title.correlativa_id'); ?>'
				,id:module+'correlativa_id'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'datefield'
				,name:'correlativa_fvigente'
				,fieldLabel:'<?= Lang::get('correlativa.columns_title.correlativa_fvigente'); ?>'
				,id:module+'correlativa_fvigente'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'textfield'
				,name:'correlativa_decreto'
				,fieldLabel:'<?= Lang::get('correlativa.columns_title.correlativa_decreto'); ?>'
				,id:module+'correlativa_decreto'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'textfield'
				,name:'correlativa_observacion'
				,fieldLabel:'<?= Lang::get('correlativa.columns_title.correlativa_observacion'); ?>'
				,id:module+'correlativa_observacion'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'textfield'
				,name:'correlativa_origen'
				,fieldLabel:'<?= Lang::get('correlativa.columns_title.correlativa_origen'); ?>'
				,id:module+'correlativa_origen'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'textfield'
				,name:'correlativa_destino'
				,fieldLabel:'<?= Lang::get('correlativa.columns_title.correlativa_destino'); ?>'
				,id:module+'correlativa_destino'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'numberfield'
				,name:'correlativa_uinsert'
				,fieldLabel:'<?= Lang::get('correlativa.columns_title.correlativa_uinsert'); ?>'
				,id:module+'correlativa_uinsert'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'datefield'
				,name:'correlativa_finsert'
				,fieldLabel:'<?= Lang::get('correlativa.columns_title.correlativa_finsert'); ?>'
				,id:module+'correlativa_finsert'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'numberfield'
				,name:'correlativa_uupdate'
				,fieldLabel:'<?= Lang::get('correlativa.columns_title.correlativa_uupdate'); ?>'
				,id:module+'correlativa_uupdate'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'datefield'
				,name:'correlativa_fupdate'
				,fieldLabel:'<?= Lang::get('correlativa.columns_title.correlativa_fupdate'); ?>'
				,id:module+'correlativa_fupdate'
				,allowBlank:false
			}]
		}]
	}]
});
