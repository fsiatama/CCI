Left = function() {
	Left.superclass.constructor.call(this, {
		 id: 'oeste'
		,region: 'west'
		,layout: 'border'
		,title:	Ext.ux.lang.menu.menu_root
		,margins: '5 0 5 5'
		,cmargins: '5 5 5 5'
		,split: true
		,collapsible:true
		,width:	200
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

		var accordion = Ext.getCmp('menupersonal');
		Ext.Ajax.request({
			 url:'menu'
			,method:'POST'
			,callback: function(options, success, response){
				var json = Ext.util.JSON.decode(response.responseText);
				if (json.success) {
					var data = json.data;
					for (var key in data) {
						
						var menuItems = data[key].items;
						for (var item in menuItems) {
							menuItems[item]['handler'] = Ext.getCmp('oeste').addTab;
						}
						
						var p = new Ext.Panel({
							frame:false,
							border:false,
							autoHeight:true,
							title:data[key].title,
							items:menuItems,
							iconCls:data[key].iconCls
						});
						if (p.title != undefined) {
							if ((p.title).length != 0) {
								accordion.add(p).show();
								accordion.doLayout();
							}
						}
					}
				};
			}
		});
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