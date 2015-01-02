<?php
session_start();
include_once('../../lib/config.php');
?>
/*<script>*/
var storeComtrade_country = new Ext.data.JsonStore({
	url:'comtrade_country/list'
	,root:'data'
	,sortInfo:{field:'id_country',direction:'ASC'}
	,totalProperty:'total'
	,baseParams:{id:'<?= $id; ?>'}
	,fields:[
		{name:'id_country', type:'float'},
		{name:'country', type:'string'}
	]
});
var comboComtrade_country = new Ext.form.ComboBox({
	hiddenName:'comtrade_country'
	,id:module+'comboComtrade_country'
	,fieldLabel:'<?= Lang::get('comtrade_country.columns_title.country'); ?>'
	,store:storeComtrade_country
	,valueField:'id_country'
	,displayField:'comtrade_country_name'
	,typeAhead:true
	,forceSelection:true
	,triggerAction:'all'
	,selectOnFocus:true
	,allowBlank:false
	,listeners:{
		select: {
			fn: function(combo,reg){
				Ext.getCmp(module + 'id_country').setValue(reg.data.id_country);
			}
		}
	}
});
var cmComtrade_country = new Ext.grid.ColumnModel({
	columns:[
		{xtype:'numbercolumn', header:'<?= Lang::get('comtrade_country.columns_title.id_country'); ?>', align:'right', hidden:false, dataIndex:'id_country'},
		{header:'<?= Lang::get('comtrade_country.columns_title.country'); ?>', align:'left', hidden:false, dataIndex:'country'}
	]
	,defaults:{
		sortable:true
		,width:100
	}
});
var tbComtrade_country = new Ext.Toolbar();

var gridComtrade_country = new Ext.grid.GridPanel({
	store:storeComtrade_country
	,id:module+'gridComtrade_country'
	,colModel:cmComtrade_country
	,viewConfig: {
		forceFit: true
		,scrollOffset:2
	}
	,sm:new Ext.grid.RowSelectionModel({singleSelect:true})
	,bbar:new Ext.PagingToolbar({pageSize:10, store:storeComtrade_country, displayInfo:true})
	,tbar:tbComtrade_country
	,loadMask:true
	,border:false
	,title:''
	,iconCls:'icon-grid'
	,plugins:[new Ext.ux.grid.Excel()]
});
var formComtrade_country = new Ext.FormPanel({
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
			{name:'id_country', mapping:'id_country', type:'float'},
			{name:'country', mapping:'country', type:'string'}
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
				,name:'id_country'
				,fieldLabel:'<?= Lang::get('comtrade_country.columns_title.id_country'); ?>'
				,id:module+'id_country'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'textfield'
				,name:'country'
				,fieldLabel:'<?= Lang::get('comtrade_country.columns_title.country'); ?>'
				,id:module+'country'
				,allowBlank:false
			}]
		}]
	}]
});
