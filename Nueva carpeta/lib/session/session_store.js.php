<?php
session_start();
include_once ('../../lib/config.php');
?>
/*<script>*/
var storeSession = new Ext.data.JsonStore({
     url:'proceso/session/'
    ,root:'datos'
    ,sortInfo:{field:'session_id',direction:'ASC'}
    ,totalProperty:'total'
    ,baseParams:{accion:'lista'}
    ,fields:[
		{name:'session_usuario_id', type:'float'},
		{name:'session_php_id', type:'string'},
		{name:'session_date', type:'string'},
		{name:'session_activa', type:'string'}
	]
});
var comboSession = new Ext.form.ComboBox({
	hiddenName:'session'
	,id:modulo+'comboSession'
	,fieldLabel:'Session'
	,store: storeSession
	,valueField:'session_id'
	,displayField:'session_nombre'
	,typeAhead: true
	,forceSelection: true
	,triggerAction: 'all'
	,selectOnFocus: true
});
var cmSession = new Ext.grid.ColumnModel({
	columns:[
		{header:'session_usuario_id', align:'right', hidden:false, dataIndex: 'session_usuario_id'},
		{header:'session_php_id', align:'left', hidden:false, dataIndex: 'session_php_id'},
		{header:'session_date', align:'left', hidden:false, dataIndex: 'session_date'},
		{header:'session_activa', align:'left', hidden:false, dataIndex: 'session_activa'}
	]
	,defaults:{
		sortable:true
		,width:100
	}
});
var tbSession = new Ext.Toolbar();

var gridSession = new Ext.grid.GridPanel({
	store:storeSession
	,id:modulo+'gridSession'
	,colModel:cmSession
	,viewConfig: {
		forceFit: true
		,scrollOffset:2
	}
	,sm:new Ext.grid.RowSelectionModel({singleSelect:true})
	,bbar:new Ext.PagingToolbar({pageSize:10, store:storeSession, displayInfo:true})
	,tbar:tbSession
	,loadMask:true
	,border:false
	,title:''
	,iconCls:'icon-grid'
	,plugins:[new Ext.ux.grid.Excel()]
});
