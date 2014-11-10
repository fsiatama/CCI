<?php
session_start();
include_once('../../lib/config.php');
?>
/*<script>*/
var storeSession = new Ext.data.JsonStore({
	url:'proceso/session/'
	,root:'datos'
	,sortInfo:{field:'session_user_id',direction:'ASC'}
	,totalProperty:'total'
	,baseParams:{accion:'lista'}
	,fields:[
		{name:'session_user_id', type:'float'},
		{name:'session_php_id', type:'string'},
		{name:'session_date', type:'date', dateFormat:'Y-m-d H:i:s'},
		{name:'session_active', type:'string'}
	]
});
var comboSession = new Ext.form.ComboBox({
	hiddenName:'session'
	,id:modulo+'comboSession'
	,fieldLabel:'<?php print _SESSION; ?>'
	,store:storeSession
	,valueField:'session_user_id'
	,displayField:'session_nombre'
	,typeAhead:true
	,forceSelection:true
	,triggerAction:'all'
	,selectOnFocus:true
});
var cmSession = new Ext.grid.ColumnModel({
	columns:[
		{xtype:'numbercolumn', header:'<?php print _SESSION_USER_ID; ?>', align:'right', hidden:false, dataIndex:'session_user_id'},
		{header:'<?php print _SESSION_PHP_ID; ?>', align:'left', hidden:false, dataIndex:'session_php_id'},
		{xtype:'datecolumn', header:'<?php print _SESSION_DATE; ?>', align:'left', hidden:false, dataIndex:'session_date', format:'Y-m-d, g:i a'},
		{header:'<?php print _SESSION_ACTIVE; ?>', align:'left', hidden:false, dataIndex:'session_active'}
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
var formSession = new Ext.FormPanel({
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
			{name:'session_user_id', mapping:'session_user_id', type:'float'},
			{name:'session_php_id', mapping:'session_php_id', type:'string'},
			{name:'session_date', mapping:'session_date', type:'date'},
			{name:'session_active', mapping:'session_active', type:'string'}
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
				,name:'session_user_id'
				,fieldLabel:'<?php print _SESSION_USER_ID; ?>'
				,id:modulo+'session_user_id'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				,xtype:'textfield'
				,name:'session_php_id'
				,fieldLabel:'<?php print _SESSION_PHP_ID; ?>'
				,id:modulo+'session_php_id'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				,xtype:'datefield'
				,name:'session_date'
				,fieldLabel:'<?php print _SESSION_DATE; ?>'
				,id:modulo+'session_date'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				,xtype:'datefield'
				,name:'session_active'
				,fieldLabel:'<?php print _SESSION_ACTIVE; ?>'
				,id:modulo+'session_active'
				,allowBlank:false
			}]
		}]
	}]
});
