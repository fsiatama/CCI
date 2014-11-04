<?php
//Trae la sesión que esté asignada
session_start();
include($_SESSION['session_diccionario']);
//Variables de configuración del sistema
include ("../../../../lib/config.php");
include ("../../../../lib/lib_sesion.php");
?>
/*<script>*/

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
	constructor: function(config) {
		this.addEvents({timeout: true});
		Ext.apply(this, config);
		Ext.ux.inactivityMonitor.superclass.constructor.apply(this, arguments);
		if (this.inactivityTimeout >= ONEMINUTE) {
			this.resetTimeout();
			var body = Ext.get(document.body);
			body.on("click", this.resetTimeout, this);
			body.on("keypress", this.resetTimeout, this);
		}
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
		var usuario = '<?php print $_SESSION['session_login']; ?>';
		if(!this.confirmacionUsuario){
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
				,url: '<?php print URL_RAIZ."login/". LOGIN; ?>/'
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
							,fieldLabel: "Usuario"
							,name: 'user_name'
							,allowBlank: false
							,selectOnFocus: true
							,anchor: '85%'
							,readOnly: true
							,value: usuario
							,msgTarget: 'qtip'
						},{
							xtype:'hidden'
							,name: 'forzar'
							,id: 'forzar'
							,value: 0
						}]
					},{
						 columnWidth: .5
						,layout: 'form'
						,autoHeight: true
						,items: [{
							 xtype: 'textfield'
							,fieldLabel: "Pass"
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
					,html: '<b><?php print _MENSESIONEXPIRA; ?></b>'
				},
					LoginPanel
				]
				,buttonAlign: 'center'
				,buttons: [{
					 text: "Logout"
					,iconCls: 'silk-logout16'
					,handler: function(){
						location.href = '<?php print URL_INGRESO; ?>';
					}
				},{
					 text: 'Aceptar'
					,iconCls: 'silk-accept'
					,scope: this
					,handler: function(){
						Ext.getCmp('password').setRawValue(Ext.ux.util.MD5(Ext.getCmp('password').getValue()));
						LoginPanel.form.submit({
							 waitTitle: " "
							,waitMsg: "...."
							,params: {
								ac: '<?php print LOGIN; ?>',
								producto:'multipais'
							}
							,reset: false
							,scope: this
							,failure: function(Login, action){
								if(action.result.msg == 'CONF'){
									Ext.getCmp('msgLogin').update('<b><?php print _INTRODPASS; ?></b>');
									Ext.Msg.confirm('','<?php print _SESSION; ?>?', function(btn){
										if(btn=='yes'){
											Ext.getCmp("forzar").setValue("1");
										}
										else{
											location.href = '<?php print URL_INGRESO; ?>';
										}
									});
								}
								else{
									Ext.getCmp('msgLogin').update('<b>'+action.result.msg+'</b>');
								}
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
						,params: {
							ac: '<?php print LOGIN; ?>',
							producto:'multipais'
						}
						,reset: false
						,scope: this
						,failure: function(Login, action) {
							if(action.result.msg == 'CONF'){
								Ext.getCmp('msgLogin').update('<b><?php print _INTRODPASS; ?></b>');
								Ext.Msg.confirm('','<?php print _SESSION; ?>?', function(btn){
									if(btn=='yes'){
										Ext.getCmp("forzar").setValue("1");
									}
									else{
										location.href = '<?php print URL_INGRESO; ?>';
									}
								});
							}
							else{
								Ext.getCmp('msgLogin').update('<b>'+action.result.msg+'</b>');
							}
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
			url: '<?php print URL_RAIZ."logout/". LOGOUT; ?>/',
			method:'POST',
			scope:this,
			//timeout: 100000,
			success: function(response) {
				//location.href = '<?php print URL_INGRESO; ?>'			 
			},
			failure: function(response) {
				
			}
		});
	}
});	