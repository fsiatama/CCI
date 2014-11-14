var ONESECOND = 1000;
var ONEMINUTE = ONESECOND * 60;

Ext.ns('Ext.ux');

Ext.ux.inactivityMonitor = Ext.extend(Ext.util.Observable, {
	inactivityTimeout: ONEMINUTE * 1,
	pollActionParam: "a",
	pollAction: "StayAlive",
	pollInterval: ONEMINUTE,
	confirmacionUsuario: null,
	messageBoxConfig: {}, // allows developers to override the appearance of the messageBox
	messageBoxCountdown: 5, // how long should the messageBox wait?
	user_email: null,
	constructor: function(config) {
		this.addEvents({timeout: true});
		Ext.apply(this, config);
		Ext.ux.inactivityMonitor.superclass.constructor.apply(this, arguments);
		if (this.inactivityTimeout >= ONEMINUTE) {
			this.resetTimeout();
			var body = Ext.get(document.body);
			body.on("click", this.resetTimeout, this);
			body.on("keypress", this.resetTimeout, this);
			this.setUser();
		}
	},
	setUser: function() {
		Ext.Ajax.request({
			 url:'auth/headerMenu/'
			,method:'POST'
			,callback: function(options, success, response){
				var json = Ext.util.JSON.decode(response.responseText);
				this.user_email = json.email;
			}
		});
	},
	destroy: function() {
		var body = Ext.get(document.body);
		body.un("click", this.resetTimeout, this);
		body.un("keypress", this.resetTimeout, this);
		this._inactivityTask.cancel();
		Ext.TaskMgr.stop(this._countdownTask);
	},
	resetTimeout: function () {
		if (!this._inactivityTask) {
			this._inactivityTask = new Ext.util.DelayedTask(this._beginCountdown, this);
		}
		this._inactivityTask.delay(this.inactivityTimeout);
	},
	
	// private stuff
	_pollTask: null, // task to poll server
	_countdownTask: null, // ONESECOND interval for updating countdown MessageBox
	_countdownMessage: null, // countdown MessageBox
	_inactivityTask: null, // task to start countdown
	_beginCountdown: function(){
		this.fireEvent('timeout', this);
		if(!this.confirmacionUsuario && this.user_email){
			Ext.TaskMgr.stopAll();
			Ext.getCmp('norte').fireEvent('render'); // Para que no pare el Reloj
			this.logout();						
			var LoginPanel = new Ext.FormPanel({
				 id: 'inactivityForm'
				,labelWidth: 100
				,labelPad: 10
				,labelAlign: 'top'
				,bodyStyle: 'padding:10px;'
				,frame: false
				,border: false
				,baseCls: 'x-panel-mc'
				,layout: 'fit'
				,url: 'auth/login'
				,method: 'POST'
				,autoHeight: true
				,items: [{
					 layout: 'column'
					,autoHeight: true
					,items:[{
						 columnWidth: .5
						,layout: 'form'
						,autoHeight: true
						,items: [{
							 xtype: 'textfield'
							,fieldLabel: 'Email'
							,name: 'email'
							,allowBlank: false
							,selectOnFocus: true
							,anchor: '85%'
							,readOnly: true
							,value: this.user_email
							,msgTarget: 'qtip'
						}]
					},{
						 columnWidth: .5
						,layout: 'form'
						,autoHeight: true
						,items: [{
							 xtype: 'textfield'
							,fieldLabel: "Password"
							,inputType: 'password'
							,name: 'password'
							,id: 'password'
							,allowBlank: false
							,minLength: 4
							,minLengthText: ""
							,selectOnFocus: true
							,anchor: '85%'
							,msgTarget: 'qtip'
						}]
					}]
				}]
			});
			
			this.confirmacionUsuario = new Ext.Window({
				id: 'inactivityWin'
				,width: 300
				,autoHeight: true
				,modal: true
				,plain: true
				,closable: false
				,draggable: false
				,resizable: false
				,layout: 'fit'
				,animateTarget: 'igp'
				,items: [{
					 baseCls: 'x-panel-mc'
					,id:'msgLogin'
					,cls: 'silk-user-warning'
					,autoHeight: true
					,bodyStyle: 'padding: 10px 10px 20px 70px;'
					,html: Ext.ux.lang.error.session_expired
				},
					LoginPanel
				]
				,buttonAlign: 'center'
				,buttons: [{
					 text: Ext.ux.lang.botones.salir
					,iconCls: 'silk-logout16'
					,handler: function(){
						location.href = Ext.ux.routes.url_index;
					}
				},{
					 text: Ext.ux.lang.botones.entrar
					,iconCls: 'silk-accept'
					,scope: this
					,handler: function(){
						Ext.getCmp('password').setRawValue(Ext.ux.util.MD5(Ext.getCmp('password').getValue()));
						LoginPanel.form.submit({
							 waitTitle: " "
							,waitMsg: "...."
							,reset: false
							,scope: this
							,failure: function(Login, action){
								Ext.getCmp('msgLogin').update('<b>'+action.result.msg+'</b>');
								Ext.getCmp('password').setRawValue('');
								LoginPanel.items.items[0].items.items[1].items.items[0].markInvalid();
								LoginPanel.items.items[0].items.items[1].items.items[0].focus(true, 750);
							}
							,success: function(Login, action) {
								this.confirmacionUsuario.close();
								this.confirmacionUsuario = null;
							}
						});
					}
				}]
				,onEsc: Ext.emptyFn()
			});
			this.confirmacionUsuario.show();
			LoginPanel.items.items[0].items.items[1].items.items[0].focus(true, 750);
			
			var nav = new Ext.KeyNav('inactivityForm', {
				'enter': function(){
					Ext.getCmp('password').setRawValue(Ext.ux.util.MD5(Ext.getCmp('password').getValue()));
					LoginPanel.form.submit({
						 waitTitle: ""
						,waitMsg: "...."
						,reset: false
						,scope: this
						,failure: function(Login, action) {
							Ext.getCmp('msgLogin').update('<b>'+action.result.msg+'</b>');
							Ext.getCmp('password').setRawValue('');
							LoginPanel.items.items[0].items.items[1].items.items[0].markInvalid();
							LoginPanel.items.items[0].items.items[1].items.items[0].focus(true, 750);
						}
						,success: function(Login, action) {
							this.confirmacionUsuario.close();
							this.confirmacionUsuario = null;
						}
					});
				}
				,scope: this
			});
		}
	}
	,logout: function(){
		Ext.Ajax.request({
			url: 'auth/logout/',
			method:'POST',
			scope:this,
			disableCaching: false
		});
	}
});	