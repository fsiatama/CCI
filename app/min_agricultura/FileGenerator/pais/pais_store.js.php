<?php
session_start();
include_once('../../lib/config.php');
?>
/*<script>*/
var storePais = new Ext.data.JsonStore({
	url:'proceso/pais/'
	,root:'datos'
	,sortInfo:{field:'pais_id',direction:'ASC'}
	,totalProperty:'total'
	,baseParams:{accion:'lista'}
	,fields:[
		{name:'pais_id', type:'float'},
		{name:'pais', type:'string'}
	]
});
var comboPais = new Ext.form.ComboBox({
	hiddenName:'pais'
	,id:modulo+'comboPais'
	,fieldLabel:'<?php print _PAIS; ?>'
	,store:storePais
	,valueField:'pais_id'
	,displayField:'pais_nombre'
	,typeAhead:true
	,forceSelection:true
	,triggerAction:'all'
	,selectOnFocus:true
});
var cmPais = new Ext.grid.ColumnModel({
	columns:[
		{xtype:'numbercolumn', header:'<?php print _PAIS_ID; ?>', align:'right', hidden:false, dataIndex:'pais_id'},
		{header:'<?php print _PAIS; ?>', align:'left', hidden:false, dataIndex:'pais'}
	]
	,defaults:{
		sortable:true
		,width:100
	}
});
var tbPais = new Ext.Toolbar();

var gridPais = new Ext.grid.GridPanel({
	store:storePais
	,id:modulo+'gridPais'
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
		root:'datos'
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
				,xtype:'numberfield'
				,name:'pais_id'
				,fieldLabel:'<?php print _PAIS_ID; ?>'
				,id:modulo+'pais_id'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				,xtype:'textfield'
				,name:'pais'
				,fieldLabel:'<?php print _PAIS; ?>'
				,id:modulo+'pais'
				,allowBlank:false
			}]
		}]
	}]
});
