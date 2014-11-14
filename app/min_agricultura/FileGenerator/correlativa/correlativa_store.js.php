<?php
session_start();
include_once('../../lib/config.php');
?>
/*<script>*/
var storeCorrelativa = new Ext.data.JsonStore({
	url:'proceso/correlativa/'
	,root:'datos'
	,sortInfo:{field:'correlativa_id',direction:'ASC'}
	,totalProperty:'total'
	,baseParams:{accion:'lista'}
	,fields:[
		{name:'correlativa_id', type:'float'},
		{name:'correlativa_origen_posicion_id', type:'string'},
		{name:'correlativa_destino_posicion_id', type:'string'},
		{name:'correlativa_fvigente', type:'string', dateFormat:'Y-m-d'},
		{name:'correlativa_decreto', type:'string'},
		{name:'correlativa_observacion', type:'string'},
		{name:'correlativa_uinsert', type:'float'},
		{name:'correlativa_finsert', type:'date', dateFormat:'Y-m-d H:i:s'}
	]
});
var comboCorrelativa = new Ext.form.ComboBox({
	hiddenName:'correlativa'
	,id:modulo+'comboCorrelativa'
	,fieldLabel:'<?php print _CORRELATIVA; ?>'
	,store:storeCorrelativa
	,valueField:'correlativa_id'
	,displayField:'correlativa_nombre'
	,typeAhead:true
	,forceSelection:true
	,triggerAction:'all'
	,selectOnFocus:true
});
var cmCorrelativa = new Ext.grid.ColumnModel({
	columns:[
		{xtype:'numbercolumn', header:'<?php print _CORRELATIVA_ID; ?>', align:'right', hidden:false, dataIndex:'correlativa_id'},
		{header:'<?php print _CORRELATIVA_ORIGEN_POSICION_ID; ?>', align:'left', hidden:false, dataIndex:'correlativa_origen_posicion_id'},
		{header:'<?php print _CORRELATIVA_DESTINO_POSICION_ID; ?>', align:'left', hidden:false, dataIndex:'correlativa_destino_posicion_id'},
		{xtype:'datecolumn', header:'<?php print _CORRELATIVA_FVIGENTE; ?>', align:'left', hidden:false, dataIndex:'correlativa_fvigente', format:'Y-m-d'},
		{header:'<?php print _CORRELATIVA_DECRETO; ?>', align:'left', hidden:false, dataIndex:'correlativa_decreto'},
		{header:'<?php print _CORRELATIVA_OBSERVACION; ?>', align:'left', hidden:false, dataIndex:'correlativa_observacion'},
		{xtype:'numbercolumn', header:'<?php print _CORRELATIVA_UINSERT; ?>', align:'right', hidden:false, dataIndex:'correlativa_uinsert'},
		{xtype:'datecolumn', header:'<?php print _CORRELATIVA_FINSERT; ?>', align:'left', hidden:false, dataIndex:'correlativa_finsert', format:'Y-m-d, g:i a'}
	]
	,defaults:{
		sortable:true
		,width:100
	}
});
var tbCorrelativa = new Ext.Toolbar();

var gridCorrelativa = new Ext.grid.GridPanel({
	store:storeCorrelativa
	,id:modulo+'gridCorrelativa'
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
		root:'datos'
		,totalProperty:'total'
		,fields:[
			{name:'correlativa_id', mapping:'correlativa_id', type:'float'},
			{name:'correlativa_origen_posicion_id', mapping:'correlativa_origen_posicion_id', type:'string'},
			{name:'correlativa_destino_posicion_id', mapping:'correlativa_destino_posicion_id', type:'string'},
			{name:'correlativa_fvigente', mapping:'correlativa_fvigente', type:'string'},
			{name:'correlativa_decreto', mapping:'correlativa_decreto', type:'string'},
			{name:'correlativa_observacion', mapping:'correlativa_observacion', type:'string'},
			{name:'correlativa_uinsert', mapping:'correlativa_uinsert', type:'float'},
			{name:'correlativa_finsert', mapping:'correlativa_finsert', type:'date'}
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
				,name:'correlativa_id'
				,fieldLabel:'<?php print _CORRELATIVA_ID; ?>'
				,id:modulo+'correlativa_id'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				,xtype:'textfield'
				,name:'correlativa_origen_posicion_id'
				,fieldLabel:'<?php print _CORRELATIVA_ORIGEN_POSICION_ID; ?>'
				,id:modulo+'correlativa_origen_posicion_id'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				,xtype:'textfield'
				,name:'correlativa_destino_posicion_id'
				,fieldLabel:'<?php print _CORRELATIVA_DESTINO_POSICION_ID; ?>'
				,id:modulo+'correlativa_destino_posicion_id'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				,xtype:'datefield'
				,name:'correlativa_fvigente'
				,fieldLabel:'<?php print _CORRELATIVA_FVIGENTE; ?>'
				,id:modulo+'correlativa_fvigente'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				,xtype:'textfield'
				,name:'correlativa_decreto'
				,fieldLabel:'<?php print _CORRELATIVA_DECRETO; ?>'
				,id:modulo+'correlativa_decreto'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				,xtype:'textfield'
				,name:'correlativa_observacion'
				,fieldLabel:'<?php print _CORRELATIVA_OBSERVACION; ?>'
				,id:modulo+'correlativa_observacion'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				,xtype:'numberfield'
				,name:'correlativa_uinsert'
				,fieldLabel:'<?php print _CORRELATIVA_UINSERT; ?>'
				,id:modulo+'correlativa_uinsert'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				,xtype:'datefield'
				,name:'correlativa_finsert'
				,fieldLabel:'<?php print _CORRELATIVA_FINSERT; ?>'
				,id:modulo+'correlativa_finsert'
				,allowBlank:false
			}]
		}]
	}]
});
