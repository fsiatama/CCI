Header = function(){

	function logout(){
		Ext.Msg.confirm('','Esta seguro de salir?', function(btn){
			if(btn=='yes'){
				Ext.Msg.wait('Closing', 'Please Wait...');
				Ext.Ajax.request({
					url: 'auth/logout'
					,method:'POST'
					,scope:this
					,timeout: 100000
					,callback: function(options, success, response){
						var json = Ext.util.JSON.decode(response.responseText);
						if(json.success){
							Ext.Msg.hide();
							location.href = json.url;
						}
					}
				});
			}
		});
	}

	var reloj = new Ext.Toolbar.TextItem('');

	function fecha(){
		var fecha = new Date();
		var formato = 'd/m/Y';
		return "<img src='img/date.png' width='24' height='24' border='0' align='middle' style='vertical-align:middle;' />&nbsp;&nbsp;"+fecha.format(formato);
	}

	function getHeaderMenu () {

		var userMenu = new Ext.menu.Menu();

		Ext.Ajax.request({
			 url:'auth/headerMenu/'
			,callback: function(options, success, response){
				var json = Ext.util.JSON.decode(response.responseText);

				var text = '<b>'+json.text+'</b>'

				var tbItem = Ext.getCmp('UserHeaderMenu');

				tbItem.setText(text);

				var ret = {
					text: 'Cerrar Sesión'
					,handler: logout
					,iconCls: 'silk-logout16'
					,listeners: {
						'render': function(){
							globalKeyMap.accessKey({
								key:'s'
								,alt:true
							}, logout, this);
						}
					}
				};
				userMenu.add(ret);
			}
		});

		return userMenu;
	}

	var globalKeyMap = new Ext.KeyMap(document);
	globalKeyMap.accessKey = function(key,handler,scope){
		var h = function(n,e){
			e.preventDefault();
			handler.call(scope||this,n,e)
		};
		this.on(key,h,scope)
	};

	var toolbar = new Ext.Toolbar({
		height: 65
		,items:[
			'<img src="img/logo.png" alt="Logo" width="190" height="61" border="0" align="middle" style="vertical-align:middle;margin:0;" />'
		,'->'
		,{
			text: ''
			,id: 'UserHeaderMenu'
			,iconCls: 'silk-user'
			,menu: getHeaderMenu()
		}
		,'-'
			,fecha()
		,'-'
			,"<img src='img/time.png' width='24' height='24' border='0' align='middle' style='vertical-align:middle;' />&nbsp;"
			,reloj
		]
	});

	Header.superclass.constructor.call(this, {
		 id:'norte'
		,region:'north'
		,alwaysShowTabs:false
		,height: 65
		,items:[toolbar]
		,listeners: {
			 'render': function(){
				Ext.TaskMgr.start({
					 run: function(){
						Ext.fly(reloj.getEl()).update(new Date().format('h:i:s a '))
					}
					,interval: 1000
				});
			}
			,'afterlayout': function(){
				this.body.dom.style.border = '0';
			}
			,scope: this
		}
	})
};

Ext.extend(Header, Ext.Panel);