<?php
session_start();
include_once('../../lib/config.php');
?>
/*<script>*/
var storePosicion = new Ext.data.JsonStore({
	url:'proceso/posicion/'
	,root:'datos'
	,sortInfo:{field:'posicion_id',direction:'ASC'}
	,totalProperty:'total'
	,baseParams:{accion:'lista'}
	,fields:[
		{name:'posicion_id', type:'string'},
		{name:'posicion', type:'string'}
	]
});
var comboPosicion = new Ext.form.ComboBox({
	hiddenName:'posicion'
	,id:modulo+'comboPosicion'
	,fieldLabel:'<?php print _POSICION; ?>'
	,store:storePosicion
	,valueField:'posicion_id'
	,displayField:'posicion_nombre'
	,typeAhead:true
	,forceSelection:true
	,triggerAction:'all'
	,selectOnFocus:true
});
var cmPosicion = new Ext.grid.ColumnModel({
	columns:[
		{header:'<?php print _POSICION_ID; ?>', align:'left', hidden:false, dataIndex:'posicion_id'},
		{header:'<?php print _POSICION; ?>', align:'left', hidden:false, dataIndex:'posicion'}
	]
	,defaults:{
		sortable:true
		,width:100
	}
});
var tbPosicion = new Ext.Toolbar();

var gridPosicion = new Ext.grid.GridPanel({
	store:storePosicion
	,id:modulo+'gridPosicion'
	,colModel:cmPosicion
	,viewConfig: {
		forceFit: true
		,scrollOffset:2
	}
	,sm:new Ext.grid.RowSelectionModel({singleSelect:true})
	,bbar:new Ext.PagingToolbar({pageSize:10, store:storePosicion, displayInfo:true})
	,tbar:tbPosicion
	,loadMask:true
	,border:false
	,title:''
	,iconCls:'icon-grid'
	,plugins:[new Ext.ux.grid.Excel()]
});
var formPosicion = new Ext.FormPanel({
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
			{name:'posicion_id', mapping:'posicion_id', type:'string'},
			{name:'posicion', mapping:'posicion', type:'string'}
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
				,xtype:'textfield'
				,name:'posicion_id'
				,fieldLabel:'<?php print _POSICION_ID; ?>'
				,id:modulo+'posicion_id'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				,xtype:'textfield'
				,name:'posicion'
				,fieldLabel:'<?php print _POSICION; ?>'
				,id:modulo+'posicion'
				,allowBlank:false
			}]
		}]
	}]
});
