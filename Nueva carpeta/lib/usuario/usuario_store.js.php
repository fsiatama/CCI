<?php
session_start();
include_once ('../../lib/config.php');
?>
/*<script>*/
var storeUsuario = new Ext.data.JsonStore({
     url:'proceso/usuario/'
    ,root:'datos'
    ,sortInfo:{field:'usuario_id',direction:'ASC'}
    ,totalProperty:'total'
    ,baseParams:{accion:'lista'}
    ,fields:[
		{name:'usuario_id', type:'float'},
		{name:'usuario_pnombre', type:'string'},
		{name:'usuario_snombre', type:'string'},
		{name:'usuario_papellido', type:'string'},
		{name:'usuario_sapellido', type:'string'},
		{name:'usuario_email', type:'string'},
		{name:'usuario_password', type:'string'},
		{name:'usuario_root', type:'string'},
		{name:'usuario_activo', type:'string'},
		{name:'usuario_perfil_id', type:'float'},
		{name:'usuario_finsert', type:'string'},
		{name:'usuario_uinsert', type:'float'},
		{name:'usuario_CityId', type:'string'},
		{name:'usuario_CountryId', type:'float'},
		{name:'usuario_CountryId2', type:'float'},
		{name:'usuario_CityId2', type:'string'},
		{name:'usuario_SkypeId', type:'string'},
		{name:'usuario_tipos_identificacion_id', type:'float'},
		{name:'usuario_documento_ident', type:'string'},
		{name:'usuario_genero', type:'string'},
		{name:'usuario_fnacimiento', type:'string'},
		{name:'usuario_activationKey', type:'string'},
		{name:'usuario_reclutador_id', type:'float'},
		{name:'usuario_identificacion_imagen', type:'string'},
		{name:'usuario_firma', type:'string'},
		{name:'usuario_fecha_formatos1', type:'string'},
		{name:'usuario_campo_disponible2', type:'string'},
		{name:'usuario_campo_disponible3', type:'string'},
		{name:'usuario_campo_disponible4', type:'string'},
		{name:'usuario_campo_disponible5', type:'string'}
	]
});
var comboUsuario = new Ext.form.ComboBox({
	hiddenName:'usuario'
	,id:modulo+'comboUsuario'
	,fieldLabel:'Usuario'
	,store: storeUsuario
	,valueField:'usuario_id'
	,displayField:'usuario_nombre'
	,typeAhead: true
	,forceSelection: true
	,triggerAction: 'all'
	,selectOnFocus: true
});
var cmUsuario = new Ext.grid.ColumnModel({
	columns:[
		{header:'usuario_id', align:'right', hidden:false, dataIndex: 'usuario_id'},
		{header:'usuario_pnombre', align:'left', hidden:false, dataIndex: 'usuario_pnombre'},
		{header:'usuario_snombre', align:'left', hidden:false, dataIndex: 'usuario_snombre'},
		{header:'usuario_papellido', align:'left', hidden:false, dataIndex: 'usuario_papellido'},
		{header:'usuario_sapellido', align:'left', hidden:false, dataIndex: 'usuario_sapellido'},
		{header:'usuario_email', align:'left', hidden:false, dataIndex: 'usuario_email'},
		{header:'usuario_password', align:'left', hidden:false, dataIndex: 'usuario_password'},
		{header:'usuario_root', align:'left', hidden:false, dataIndex: 'usuario_root'},
		{header:'usuario_activo', align:'left', hidden:false, dataIndex: 'usuario_activo'},
		{header:'usuario_perfil_id', align:'right', hidden:false, dataIndex: 'usuario_perfil_id'},
		{header:'usuario_finsert', align:'left', hidden:false, dataIndex: 'usuario_finsert'},
		{header:'usuario_uinsert', align:'right', hidden:false, dataIndex: 'usuario_uinsert'},
		{header:'usuario_CityId', align:'left', hidden:false, dataIndex: 'usuario_CityId'},
		{header:'usuario_CountryId', align:'right', hidden:false, dataIndex: 'usuario_CountryId'},
		{header:'usuario_CountryId2', align:'right', hidden:false, dataIndex: 'usuario_CountryId2'},
		{header:'usuario_CityId2', align:'left', hidden:false, dataIndex: 'usuario_CityId2'},
		{header:'usuario_SkypeId', align:'left', hidden:false, dataIndex: 'usuario_SkypeId'},
		{header:'usuario_tipos_identificacion_id', align:'right', hidden:false, dataIndex: 'usuario_tipos_identificacion_id'},
		{header:'usuario_documento_ident', align:'left', hidden:false, dataIndex: 'usuario_documento_ident'},
		{header:'usuario_genero', align:'left', hidden:false, dataIndex: 'usuario_genero'},
		{header:'usuario_fnacimiento', align:'left', hidden:false, dataIndex: 'usuario_fnacimiento'},
		{header:'usuario_activationKey', align:'left', hidden:false, dataIndex: 'usuario_activationKey'},
		{header:'usuario_reclutador_id', align:'right', hidden:false, dataIndex: 'usuario_reclutador_id'},
		{header:'usuario_identificacion_imagen', align:'left', hidden:false, dataIndex: 'usuario_identificacion_imagen'},
		{header:'usuario_firma', align:'left', hidden:false, dataIndex: 'usuario_firma'},
		{header:'usuario_fecha_formatos1', align:'left', hidden:false, dataIndex: 'usuario_fecha_formatos1'},
		{header:'usuario_campo_disponible2', align:'left', hidden:false, dataIndex: 'usuario_campo_disponible2'},
		{header:'usuario_campo_disponible3', align:'left', hidden:false, dataIndex: 'usuario_campo_disponible3'},
		{header:'usuario_campo_disponible4', align:'left', hidden:false, dataIndex: 'usuario_campo_disponible4'},
		{header:'usuario_campo_disponible5', align:'left', hidden:false, dataIndex: 'usuario_campo_disponible5'}
	]
	,defaults:{
		sortable:true
		,width:100
	}
});
var tbUsuario = new Ext.Toolbar();

var gridUsuario = new Ext.grid.GridPanel({
	store:storeUsuario
	,id:modulo+'gridUsuario'
	,colModel:cmUsuario
	,viewConfig: {
		forceFit: true
		,scrollOffset:2
	}
	,sm:new Ext.grid.RowSelectionModel({singleSelect:true})
	,bbar:new Ext.PagingToolbar({pageSize:10, store:storeUsuario, displayInfo:true})
	,tbar:tbUsuario
	,loadMask:true
	,border:false
	,title:''
	,iconCls:'icon-grid'
	,plugins:[new Ext.ux.grid.Excel()]
});
var formUsuario = new Ext.FormPanel({
	,name: 'formulario'
	,labelAlign: 'top'
	,baseCls: 'x-panel-mc'
	,method: 'POST'
	,baseParams: { accion: 'actualizar' }
	,autoWidth:	true
	,autoScroll: true
	,trackResetOnLoad: true
	,monitorValid: true
	,bodyStyle:	'padding:15px;'
	,reader: new Ext.data.JsonReader({
		root:'datos',totalProperty: 'total'
		,fields:[
			{name:'usuario_id', mapping:'usuario_id' , type:'float'},
			{name:'usuario_pnombre', mapping:'usuario_pnombre' , type:'string'},
			{name:'usuario_snombre', mapping:'usuario_snombre' , type:'string'},
			{name:'usuario_papellido', mapping:'usuario_papellido' , type:'string'},
			{name:'usuario_sapellido', mapping:'usuario_sapellido' , type:'string'},
			{name:'usuario_email', mapping:'usuario_email' , type:'string'},
			{name:'usuario_password', mapping:'usuario_password' , type:'string'},
			{name:'usuario_root', mapping:'usuario_root' , type:'string'},
			{name:'usuario_activo', mapping:'usuario_activo' , type:'string'},
			{name:'usuario_perfil_id', mapping:'usuario_perfil_id' , type:'float'},
			{name:'usuario_finsert', mapping:'usuario_finsert' , type:'string'},
			{name:'usuario_uinsert', mapping:'usuario_uinsert' , type:'float'},
			{name:'usuario_CityId', mapping:'usuario_CityId' , type:'string'},
			{name:'usuario_CountryId', mapping:'usuario_CountryId' , type:'float'},
			{name:'usuario_CountryId2', mapping:'usuario_CountryId2' , type:'float'},
			{name:'usuario_CityId2', mapping:'usuario_CityId2' , type:'string'},
			{name:'usuario_SkypeId', mapping:'usuario_SkypeId' , type:'string'},
			{name:'usuario_tipos_identificacion_id', mapping:'usuario_tipos_identificacion_id' , type:'float'},
			{name:'usuario_documento_ident', mapping:'usuario_documento_ident' , type:'string'},
			{name:'usuario_genero', mapping:'usuario_genero' , type:'string'},
			{name:'usuario_fnacimiento', mapping:'usuario_fnacimiento' , type:'string'},
			{name:'usuario_activationKey', mapping:'usuario_activationKey' , type:'string'},
			{name:'usuario_reclutador_id', mapping:'usuario_reclutador_id' , type:'float'},
			{name:'usuario_identificacion_imagen', mapping:'usuario_identificacion_imagen' , type:'string'},
			{name:'usuario_firma', mapping:'usuario_firma' , type:'string'},
			{name:'usuario_fecha_formatos1', mapping:'usuario_fecha_formatos1' , type:'string'},
			{name:'usuario_campo_disponible2', mapping:'usuario_campo_disponible2' , type:'string'},
			{name:'usuario_campo_disponible3', mapping:'usuario_campo_disponible3' , type:'string'},
			{name:'usuario_campo_disponible4', mapping:'usuario_campo_disponible4' , type:'string'},
			{name:'usuario_campo_disponible5', mapping:'usuario_campo_disponible5', type:'string'}
		]
	})
	,items:[{
		xtype:'fieldset',title:'Information',layout:'column'
		,defaults:{columnWidth:0.33,layout:'form',labelAlign:'top',border:false,xtype:'panel',bodyStyle:'padding:0 18px 0 0'}
		,items:[{
				defaults:{anchor:'100%'}
				,items:[{
					xtype:'numberfield'
					,name:'usuario_id'
					,fieldLabel:'usuario_id'
					,id:prefijoId+'usuario_id'
					,allowBlank:false
				}]
			},{
				defaults:{anchor:'100%'}
				,items:[{
					xtype:'textfield'
					,name:'usuario_pnombre'
					,fieldLabel:'usuario_pnombre'
					,id:prefijoId+'usuario_pnombre'
					,allowBlank:false
				}]
			},{
				defaults:{anchor:'100%'}
				,items:[{
					xtype:'textfield'
					,name:'usuario_snombre'
					,fieldLabel:'usuario_snombre'
					,id:prefijoId+'usuario_snombre'
					,allowBlank:false
				}]
			},{
				defaults:{anchor:'100%'}
				,items:[{
					xtype:'textfield'
					,name:'usuario_papellido'
					,fieldLabel:'usuario_papellido'
					,id:prefijoId+'usuario_papellido'
					,allowBlank:false
				}]
			},{
				defaults:{anchor:'100%'}
				,items:[{
					xtype:'textfield'
					,name:'usuario_sapellido'
					,fieldLabel:'usuario_sapellido'
					,id:prefijoId+'usuario_sapellido'
					,allowBlank:false
				}]
			},{
				defaults:{anchor:'100%'}
				,items:[{
					xtype:'textfield'
					,name:'usuario_email'
					,fieldLabel:'usuario_email'
					,id:prefijoId+'usuario_email'
					,allowBlank:false
				}]
			},{
				defaults:{anchor:'100%'}
				,items:[{
					xtype:'textfield'
					,name:'usuario_password'
					,fieldLabel:'usuario_password'
					,id:prefijoId+'usuario_password'
					,allowBlank:false
				}]
			},{
				defaults:{anchor:'100%'}
				,items:[{
					xtype:'textfield'
					,name:'usuario_root'
					,fieldLabel:'usuario_root'
					,id:prefijoId+'usuario_root'
					,allowBlank:false
				}]
			},{
				defaults:{anchor:'100%'}
				,items:[{
					xtype:'textfield'
					,name:'usuario_activo'
					,fieldLabel:'usuario_activo'
					,id:prefijoId+'usuario_activo'
					,allowBlank:false
				}]
			},{
				defaults:{anchor:'100%'}
				,items:[{
					xtype:'numberfield'
					,name:'usuario_perfil_id'
					,fieldLabel:'usuario_perfil_id'
					,id:prefijoId+'usuario_perfil_id'
					,allowBlank:false
				}]
			},{
				defaults:{anchor:'100%'}
				,items:[{
					xtype:'datefield'
					,name:'usuario_finsert'
					,fieldLabel:'usuario_finsert'
					,id:prefijoId+'usuario_finsert'
					,allowBlank:false
				}]
			},{
				defaults:{anchor:'100%'}
				,items:[{
					xtype:'numberfield'
					,name:'usuario_uinsert'
					,fieldLabel:'usuario_uinsert'
					,id:prefijoId+'usuario_uinsert'
					,allowBlank:false
				}]
			},{
				defaults:{anchor:'100%'}
				,items:[{
					xtype:'textfield'
					,name:'usuario_CityId'
					,fieldLabel:'usuario_CityId'
					,id:prefijoId+'usuario_CityId'
					,allowBlank:false
				}]
			},{
				defaults:{anchor:'100%'}
				,items:[{
					xtype:'numberfield'
					,name:'usuario_CountryId'
					,fieldLabel:'usuario_CountryId'
					,id:prefijoId+'usuario_CountryId'
					,allowBlank:false
				}]
			},{
				defaults:{anchor:'100%'}
				,items:[{
					xtype:'numberfield'
					,name:'usuario_CountryId2'
					,fieldLabel:'usuario_CountryId2'
					,id:prefijoId+'usuario_CountryId2'
					,allowBlank:false
				}]
			},{
				defaults:{anchor:'100%'}
				,items:[{
					xtype:'textfield'
					,name:'usuario_CityId2'
					,fieldLabel:'usuario_CityId2'
					,id:prefijoId+'usuario_CityId2'
					,allowBlank:false
				}]
			},{
				defaults:{anchor:'100%'}
				,items:[{
					xtype:'textfield'
					,name:'usuario_SkypeId'
					,fieldLabel:'usuario_SkypeId'
					,id:prefijoId+'usuario_SkypeId'
					,allowBlank:false
				}]
			},{
				defaults:{anchor:'100%'}
				,items:[{
					xtype:'numberfield'
					,name:'usuario_tipos_identificacion_id'
					,fieldLabel:'usuario_tipos_identificacion_id'
					,id:prefijoId+'usuario_tipos_identificacion_id'
					,allowBlank:false
				}]
			},{
				defaults:{anchor:'100%'}
				,items:[{
					xtype:'textfield'
					,name:'usuario_documento_ident'
					,fieldLabel:'usuario_documento_ident'
					,id:prefijoId+'usuario_documento_ident'
					,allowBlank:false
				}]
			},{
				defaults:{anchor:'100%'}
				,items:[{
					xtype:'textfield'
					,name:'usuario_genero'
					,fieldLabel:'usuario_genero'
					,id:prefijoId+'usuario_genero'
					,allowBlank:false
				}]
			},{
				defaults:{anchor:'100%'}
				,items:[{
					xtype:'datefield'
					,name:'usuario_fnacimiento'
					,fieldLabel:'usuario_fnacimiento'
					,id:prefijoId+'usuario_fnacimiento'
					,allowBlank:false
				}]
			},{
				defaults:{anchor:'100%'}
				,items:[{
					xtype:'textfield'
					,name:'usuario_activationKey'
					,fieldLabel:'usuario_activationKey'
					,id:prefijoId+'usuario_activationKey'
					,allowBlank:false
				}]
			},{
				defaults:{anchor:'100%'}
				,items:[{
					xtype:'numberfield'
					,name:'usuario_reclutador_id'
					,fieldLabel:'usuario_reclutador_id'
					,id:prefijoId+'usuario_reclutador_id'
					,allowBlank:false
				}]
			},{
				defaults:{anchor:'100%'}
				,items:[{
					xtype:'numberfield'
					,name:'usuario_identificacion_imagen'
					,fieldLabel:'usuario_identificacion_imagen'
					,id:prefijoId+'usuario_identificacion_imagen'
					,allowBlank:false
				}]
			},{
				defaults:{anchor:'100%'}
				,items:[{
					xtype:'numberfield'
					,name:'usuario_firma'
					,fieldLabel:'usuario_firma'
					,id:prefijoId+'usuario_firma'
					,allowBlank:false
				}]
			},{
				defaults:{anchor:'100%'}
				,items:[{
					xtype:'numberfield'
					,name:'usuario_fecha_formatos1'
					,fieldLabel:'usuario_fecha_formatos1'
					,id:prefijoId+'usuario_fecha_formatos1'
					,allowBlank:false
				}]
			},{
				defaults:{anchor:'100%'}
				,items:[{
					xtype:'numberfield'
					,name:'usuario_campo_disponible2'
					,fieldLabel:'usuario_campo_disponible2'
					,id:prefijoId+'usuario_campo_disponible2'
					,allowBlank:false
				}]
			},{
				defaults:{anchor:'100%'}
				,items:[{
					xtype:'numberfield'
					,name:'usuario_campo_disponible3'
					,fieldLabel:'usuario_campo_disponible3'
					,id:prefijoId+'usuario_campo_disponible3'
					,allowBlank:false
				}]
			},{
				defaults:{anchor:'100%'}
				,items:[{
					xtype:'numberfield'
					,name:'usuario_campo_disponible4'
					,fieldLabel:'usuario_campo_disponible4'
					,id:prefijoId+'usuario_campo_disponible4'
					,allowBlank:false
				}]
			},{
				defaults:{anchor:'100%'}
				,items:[{
					xtype:'numberfield'
					,name:'usuario_campo_disponible5'
					,fieldLabel:'usuario_campo_disponible5'
					,id:prefijoId+'usuario_campo_disponible5'
					,allowBlank:false
				}]
			}]
	}]
});
