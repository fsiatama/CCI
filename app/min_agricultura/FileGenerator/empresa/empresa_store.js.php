<?php
session_start();
include_once('../../lib/config.php');
?>
/*<script>*/
var storeEmpresa = new Ext.data.JsonStore({
	url:'empresa/list'
	,root:'data'
	,sortInfo:{field:'id_empresa',direction:'ASC'}
	,totalProperty:'total'
	,baseParams:{id:'<?= $id; ?>'}
	,fields:[
		{name:'id_empresa', type:'string'},
		{name:'digito_cheq', type:'string'},
		{name:'empresa', type:'string'},
		{name:'representante', type:'string'},
		{name:'id_departamentos', type:'float'},
		{name:'departamentos', type:'string'},
		{name:'id_ciudad', type:'float'},
		{name:'ciudad', type:'string'},
		{name:'direccion', type:'string'},
		{name:'telefono', type:'string'},
		{name:'telefono2', type:'string'},
		{name:'telefono3', type:'string'},
		{name:'fax', type:'string'},
		{name:'fax2', type:'string'},
		{name:'fax3', type:'string'},
		{name:'email', type:'string'},
		{name:'clase', type:'string'},
		{name:'uap', type:'string'},
		{name:'altex', type:'string'},
		{name:'web', type:'string'},
		{name:'contacto1', type:'string'},
		{name:'id_tipo_empresa', type:'string'}
	]
});
var comboEmpresa = new Ext.form.ComboBox({
	hiddenName:'empresa'
	,id:module+'comboEmpresa'
	,fieldLabel:'<?= Lang::get('empresa.columns_title.id_tipo_empresa'); ?>'
	,store:storeEmpresa
	,valueField:'id_empresa'
	,displayField:'empresa_name'
	,typeAhead:true
	,forceSelection:true
	,triggerAction:'all'
	,selectOnFocus:true
	,allowBlank:false
	,listeners:{
		select: {
			fn: function(combo,reg){
				Ext.getCmp(module + 'id_empresa').setValue(reg.data.id_empresa);
			}
		}
	}
});
var cmEmpresa = new Ext.grid.ColumnModel({
	columns:[
		{header:'<?= Lang::get('empresa.columns_title.id_empresa'); ?>', align:'left', hidden:false, dataIndex:'id_empresa'},
		{header:'<?= Lang::get('empresa.columns_title.digito_cheq'); ?>', align:'left', hidden:false, dataIndex:'digito_cheq'},
		{header:'<?= Lang::get('empresa.columns_title.empresa'); ?>', align:'left', hidden:false, dataIndex:'empresa'},
		{header:'<?= Lang::get('empresa.columns_title.representante'); ?>', align:'left', hidden:false, dataIndex:'representante'},
		{xtype:'numbercolumn', header:'<?= Lang::get('empresa.columns_title.id_departamentos'); ?>', align:'right', hidden:false, dataIndex:'id_departamentos'},
		{header:'<?= Lang::get('empresa.columns_title.departamentos'); ?>', align:'left', hidden:false, dataIndex:'departamentos'},
		{xtype:'numbercolumn', header:'<?= Lang::get('empresa.columns_title.id_ciudad'); ?>', align:'right', hidden:false, dataIndex:'id_ciudad'},
		{header:'<?= Lang::get('empresa.columns_title.ciudad'); ?>', align:'left', hidden:false, dataIndex:'ciudad'},
		{header:'<?= Lang::get('empresa.columns_title.direccion'); ?>', align:'left', hidden:false, dataIndex:'direccion'},
		{header:'<?= Lang::get('empresa.columns_title.telefono'); ?>', align:'left', hidden:false, dataIndex:'telefono'},
		{header:'<?= Lang::get('empresa.columns_title.telefono2'); ?>', align:'left', hidden:false, dataIndex:'telefono2'},
		{header:'<?= Lang::get('empresa.columns_title.telefono3'); ?>', align:'left', hidden:false, dataIndex:'telefono3'},
		{header:'<?= Lang::get('empresa.columns_title.fax'); ?>', align:'left', hidden:false, dataIndex:'fax'},
		{header:'<?= Lang::get('empresa.columns_title.fax2'); ?>', align:'left', hidden:false, dataIndex:'fax2'},
		{header:'<?= Lang::get('empresa.columns_title.fax3'); ?>', align:'left', hidden:false, dataIndex:'fax3'},
		{header:'<?= Lang::get('empresa.columns_title.email'); ?>', align:'left', hidden:false, dataIndex:'email'},
		{header:'<?= Lang::get('empresa.columns_title.clase'); ?>', align:'left', hidden:false, dataIndex:'clase'},
		{header:'<?= Lang::get('empresa.columns_title.uap'); ?>', align:'left', hidden:false, dataIndex:'uap'},
		{header:'<?= Lang::get('empresa.columns_title.altex'); ?>', align:'left', hidden:false, dataIndex:'altex'},
		{header:'<?= Lang::get('empresa.columns_title.web'); ?>', align:'left', hidden:false, dataIndex:'web'},
		{header:'<?= Lang::get('empresa.columns_title.contacto1'); ?>', align:'left', hidden:false, dataIndex:'contacto1'},
		{header:'<?= Lang::get('empresa.columns_title.id_tipo_empresa'); ?>', align:'left', hidden:false, dataIndex:'id_tipo_empresa'}
	]
	,defaults:{
		sortable:true
		,width:100
	}
});
var tbEmpresa = new Ext.Toolbar();

var gridEmpresa = new Ext.grid.GridPanel({
	store:storeEmpresa
	,id:module+'gridEmpresa'
	,colModel:cmEmpresa
	,viewConfig: {
		forceFit: true
		,scrollOffset:2
	}
	,sm:new Ext.grid.RowSelectionModel({singleSelect:true})
	,bbar:new Ext.PagingToolbar({pageSize:10, store:storeEmpresa, displayInfo:true})
	,tbar:tbEmpresa
	,loadMask:true
	,border:false
	,title:''
	,iconCls:'icon-grid'
	,plugins:[new Ext.ux.grid.Excel()]
});
var formEmpresa = new Ext.FormPanel({
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
			{name:'id_empresa', mapping:'id_empresa', type:'string'},
			{name:'digito_cheq', mapping:'digito_cheq', type:'string'},
			{name:'empresa', mapping:'empresa', type:'string'},
			{name:'representante', mapping:'representante', type:'string'},
			{name:'id_departamentos', mapping:'id_departamentos', type:'float'},
			{name:'departamentos', mapping:'departamentos', type:'string'},
			{name:'id_ciudad', mapping:'id_ciudad', type:'float'},
			{name:'ciudad', mapping:'ciudad', type:'string'},
			{name:'direccion', mapping:'direccion', type:'string'},
			{name:'telefono', mapping:'telefono', type:'string'},
			{name:'telefono2', mapping:'telefono2', type:'string'},
			{name:'telefono3', mapping:'telefono3', type:'string'},
			{name:'fax', mapping:'fax', type:'string'},
			{name:'fax2', mapping:'fax2', type:'string'},
			{name:'fax3', mapping:'fax3', type:'string'},
			{name:'email', mapping:'email', type:'string'},
			{name:'clase', mapping:'clase', type:'string'},
			{name:'uap', mapping:'uap', type:'string'},
			{name:'altex', mapping:'altex', type:'string'},
			{name:'web', mapping:'web', type:'string'},
			{name:'contacto1', mapping:'contacto1', type:'string'},
			{name:'id_tipo_empresa', mapping:'id_tipo_empresa', type:'string'}
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
				xtype:'textfield'
				,name:'id_empresa'
				,fieldLabel:'<?= Lang::get('empresa.columns_title.id_empresa'); ?>'
				,id:module+'id_empresa'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'textfield'
				,name:'digito_cheq'
				,fieldLabel:'<?= Lang::get('empresa.columns_title.digito_cheq'); ?>'
				,id:module+'digito_cheq'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'textfield'
				,name:'empresa'
				,fieldLabel:'<?= Lang::get('empresa.columns_title.empresa'); ?>'
				,id:module+'empresa'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'textfield'
				,name:'representante'
				,fieldLabel:'<?= Lang::get('empresa.columns_title.representante'); ?>'
				,id:module+'representante'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'numberfield'
				,name:'id_departamentos'
				,fieldLabel:'<?= Lang::get('empresa.columns_title.id_departamentos'); ?>'
				,id:module+'id_departamentos'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'textfield'
				,name:'departamentos'
				,fieldLabel:'<?= Lang::get('empresa.columns_title.departamentos'); ?>'
				,id:module+'departamentos'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'numberfield'
				,name:'id_ciudad'
				,fieldLabel:'<?= Lang::get('empresa.columns_title.id_ciudad'); ?>'
				,id:module+'id_ciudad'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'textfield'
				,name:'ciudad'
				,fieldLabel:'<?= Lang::get('empresa.columns_title.ciudad'); ?>'
				,id:module+'ciudad'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'textfield'
				,name:'direccion'
				,fieldLabel:'<?= Lang::get('empresa.columns_title.direccion'); ?>'
				,id:module+'direccion'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'textfield'
				,name:'telefono'
				,fieldLabel:'<?= Lang::get('empresa.columns_title.telefono'); ?>'
				,id:module+'telefono'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'textfield'
				,name:'telefono2'
				,fieldLabel:'<?= Lang::get('empresa.columns_title.telefono2'); ?>'
				,id:module+'telefono2'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'textfield'
				,name:'telefono3'
				,fieldLabel:'<?= Lang::get('empresa.columns_title.telefono3'); ?>'
				,id:module+'telefono3'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'textfield'
				,name:'fax'
				,fieldLabel:'<?= Lang::get('empresa.columns_title.fax'); ?>'
				,id:module+'fax'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'textfield'
				,name:'fax2'
				,fieldLabel:'<?= Lang::get('empresa.columns_title.fax2'); ?>'
				,id:module+'fax2'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'textfield'
				,name:'fax3'
				,fieldLabel:'<?= Lang::get('empresa.columns_title.fax3'); ?>'
				,id:module+'fax3'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'textfield'
				,name:'email'
				,fieldLabel:'<?= Lang::get('empresa.columns_title.email'); ?>'
				,id:module+'email'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'textfield'
				,name:'clase'
				,fieldLabel:'<?= Lang::get('empresa.columns_title.clase'); ?>'
				,id:module+'clase'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'textfield'
				,name:'uap'
				,fieldLabel:'<?= Lang::get('empresa.columns_title.uap'); ?>'
				,id:module+'uap'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'textfield'
				,name:'altex'
				,fieldLabel:'<?= Lang::get('empresa.columns_title.altex'); ?>'
				,id:module+'altex'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'textfield'
				,name:'web'
				,fieldLabel:'<?= Lang::get('empresa.columns_title.web'); ?>'
				,id:module+'web'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'textfield'
				,name:'contacto1'
				,fieldLabel:'<?= Lang::get('empresa.columns_title.contacto1'); ?>'
				,id:module+'contacto1'
				,allowBlank:false
			}]
		},{
			defaults:{anchor:'100%'}
			,items:[{
				xtype:'textfield'
				,name:'id_tipo_empresa'
				,fieldLabel:'<?= Lang::get('empresa.columns_title.id_tipo_empresa'); ?>'
				,id:module+'id_tipo_empresa'
				,allowBlank:false
			}]
		}]
	}]
});
