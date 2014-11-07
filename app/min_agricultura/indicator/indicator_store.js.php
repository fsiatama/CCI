<?php
session_start();
include_once('../../lib/config.php');
?>
/*<script>*/
var storeIndicator = new Ext.data.JsonStore({
	url:'proceso/indicator/'
	,root:'datos'
	,sortInfo:{field:'indicator_id',direction:'ASC'}
	,totalProperty:'total'
	,baseParams:{accion:'lista'}
	,fields:[
		{name:'indicator_id', type:'float'},
		{name:'indicator_name', type:'string'}
	]
});
var comboIndicator = new Ext.form.ComboBox({
	hiddenName:'indicator'
	,id:modulo+'comboIndicator'
	,fieldLabel:'<?php print _INDICATOR; ?>'
	,store:storeIndicator
	,valueField:'indicator_id'
	,displayField:'indicator_nombre'
	,typeAhead:true
	,forceSelection:true
	,triggerAction:'all'
	,selectOnFocus:true
});
var cmIndicator = new Ext.grid.ColumnModel({
	columns:[
		{xtype:'numbercolumn', header:'<?php print _INDICATOR_ID; ?>', align:'right', hidden:false, dataIndex:'indicator_id'},
		{header:'<?php print _INDICATOR_NAME; ?>', align:'left', hidden:false, dataIndex:'indicator_name'}
	]
	,defaults:{
		sortable:true
		,width:100
	}
});
var tbIndicator = new Ext.Toolbar();

var gridIndicator = new Ext.grid.GridPanel({
	store:storeIndicator
	,id:modulo+'gridIndicator'
	,colModel:cmIndicator
	,viewConfig: {
		forceFit: true
		,scrollOffset:2
	}
	,sm:new Ext.grid.RowSelectionModel({singleSelect:true})
	,bbar:new Ext.PagingToolbar({pageSize:10, store:storeIndicator, displayInfo:true})
	,tbar:tbIndicator
	,loadMask:true
	,border:false
	,title:''
	,iconCls:'icon-grid'
	,plugins:[new Ext.ux.grid.Excel()]
});
var formIndicator = new Ext.FormPanel({
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
			{name:'indicator_id', mapping:'indicator_id', type:'float'},
			{name:'indicator_name', mapping:'indicator_name', type:'string'}
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
				,name:'indicator_id'
				,fieldLabel:'<?php print _INDICATOR_ID; ?>'
				,id:modulo+'indicator_id'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				,xtype:'textfield'
				,name:'indicator_name'
				,fieldLabel:'<?php print _INDICATOR_NAME; ?>'
				,id:modulo+'indicator_name'
				,allowBlank:false
			}]
		}]
	}]
});
