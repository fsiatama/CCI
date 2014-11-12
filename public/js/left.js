
/*menu__ADMINISTRACION= function(){
	return "{
		title:'Administración'	
		,iconCls:'_ADMINISTRACION'	
		,items:[{
			id:'_MNUAVISOMAIL'
			,title:'Avisar por Email'
			,iconCls:'_MNUAVISOMAIL'
			,titleTab: 'Avisar por Email'
			,url:'jscode/enviar_reportes/'
			,params:{
				id:'_MNUAVISOMAIL'
				,url:'jscode/enviar_reportes/'
				, producto:''
			}
			,handler:Ext.getCmp('oeste').addTab
		},{
			id:'_MNUEMPRESA'
			,title:'Directorio Empresas',iconCls:'_MNUEMPRESA',titleTab: 'Directorio Empresas',url:'jscode/empresa_list/',params:{id:'_MNUEMPRESA',url:'jscode/empresa_list/', producto:''},handler:Ext.getCmp('oeste').addTab},{id:'_MNUUSUARIO',title:'Usuario',iconCls:'_MNUUSUARIO',titleTab: 'Usuario',url:'jscode/usuario_list/',params:{id:'_MNUUSUARIO',url:'jscode/usuario_list/', producto:''},handler:Ext.getCmp('oeste').addTab},{id:'_MONITOR',title:'MONITOR',iconCls:'_MONITOR',titleTab: 'MONITOR',url:'jscode/monitor/',params:{id:'_MONITOR',url:'jscode/monitor/', producto:'17'},handler:Ext.getCmp('oeste').addTab},{id:'_REPMONITOREO',title:'Monitoreo On-Line',iconCls:'_REPMONITOREO',titleTab: 'Monitoreo On-Line',url:'jscode/reportes_list/',params:{id:'_REPMONITOREO',url:'jscode/reportes_list/', producto:''},handler:Ext.getCmp('oeste').addTab},{id:'_REPPROCESS',title:'Procesos BD',iconCls:'_REPPROCESS',titleTab: 'Procesos BD',url:'jscode/procesos_list/',params:{id:'_REPPROCESS',url:'jscode/procesos_list/', producto:''},handler:Ext.getCmp('oeste').addTab},{id:'_VIGENTECONTABILIDAD',title:'V. Contabilidad',iconCls:'_VIGENTECONTABILIDAD',titleTab: 'V. Contabilidad',url:'jscode/vigentecontabilidad_list/',params:{id:'_VIGENTECONTABILIDAD',url:'jscode/vigentecontabilidad_list/', producto:''},handler:Ext.getCmp('oeste').addTab},{id:'_VIGENTECOTIZA',title:'CRM',iconCls:'_VIGENTECOTIZA',titleTab: 'CRM',url:'jscode/vigente_list/',params:{id:'_VIGENTECOTIZA',url:'jscode/vigente_list/', producto:'22'},handler:Ext.getCmp('oeste').addTab	}]}"};menu__GESTION_CALIDAD= function(){return "{	title:'Gestión Calidad'	,iconCls:'_GESTION_CALIDAD'	,items:[{id:'_DTTOS',title:'Documentos',iconCls:'_DTTOS',titleTab: 'Documentos',url:'jscode/documentos_calidad/',params:{id:'_DTTOS',url:'jscode/documentos_calidad/'},handler:Ext.getCmp('oeste').addTab	}]}"};*/
Left = function() {
	Left.superclass.constructor.call(this, {
		 id: 'oeste'
		,region: 'west'
		,layout: 'border'
		,title:	'Menu Principal'
		,margins: '5 0 5 5'
		,cmargins: '5 5 5 5'
		,split: true
		,collapsible:true
		,width:	160
		,border: false
		,frame:	true
		,items: [{
			 id:'menupersonal'
			,region:'center'
			,layout:'links'
			,margins:'0 0 0 0'
			,split:false
			,collapsible:false
			,autoScroll:true
			,border:false
			,frame:false
			,alwaysShowTabs:true
			,maskDisabled:false
			,escale:'medium'
			,defaults: {
				 bodyStyle:'padding-left:8px; padding-top:3px; padding-bottom:3px;'
			}
			,layoutConfig: {
				 hideCollapseTool:false
				,renderHidden:false
				,titleCollapse:true
				,animate:true
				,activeLink:0
			}
		}]
	});
	this.getMenu();
};
	
Ext.extend(Left, Ext.Panel, {
	getMenu: function(response){

		Ext.Ajax.request({
			 url:'user/mainMenu/'
			,method:'POST'
			,callback: function(options, success, response){
				var json = Ext.util.JSON.decode(response.responseText);
			}
		});

		

		/*var listadoDeModulos = menu__ADMINISTRACION() + ', ' + menu__GESTION_CALIDAD();
		listadoDeModulos = '['+listadoDeModulos+']';
		listadoDeModulos = Ext.util.JSON.decode(listadoDeModulos);
		this.addMenu(listadoDeModulos);*/
	}
	,addMenu: function(data){
		var accordion = Ext.getCmp('menupersonal');
		for (var key in data){
			var p = new Ext.Panel({
				frame:false,
				border:false,
				autoHeight:true,
				title:data[key].title,
				items:data[key].items,
				iconCls:data[key].iconCls
			});
			if (p.title != undefined) {
				if ((p.title).length != 0) {
					accordion.add(p).show();
					accordion.doLayout();
				}
			}
		}
	}
	,addTab: function(event,element,linkCmp){
		var tabPanel = Ext.getCmp('tabpanel');		
		var id = 'tab-'+linkCmp.id;
		var title = linkCmp.titleTab;
		var iconCls = linkCmp.iconCls;
		var url = linkCmp.url;
		var params = linkCmp.params;
		var mantenimiento = linkCmp.mantenimiento;
		
		var tab = tabPanel.getItem(id);
		
		if(mantenimiento){
			Ext.getCmp('oeste').mantenimiento();
		}else if(!tab){
			tab = tabPanel.add({
				 id: id
				,title: title
				,iconCls: iconCls
				,closable: true
				,autoScroll: false
				,autoShow: true
				,border: false
				,frame: false
				,buttonAlign: 'center'
				,layout: 'fit'
				,plugins: new Ext.ux.Plugin.RemoteComponent({
					 url:url
					,params:params
					,disableCaching:false
					,method:'POST'
					,loadOn:'show'
					,mask:Ext.getCmp('tabpanel').body
					,maskConfig:{
						msg:Ext.LoadMask.prototype.msg
					}
				})
			});
    	}
		tabPanel.doLayout();
		tabPanel.setActiveTab(tab);
	}
});