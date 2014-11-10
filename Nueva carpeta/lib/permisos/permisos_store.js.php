<?php
session_start();
include_once ('../../lib/config.php');
?>
/*<script>*/
var storePermisos = new Ext.data.JsonStore({
     url:'proceso/permisos/'
    ,root:'datos'
    ,sortInfo:{field:'permisos_id',direction:'ASC'}
    ,totalProperty:'total'
    ,baseParams:{accion:'lista'}
    ,fields:[
		{name:'permisos_id', type:'float'},
		{name:'permisos_perfil_id', type:'float'},
		{name:'permisos_opc_menu_id', type:'float'},
		{name:'permisos_listar', type:'float'},
		{name:'permisos_modificar', type:'float'},
		{name:'permisos_crear', type:'float'},
		{name:'permisos_borrar', type:'float'},
		{name:'permisos_exportar', type:'float'}
	]
});
var comboPermisos = new Ext.form.ComboBox({
	hiddenName:'permisos'
	,id:modulo+'comboPermisos'
	,fieldLabel:'Permisos'
	,store: storePermisos
	,valueField:'permisos_id'
	,displayField:'permisos_nombre'
	,typeAhead: true
	,forceSelection: true
	,triggerAction: 'all'
	,selectOnFocus: true
});
var cmPermisos = new Ext.grid.ColumnModel({
	columns:[
		{header:'permisos_id', align:'right', hidden:false, dataIndex: 'permisos_id'},
		{header:'permisos_perfil_id', align:'right', hidden:false, dataIndex: 'permisos_perfil_id'},
		{header:'permisos_opc_menu_id', align:'right', hidden:false, dataIndex: 'permisos_opc_menu_id'},
		{header:'permisos_listar', align:'right', hidden:false, dataIndex: 'permisos_listar'},
		{header:'permisos_modificar', align:'right', hidden:false, dataIndex: 'permisos_modificar'},
		{header:'permisos_crear', align:'right', hidden:false, dataIndex: 'permisos_crear'},
		{header:'permisos_borrar', align:'right', hidden:false, dataIndex: 'permisos_borrar'},
		{header:'permisos_exportar', align:'right', hidden:false, dataIndex: 'permisos_exportar'}
	]
	,defaults:{
		sortable:true
		,width:100
	}
});
var tbPermisos = new Ext.Toolbar();

var gridPermisos = new Ext.grid.GridPanel({
	store:storePermisos
	,id:modulo+'gridPermisos'
	,colModel:cmPermisos
	,viewConfig: {
		forceFit: true
		,scrollOffset:2
	}
	,sm:new Ext.grid.RowSelectionModel({singleSelect:true})
	,bbar:new Ext.PagingToolbar({pageSize:10, store:storePermisos, displayInfo:true})
	,tbar:tbPermisos
	,loadMask:true
	,border:false
	,title:''
	,iconCls:'icon-grid'
	,plugins:[new Ext.ux.grid.Excel()]
});
