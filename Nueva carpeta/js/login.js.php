<?php
//Trae la sesión que esté asignada
session_start();
//Variables de configuración del sistema
include ("../lib/config.php");
include (PATH_RAIZ."lib/idioma.php");
?>

/*<script>*/

Ext.onReady(function(){
	<?php
	$currentDate = date("Y-m-d");
	$date = strtotime(date("Y-m-d", strtotime($currentDate)) . " -18 year");
	print "var minDate = '".date("Y-m-d", $date)."';";
	?>
		
	Ext.QuickTips.init();
	Ext.state.Manager.setProvider(new Ext.state.CookieProvider);
	Ext.form.Field.prototype.msgTarget = 'side';
	
	var myMask = new Ext.LoadMask('contenido', {msg:"Please wait..."});
	
	Ext.apply(Ext.form.VTypes, {
		confirmVal:function(val,field) {
			if (field.initialField) {
				var pwd = Ext.getCmp(field.initialField); 
				return (val == pwd.getValue());
			}
			return true;
		}
		,password: function(val, field) {
			if (!/[0-9]/.test(val)) {
				return false;
			}
			else if (!/[a-z]/.test(val)) {
				return false;
			}
			else if (!/[A-Z]/.test(val)) {
				return false;
			}
			else{
				return true
			}
			
		}
		,passwordText: 'Not a valid Password. Must contain at least one uppercase letter, one lowercase letter and at least one number.'
		,confirmValText :'Your passwords do not match'
	});

	var formLogin = new Ext.FormPanel({
		 frame:false
		,border:false
		,buttonAlign:'center'
		,url:'login/<?php print LOGIN; ?>/'
		,method:'POST'
		,id: 'frmLogin'
		,monitorValid:true
		,bodyStyle:'padding:70px 10px 15px 15px;'
		,cls:'formlogin'
		,width: 400
		,labelWidth: 100
		,labelPad: 10
		,monitorValid:true
		,labelAlign:'top'
		,defaults:{selectOnFocus: true,anchor:'90%'}
		,items: [{
			xtype: 'textfield',
			fieldLabel: 'Email',
			vtype:'email',			
			name: 'username',
			id: 'logUsername',
			allowBlank: false,
			value:Ext.util.Cookies.get('username')
		}, {
			xtype: 'textfield',
			fieldLabel: 'Password',
			id: 'logPassword',
			submitValue:false,
			allowBlank: false,
			inputType: 'password',
			value:Ext.util.Cookies.get('password')
		},{
			xtype: 'linkbutton'
			,text:'Lost your password?'
			,border:false
			,cls:'rLink'
			,bodyStyle:'padding:5px 10px;'
			,listeners:{
				click: {
					fn: fnRecordar
				}
			}
		},{
			xtype:'checkbox',
            id:'rememberMe',
            name:'rememberme',
            fieldLabel:'',
            boxLabel:'Remember me on this computer',
            listeners:{
				render: function() {
                    Ext.get(Ext.DomQuery.select('#x-form-el-rememberMe input')).set({
                        qtip:'This is not recommended for shared computers.'
                    });

                }
            }
		}],
		buttons: [
			{ text:'Reset', handler: function() {formLogin.getForm().reset();}},
			{ formBind: true, text: 'Login', handler: fnLogin }
		]
	});
	
	
	var storeUsuario = new Ext.data.JsonStore({
		 url:'proceso/usuario/'
		,root:'datos'
		,sortInfo:{field:'nombre',direction:'ASC'}
		,totalProperty:'total'
		,baseParams:{accion:'lista_reclutadores_combo'}
		,fields:[
			{name:'usuario_id', type:'float'},
			{name:'nombre', type:'string'}
		]
	});
	var comboUsuario = new Ext.form.ComboBox({
		hiddenName:'reclutador'
		,id:'comboUsuario'
		,fieldLabel:''
		,store: storeUsuario
		,valueField:'usuario_id'
		,displayField:'nombre'
		,typeAhead: true
		,forceSelection: true
		,triggerAction: 'all'
		,selectOnFocus: true
	});
	
	var storeTipos_identificacion = new Ext.data.JsonStore({
		 url:'proceso/tipos_identificacion/'
		,root:'datos'
		,sortInfo:{field:'tipos_identificacion_id',direction:'ASC'}
		,totalProperty:'total'
		,baseParams:{accion:'lista'}
		,fields:[
			{name:'tipos_identificacion_id', type:'float'},
			{name:'tipos_identificacion_nombre', type:'string'}
		]
	});
	var comboTipos_identificacion = new Ext.form.ComboBox({
		hiddenName:'tipos_identificacion'
		,id:'comboTipos_identificacion'
		,fieldLabel:'Identification Type <span>*</span>'
		,store: storeTipos_identificacion
		,valueField:'tipos_identificacion_id'
		,displayField:'tipos_identificacion_nombre'
		,typeAhead: true
		,forceSelection: true
		,triggerAction: 'all'
		,selectOnFocus: true
		,allowBlank:false
		,listeners:{
			select: {
				fn: function(combo,reg){
					Ext.getCmp('regIdentificacion').setValue('');
					if(combo.getValue() != ''){
						Ext.getCmp('regIdentificacion').enable();
					}
				}
			}
			,change: {
				fn: function(combo,value){
					Ext.getCmp('regIdentificacion').setValue('');
					if(combo.getValue() == ''){
						Ext.getCmp('regIdentificacion').disable();
					}
				}
			}
		}
	});
	
	var passwordField = new Ext.ux.PasswordMeter({
		name:'regPassword'
		,msgTarget:'under'
		,allowBlank:false
		,fieldLabel:'<?php print _PASS; ?> <span>*</span>'
		,id:'regPassword'
		,minLength:8
		,submitValue:false
		,inputType:'password'
		,vtype:'password'
	});
	var comboGenero = new Ext.form.ComboBox({
		hiddenName:'regGenero'
		,id:'comboGenero'
		,fieldLabel:'<?php print _GENDER; ?> <span>*</span>'
		,mode: 'local'
		,store: new Ext.data.ArrayStore({
			id: 0
			,fields: [
				'regGenero',
				'displayText'
			]
			,data: [[0, 'Male'], [1, 'Female']]
		})
		,valueField:'regGenero'
		,displayField:'displayText'
		,typeAhead: true
		,forceSelection: true
		,triggerAction: 'all'
		,selectOnFocus: true
		,allowBlank:false
	});
	
	var storeCountries = new Ext.data.JsonStore({
		 url:'proceso/countries/listaResidencia/'
		,root:'datos'
		,sortInfo:{field:'CountryId',direction:'ASC'}
		,totalProperty:'total'
		,autoLoad:true
		,fields:[
			{name:'CountryId', type:'float'},
			{name:'Country', type:'string'}
		]
	});
	var storeCountries2 = new Ext.data.JsonStore({
		 url:'proceso/countries/listaNacionalidad/'
		,root:'datos'
		,sortInfo:{field:'CountryId',direction:'ASC'}
		,totalProperty:'total'
		,autoLoad:true
		,fields:[
			{name:'CountryId', type:'float'},
			{name:'Country', type:'string'}
		]
	});
	var comboCountries = new Ext.form.ComboBox({
		hiddenName:'country'
		,id:'comboCountries'
		,fieldLabel:'Country of residence <span>*</span>'
		,store: storeCountries
		,valueField:'CountryId'
		,displayField:'Country'
		,typeAhead: false
		,forceSelection: true
		,hideTrigger:false
		,selectOnFocus: true
		,mode:'local'
		,allowBlank:false
		/*,listeners:{
			select: {
				fn: function(combo,reg){
					myMask.show();
					Ext.getCmp('comboRegions').setValue('');
					Ext.getCmp('comboCities').setValue('');
					storeRegions.load({
						 params:{country:reg.data.CountryId}
						,callback :function(){
							Ext.getCmp('comboRegions').enable();
							myMask.hide();
						}
					});
				}
			}
		}*/
	});
	var comboCountries2 = new Ext.form.ComboBox({
		hiddenName:'country2'
		,id:'comboCountries2'
		,fieldLabel:'Country of origin (nationality)<span>*</span>'
		,store: storeCountries2
		,valueField:'CountryId'
		,displayField:'Country'
		,typeAhead: false
		,forceSelection: true
		,hideTrigger:false
		,selectOnFocus: true
		,mode:'local'
		,allowBlank:false
		,listeners:{
			/*select: {
				fn: function(combo,reg){
					myMask.show();
					Ext.getCmp('comboRegions2').setValue('');
					Ext.getCmp('comboCities2').setValue('');
					storeRegions2.load({
						 params:{country:reg.data.CountryId}
						,callback :function(){
							Ext.getCmp('comboRegions2').enable();
							myMask.hide();
						}
					});
				}
			}*/
		}
	});
	/*var storeRegions = new Ext.data.JsonStore({
		 url:'proceso/regions/listaByCountry/'
		,root:'datos'
		,sortInfo:{field:'RegionID',direction:'ASC'}
		,totalProperty:'total'
		,fields:[
			{name:'RegionID', type:'float'},
			{name:'Region', type:'string'}
		]
	});
	var storeRegions2 = new Ext.data.JsonStore({
		 url:'proceso/regions/listaByCountry/'
		,root:'datos'
		,sortInfo:{field:'RegionID',direction:'ASC'}
		,totalProperty:'total'
		,fields:[
			{name:'RegionID', type:'float'},
			{name:'Region', type:'string'}
		]
	});
	var comboRegions = new Ext.form.ComboBox({
		hiddenName:'region'
		,id:'comboRegions'
		,fieldLabel:'Region or State or Province of residence <span>*</span>'
		,store: storeRegions
		,valueField:'RegionID'
		,displayField:'Region'
		,typeAhead: false
		,forceSelection: true
		,hideTrigger:true
		,selectOnFocus: true
		,mode:'local'
		,allowBlank:false
		,disabled:true
		,listeners:{
			select: {
				fn: function(combo,reg){
					myMask.show();
					Ext.getCmp('comboCities').setValue('');
					storeCities.load({
						 params:{region:reg.data.RegionID}
						,callback :function(){
							Ext.getCmp('comboCities').enable();
							myMask.hide();
						}
					});
				}
			}
		}
	});*/
	/*var comboRegions2 = new Ext.form.ComboBox({
		hiddenName:'region2'
		,id:'comboRegions2'
		,fieldLabel:'Region or State or Province of origin <span>*</span>'
		,store: storeRegions2
		,valueField:'RegionID'
		,displayField:'Region'
		,typeAhead: false
		,forceSelection: true
		,hideTrigger:true
		,selectOnFocus: true
		,mode:'local'
		,allowBlank:false
		,disabled:true
		,listeners:{
			select: {
				fn: function(combo,reg){
					myMask.show();
					Ext.getCmp('comboCities2').setValue('');
					storeCities2.load({
						 params:{region:reg.data.RegionID}
						,callback :function(){
							Ext.getCmp('comboCities2').enable();
							myMask.hide();
						}
					});
				}
			}
		}
	});*/
	/*var storeCities = new Ext.data.JsonStore({
		 url:'proceso/cities/listaByRegion/'
		,root:'datos'
		,sortInfo:{field:'CityId',direction:'ASC'}
		,totalProperty:'total'
		,fields:[
			{name:'CityId', type:'float'},
			{name:'City', type:'string'}
		]
	});
	var storeCities2 = new Ext.data.JsonStore({
		 url:'proceso/cities/listaByRegion/'
		,root:'datos'
		,sortInfo:{field:'CityId',direction:'ASC'}
		,totalProperty:'total'
		,fields:[
			{name:'CityId', type:'float'},
			{name:'City', type:'string'}
		]
	});
	var comboCities = new Ext.form.ComboBox({
		hiddenName:'city'
		,id:'comboCities'
		,fieldLabel:'City of residence <span>*</span>'
		,store: storeCities
		,valueField:'CityId'
		,displayField:'City'
		,typeAhead: false
		,forceSelection: true
		,hideTrigger:true
		,selectOnFocus: true
		,mode:'local'
		,allowBlank:false
		,disabled:true
	});*/
	/*var comboCities2 = new Ext.form.ComboBox({
		hiddenName:'city2'
		,id:'comboCities2'
		,fieldLabel:'City of origin <span>*</span>'
		,store: storeCities2
		,valueField:'CityId'
		,displayField:'City'
		,typeAhead: false
		,forceSelection: true
		,hideTrigger:true
		,selectOnFocus: true
		,mode:'local'
		,allowBlank:false
		,disabled:true
	});*/
	var formRegister = new Ext.FormPanel({
		 frame:false
		,border:false
		,buttonAlign:'center'
		,url:'login/<?php print REGISTRO; ?>/'
		,method:'POST'
		,id: 'frmRegister'
		,monitorValid:true
		,bodyStyle:'padding:5px 10px 15px 15px;'
		,autoWidth:true
		,items: [{
			xtype: 'fieldset'
			,title: 'Personal information'
			,collapsible: false
			,layout:'column'
			,defaults:{
				columnWidth:0.5
				,labelWidth: 120
				,labelPad: 10
				,layout:'form'
				,labelAlign:'top'
				,border:false
				,xtype:'panel'
				,bodyStyle:'padding:0 2px 0 0'
			}
			,items: [{
				columnWidth:1
				,html:'<h3 style="margin-bottom:10px; text-align:center; color:red;">The names should be written exactly as shown in your passport or National Id</h3>'
			},{
				defaults:{anchor:'88%'}
				,items:[{
					xtype: 'textfield',
					fieldLabel: 'First name <span>*</span>',
					name: 'regPnombre',
					id: 'regPnombre',
					allowBlank: false
				}]
			},{
				defaults:{anchor:'88%'}
				,items:[{
					xtype: 'textfield',
					fieldLabel: 'Middle name',
					name: 'regSnombre',
					id: 'regSnombre'
				}]
			},{
				defaults:{anchor:'88%'}
				,items:[{
					xtype: 'textfield',
					fieldLabel: '<?php print _LAST_NAME; ?> <span>*</span>',
					name: 'regPapellido',
					id: 'regPapellido',
					allowBlank: false
				}]
			/*},{
				defaults:{anchor:'88%'}
				,items:[{
					xtype: 'textfield',
					fieldLabel: 'Maiden or other name',
					name: 'regSapellido',
					id: 'regSapellido',
					allowBlank: true
				}]*/
			},{
				defaults:{anchor:'88%'}
				,items:[comboTipos_identificacion]
			},{
				defaults:{anchor:'88%'}
				,items:[{
					xtype:'textfield',
					fieldLabel:'Identification Number<span>*</span>',
					name:'regIdentificacion',
					id:'regIdentificacion',
					allowBlank:false,
					disabled:true,
					plugins:[Ext.ux.plugins.RemoteValidator],
					rvOptions:{
						url:'proceso/usuario/existeIdentification/'
						,params:{
							tipo:function(){
								return Ext.getCmp('comboTipos_identificacion').getValue();
							}
						}
					}
				}]
			},{
				defaults:{anchor:'88%'}
				,items:[{
					xtype: 'datefield',
					fieldLabel: 'Birth date <span>*</span>',
					name: 'regFnacimiento',
					id: 'regFnacimiento',
					maxValue:minDate,
					value:minDate,
					format:'Y-m-d',
					allowBlank: false,
					plugins:[new Ext.ux.FieldHelp('<?php print _DATE_HELP; ?>')],
				}]
			},{
				defaults:{anchor:'88%'}
				,items:[comboGenero]
			}]
		},{
			xtype: 'fieldset'
			,title: 'Information on nationality'
			,collapsible: false
			,layout:'column'
			,defaults:{
				columnWidth:0.5
				,labelWidth: 120
				,labelPad: 10
				,layout:'form'
				,labelAlign:'top'
				,border:false
				,xtype:'panel'
				,bodyStyle:'padding:0 2px 0 0'
			}
			,items: [{
				columnWidth:1
				,html:'<h3 style="margin-bottom:10px; text-align:center;">If your country does not appear on the country of resident list, we are unfortunately unable to continue with your application</h3>'
			},{
				defaults:{anchor:'88%'}
				,items:[comboCountries]
			},{
				defaults:{anchor:'88%'}
				,items:[{
					xtype: 'textfield',
					fieldLabel: '<?php print _REGION_RESIDENCE; ?> <span>*</span>',
					name: 'region',
					id: 'region',
					allowBlank: false
				}]
			},{
				defaults:{anchor:'88%'}
				,items:[{
					xtype: 'textfield',
					fieldLabel: '<?php print _CITY_RESIDENCE; ?> <span>*</span>',
					name: 'city',
					id: 'city',
					allowBlank: false
				}]
			},{
				defaults:{anchor:'88%'}
				,items:[comboCountries2]
			/*},{
				defaults:{anchor:'88%'}
				,items:[comboRegions2]
			},{
				defaults:{anchor:'88%'}
				,items:[comboCities2]*/
			},{
				defaults:{anchor:'88%'}
				,html:''
			}]			
		},{
			xtype: 'fieldset'
			,title: 'User information'
			,collapsible: false
			,layout:'column'
			,defaults:{
				columnWidth:0.5
				,labelWidth: 120
				,labelPad: 10
				,layout:'form'
				,labelAlign:'top'
				,border:false
				,xtype:'panel'
				,bodyStyle:'padding:0 2px 0 0'
			}
			,items: [{
				defaults:{anchor:'88%'}
				,items:[{
					xtype: 'textfield',
					fieldLabel: 'Skype ID <span>*</span>',
					selectOnFocus: true,
					name: 'skypename',
					id: 'skypename',
					allowBlank: false,
					plugins:[Ext.ux.plugins.RemoteValidator, new Ext.ux.FieldHelp('It is necessary for some interviews')],
					rvOptions:{
						url:'proceso/usuario/existeSkypeId/'
					}
				}]
			},{
				defaults:{anchor:'88%'}
				,html:'<p>If you do not have a SKYPE ID, please create an account by clicking on the link below before continuing your application</p><a target="_blank"  href="https://login.skype.com/intl/en/account/signup-form" ><img src="./images/skype.png" width="48" height="48" /></a>'
			},{
				defaults:{anchor:'88%'}
				,items:[{
					xtype: 'textfield',
					fieldLabel: 'Email Address<span>*</span>',
					vtype:'email',
					selectOnFocus: true,
					name: 'regEmail',
					id: 'regEmail',
					allowBlank: false,
					plugins:[Ext.ux.plugins.RemoteValidator],
					rvOptions: {url:'proceso/usuario/existeEmail/'}
				}]
			},{
				defaults:{anchor:'88%'}
				,items:[{
					xtype: 'textfield',
					fieldLabel: 'Repeat Email Address<span>*</span>',
					vtype:'confirmVal',
					initialField:'regEmail',
					selectOnFocus: true,
					name:'regConfEmail',
					id:'regConfEmail',
					allowBlank: false
				}]
			},{
				columnWidth:1
				,html:'<p style="margin-bottom:5px;color:#F00;">Activation link will be sent to this email!</p>'
			},{
				columnWidth:1
				,html:'<p style="margin:30px 0 0 0;"><b><?php print _PASS_HELP; ?></b></p>'
			},{
				defaults:{anchor:'88%'}
				,bodyStyle:'padding:0 18px 0 0'
				,items:[passwordField]
			},{
				defaults:{anchor:'88%'}
				,items:[{
					xtype: 'textfield'
					,fieldLabel: 'Repeat password <span>*</span>'
					,name: 'regConfPassword'
					,id: 'regConfPassword'
					,vtype:'confirmVal'
					,initialField:'regPassword'
					,allowBlank: false
					,inputType: 'password'
					,submitValue:false
					,msgTarget:'under'
				}]
			},{
				columnWidth:1
				,html:'<p style="margin:30px 0 0 0;"><b><?php print _IF_REFERRED_BY_SSG_TEAM; ?>:</b> </p>'
			},{
				defaults:{anchor:'50%'}
				,items:[comboUsuario]
			}]			
		},{
			xtype:'panel'
			,html: '<div id="recaptcha"></div>'
			,border:false
			,bodyCfg: {
				tag: 'center'
			}
			,listeners:{
				afterrender:function() {
					//console.log(Ext.getDom(this.body));
					Recaptcha.create("6Lei2NUSAAAAAMLxgp0AqjV3C1yu1jRMKb-SrLye",
						Ext.getDom(this.body),
						{
							theme: "white",
							lang : 'en',
							tabindex : 20
						}
					);
					Ext.getCmp("regPnombre").focus(true, true);
				}
			}
		},{
			xtype:'panel'
			,border:false
			,bodyStyle:'padding:30px 0 0 0;'
			,defaults:{bodyStyle:'padding:30px 0;'}
			,items:[{
				xtype:'checkboxgroup'
				,fieldLabel:''
				,allowBlank:false
				,items:[{
					name:'permite_compartir_info'
					,boxLabel:'By registering with The Seven Seas Group/SSG Evropa I understand that I am allowing them to share all of my information herein to any of its partner companies for the sole purpose of a job search in the position(s) I will apply for, or other positions I may be suited in, as suggested by The Seven Seas Group/SSG Evropa and/or its partner companies.'
				}]
			},{
				xtype:'checkboxgroup'
				,fieldLabel:''
				,allowBlank:false
				,items:[{
					name:'acepta_terminos'
					,boxLabel:'I declare that i have read and understood the terms and conditions.'
					,listeners:{
						check: {
							fn: function(checkbox,value){
								Ext.getCmp('terms').setVisible(value);
							}
						}
					}
				}]
			},{
				html:'<div class="terms"><h3>Law 15/99 on the Protection of Personal Data</h3><p><br />In compliance with Law 15/ 99 on the Protection of Personal Data, that personal data provided on this online application and the person concerned gives this company whose owner is <strong>SEVEN SEAS RECRUITMENT SERVICE SL CIF B76158310</strong> of Spain and with registered address at Calle Cirilo Amoros, 90  Piso 1ro , Pta 4 ( 46004 - Valencia) Spain, in order to qualify for a staff selection for placement with its partner companies. These companies can be domestic or foreign.<br /><br />Online application and uploaded data will be kept for an undefined time, then proceeding to its destruction.<br /><br />You may exercise your rights to access, rectify, opposition and cancel of the information provided by sending an email to <a href="mailto:jflorez@ssg.eu.com">jflorez@ssg.eu.com</a> or by sending via post mail a request  letter if you are in Europe to Seven Seas Recruitment Services SL with address in  Calle Cirilo Amoros, 90  Piso 1ro , Pta 4 ( 46004 - Valencia) Spain , If  you are located in South America to The Seven Seven Seas Group with address Calle 59 # 6-36 Ofc 403 , Bogota, Colombia of if located in North America to The Seven Seas Group with address at PO Box 530712, Miami, Florida 33153 attaching a copy of your ID .<br /><br />I understand the conditions of submission of my online application with personal data and <strong>EXPRESSLY AGREE</strong> national and <strong>INTERNATIONAL</strong> assignment of the data provided in my Online application submitted via <strong>Seven Seas Recruitment Services SL</strong>, a subsidiary of <strong>The Seven Seas Group/SSG Evropa</strong> , the results of possible medical examinations for fitness for the job they have to perform, to third parties for recruitment processes.</p></div>'
				,hidden:true
				,id:'terms'
				,border:false
			}]
		}],
		buttons:[
			{ text: 'Reset', handler: function() {formRegister.getForm().reset();}},
			{ formBind: true, text: 'Register', handler: fnRegister }
		]
	});
    function fnLogin(){
		Ext.getCmp('frmLogin').on({
			beforeaction: function() {
				if (formLogin.getForm().isValid()){
                    Ext.getCmp('winLogin').body.mask();
                    Ext.getCmp('sbWinLogin').showBusy();
                }
            }
        });
        formLogin.getForm().submit({
			params:{password:Ext.ux.util.MD5(Ext.getCmp('logPassword').getValue())}
			,success: function(){
				if(Ext.getCmp('rememberMe').getValue()){
					console.log(Ext.getCmp('logUsername').getValue());
					Ext.util.Cookies.set('username', Ext.getCmp('logUsername').getValue(), new Date().add(Date.DAY, 90));
					Ext.util.Cookies.set('password', Ext.getCmp('logPassword').getValue(), new Date().add(Date.DAY, 90));
				}
				window.location = '<?php print URL_INGRESO; ?>';
			}
			,failure: function(form, action){
				Ext.getCmp('logPassword').setValue('');
				Ext.getCmp('winLogin').body.unmask();
				if (action.failureType == 'server') {
                    obj = Ext.util.JSON.decode(action.response.responseText);
                    Ext.getCmp('sbWinLogin').setStatus({
                        text: obj.errors.reason,
                        iconCls: 'x-status-error'
                    });
                }
				else{
					if(formLogin.getForm().isValid()) {
                        Ext.getCmp('sbWinLogin').setStatus({
                            text: 'Authentication server is unreachable',
                            iconCls: 'x-status-error'
                        });
                    }
					else{
						Ext.getCmp('sbWinLogin').setStatus({
                            text: 'Something error in form !',
                            iconCls: 'x-status-error'
                        });
                    }
                }
			}
        });
    }
	function fnRecordar(){
		if(Ext.getCmp('logUsername').isValid(true)){
			var email = Ext.getCmp('logUsername').getValue();
			Ext.Ajax.request({
				form: 'frmLogin'
				,url:'login/<?php print FORGOT; ?>/'
				,params:{username:email}
				,success: function(action){
					var obj = Ext.util.JSON.decode(action.responseText);
					if(obj.success == true){
						//Ext.Msg.alert('Status', obj.errors.reason);
						Ext.Msg.show({
						   title:'Information',
						   buttons: Ext.Msg.OK,
						   msg:obj.errors.reason,
						   animEl: 'elId',
						   icon: Ext.MessageBox.INFO
						});
						Ext.getCmp('sbWinLogin').setStatus({
							text: ''
						});	
					}
					else{
						Ext.Msg.show({
						   title:'Error',
						   buttons: Ext.Msg.OK,
						   msg:obj.errors.reason,
						   animEl: 'elId',
						   icon: Ext.MessageBox.ERROR
						});
						Ext.getCmp('sbWinLogin').setStatus({
							text: obj.errors.reason,
							iconCls: 'x-status-error'
						});
					}
				}
			});
		}
		else{
			Ext.getCmp('logUsername').markInvalid();
			Ext.getCmp('sbWinLogin').setStatus({
				text: 'Please, type in the email address you used when you registered with us.',
				iconCls: 'x-status-error'
			});
		}
    }
	function fnRegister(){
		Ext.getCmp('frmRegister').on({
			beforeaction: function() {
				if (formRegister.getForm().isValid()) {
					Ext.getCmp('winLogin').body.mask();
                    Ext.getCmp('sbWinLogin').showBusy();
                }
            }
        });
        formRegister.getForm().submit({
			params:{regPassword:Ext.ux.util.MD5(Ext.getCmp('regPassword').getValue())}
			,success: function(){
				Ext.Msg.show({
				   title:'Activate Your Account:',
				   msg: 'Congratulations - You have registered with The Seven Seas Group!<br>Now we just need you to confirm your registration by clicking on the link that we have sent to your email. If you do not receive an email, please check your spam folder.',
				   buttons: Ext.Msg.OK,
				   fn:function(){
					   window.location = '<?php print URL_INGRESO; ?>';
				   },
				   icon: Ext.MessageBox.INFO
				});
			}
			,failure: function(form, action){
				Ext.getCmp('regPassword').setValue('');
				Ext.getCmp('regConfPassword').setValue('');
				Ext.getCmp('winLogin').body.unmask();
				Recaptcha.reload();
				if (action.failureType == 'server') {
                    obj = Ext.util.JSON.decode(action.response.responseText);
                    Ext.getCmp('sbWinLogin').setStatus({
                        text: obj.errors.reason,
                        iconCls: 'x-status-error'
                    });
                }
				else{
					if(formRegister.getForm().isValid()) {
                        Ext.getCmp('sbWinLogin').setStatus({
                            text: 'Authentication server is unreachable',
                            iconCls: 'x-status-error'
                        });
                    }
					else{
						Ext.getCmp('sbWinLogin').setStatus({
                            text: 'Something error in form !',
                            iconCls: 'x-status-error'
                        });
                    }
                }
			}
        });
    }
	var winLogin = new Ext.Panel({
        layout:'form',
		renderTo: "contenido",
		id: 'winLogin',
		//autoWidth: 1000,
		//baseCls:'box',
		autoHeight: true,
		resizable: false,
		closable: false,
		plain: false,
		modal: true,
		items: [{
			xtype:'tabpanel',
			//layout:'column',
			bodyStyle: 'background:#fefefe',
			activeTab: 0,
			defaults: {
				layout: 'form',
				border: false,
				bodyStyle: 'padding:4px',
				cls:'boxBody'
			},
			items: [{
				//xtype:'fieldset',
				//columnWidth: 0.48,
				title: 'Already a Member? Sign-in',
				autoHeight:true,
				//layout:'anchor',
				cls:'',
				items :[formLogin]
			},{
				// Fieldset in Column 2 - Panel inside
				//xtype:'fieldset',
				title: 'Register New Account', // title, header, or checkboxToggle creates fieldset header
				autoHeight:true,
				//columnWidth: 0.52,
				//layout:'anchor',
				items :[formRegister]
			}]
		}],
        bbar: new Ext.ux.StatusBar({
            text: '',
            id: 'sbWinLogin'
        })
	});

	//winLogin.show();
	//Ext.getCmp('logUsername').focus(false,750);	
});