<?php
session_start();
include_once('../../lib/config.php');
?>
/*<script>*/
var storePais = new Ext.data.JsonStore({
	url:'pais/list'
	,root:'data'
	,sortInfo:{field:'pais_id',direction:'ASC'}
	,totalProperty:'total'
	,baseParams:{id:'<?= $id; ?>'}
	,fields:[
		{name:'pais_id', type:'float'},
		{name:'pais', type:'string'}
	]
});
var comboPais = new Ext.form.ComboBox({
	hiddenName:'pais'
	,id:module+'comboPais'
	,fieldLabel:'<?= Lang::get('pais.columns_title.pais'); ?>'
	,store:storePais
	,valueField:'pais_id'
	,displayField:'pais_name'
	,typeAhead:true
	,forceSelection:true
	,triggerAction:'all'
	,selectOnFocus:true
	,listeners:{
		select: {
			fn: function(combo,reg){
				Ext.getCmp(module + 'pais_id').setValue(reg.data.pais_id);
			}
		}
	}
});
var cmPais = new Ext.grid.ColumnModel({
	columns:[
		{xtype:'numbercolumn', header:'<?= Lang::get('pais.columns_title.pais_id'); ?>', align:'right', hidden:false, dataIndex:'pais_id'},
		{header:'<?= Lang::get('pais.columns_title.pais'); ?>', align:'left', hidden:false, dataIndex:'pais'}
	]
	,defaults:{
		sortable:true
		,width:100
	}
});
var tbPais = new Ext.Toolbar();

var gridPais = new Ext.grid.GridPanel({
	store:storePais
	,id:module+'gridPais'
	,colModel:cmPais
	,viewConfig: {
		forceFit: true
		,scrollOffset:2
	}
	,sm:new Ext.grid.RowSelectionModel({singleSelect:true})
	,bbar:new Ext.PagingToolbar({pageSize:10, store:storePais, displayInfo:true})
	,tbar:tbPais
	,loadMask:true
	,border:false
	,title:''
	,iconCls:'icon-grid'
	,plugins:[new Ext.ux.grid.Excel()]
});
var formPais = new Ext.FormPanel({
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
			{name:'pais_id', mapping:'pais_id', type:'float'},
			{name:'pais', mapping:'pais', type:'string'}
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
				,name:'pais_id'
				,fieldLabel:'<?= Lang::get('pais.columns_title.pais_id'); ?>'
				,id:module+'pais_id'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'textfield'
				,name:'pais'
				,fieldLabel:'<?= Lang::get('pais.columns_title.pais'); ?>'
				,id:module+'pais'
				,allowBlank:false
			}]
		}]
	}]
});
