Centro = function(){
	
	Centro.superclass.constructor.call(this,{
		 id: 'tabpanel'
		,region: 'center'
		,margins: '5 5 5 0'
		,bodyStyle: 'background-color:#333333;'
		,split: true
		,frame: false
		,autoScroll: false
		,animScroll: true
		,deferredRender: true
		,enableTabScroll: true
		,bodyBorder: false
		,hideBorders: true
		,layoutOnTabChange: true
		,buttonAlign: 'center'
		,activeTab: 0
		,listeners: {
			 'afterrender': function(container){
				
			}
		}
		,items: [{
			 id: 'tab-bienvenidos'
			,title: 'Inicio'
			,iconCls: 'home'
			,closable: false
			,autoScroll: true
			,resizeTabs: true
			,enableTabScroll: false
			,slideDuration: .15
			,items: [{
				xtype:'portal'
				,region:'center'
				,items:[]
			}]
		}]
	});
};
Ext.extend(Centro, Ext.ux.SlidingTabPanel);